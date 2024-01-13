<?php

namespace Tests\Unit;

use App\Models\Customer;
use Exception;
use Tests\TestCase;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SmsSendTest extends TestCase
{

    public function test_example(): void
    {
        $faker = Factory::create();
        for ($i = 0; $i < 500; $i++) {
            $generatedNumbers[] = strval($faker->numerify('##########')); // 10 haneli rastgele sayı
        }
        $password = $faker->unique()->password();
        $customer = Customer::create([
            'user_name' => $faker->unique()->userName(),
            'email' => $faker->unique()->safeEmail(),
            'first_name' => $faker->unique()->name(),
            'last_name' => $faker->unique()->lastName(),
            'password' => Hash::make($password),
            'is_active' => 1,
        ]);
        $responseToken = $this->post('api/auth/login', ['user_name' => $customer->user_name, 'password' => $password]);
        $token = $responseToken->getOriginalContent()['data']['access_token'];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->json('POST', 'api/sms', [
                'numbers' => $generatedNumbers,
                'message' => 'Test Mesajı',
                'country_id' => 1
            ]);
        $response->assertStatus(200);

        $response->assertJson(['status' => true]);
    }
}
