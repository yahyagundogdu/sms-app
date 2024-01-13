<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ActiveEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RefreshTokenRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Customer;
use App\Models\CustomerDevice;
use Exception;
use Laravel\Passport\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;


/**
 * @OA\Info(
 *     title="Sms Api",
 *     version="1.0.0",
 *     description="Sms İşlemlerinizi Giriş yaptıktan sonra yapabilirsini",
 *     @OA\Contact(
 *         email="kml.yhy.65@gmail.com"
 *     )
 * )
 */
class AuthController extends Controller
{
    private Customer $customer;
    private CustomerDevice $customerDevice;
    protected Client $client;

    public function __construct(
        Customer        $customer,
        CustomerDevice  $customerDevice,
        Client          $client
    ) {
        $this->customer = $customer;
        $this->customerDevice = $customerDevice;
        $this->client = $client;
    }

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Kayıt Ol",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="first_name", type="string", example="Hakan"),
     *             @OA\Property(property="last_name", type="string", example="Özbay"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123"),
     *             @OA\Property(property="email", type="string", format="email", example="test@gmail.com"),
     *             @OA\Property(property="user_name", type="string", example="testkullanici"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Başarılı",
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Doğrulama Hatası",
     *     ),
     * )
     */
    public function register(RegisterRequest $request)
    {

        DB::beginTransaction();
        try {
            $email = $request->input('email');
            $first_name = $request->input('first_name');
            $last_name = $request->input('last_name');
            $password = $request->input('password');
            $user_name = $request->input('user_name');


            $data = [];

            $this->customer->create([
                'first_name' => $first_name,
                'user_name' => $user_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);



            $message = __('Kayıt İşlemi Başarıyla Tamamlandı Lütfen Giriş Yapınız.');

            DB::commit();

            return outputSuccess(data: $data, message: $message);
        } catch (\Exception $exception) {
            DB::rollBack();
            return outputError(message: $exception->getMessage());
        }
    }


    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Giriş Yap",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user_name", type="string", example="programmer1453"),
     *             @OA\Property(property="password", type="string", format="password", example="123456"),
     *             @OA\Property(property="device_id", type="string", example="123456", nullable=true),
     *             @OA\Property(property="device_model", type="string", example="iPhone 12 Pro Max", nullable=true),
     *             @OA\Property(property="device_version", type="string", example="14.4", nullable=true),
     *             @OA\Property(property="app_version", type="string", example="1.0", nullable=true),
     *             @OA\Property(property="device_type", type="string", enum={"I", "A", "O"}, example="A", nullable=true),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Başarılı",
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Giriş Başarısız",
     *     ),
     * )
     */

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $user_name = $request->input('user_name');
            $password = $request->input('password');
            $customer = Customer::where('user_name', $user_name)->first();

            if (!$customer) {
                throw new \Exception(__('Kullanıcı Bulunamadı.'));
            }

            if (Hash::check($password, $customer->password)) {
                $loginUser = $this->loginUser($request, $user_name, $password);
                return $loginUser;
            } else {
                throw new Exception(__('Şifre Yanlış.'));
            }
        } catch (\Exception $exception) {
            DB::rollback();
            return outputError(message: $exception->getMessage());
        }
    }


    private function loginUser($request, $user_name, $password): JsonResponse
    {


        $customer = $this->customer::query()->where('user_name', $user_name)->first();


        $client = $this->client->query()->where('password_client', 1)->where('provider', 'customers')->first();
        activity('customer')
            ->causedBy($customer)
            ->log('login');

        $proxy = Request::create(
            'oauth/token',
            'POST',
            [
                'username' => $user_name,
                'password' => $password,
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'scope' => '*'
            ]
        );

        app()->instance('request', $proxy);

        $response = Route::dispatch($proxy);

        if ($response->isSuccessful()) {
            $data = json_decode($response->getContent(), true);

            $customer->last_login_at = now();
            $customer->saveOrFail();

            $deviceParams = $request->only(['app_version', 'device_id', 'device_model', 'device_version', 'device_type']);

            if (isset($deviceParams['device_id']) && $deviceParams['device_id']) {
                CustomerDevice::query()->updateOrCreate(
                    ['device_id' => $deviceParams['device_id']],
                    [
                        'customer_id' => $customer->id,
                        'app_version' => $deviceParams['app_version'],
                        'device_model' => $deviceParams['device_model'],
                        'device_version' => $deviceParams['device_version'],
                        'device_type' => $deviceParams['device_type'],
                        'is_active' => true,
                    ]
                );
            }


            $customerData = [
                'id' => $customer->id ? $customer->id : '',
                'user_name' => $customer->user_name ? $customer->user_name : '',
                'first_name' => $customer->first_name ? $customer->first_name : '',
                'last_name' => $customer->last_name ? $customer->last_name : '',
                'email' => $customer->email ? $customer->email : '',
                'full_name' => $customer->full_name ? $customer->full_name : '',
                'is_active' => $customer->is_active == ActiveEnum::ACTIVE->value ? ActiveEnum::ACTIVE->value : ActiveEnum::PASSIVE->value,
                'last_login_at' => $customer->last_login_at ? $customer->last_login_at : '',
            ];
            $data['customer'] = $customerData;
            DB::commit();
            return outputSuccess(data: $data, message: __('Giriş işlemi başarıyla tamamlandı.'));
        }

        $content = $response->getContent();

        $messageArray = json_decode($content);
    }


    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Çıkış Yap",
     *     tags={"Auth"},
     *     security={{"bearer_token": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="app_version", type="string", example="1.0", nullable=true),
     *             @OA\Property(property="device_id", type="string", example="123456", nullable=true),
     *             @OA\Property(property="device_model", type="string", example="iPhone 12 Pro Max", nullable=true),
     *             @OA\Property(property="device_version", type="string", example="14.4", nullable=true),
     *             @OA\Property(property="device_type", type="string", enum={"I", "A", "O"}, example="A", nullable=true),
     *         ),
     *     ),
     *      security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response="200",
     *         description="Başarılı",
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="İşlem Başarısız",
     *     ),
     * )
     */

    public function logout(LogoutRequest $request): JsonResponse
    {
        $customer = $request->user();

        activity('customer')
            ->causedBy($customer)
            ->log('logout');

        $deviceParams = $request->only(['app_version', 'device_id', 'device_model', 'device_version', 'device_type']);

        if (isset($deviceParams['device_id']) && $deviceParams['device_id']) {
            $this->customerDevice->query()->updateOrCreate(
                ['device_id' => $deviceParams['device_id']],
                [
                    'customer_id' => $customer->id,
                    'app_version' => $deviceParams['app_version'],
                    'device_model' => $deviceParams['device_model'],
                    'device_version' => $deviceParams['device_version'],
                    'device_type' => $deviceParams['device_type'],
                    'is_active' => 0,
                ]
            );
        }

        $request->user()->token()->revoke();

        return outputSuccess(message: __('Oturumunuz başarılı olarak sonlandırıldı.'));
    }


    /**
     * @OA\Post(
     *     path="/api/auth/token/refresh",
     *     summary="Token Yenile",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="refresh_token", type="string", example="your_refresh_token"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Başarılı",
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="İşlem Başarısız",
     *     ),
     * )
     */

    public function refreshToken(RefreshTokenRequest $request)
    {

        $client = $this->client->query()->where('password_client', 1)->where('provider', 'customers')->first();
        $proxy = Request::create(
            '/oauth/token',
            'POST',
            [
                'refresh_token' => $request->refresh_token,
                'grant_type' => 'refresh_token',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'scope' => '*'
            ]
        );

        app()->instance('request', $proxy);

        return Route::dispatch($proxy);
    }

}
