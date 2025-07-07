<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Tenants\Models\Tenant;
use Modules\Users\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            User::create([
                'tenant_id' => $tenant->id,
                'name' => 'Admin ' . $tenant->name,
                'email' => strtolower(str_replace(' ', '', $tenant->name)) . '@test.com',
                'password' => 'password',
            ]);
        }
    }
}
