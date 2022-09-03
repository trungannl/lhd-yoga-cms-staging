<?php


namespace App\Repositories\Role;


use App\Repositories\RepositoryInterface;

interface RoleRepositoryInterface extends RepositoryInterface
{
    public function givePermissionToRole($input);

    public function revokePermissionToRole($input);

    public function roleHasPermission($input);
}
