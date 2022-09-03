<?php


namespace App\Repositories\UserOtp;

use App\Models\UserOtp;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class UserOtpRepository extends BaseRepository implements UserOtpRepositoryInterface
{
    public function getModel()
    {
        return UserOtp::class;
    }

    public function checkPhone($phone)
    {
        return $this->model->where('phone', $phone)->first();
    }

    public function checkVerifyOtp($phone, $otp)
    {
        return $this->model->where('phone', $phone)
            ->where('otp', $otp)
            ->where('verified', 0)
            ->whereDate('otp_time_expire', '>=', Carbon::now()->toDateString())
            ->first();
    }

    public function checkPhoneVerified($phone)
    {
        return $this->model->where('phone', $phone)
            ->where('verified', 1)
            ->first();
    }
}
