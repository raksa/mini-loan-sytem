<?php
namespace App\Components\MiniAspire\Modules\User;

use App\Components\MiniAspire\Modules\Loan\Loan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/*
 * Author: Raksa Eng
 */
class User extends Model
{

    const TABLE_NAME = 'users';

    const ID = 'id';
    const USER_CODE = 'user_code'; //special string to make unique identify user
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
    public function getUserCode()
    {
        return $this->{self::USER_CODE};
    }
    public static function generateUserCode()
    {
        $latestUserRecord = self::orderBy(self::ID, 'desc')->first();
        if (!$latestUserRecord) {
            return 'a000000';
        }
        $userCode = $latestUserRecord->getUserCode();
        while (true) {
            if (!self::where(self::USER_CODE, ++$userCode)->exists()) {
                break;
            }
        }
        return $userCode;
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
        $this->{self::USER_CODE} = static::generateUserCode();
    }

    /**
     * Association one to many, one user can belong to many loans.
     */
    public function loans()
    {
        return $this->hasMany(Loan::class,
            Loan::USER_ID,
            self::ID);
    }

    /**
     * Filter user as pagination
     */
    public static function filterUser($data = [])
    {
        $result = self::orderBy(self::ID, 'desc');
        $users = $result->paginate($data['perPage']);
        return $users;
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
