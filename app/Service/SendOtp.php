<?php


namespace App\Service;


use GuzzleHttp\Client;

class SendOtp
{
    public function send($phone, $otp)
    {
        $client = new Client();
        $response = $client->post(config("vietguy.otp_service_url"), [
            'form_params' => [
                'from' => config("vietguy.otp_from"),
                'u' => config("vietguy.otp_account"),
                'pwd' => config("vietguy.otp_password"),
                'phone' => '84' . substr($phone, 1),
                'sms' => $otp,
                'bid' => '123',
                'type' => config("vietguy.otp_type"),
                'json' => config("vietguy.otp_json"),
            ]
        ]);

        dd($response->getBody()->getContents());
        if ($response->getStatusCode() === 200) {
            return true;
        }

        return false;
    }
}
