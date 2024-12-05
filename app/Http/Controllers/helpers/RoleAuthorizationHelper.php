<?php

namespace App\Http\Controllers\helpers;

class RoleAuthorizationHelper
{

  public static function hasPermission($role, $permission): bool
  {
    return $role->permissions->contains('name', $permission);
  }

  public static function hasAnyPermission($role, $permissions): bool
  {
    return $role->permissions->whereIn('name', $permissions)->first() ? true : false;
  }

  public static function hasAllPermissions($role, $permissions): bool
  {
    return $role->permissions->whereIn('name', $permissions)->count() == count($permissions);
  }
}
