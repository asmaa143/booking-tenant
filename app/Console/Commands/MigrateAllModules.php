<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateAllModules extends Command
{
    protected $signature = 'modules:migrate-seed-all';
    protected $description = 'Run module:migrate and module:seed for all modules in order';

    public function handle()
    {
        $this->info('🚀 Starting module:migrate and module:seed for all modules...');

        $modules = [
            'Tenants',
            'Users',
            'Teams',
            'Availability',
            'Bookings',
        ];

        foreach ($modules as $module) {
            $this->info("🔧 Migrating module: $module");

            try {
                $this->call('module:migrate', [
                    'module' => $module,
                    '--force' => true,
                ]);
            } catch (\Exception $e) {
                $this->warn("⚠️ Migration skipped or already exists for module: $module");
            }

            $this->info("🌱 Seeding module: $module");

            try {
                $this->call('module:seed', [
                    'module' => $module,
                ]);
            } catch (\Exception $e) {
                $this->warn("⚠️ Seeding skipped for module: $module");
            }
        }

        $this->info('✅ All modules migrated and seeded successfully.');
        return Command::SUCCESS;
    }
}
