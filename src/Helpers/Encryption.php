<?php

namespace Spatie\Backup\Helpers;

use Defuse\Crypto\Encoding;
use Defuse\Crypto\Key;
use Illuminate\Support\Facades\Crypt;

class Encryption
{
    /**
     * Create a Key object using the Laravel APP_KEY
     *
     * @return Key
     */
    public static function getKey()
    {
        return Key::loadFromAsciiSafeString(Encoding::saveBytesToChecksummedAsciiSafeString(
            Key::KEY_CURRENT_VERSION,
            Crypt::getKey()
        ));
    }
}
