<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\UserOtp\UserOtpRepositoryInterface;
use App\Resource\UserResource;
use App\Service\SendOtp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use JWTAuth;

class AuthController extends Controller
{
    protected $userRepository;
    protected $userOtpRepository;
    public function __construct(UserRepositoryInterface $userRepository, UserOtpRepositoryInterface $userOtpRepository){
        $this->middleware('auth:api', ['except' => ['login', 'register', 'sendOtp', 'checkOtp']]);
        $this->userRepository = $userRepository;
        $this->userOtpRepository = $userOtpRepository;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|regex:/(0)[0-9]{9}/|digits:10',
            'password' => 'required|string|min:6',
            ]);

        if ($validator->fails()) {
            return $this->sendError(['error' => $validator->errors()], 'Fail to log in',401);
        }

        $credentials = $request->only('phone', 'password');

        try {
            if (! $token = $this->guard()->attempt($credentials)) {
                return $this->sendError(['error' => 'Unauthorized'], 'Fail to log in', 401);

            }
        }
        catch (JWTException $th) {
            return $this->sendError(['error' => 'Unauthorized'], 'Fail to log in', 401);
        }


        return $this->sendResponse($this->respondWithToken($token), 'Successfully logged in');
    }

    protected function respondWithToken($token)
    {
        $user = $this->guard()->user();
        return [
            'accessToken' => $token,
            'user'  => UserResource::make($user),
            'tokenType' => 'bearer',
            'expiresIn' => $this->guard()->factory()->getTTL()
        ];
    }

    public function  guard()
    {
        return Auth::guard('api');
    }

    public function logout(){
        try {
            JWTAuth::parseToken()->invalidate();
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        }
        catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out',
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    public function refresh()
    {
        return $this->sendResponse($this->respondWithToken($this->guard()->refresh()), 'User refresh successfully', 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,255',
            'phone' => 'required|numeric|regex:/(0)[0-9]{9}/|digits:10|unique:users',
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|confirmed|min:6',
            'birthday' => 'nullable|date_format:Y-m-d|before:today',
            'gender'=> 'required|in:male,female'
        ]);

        if($validator->fails()){
            return $this->sendError(['error' => $validator->errors()], 'Fail to register',401);
        }

        try {
            $data = array_merge(
                $validator->validated(),
                ['password' => Hash::make($request->password), 'is_student' => 1]
            );

            $user = $this->userRepository->create($data);
            return $this->sendResponse(UserResource::make($user), 'User successfully registered');
        }
        catch (JWTException $th) {
            return $this->sendError(['error' => 'Unauthorized'], 'Fail to register', 401);
        }
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|regex:/(0)[0-9]{9}/|digits:10|unique:users'
        ]);

        if($validator->fails()){
            return $this->sendError(['error' => $validator->errors()], 'Fail to send otp',401);
        }

        try {
            $otp = rand(pow(10, 5), pow(10, 6)-1);
            $otpTimeExpire = Carbon::now()->addMinute(5)->format('Y-m-d H:i:s');
            $data = [
                'phone' => $request->phone,
                'otp' => $otp,
                'otp_time_expire' => $otpTimeExpire,
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

            return $this->sendResponse([], 'Send otp successfully');
        }
        catch (JWTException $th) {
            return $this->sendError([], 'Fail to send otp');
        }

    }

    public function checkOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|regex:/(0)[0-9]{9}/|digits:10|unique:users'
        ]);

        if($validator->fails()){
            return $this->sendError(['error' => $validator->errors()], 'Fail to check otp',401);
        }

        $item = $this->userOtpRepository->checkVerifyOtp($request->phone, $request->otp);
        if ($item) {
            $this->userOtpRepository->update($item->id, ["verified" => 1]);
            return $this->sendResponse([], 'Check otp successfully');
        }

        return $this->sendError([], 'Fail to check otp');

    }
}
