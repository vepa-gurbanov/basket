<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $objs = [
            ['name' => 'Admin', 'ability' => str('Admin')->slug('_')],
            ['name' => 'Brand Manager', 'ability' => str('Brand Manager')->slug('_')],
            ['name' => 'Category Manager', 'ability' => str('Category Manager')->slug('_')],
            ['name' => 'CEO', 'ability' => str('CEO')->slug('_')],
            ['name' => 'Customer Manager', 'ability' => str('Customer Manager')->slug('_')],
            ['name' => 'Human Resources Manager', 'ability' => str('Human Resources Manager')->slug('_')],
            ['name' => 'Inventory Manager', 'ability' => str('Inventory Manager')->slug('_')],
            ['name' => 'Order Manager', 'ability' => str('Order Manager')->slug('_')],
            ['name' => 'Product Manager', 'ability' => str('Product Manager')->slug('_')],
            ['name' => 'Role Manager', 'ability' => str('Role Manager')->slug('_')],
            ['name' => 'Sales Manager', 'ability' => str('Sales Manager')->slug('_')],
            ['name' => 'User Manager', 'ability' => str('User Manager')->slug('_')],
            ['name' => 'User Agent Manager', 'ability' => str('User Agent Manager')->slug('_')],
            ['name' => 'Auth Attempt Manager', 'ability' => str('Auth Attempt Manager')->slug('_')],
            ['name' => 'Visitor Manager', 'ability' => str('Visitor Manager')->slug('_')],
        ];

        DB::table('roles')->insert($objs);
    }
}
