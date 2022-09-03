<?php


namespace App\Repositories\Role;

use App\Repositories\BaseRepository;
use Spatie\Permission\Models\Role;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function getModel()
    {
        return Role::class;
    }

    public function givePermissionToRole($input){
        $role = Role::findOrfail($input['roleId']);
        $role->givePermissionTo($input['permission']);
    }

    public function revokePermissionToRole($input){
        $role = Role::findOrfail($input['roleId']);
        $role->revokePermissionTo($input['permission']);
    }

    public function roleHasPermission($input){
        $role = Role::findOrfail($input['roleId']);
        if($role->hasPermissionTo($input['permission'])){
            return ['result'=>1];
        }
        return ['result'=>0];
    }

    public function getPermission($role_id)
    {
        $role = Role::findOrfail($role_id);
    }
}
