<?php

namespace App\Http\Controllers\Sms;

use App\Enums\SmsStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sms\SmsIndexRequest;
use App\Http\Requests\Sms\SmsShowRequest;
use App\Http\Requests\Sms\SmsStoreRequest;
use App\Http\Resources\SmsResource;
use App\Jobs\SmsSendJob;
use App\Models\Sms;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SmsController extends Controller
{
    private Sms $smsModel;

    public function __construct(Sms $smsModel)
    {
        $this->smsModel = $smsModel;
    }

    /**
     * @OA\Get(
     *     path="/api/sms",
     *     summary="Tarih Aralığı Sorgula",
     *     tags={"Sms"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", format="date", example="2024-01-01"),
     *         description="Başlangıç Tarihi"
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", format="date", example="2024-12-31"),
     *         description="Bitiş Tarihi"
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Başarılı",
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Geçersiz istek parametreleri",
     *     ),
     * )
     */
    public function index(SmsIndexRequest $request)
    {
        $customer = Auth::user();
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $filter_start_date = Carbon::parse($start_date);
        $filter_end_date = Carbon::parse($end_date)->addHours(11)->addMinutes(59)->addSeconds(59);

        $result = $this->smsModel::where('customer_id', $customer->id)
            ->when([$start_date, $end_date], function ($query) use ($filter_start_date, $filter_end_date) {
                if ($filter_start_date && $filter_end_date) {
                    $query->whereBetween('created_at', [$filter_start_date, $filter_end_date]);
                }
            })->get();

        $resultData = SmsResource::collection($result);
        return outputSuccess($resultData);
    }



    /**
     * @OA\Post(
     *     path="/api/sms",
     *     summary="Sms Gönder",
     *     tags={"Sms"},
     *     security={{"bearer_token": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="numbers", type="array",@OA\Items(type="integer", example=5462478411), example="[5462478412,5462478411]"),
     *             @OA\Property(property="message", type="string", example="Test Mesajı"),
     *             @OA\Property(property="country_id", type="String", example="1"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Sms talebiniz başarıyla alınmıştır. İşlem sonucunu geçmişten listeleterek görebilirsiniz.",
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Sms gönderiminde bir hata oluştu. Lütfen tekrar deneyiniz.",
     *     ),
     * )
     */
    public function store(SmsStoreRequest $request)
    {
        $customer = Auth::user();
        $numbers = $request->input('numbers');
        $message = $request->input('message');
        $country_id = $request->input('country_id');
        try {
            foreach ($numbers as $number) {
                $sms = $this->smsModel::create([
                    "customer_id" => $customer->id,
                    "country_id" => $country_id,
                    "number" => clearPhoneNumber($number),
                    "status" => SmsStatusEnum::PENDING->value,
                    "message" => $message,
                ]);
                dispatch(new SmsSendJob($sms));
            }
            return outputSuccess(message: __('Sms talebiniz başarıyla alınmıştır. İşlem sonucunu geçmişten listeleterek görebilirsiniz.'));
        } catch (Exception $e) {
            DB::rollback();
            return outputError(message: $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/sms/{id}",
     *     summary="Belirli bir SMS'i getir",
     *     tags={"Sms"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="SMS ID"
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="İşlem başarıyla gerçekleşti.",
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Hata.",
     *     ),
     * )
     */
    public function show(SmsShowRequest $request, string $id)
    {
        try {
            $sms = $this->smsModel::find($id);
            if (!$sms) {
                throw new Exception(__('Sms bilgileri bulunamadı'));
            }
            $smsData = SmsResource::make($sms);
            return outputSuccess($smsData);
        } catch (Exception $e) {
            return outputError(message: $e->getMessage());
        }
    }
}
