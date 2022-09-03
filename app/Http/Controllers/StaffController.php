<?php

namespace App\Http\Controllers;

use App\Datatables\StaffDatatable;
use App\Events\StaffRoleChangedEvent;
use App\Http\Requests\StaffRequest;
use App\Mail\NewStaffMail;
use App\Repositories\Area\AreaRepositoryInterface;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\Staff\StaffRepositoryInterface;
use App\Repositories\Upload\UploadRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Flash;
use Auth;
use Illuminate\Support\Facades\Mail;

class StaffController extends Controller
{
    protected $areaRepository;
    protected $staffRepository;
    protected $uploadRepository;
    protected $roleRepository;
    protected $permissionRepository;

    public function __construct(AreaRepositoryInterface $areaRepository, StaffRepositoryInterface $staffRepository, UploadRepositoryInterface $uploadRepository, RoleRepositoryInterface $roleRepository, PermissionRepositoryInterface $permissionRepository)
    {
        $this->areaRepository = $areaRepository;
        $this->staffRepository = $staffRepository;
        $this->uploadRepository = $uploadRepository;
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StaffDatatable $staffDatatable)
    {
        $cities = $this->areaRepository->getCity();
        $roles = $this->roleRepository->getAll()->pluck(
            'name',
            'id'
        )->toArray();
        return $staffDatatable->render('staffs.index', compact('cities', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = $this->roleRepository->getAll()->pluck(
            'name',
            'id'
        )->toArray();

        $permissions = $this->permissionRepository->getAll();

        return view('staffs.create', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StaffRequest $request)
    {
        $input = $request->all();

        try {

            $password = $this->randomPassword();
            $input['password'] = Hash::make($password);

            $staff = $this->staffRepository->create($input);

            if (isset($input['avatar']) && $input['avatar']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['avatar']);
                $mediaItem = $cacheUpload->getMedia('avatar')->first();
                $mediaItem->copy($staff, 'avatar');
            }

            $staff->syncRoles($input['role_id']);
            event(new StaffRoleChangedEvent($staff));

//            $this->sendMail($staff, $password);

        }
        catch (\Exception $e) {
            Flash::error($e->getMessage());
            return redirect(route('staffs.create'));
        }

        return redirect(route('staffs.show', $staff->id))->with('success', 'Saved successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $staff = $this->staffRepository->find($id);

        if (empty($staff)) {
            Flash::error('Staff not found');

            return redirect(route('staffs.index'));
        }

        $permissions = $this->permissionRepository->getAll();

        $isProfile = false;

        return view('staffs.profile', compact('staff', 'permissions', 'isProfile'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $staff = $this->staffRepository->find($id);

        if (empty($staff)) {
            Flash::error('Staff not found');

            return redirect(route('staffs.index'));
        }

        $roles = $this->roleRepository->getAll()->pluck(
            'name',
            'id'
        )->toArray();

        $permissions = $this->permissionRepository->getAll();

        return view('staffs.edit', compact('staff', 'roles', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StaffRequest $request, $id)
    {
        $staff = $this->staffRepository->find($id);

        if (empty($staff)) {
            Flash::error('Staff not found');

            return redirect(route('staffs.show', $id));
        }

        $input = $request->all();
        try {
            $staff = $this->staffRepository->update($id, $input);

            if (isset($input['avatar']) && $input['avatar']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['avatar']);
                $mediaItem = $cacheUpload->getMedia('avatar')->first();
                $mediaItem->copy($staff, 'avatar');
            }

            $staff->syncRoles($input['role_id']);
            event(new StaffRoleChangedEvent($staff));
        }
        catch (\Exception $e) {
            Flash::error($e->getMessage());
            return redirect()->back();
        }

        return redirect(route('staffs.show', $id))->with('success', 'Staff updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $staff = $this->staffRepository->find($id);

        if (empty($staff)) {
            Flash::error('Staff not found');

            return redirect(route('staffs.index'));
        }

        $this->staffRepository->destroy($id);

        Flash::success('Staff removed successfully.');

        return redirect(route('staffs.index'));
    }

    public function active($id)
    {
        $staff = $this->staffRepository->find($id);

        if (empty($staff)) {
            Flash::error('Staff not found');

            return redirect(route('staffs.index'));
        }

        try {
            $input = [
                'active' => ($staff->active) ? false : true
            ];

            $staff = $this->staffRepository->update($id, $input);
            if (empty($staff)) {
                Flash::error('Staff not found');
                return redirect()->back();
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        if (!$staff->active) {
            Flash::success('Staff Inactived successfully.');
        }
        else {
            Flash::success('Staff actived successfully.');
        }

        return redirect()->back();
    }

    protected function randomPassword($length = 8)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }

    protected function sendMail($staff, $password)
    {
        Mail::to($staff->email)->send(new NewStaffMail($staff, $password));
    }
}
