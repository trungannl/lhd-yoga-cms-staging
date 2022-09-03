<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\UserSkinType\UserSkinTypeRepositoryInterface;
use App\Resource\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class UserApiController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function update(Request $request)
    {
        $user = Auth::guard('api')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,255',
            'email' => 'string|email|max:100',
            'gender' => 'in:male,female',
            'birthday' => 'nullable|date_format:Y-m-d|before:today',
        ]);

        if ($validator->fails()) {
            return $this->sendError(['error' => $validator->errors()], 'Fail to update user info',401);
        }

        try {
            $data = $request->all();

            $user = $this->userRepository->update($user->id, $data);
        }
        catch (JWTException $th) {
            return $this->sendError(['error' => 'Unauthorized'], 'Fail to register', 401);
        }

        return $this->sendResponse(UserResource::make($user), 'User successfully updated',200);
    }

    public function profile()
    {
        $user = Auth::guard('api')->user();
        return $this->sendResponse(UserResource::make($user), 'Get info user',200);
    }

    public function upload(Request $request)
    {
        $user = Auth::guard('api')->user();

        if ($request->hasFile('avatar')) {
            $user->clearMediaCollection('avatar');
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        return $this->sendResponse(UserResource::make($user), 'Upload successfully');
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return $this->sendError(['error' => $validator->errors()], 'Fail to change passwod',401);
        }

        $user = Auth::guard('api')->user();
        if (Hash::check($request->old_password, $user->password)) {
            $data = [
                'password' => Hash::make($request->new_password)
            ];
            $this->userRepository->update($user->id, $data);
            return $this->sendResponse(null, 'User successfully change password');

        }

        return $this->sendError(['error' => 'Old password does not match'], 'Fail to change password', 401);
    }

    public function remove()
    {
        try {
            $user = Auth::guard('api')->user();
            $this->userRepository->destroy($user->id);
            return $this->sendResponse(null, 'User successfully removed',200);
        }
        catch (JWTException $th) {
            return $this->sendError(['error' => 'User remove fail'], 'Fail to remove', 401);
        }
    }

    public function getStudentByPhone(Request $request)
    {
        $user = $this->userRepository->getStudentForPhone($request->phone);
        if ($user) {
            return $this->sendResponse(UserResource::make($user), 'Get student by phone',200);
        }

        return $this->sendError(['error' => 'Student not found'], 'Student not found', 401);
    }

    public function getCoacherByPhone(Request $request)
    {
        $user = $this->userRepository->getCoacherForPhone($request->phone);
        if ($user) {
            return $this->sendResponse(UserResource::make($user), 'Get coacher by phone',200);
        }

        return $this->sendError(['error' => 'Coacher not found'], 'Coacher not found', 401);
    }

    public function searchStudent(Request $request)
    {
        $data = [];
        if ($request->search) {
            $data = $this->userRepository->searchStudentFromPhone($request->search);
        }

        return $this->sendResponse(
            UserResource::collection($data),
            'Get student by phone'
        );
    }

    public function searchCoacher(Request $request)
    {
        $data = [];
        if ($request->search) {
            $data = $this->userRepository->searchCoacherFromPhone($request->search);
        }

        return $this->sendResponse(
            UserResource::collection($data),
            'Get coacher by phone'
        );
    }

}
