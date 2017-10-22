<?php

namespace Spatie\Backup\Commands;

use Exception;
use Spatie\Backup\Tasks\Backup\Decrypt;

class DecryptCommand extends BaseCommand
{
    /** @var string */
    protected $signature = 'backup:decrypt {file}';

    /** @var string */
    protected $description = 'Decrypt a backup file.';

    public function handle()
    {
        try {
            Decrypt::decryptFile($this->argument('file'));
        } catch (Exception $exception) {
            consoleOutput()->error("Decryption failed because: {$exception->getMessage()}.");

            return -1;
        }
    }
}
