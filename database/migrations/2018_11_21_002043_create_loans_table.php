<?php

use App\Components\CoreComponent\Modules\Loan\Loan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Author: Raksa Eng
 */
class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Loan::TABLE_NAME, function (Blueprint $table) {
            $table->increments(Loan::ID);
            $table->integer(Loan::CLIENT_ID, false, true);
            $table->foreign(Loan::CLIENT_ID)->references('id')->on('clients');
            $table->double(Loan::AMOUNT, 18, 8)->unsigned();
            $table->integer(Loan::DURATION)->unsigned();
            $table->integer(Loan::REPAYMENT_FREQUENCY)->unsigned();
            $table->double(Loan::INTEREST_RATE, 3, 2)->unsigned();
            $table->double(Loan::ARRANGEMENT_FEE, 18, 8)->unsigned();
            $table->longText(Loan::REMARKS)->nullable();
            $table->timestamp(Loan::DATE_CONTRACT_START)->nullable();
            $table->timestamp(Loan::DATE_CONTRACT_END)->nullable();
            $table->timestamp(Loan::LAST_UPDATED)->default(
                DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP')
            );
            $table->timestamp(Loan::CREATED)->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Loan::TABLE_NAME);
    }
}
