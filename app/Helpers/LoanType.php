<?php
namespace App\Helpers;

/*
 * Author: Raksa Eng
 */

class LoanType
{
    const STANDARD = [
        'id' => 1,
        'name' => 'standard',
        'description' => 'Standard loan',
    ];

    public static function isStandard($typeId)
    {
        return $typeId == self::STANDARD['id'];
    }
}
