<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawal_list', function (Blueprint $table) {
            $table->id();
            $table->date('processing_date');
            $table->bigInteger('user_id');
            $table->string('email');
            $table->string('payment_method');
            $table->decimal('amount', 20, 2);
            $table->integer('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdrawal_list');
    }
}
