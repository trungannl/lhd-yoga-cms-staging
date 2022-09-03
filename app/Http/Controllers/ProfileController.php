<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\Staff\StaffRepositoryInterface;
use Flash;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    protected $permissionRepository;
    protected $staffRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository, StaffRepositoryInterface $staffRepository)
    {
        $this->permissionRepository = $permissionRepository;
        $this->staffRepository = $staffRepository;
    }

    public function index()
    {
        $staff = auth()->user();

        if (empty($staff)) {
            abort(404);
        }

        $permissions = $this->permissionRepository->getAll();

        $isProfile = true;

        return view('staffs.profile', compact('staff', 'permissions', 'isProfile'));
    }

    public function changePassword()
    {
        return view('profile.change_password');
    }

    public function change(ChangePasswordRequest $request)
    {
//        $staff = auth()->user();
//
//        if (!Hash::check($request->input('current_password'), $staff->password)) {
//            Flash::error('Current password is not valid');
//            return redirect()->back();
//        }
//
//        try {
//            $data = [
//                'password' => Hash::make($request->input('new_password'))
//            ];
//            $this->staffRepository->update($staff->id, $data);
//        }
//        catch (\Exception $e) {
//            Flash::error($e->getMessage());
//            return redirect()->back();
//        }
//
//        return redirect(route('profile.index'))->with('success', 'Change password successfully.');

    }
}
