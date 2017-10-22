<?php

namespace Spatie\Backup\Tasks\Backup;

use Defuse\Crypto\File as CryptoFile;
use Illuminate\Support\Facades\File;
use Spatie\Backup\Exceptions\BackupNotFound;
use Spatie\Backup\Helpers\Encryption;

class Decrypt
{
    public static function decryptFile(string $fileName) : string
    {
        consoleOutput()->info('Starting decryption...');

        if (!File::exists($fileName)) {
            throw BackupNotFound::fileNotFound($fileName);
        }

        $decryptedFile = $fileName . '.decrypted';

        CryptoFile::decryptFile($fileName, $decryptedFile, Encryption::getKey());

        /**
         * Overwrite the original encrypted file with the decrypted file.
         * We cannot replace the encrypted file directly, it has to be decrypted fully first.
         */
        rename($decryptedFile, $fileName);

        consoleOutput()->info('Decryption completed!');

        return $fileName;
    }
}
