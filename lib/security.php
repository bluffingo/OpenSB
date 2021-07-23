<?php

function hCaptcha($response) {
	global $hCaptchaSecret;

	$data = [
		'secret' => $hCaptchaSecret,
		'response' => $response
	];

	$verify = curl_init();
	curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
	curl_setopt($verify, CURLOPT_POST, true);
	curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($verify);

	$responseData = json_decode($response);

	return $responseData->success;
}
