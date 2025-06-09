<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class ProjectSetup extends Command
{
    protected $signature = 'project:setup';
    protected $description = 'Prepare the project after cloning: copy .env, run migrations, seeders, etc.';

    public function handle(): void
    {
        $this->info('Starting project setup...');

        if (!File::exists(base_path('.env'))) {
            File::copy(base_path('.env.example'), base_path('.env'));
            $this->info('.env file copied');
        } else {
            $this->info('.env already exists, skipping...');
        }

        $this->call('key:generate');

        $storageLink = public_path('storage');

        if (is_link($storageLink) || File::exists($storageLink)) {
            $this->info('Storage link already exists or is a folder, skipping...');
        } else {
            $this->call('storage:link');
            $this->info('Storage link created');
        }


        $this->call('migrate:fresh', ['--seed' => true]);

        $this->info('Building frontend assets...');
        echo shell_exec('npm run build 2>&1');

        $this->info('Project is ready!');
    }
}
