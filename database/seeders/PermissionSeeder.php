<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            // Dashboard
            ['group_name' => 'Dashboard', 'permissions' => [
                'dashboard.view',
            ]],

            // Brand
            ['group_name' => 'Brand', 'permissions' => [
                'brand.view',
                'brand.create',
                'brand.edit',
                'brand.delete',
            ]],

            // Warehouse
            ['group_name' => 'Warehouse', 'permissions' => [
                'warehouse.view',
                'warehouse.create',
                'warehouse.edit',
                'warehouse.delete',
            ]],

            // Supplier
            ['group_name' => 'Supplier', 'permissions' => [
                'supplier.view',
                'supplier.create',
                'supplier.edit',
                'supplier.delete',
            ]],

            // Customer
            ['group_name' => 'Customer', 'permissions' => [
                'customer.view',
                'customer.create',
                'customer.edit',
                'customer.delete',
            ]],

            // Product Category
            ['group_name' => 'Product Category', 'permissions' => [
                'product-category.view',
                'product-category.create',
                'product-category.edit',
                'product-category.delete',
            ]],

            // Product
            ['group_name' => 'Product', 'permissions' => [
                'product.view',
                'product.create',
                'product.edit',
                'product.delete',
            ]],

            // Purchase
            ['group_name' => 'Purchase', 'permissions' => [
                'purchase.view',
                'purchase.create',
                'purchase.edit',
                'purchase.delete',
                'purchase.approve',
                'purchase.return',
            ]],

            // Sales
            ['group_name' => 'Sales', 'permissions' => [
                'sale.view',
                'sale.create',
                'sale.edit',
                'sale.delete',
                'sale.return',
            ]],

            // Due
            ['group_name' => 'Due', 'permissions' => [
                'due.view',
                'due.payment',
            ]],

            // Transfer
            ['group_name' => 'Transfer', 'permissions' => [
                'transfer.view',
                'transfer.create',
                'transfer.edit',
                'transfer.delete',
                'transfer.approve',
            ]],

            // Reports
            ['group_name' => 'Report', 'permissions' => [
                'report.view',
                'report.export',
                'report.print',
            ]],

            // Permission
            ['group_name' => 'Permission', 'permissions' => [
                'permission.view',
                'permission.create',
                'permission.edit',
                'permission.delete',
            ]],

            // Role
            ['group_name' => 'Role', 'permissions' => [
                'role.view',
                'role.create',
                'role.edit',
                'role.delete',
                'role.permission.assign',
            ]],

            // Admin
            ['group_name' => 'Admin', 'permissions' => [
                'admin.view',
                'admin.create',
                'admin.edit',
                'admin.delete',
            ]],

            // ActivityLog
            ['group_name' => 'Activity Log', 'permissions' => [
                'activity-log.view',
            ]],
        ];

        foreach ($permissions as $group) {
            foreach ($group['permissions'] as $permission) {

                Permission::firstOrCreate(
                    ['name' => $permission],
                    [
                        'group_name' => $group['group_name'],
                        'guard_name' => 'web',
                    ]
                );

            }
        }
    }
}
