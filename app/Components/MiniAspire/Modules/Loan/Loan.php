<?php
namespace App\Components\MiniAspire\Modules\Loan;

use App\Components\MiniAspire\Modules\Repayment\Repayment;
use App\Components\MiniAspire\Modules\Client\Client;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

// TODO: use all eloquent features
/*
 * Author: Raksa Eng
 */
class Loan extends Model
{

    const TABLE_NAME = 'loans';

    const ID = 'id';
    const CLIENT_ID = 'client_id';
    const AMOUNT = 'amount';
    const DURATION = 'duration';
    const REPAYMENT_FREQUENCY = 'repayment_frequency';
    const INTEREST_RATE = 'interest_rate';
    const ARRANGEMENT_FEE = 'arrangement_fee';
    const REMARKS = 'remarks';
    const DATE_CONTRACT_START = 'date_contract_start';
    const DATE_CONTRACT_END = 'date_contract_end';
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
    public function getMonthsDuration()
    {
        return $this->{self::DURATION};
    }
    public function getRepaymentFrequencyTypeId()
    {
        return $this->{self::REPAYMENT_FREQUENCY};
    }
    public function getMonthlyInterestRate()
    {
        return $this->{self::INTEREST_RATE};
    }
    public function getArrangementFee()
    {
        return $this->{self::ARRANGEMENT_FEE};
    }
    public function getRemarks()
    {
        return $this->{self::REMARKS};
    }
    public function getDateContractStart()
    {
        return new Carbon($this->{self::DATE_CONTRACT_START});
    }
    public function getDateContractEnd()
    {
        return new Carbon($this->{self::DATE_CONTRACT_END});
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
        $this->{self::CLIENT_ID} = $data[self::CLIENT_ID];
        $this->{self::AMOUNT} = $data[self::AMOUNT];
        $this->{self::DURATION} = $data[self::DURATION];
        $this->{self::REPAYMENT_FREQUENCY} = $data[self::REPAYMENT_FREQUENCY];
        $this->{self::INTEREST_RATE} = $data[self::INTEREST_RATE];
        $this->{self::ARRANGEMENT_FEE} = $data[self::ARRANGEMENT_FEE];
        $this->{self::REMARKS} = $data[self::REMARKS];
        $this->{self::DATE_CONTRACT_START} = new Carbon($data[self::DATE_CONTRACT_START]);
        $endDate = $this->getDateContractStart()->copy()->addMonth($this->getMonthsDuration() + 1);
        $this->{self::DATE_CONTRACT_END} = $endDate;
    }

    /**
     * Association one to many, one loan can belong to many repayments.
     */
    public function repayments()
    {
        return $this->hasMany(Repayment::class,
            Repayment::LOAN_ID,
            self::ID);
    }

    /**
     * Association many to one, many loans can have same one client.
     */
    public function client()
    {
        return $this->hasOne(Client::class,
            Client::ID,
            self::CLIENT_ID);
    }

    /**
     * Filter loan as pagination
     */
    public static function filterLoan($data = [])
    {
        $result = self::orderBy(self::ID, 'desc');
        $loans = $result->paginate($data['perPage']);
        return $loans;
    }

    /**
     * Force delete this record
     */
    public function deleteThis()
    {
        foreach ($this->repayments as $repayment) {
            $repayment->delete();
        }
        $this->delete();
    }
}
