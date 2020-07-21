<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DishtansyaConfigCommand extends Command
{
    const ENV_FILE = '/.env';
    const ENV_EXAMPLE_FILE = '/.env.example';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dishtansya:config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Create .env file for Dishtansya.";

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        if (file_exists(base_path().self::ENV_FILE)) {
            $this->error("The .env file already exist!");

            return false;
        }

        if (file_exists(base_path().self::ENV_EXAMPLE_FILE) && !file_exists(base_path().self::ENV_FILE)) {
            copy(base_path().self::ENV_EXAMPLE_FILE, base_path().self::ENV_FILE);
        }

        $keyword1 = 'APP_NAME=';

        $key1 = $keyword1 . 'Dishtansya' . PHP_EOL;

        $keyword2 = 'APP_URL=';

        $key2 = $keyword2 . 'https://dishtansya.local/' . PHP_EOL;

        $keyword3 = 'DB_DATABASE=';

        $key3 = $keyword3 . 'dishtansya' . PHP_EOL;

        $path = base_path() . '/.env';

        if (file_exists($path)) {
            $lines = file($path);
            foreach ($lines as $line) {
                if (strpos($line, $keyword1) !== false) {
                    file_put_contents(
                        $path,
                        str_replace($line, $key1, file_get_contents($path))
                    );
                }

                if (strpos($line, $keyword2) !== false) {
                    file_put_contents(
                        $path,
                        str_replace($line, $key2, file_get_contents($path))
                    );
                }

                if (strpos($line, $keyword3) !== false) {
                    file_put_contents(
                        $path,
                        str_replace($line, $key3, file_get_contents($path))
                    );
                }
            }
        }

        $this->info("The .env file has been successfully created!");

        return true;
    }
}
