<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('location')->nullable();
            $table->decimal('lat', 5, 2)->nullable();
            $table->decimal('lng', 5, 2)->nullable();
            $table->bigInteger('upvotes')->default(0);
            $table->bigInteger('downvotes')->default(0);
            $table->bigInteger('seen_count')->default(0);
            $table->bigInteger('comments_count')->default(0);
            $table->timestamp('post_datetime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('generalinfo');
    }
}
