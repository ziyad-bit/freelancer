<?php

namespace App\Traits;

trait Payment
{
	//MARK: forgetCache
	public function getPaymentStatus(string $url, string $data = ''):array|string
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Authorization:Bearer OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8ZmY0b1UhZSVlckI9YUJzQj82KyU=']);
		if ($data == '') {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		} else {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$responseData = curl_exec($ch);
		if (curl_errno($ch)) {
			return curl_error($ch);
		}
		curl_close($ch);

		return json_decode($responseData, true);
	}
}
