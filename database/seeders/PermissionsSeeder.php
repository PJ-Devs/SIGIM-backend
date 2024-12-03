<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Enterprise Permissions
        Permission::insert([
            [
                "name" => "enterprise.read",
                "description" => "Read Enterprise",
            ],
            [
                "name" => "enterprise.update",
                "description" => "Update Enterprise",
            ],
            [
                "name" => "enterprise.delete",
                "description" => "Delete Enterprise",
            ],
        ]);

        // Product Permissions
        Permission::insert([
            [
                "name" => "product.create",
                "description" => "Create Product",
            ],
            [
                "name" => "product.read",
                "description" => "Read Product",
            ],
            [
                "name" => "product.update",
                "description" => "Update Product",
            ],
            [
                "name" => "product.delete",
                "description" => "Delete Product",
            ],
        ]);

        // Category Permissions
        Permission::insert([
            [
                "name" => "category.create",
                "description" => "Create Category",
            ],
            [
                "name" => "category.read",
                "description" => "Read Category",
            ],
            [
                "name" => "category.update",
                "description" => "Update Category",
            ],
            [
                "name" => "category.delete",
                "description" => "Delete Category",
            ],
        ]);

        // Supplier Permissions
        Permission::insert([
            [
                "name" => "supplier.create",
                "description" => "Create Supplier",
            ],
            [
                "name" => "supplier.read",
                "description" => "Read Supplier",
            ],
            [
                "name" => "supplier.update",
                "description" => "Update Supplier",
            ],
            [
                "name" => "supplier.delete",
                "description" => "Delete Supplier",
            ],
        ]);

        // User Permissions
        Permission::insert([
            [
                "name" => "user.create",
                "description" => "Create User",
            ],
            [
                "name" => "user.read",
                "description" => "Read User",
            ],
            [
                "name" => "user.update",
                "description" => "Update User information, except password",
            ],
            [
                "name" => "user.delete",
                "description" => "Delete User",
            ],
            [
                "name" => "user.roles",
                "description" => "Assign Roles to User",
            ],
            [
                "name" => "user.change_password",
                "description" => "Change User Password",
            ]
        ]);

        // Client Permissions
        Permission::insert([
            [
                "name" => "client.create",
                "description" => "Create Client",
            ],
            [
                "name" => "client.read",
                "description" => "Read Client",
            ],
            [
                "name" => "client.update",
                "description" => "Update Client",
            ],
            [
                "name" => "client.delete",
                "description" => "Delete Client",
            ],
        ]);

        // Sale Permissions
        Permission::insert([
            [
                "name" => "sale.create",
                "description" => "Create Sale",
            ],
            [
                "name" => "sale.read",
                "description" => "Read Sale",
            ],
            [
                "name" => "sale.update",
                "description" => "Update Sale",
            ],
            [
                "name" => "sale.delete",
                "description" => "Delete Sale",
            ],
        ]);

        // Invoice Permissions
        Permission::insert([
            [
                "name" => "invoice.create",
                "description" => "Create Invoice",
            ],
            [
                "name" => "invoice.read",
                "description" => "Read Invoice",
            ],
            [
                "name" => "invoice.update",
                "description" => "Update Invoice",
            ],
            [
                "name" => "invoice.delete",
                "description" => "Delete Invoice",
            ],
        ]);
    }
}
