<?php
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->fill([
            'name' => 'test1',
            'email' => 'test1@localhost',
            'password' => '123',
        ]);
        $user->email_verified_at = now();
        $user->save();
    }
}
