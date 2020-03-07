<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConversationUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversation_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('conversation_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->timestamps();

            $table->index('updated_at')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('conversation_user');
    }
}
