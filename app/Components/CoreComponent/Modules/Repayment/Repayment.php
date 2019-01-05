<?php
namespace App\Components\CoreComponent\Modules\Repayment;

use App\Components\CoreComponent\Modules\Loan\Loan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

// TODO: use all eloquent features
/*
 * Author: Raksa Eng
 */
class Repayment extends Model
{

    const TABLE_NAME = 'repayments';

    const ID = 'id';
    const LOAN_ID = 'loan_id';
    const AMOUNT = 'amount';
    const PAYMENT_STATUS = 'payment_status';
    const DUE_DATE = 'due_date';
    const DATE_OF_PAYMENT = 'date_of_payment';
    const REMARKS = 'remarks';
    const LAST_UPDATED = 'last_updated';
    const CREATED = 'created';

    // Disable default timestamp to easy control
    public $timestamps = false;

    protected $primaryKey = self::ID;

    protected $table = self::TABLE_NAME;

    public function getId()
    {
        return $this->{self::ID};
    }
    public function getAmount()
    {
        return $this->{self::AMOUNT};
    }
    public function getPaymentStatusId()
    {
        return $this->{self::PAYMENT_STATUS};
    }
    public function setPaymentStatusId($statusId)
    {
        $this->{self::PAYMENT_STATUS} = $statusId;
        if (RepaymentStatus::isPaid($statusId)) {
            $this->{self::DATE_OF_PAYMENT} = Carbon::now();
        }
    }
    public function getDueDate()
    {
        return new Carbon($this->{self::DUE_DATE});
    }
    public function getDateOfPayment()
    {
        return new Carbon($this->{self::DATE_OF_PAYMENT});
    }
    public function getRemarks()
    {
        return $this->{self::REMARKS};
    }
    public function setRemarks($remarks)
    {
        $this->{self::REMARKS} = $remarks;
    }
    public function getLastUpdatedTime()
    {
        return new Carbon($this->{self::LAST_UPDATED});
    }
    public function getCreatedTime()
    {
        return new Carbon($this->{self::CREATED});
    }

    public function setProps($data)
    {
        $this->{self::LOAN_ID} = $data[self::LOAN_ID];
        $this->{self::AMOUNT} = $data[self::AMOUNT];
        $this->{self::PAYMENT_STATUS} = $data[self::PAYMENT_STATUS];
        $this->{self::DUE_DATE} = $data[self::DUE_DATE];
        $this->{self::DATE_OF_PAYMENT} = $data[self::DATE_OF_PAYMENT];
        $this->{self::REMARKS} = $data[self::REMARKS];
    }

    /**
     * Association many to one, many repayments can have same one loan.
     */
    public function loan()
    {
        return $this->hasOne(Loan::class,
            Loan::ID,
            self::LOAN_ID);
    }

    public function activate($isActive)
    {
        $this->active = $isActive;
        return $this->save();
    }
}
