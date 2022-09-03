<?php

namespace App\Http\Controllers;

use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\Role\RoleRepositoryInterface;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleRepository;
    protected $permissionRepository;

    public function __construct(RoleRepositoryInterface $roleRepository, PermissionRepositoryInterface $permissionRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = $this->roleRepository->getAll();
        $permissions = $this->permissionRepository->getAll();

        return view('role.index', compact('roles', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        try {
            $role = $this->roleRepository->create($input);
        }
        catch (\Exception $e) {
            return response()->json([], 401);
        }

        return response()->json(['id' => $role->id, 'name' => $role->name], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = $this->roleRepository->find($id);
        $permission = [];
        foreach ($role->permissions as $item) {
            $permission[] = [
                'id' => $item->id,
                'name' => $item->name
            ];
        }
        return response()->json($permission, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = $this->roleRepository->find($id);
        if (empty($role)) {
            return response()->json([], 400);
        }
        $this->roleRepository->destroy($id);
        return response()->json([], 200);
    }

    public function givePermissionToRole(Request $request)
    {
        $input = $request->all();
        $this->roleRepository->givePermissionToRole($input);
        return response()->json([], 200);
    }

    public function revokePermissionToRole(Request $request)
    {
        $input = $request->all();
        $this->roleRepository->revokePermissionToRole($input);
        return response()->json([], 200);
    }
}
