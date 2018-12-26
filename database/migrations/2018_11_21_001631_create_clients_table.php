<?php

use App\Components\CoreComponent\Modules\Client\Client;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Client::TABLE_NAME, function (Blueprint $table) {
            $table->increments(Client::ID);
            $table->string(Client::CLIENT_CODE, 20)->unique();
            $table->string(Client::FIRST_NAME, 50);
            $table->string(Client::LAST_NAME, 50);
            $table->string(Client::PHONE_NUMBER, 50)->unique();
            $table->longText(Client::ADDRESS)->nullable();
            $table->timestamp(Client::LAST_UPDATED)->default(
                DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP')
            );
            $table->timestamp(Client::CREATED)->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Client::TABLE_NAME);
    }
}
