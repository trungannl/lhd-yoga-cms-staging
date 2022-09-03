<?php


namespace App\Repositories\UserOtp;


use App\Repositories\RepositoryInterface;

interface UserOtpRepositoryInterface extends RepositoryInterface
{
    public function checkVerifyOtp($phone, $otp);
    public function checkPhoneVerified($phone);
}
