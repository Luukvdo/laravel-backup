<?php

namespace Spatie\Backup\Commands;

use Exception;
use Spatie\Backup\Tasks\Backup\Encrypt;

class EncryptCommand extends BaseCommand
{
    /** @var string */
    protected $signature = 'backup:encrypt {file}';

    /** @var string */
    protected $description = 'Encrypt a backup file.';

    public function handle()
    {
        try {
            Encrypt::encryptFile($this->argument('file'));
        } catch (Exception $exception) {
            consoleOutput()->error("Encryption failed because: {$exception->getMessage()}.");

            return -1;
        }
    }
}
