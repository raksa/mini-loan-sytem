<?php
use App\User;
use Illuminate\Database\Seeder;

/*
 * Author: Raksa Eng
 */
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
            'name' => 'raksa',
            'email' => 'eng.raksa@gmail.com',
            'password' => bcrypt('123456'),
        ]);
        $user->email_verified_at = now();
        $user->save();
    }
}
