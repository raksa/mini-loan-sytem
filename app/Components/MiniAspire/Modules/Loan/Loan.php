<?php
namespace App\Components\MiniAspire\Modules\Loan;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/*
 * Author: Raksa Eng
 */
class Loan extends Model
{

    const TABLE_NAME = 'loans';

    const ID = 'id';
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
    public function getLastUpdatedTime()
    {
        return new Carbon($this->{self::LAST_UPDATED}) . '';
    }
    public function getCreatedTime()
    {
        return new Carbon($this->{self::CREATED}) . '';
    }

    public function setProps($data)
    {
        $this->{self::AMOUNT} = $data[self::AMOUNT];
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
}
