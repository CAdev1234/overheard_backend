<?php

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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->unique();
            $table->mediumText('avatar')->nullable();
            $table->string('firebaseUID')->nullable();
            $table->mediumText('bio')->nullable();
            $table->string('phonenumber', 50)->nullable();
            $table->integer('community_id')->nullable();
            $table->tinyInteger('isReporter')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->tinyInteger('isActive')->default(1);
            $table->tinyInteger('isVerified')->default(1);
            $table->string('authSource')->default('Email');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
