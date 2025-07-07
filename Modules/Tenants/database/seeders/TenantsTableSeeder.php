<?php

namespace Modules\Tenants\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Tenants\Models\Tenant;

class TenantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tenant::forceCreate(['name' => 'Test Company']);
        Tenant::forceCreate(['name' => 'Test Company 2']);
    }
}
