<?php

use App\Components\CoreComponent\Modules\Repayment\Repayment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Author: Raksa Eng
 */
class CreateRepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Repayment::TABLE_NAME, function (Blueprint $table) {
            $table->increments(Repayment::ID);
            $table->boolean(Repayment::ACTIVE)->default(true);
            $table->integer(Repayment::LOAN_ID, false, true);
            $table->foreign(Repayment::LOAN_ID)->references('id')->on('loans');
            $table->double(Repayment::AMOUNT, 18, 8)->unsigned();
            $table->integer(Repayment::PAYMENT_STATUS)->unsigned();
            $table->timestamp(Repayment::DUE_DATE)->nullable();
            $table->timestamp(Repayment::DATE_OF_PAYMENT)->nullable();
            $table->longText(Repayment::REMARKS)->nullable();
            $table->timestamp(Repayment::LAST_UPDATED)->default(
                DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP')
            );
            $table->timestamp(Repayment::CREATED)->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Repayment::TABLE_NAME);
    }
}
