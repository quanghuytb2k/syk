<?php

namespace App\Enums;

final class FileEnums
{
    const CONTRACT_FILE = 1;

    const USAGE_FILE = 2;

    /**
     * @param int $key
     * @return string
     */
    public static function getFileParam(int $key): string
    {
        $params = [
            self::CONTRACT_FILE => 'contract_id',
            self::USAGE_FILE => 'usage_id'
        ];

        return $params[$key];
    }
}
