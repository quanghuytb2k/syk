<?php

namespace App\Enums;

final class EquipmentEnums
{
    const CONTRACT_BUY = 1;
    const CONTRACT_LEASE = 2;

    public static function getContractTypeParams($type)
    {
        $arr = [
            self::CONTRACT_BUY => '購入',
            self::CONTRACT_LEASE => 'リース'
        ];

        return $arr[$type] ?? '';
    }
}
