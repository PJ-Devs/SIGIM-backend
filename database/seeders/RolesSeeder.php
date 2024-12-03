<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseEmployeeRole = Role::create([
            "name" => "Empleado Base",
            "description" => "User",
        ]);

        $salesManRole = Role::create([
            "name" => "Gestor de Ventas",
            "description" => "Seller",
        ]);

        $inventoryAdminRole = Role::create([
            "name" => "Administrador de Inventario",
            "description" => "Seller",
        ]);

        $adminRole = Role::create([
            "name" => "Co-Administrador",
            "description" => "Administrator",
        ]);

        $ownerRole = Role::create([
            "name" => "Dueño de Empresa",
            "description" => "Enterprise Owner",
        ]);

        // Set permissions to roles

        // 1. Base Employee Role
        $baseEmployeePermissions = [
            'enterprise.read',
            'user.read',
            'user.update',
            'user.delete',
            'user.change_password',
        ];
        $permissionIds = Permission::whereIn('name', $baseEmployeePermissions)->pluck('id'); // Get the ids of the permissions
        $baseEmployeeRole->permissions()->attach($permissionIds);

        // 2. Sales Man Role
        $salesManPermissions = array_merge($baseEmployeePermissions, [
            'product.read',
            'client.read',
            'client.create',
            'client.update',
            'client.delete',
            'inoice.read',
            'inoice.create',
            'inoice.update',
            'inoice.delete',
        ]);
        $permissionIds = Permission::whereIn('name', $salesManPermissions)->pluck('id');
        $salesManRole->permissions()->attach($permissionIds);

        // 3. Inventory Admin Role
        $inventoryAdminPermissions = array_merge($salesManPermissions, [
            'product.create',
            'product.update',
            'product.delete',
            'category.read',
            'category.create',
            'category.update',
            'category.delete',
            'supplier.read',
            'supplier.create',
            'supplier.update',
            'supplier.delete',
        ]);
        $permissionIds = Permission::whereIn('name', $inventoryAdminPermissions)->pluck('id');
        $inventoryAdminRole->permissions()->attach($permissionIds);

        // 4. Admin Role
        $adminPermissions = array_merge($inventoryAdminPermissions, [
            'user.create',
            'user.roles',
            'enterprise.update',
        ]);
        $permissionIds = Permission::whereIn('name', $adminPermissions)->pluck('id');
        $adminRole->permissions()->attach($permissionIds);

        // 5. Owner Role
        $ownerPermissions = array_merge($adminPermissions, [
            'enterprise.delete',
        ]);
        $permissionIds = Permission::whereIn('name', $ownerPermissions)->pluck('id');
        $ownerRole->permissions()->attach($permissionIds);
    }
}
