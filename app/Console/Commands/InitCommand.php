<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initializes application after composer install';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->isInitialized() && !$this->confirm('Application already initialized! Overwrite existing data?')) {
            return 1;
        }

        $this->info('Initializing application...');

        $this->call('key:generate');

        if ($this->getDatabaseConfig('driver') === 'sqlite') {
            $databaseFile = $this->getDatabaseConfig('database');

            $this->info('Creating sqlite database  (' . $databaseFile . ')');

            if (file_exists($databaseFile)) {
                unlink($databaseFile);
            }
            touch($databaseFile);
        }

        $this->call('migrate:fresh', ['--seed' => true]);

        return 0;
    }

    protected function getDatabaseConfig($key, $connectionName = null)
    {
        $connectionName = $connectionName ?? config('database.default');
        return config('database.connections.' . $connectionName . '.' . $key);
    }

    protected function isInitialized()
    {
        return !empty(env('APP_KEY'));
    }
}
