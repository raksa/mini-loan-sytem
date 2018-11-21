<?php

use App\Components\MiniAspire\Modules\Loan\Loan;
use App\Components\MiniAspire\Modules\Repayment\Repayment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integer(Repayment::LOAN_ID, false, true);
            $table->foreign(Repayment::LOAN_ID)
                ->references(Loan::ID)
                ->on(Loan::TABLE_NAME);
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
