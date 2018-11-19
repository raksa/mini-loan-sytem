<?php

use App\Components\MiniAspire\Modules\Loan\Loan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->double(Loan::AMOUNT, 18, 8);
            $table->timestamp(Loan::CREATED)->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp(Loan::LAST_UPDATED)->default(
                DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP')
            );
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
