<?php

use App\Components\MiniAspire\Modules\User\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(User::TABLE_NAME, function (Blueprint $table) {
            $table->increments(User::ID);
            $table->string(User::USER_CODE, 20)->unique();
            $table->string(User::FIRST_NAME, 50);
            $table->string(User::LAST_NAME, 50);
            $table->string(User::PHONE_NUMBER, 50);
            $table->longText(User::ADDRESS);
            $table->timestamp(User::LAST_UPDATED)->default(
                DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP')
            );
            $table->timestamp(User::CREATED)->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(User::TABLE_NAME);
    }
}
