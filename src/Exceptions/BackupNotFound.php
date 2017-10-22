<?php

namespace Spatie\Backup\Exceptions;

use Exception;

class BackupNotFound extends Exception
{
    public static function fileNotFound(string $fileName): BackupNotFound
    {
        return new static("File `{$fileName}` not found.");
    }
}
