<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class TestModules extends Command
{
    protected $signature = 'test:bookings';
    protected $description = 'Run all tests inside the Bookings module';

    public function handle()
    {
        $this->info('Running Bookings module tests...');

        $dir = base_path('modules/Bookings/tests');

        if (!is_dir($dir)) {
            $this->warn('No tests folder found in Bookings module.');
            return;
        }

        $this->line("\n=======================================");
        $this->line("Running tests in: " . $dir);
        $this->line("=======================================");

        // ✅ استخدام php artisan test مع --verbose و --testdox
        $process = Process::fromShellCommandline("php artisan test {$dir} --testdox");

        $process->setTimeout(300);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        if (!$process->isSuccessful()) {
            $this->error("❌ Tests failed in: " . $dir);
        } else {
            $this->info("✅ Tests passed in: " . $dir);
        }

        $this->info("\n🏁 Bookings module tests executed.");
    }
}
