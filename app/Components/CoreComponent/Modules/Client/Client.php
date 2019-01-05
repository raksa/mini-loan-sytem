<?php
namespace App\Components\CoreComponent\Modules\Client;

use App\Components\CoreComponent\Modules\Loan\Loan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * Author: Raksa Eng
 */
class Client extends Model
{
    use SoftDeletes;
    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $fillable = [
        'active',
        'client_code',
        'first_name',
        'last_name',
        'phone_number',
        'address',
    ];
    protected $attributes = [
        'active' => true,
    ];
    protected $casts = [
        'active' => 'boolean',
    ];
    protected $hidden = [
        'active',
        'deleted_at',
    ];

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = strtolower($value);
    }
    public function getFirstNameAttribute($value)
    {
        return ucfirst($value);
    }
    public function getLastNameAttribute($value)
    {
        return ucfirst($value);
    }
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
    public function scopeOrderDesc($query)
    {
        return $query->orderBy('id', 'desc');
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function activate($isActive)
    {
        foreach ($this->loans as $loan) {
            $loan->activate($isActive);
        }
        $this->active = $isActive;
        return $this->save();
    }
    public function delete()
    {
        foreach ($this->loans as $loan) {
            $loan->delete();
        }
        return parent::delete();
    }
    public function forceDelete()
    {
        foreach ($this->loans as $loan) {
            $loan->forceDelete();
        }
        return parent::forceDelete();
    }
}
