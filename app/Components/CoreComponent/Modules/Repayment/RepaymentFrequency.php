<?php
namespace App\Components\CoreComponent\Modules\Repayment;

/*
 * Author: Raksa Eng
 */
class RepaymentFrequency
{
    const MONTHLY = [
        'id' => 1,
        'name' => 'monthly',
        'description' => 'The most common repayment frequency',
    ];
    const FORTNIGHTLY = [
        'id' => 2,
        'name' => 'fortnightly',
        'description' => 'Fortnightly repayment frequency',
    ];
    const WEEKLY = [
        'id' => 3,
        'name' => 'weekly',
        'description' => 'Weekly repayment frequency',
    ];

    public static function isValidType($typeId)
    {
        return \in_array($typeId, [
            self::MONTHLY['id'],
            self::FORTNIGHTLY['id'],
            self::WEEKLY['id'],
        ]);
    }
    public static function isMonthly($typeId)
    {
        return $typeId == self::MONTHLY['id'];
    }
    public static function isFortnightly($typeId)
    {
        return $typeId == self::FORTNIGHTLY['id'];
    }
    public static function isWeekly($typeId)
    {
        return $typeId == self::WEEKLY['id'];
    }

    public static function toArrayForApi()
    {
        return [
            self::MONTHLY['id'] => self::MONTHLY['name'],
            self::FORTNIGHTLY['id'] => self::FORTNIGHTLY['name'],
            self::WEEKLY['id'] => self::WEEKLY['name'],
        ];
    }
}
