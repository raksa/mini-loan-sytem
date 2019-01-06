<?php
namespace App\Components\CoreComponent\Modules\Repayment;

use App\Components\CoreComponent\Modules\Loan\Loan;
use App\Components\CoreComponent\Modules\Repayment\RepaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * Author: Raksa Eng
 */
class Repayment extends Model
{
    use SoftDeletes;
    protected $table = 'repayments';
    protected $primaryKey = 'id';
    protected $fillable = [
        'active',
        'loan_id',
        'amount',
        'payment_status',
        'due_date',
        'date_of_payment',
        'remarks',
    ];
    protected $attributes = [
        'active' => true,
    ];
    protected $casts = [
        'active' => 'boolean',
        'due_date' => 'datetime',
        'date_of_payment' => 'datetime',
    ];
    protected $hidden = [
        'active',
        'deleted_at',
    ];

    public function setPaymentStatusAttribute($value)
    {
        if (!RepaymentStatus::isValidStatus($value)) {
            throw new \Exception(trans('default.invalid_payment_status'));
        }
        $this->attributes['payment_status'] = $value;
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
    public function scopeOrderDesc($query)
    {
        return $query->orderBy('id', 'desc');
    }

    /**
     * Association many to one, many repayments can have same one loan.
     */
    public function loan()
    {
        return $this->hasOne(Loan::class, 'id', 'loan_id');
    }

    public function activate($isActive)
    {
        $this->active = $isActive;
        return $this->save();
    }
}
