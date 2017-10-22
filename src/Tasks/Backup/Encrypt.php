<?php

namespace Spatie\Backup\Tasks\Backup;

use Defuse\Crypto\File as CryptoFile;
use Illuminate\Support\Facades\File;
use Spatie\Backup\Exceptions\BackupNotFound;
use Spatie\Backup\Helpers\Encryption;

class Encrypt
{
    public static function encryptFile(string $fileName): string
    {
        consoleOutput()->info('Starting encryption...');

        if (!File::exists($fileName)) {
            throw BackupNotFound::fileNotFound($fileName);
        }

        $encryptedFile = $fileName . '.encrypted';

        CryptoFile::encryptFile($fileName, $encryptedFile, Encryption::getKey());

        /**
         * Overwrite the original file with the encrypted file.
         * We cannot replace the original file directly, it has to be encrypted fully first.
         */
        rename($encryptedFile, $fileName);

        consoleOutput()->info('Encryption completed!');

        return $fileName;
    }
}
