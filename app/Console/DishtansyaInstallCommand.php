<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DishtansyaInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dishtansya:install {--force} {--seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "[Not allowed in production and testing env] Execute migration and other dependency add --force option to to refresh your whole database " .
    "and --seed option to execute seeders but make sure all records has been truncated in your DB to avoid conflicts";

    /**
     * Execute the console command.
     *
     * @return int Number of transaction that are expired
     */
    public function handle()
    {
        if (config('app.env') === 'production' || config('app.env') === 'testing') {
            $this->error("This command is not allowed in production and testing environment");
            return false;
        }

        if ($this->option('force')) {
            $confirmation = $this->ask(
                'The following command will execute.' . PHP_EOL .
                ' 1. php artisan key:generate' . PHP_EOL .
                ' 2. migrate:refresh' . PHP_EOL .
                ' 3. db:seed' . PHP_EOL .
                ' 4. cache:clear' . PHP_EOL .
                ' 5. storage:link' . PHP_EOL .
                ' Do you wish to continue? (y/N)'
            );

            if (strtolower($confirmation) !== 'y') {
                $this->info('Installation has been cancelled');

                return false;
            }

            if (!file_exists(storage_path('/framework/cache/data'))) {
                mkdir(storage_path('/framework/cache/data'));
            }

            $this->makeSqliteDependencies();

            $this->info(PHP_EOL . 'Generating keys...');
            $this->call('key:generate');
            $this->info(PHP_EOL . 'Migrating Database...');
            $this->call('migrate:refresh');
            $this->info(PHP_EOL . 'Seeding Database...');
            $this->call('db:seed');
            $this->info(PHP_EOL . 'Clearing Cache...');
            $this->call('cache:clear');
            $this->info(PHP_EOL . 'Linking storage directory to public...');
            $this->call('storage:link');

            return true;
        }

        if (!file_exists(storage_path('/framework/cache/data'))) {
            mkdir(storage_path('/framework/cache/data'));
        }

        $this->makeSqliteDependencies();

        //generate keys
        $this->info(PHP_EOL . 'Generating keys...');
        $this->call('key:generate');

        //build db
        $this->info(PHP_EOL . 'Migrating Database...');
        $this->call('migrate');

        //cache clear
        $this->info(PHP_EOL . 'Clearing Cache...');
        $this->call('cache:clear');

        //storage link
        $this->info(PHP_EOL . 'Linking storage directory to public...');
        $this->call('storage:link');

        return true;
    }

    public function makeSqliteDependencies()
    {
        if (!file_exists(database_path('stub.sqlite'))) {
            $file = fopen(database_path('stub.sqlite'), 'w');
            fclose($file);
        }
    }
}
