<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanEnv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:clean {--backup : create .env.bak before modifying}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove BOM and non-printable/control characters from .env file';

    public function handle()
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            $this->error('.env not found at: ' . $envPath);
            return 1;
        }

        if ($this->option('backup')) {
            copy($envPath, $envPath . '.bak');
            $this->info('Backup created: .env.bak');
        }

        $bytes = file_get_contents($envPath);
        // Remove UTF-8 BOM
        if (substr($bytes, 0, 3) === "\xEF\xBB\xBF") {
            $bytes = substr($bytes, 3);
            $this->info('BOM removed.');
        } else {
            $this->info('No BOM found.');
        }

        // Remove non-printable/control characters except CR(13), LF(10), TAB(9)
        $chars = preg_split('//u', $bytes, -1, PREG_SPLIT_NO_EMPTY);
        $filtered = '';
        foreach ($chars as $ch) {
            $ord = ord($ch);
            if ($ord >= 32 || $ord === 9 || $ord === 10 || $ord === 13) {
                $filtered .= $ch;
            } else {
                // skip
            }
        }

        file_put_contents($envPath, $filtered);
        $this->info('.env cleaned successfully.');

        $this->info('Now run: php artisan config:clear && php artisan cache:clear');
        return 0;
    }
}


