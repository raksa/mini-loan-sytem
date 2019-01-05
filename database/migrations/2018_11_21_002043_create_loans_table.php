<?php

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
        Schema::create('loans', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default(true);
            $table->integer('client_id', false, true);
            $table->foreign('client_id')->references('id')->on('clients');
            $table->double('amount', 18, 8)->unsigned();
            $table->integer('duration')->unsigned();
            $table->integer('repayment_frequency')->unsigned();
            $table->double('interest_rate', 3, 2)->unsigned();
            $table->double('arrangement_fee', 18, 8)->unsigned();
            $table->longText('remarks')->nullable();
            $table->timestamp('date_contract_start')->nullable();
            $table->timestamp('date_contract_end')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
