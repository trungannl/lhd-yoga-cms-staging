<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\UserOtp\UserOtpRepositoryInterface;
use App\Service\SendOtp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class ResetPasswordController extends Controller
{
    protected $userRepository;

    protected $userOtpRepository;

    public function __construct(UserRepositoryInterface $userRepository, UserOtpRepositoryInterface $userOtpRepository)
    {
        $this->userRepository = $userRepository;
        $this->userOtpRepository = $userOtpRepository;
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|regex:/(0)[0-9]{9}/|digits:10',
        ]);

        if ($validator->fails()) {
            return $this->sendError(['error' => $validator->errors()], 'Fail to forgot password',401);
        }

        $user = $this->userRepository->getUserByNameColumn('phone', $request->get('phone'));
        if (empty($user)) {
            return $this->sendError(['error' => 'User not found'], 'Fail to change password',401);
        }

        try {
            $data = [
                'password_reset_otp' => randomOtp(),
                'otp_time_expire' => Carbon::now()->addMinutes(10)->format('Y-m-d H:i:s')
            ];
            $this->userRepository->update($user->id, $data);

            $otp = rand(pow(10, 5), pow(10, 6)-1);
            $otpTimeExpire = Carbon::now()->addMinute(5)->format('Y-m-d H:i:s');
            $data = [
                'phone' => $request->phone,
                'otp' => $otp,
                'otp_time_expire' => $otpTimeExpire,
                'user_id' => $user->id,
                'verified' => 0,
            ];
            $item = $this->userOtpRepository->checkPhone($request->phone);
            if ($item) {
                $this->userOtpRepository->update($item->id, $data);
            }
            else {
                $this->userOtpRepository->create($data);
            }

            $sendOtp = new SendOtp();
            $status = $sendOtp->send($request->phone, 'otp: '.$otp);
            if (!$status) {
                return $this->sendError([], 'Fail to send otp');
            }
        }
        catch (JWTException $th) {
            return $this->sendError(['error' => 'Unauthorized'], 'Fail to forgot password', 401);
        }

        return $this->sendResponse(null, 'Sent verify code',200);
    }

    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|regex:/(0)[0-9]{9}/|digits:10',
            'otp' => 'required|numeric|digits:6',
        ]);

        if ($validator->fails()) {
            return $this->sendError(['error' => $validator->errors()], 'Fail to verify code',401);
        }

        $item = $this->userOtpRepository->checkVerifyOtp($request->phone, $request->otp);
        if (empty($item)) {
            return $this->sendError(['error' => 'This code expired'], 'Fail to verify code',401);
        }

        try {
            $this->userOtpRepository->update($item->id, ["verified" => 1]);
        }
        catch (JWTException $th) {
            return $this->sendError(['error' => 'Unauthorized'], 'Fail to verify code', 401);
        }

        return $this->sendResponse(null, 'Verify code successfully',200);
    }

    public function changePasswod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|regex:/(0)[0-9]{9}/|digits:10',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return $this->sendError(['error' => $validator->errors()], 'Fail to change passwod',401);
        }

        $user = $this->userRepository->getUserByNameColumn('phone', $request->get('phone'));
        if (empty($user)) {
            return $this->sendError(['error' => 'User not found'], 'Fail to change password',401);
        }

        $item = $this->userOtpRepository->checkPhoneVerified($request->phone);
        if (!$item) {
            return $this->sendError(['error' => 'The user does not verify code'], 'Fail to change password',401);
        }

        try {
            $data = [
                'password' => Hash::make($request->password)
            ];
            $this->userRepository->update($user->id, $data);
        }
        catch (JWTException $th) {
            return $this->sendError(['error' => 'Unauthorized'], 'Fail to change password', 401);
        }

        return $this->sendResponse(null, 'User successfully change password',200);
    }
}
