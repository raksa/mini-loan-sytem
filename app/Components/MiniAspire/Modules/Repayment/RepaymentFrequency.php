<?php
namespace App\Components\MiniAspire\Modules\Repayment;

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

    public static function isMonthly($repaymentId)
    {
        return $repaymentId == self::MONTHLY['id'];
    }
    public static function isFortnightly($repaymentId)
    {
        return $repaymentId == self::FORTNIGHTLY['id'];
    }
    public static function isWeekly($repaymentId)
    {
        return $repaymentId == self::WEEKLY['id'];
    }
}
