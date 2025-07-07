<?php

namespace Modules\Teams\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Teams\Models\Team;
use Modules\Tenants\Models\Tenant;


class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            Team::create([
                'tenant_id' => $tenant->id,
                'name' => 'Team A for ' . $tenant->name,
            ]);
            Team::create([
                'tenant_id' => $tenant->id,
                'name' => 'Team B for ' . $tenant->name,
            ]);
        }
    }

}
