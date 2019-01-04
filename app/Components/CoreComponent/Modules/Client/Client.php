<?php
namespace App\Components\CoreComponent\Modules\Client;

use App\Components\CoreComponent\Modules\Loan\Loan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

// TODO: use all eloquent features
/*
 * Author: Raksa Eng
 */
class Client extends Model
{

    const TABLE_NAME = 'clients';

    const ID = 'id';
    const CLIENT_CODE = 'client_code'; //special string to make unique identify client
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const PHONE_NUMBER = 'phone_number';
    const ADDRESS = 'address';
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
    public function getClientCode()
    {
        return $this->{self::CLIENT_CODE};
    }
    public function getFirstName()
    {
        return $this->{self::FIRST_NAME};
    }
    public function getLastName()
    {
        return $this->{self::LAST_NAME};
    }
    public function getPhoneNumber()
    {
        return $this->{self::PHONE_NUMBER};
    }
    public function getAddress()
    {
        return $this->{self::ADDRESS};
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
        $this->{self::FIRST_NAME} = $data[self::FIRST_NAME];
        $this->{self::LAST_NAME} = $data[self::LAST_NAME];
        $this->{self::PHONE_NUMBER} = $data[self::PHONE_NUMBER];
        isset($data[self::ADDRESS]) && ($this->{self::ADDRESS} = $data[self::ADDRESS]);
        $this->{self::CLIENT_CODE} = $data[self::CLIENT_CODE];
    }

    /**
     * Association one to many, one client can belong to many loans.
     */
    public function loans()
    {
        return $this->hasMany(Loan::class,
            Loan::CLIENT_ID,
            self::ID);
    }

    /**
     * Force delete this record
     */
    public function deleteThis()
    {
        foreach ($this->loans as $loan) {
            $loan->deleteThis();
        }
        $this->delete();
    }
}
