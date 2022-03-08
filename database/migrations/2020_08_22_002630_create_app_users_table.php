<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_users', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('avatar')->nullable();
            $table->bigInteger('community_id')->nullable();
            $table->date('registered_date')->nullable();
            $table->integer('verified_reported')->default(0);
            $table->decimal('wallet_balance')->default(0);
            $table->string('remember_token')->nullable();
            $table->integer('isBlocked')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customertype');
    }
}
