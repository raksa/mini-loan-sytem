<?php
namespace App\Components\CoreComponent\Modules\Repayment;

/*
 * Author: Raksa Eng
 */
class RepaymentStatus
{
    const PAID = [
        'id' => 1,
        'name' => 'paid',
        'description' => 'Paid status',
    ];
    const UNPAID = [
        'id' => 2,
        'name' => 'unpaid',
        'description' => 'Unpaid status',
    ];

    public static function isPaid($statusId)
    {
        return $statusId == self::PAID['id'];
    }
    public static function isUnpaid($statusId)
    {
        return $statusId == self::UNPAID['id'];
    }
}
