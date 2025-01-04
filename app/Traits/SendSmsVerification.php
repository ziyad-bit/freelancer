<?php

namespace App\Traits;

use Illuminate\Support\Facades\{Cache};
use Illuminate\Support\Str;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;

trait SendSmsVerification
{
	//MARK: sendVerification
	public function sendSmsVerification(int $user_id,int $phone_number):array
	{
		$secret   = env('VONAGE_SECRET');
		$key      = env('VONAGE_KEY');
		$app_name = env('APP_NAME');

		$basic    = new Basic($key, $secret);
		$client   = new Client($basic);
		$code_num = mt_rand(10000000,99999999);

		Cache::put('code_num_' . $user_id, $code_num, now()->addMinutes(5));

		$response = $client->sms()->send(
			new SMS($phone_number, $app_name, 'Your verification code is: ' . $code_num)
		);

		$message = $response->current();

		if ($message->getStatus() == 0) {
			return  ['success' => 'The message was sent successfully','user_id' => $user_id];
		} else {
			return ['error' => 'The message failed with status: ' . $message->getStatus()];
		}
	}
}
