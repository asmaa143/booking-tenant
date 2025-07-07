<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;

class UsersDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([

            \Modules\Users\Database\Seeders\UsersTableSeeder::class,

        ]);
    }
}
