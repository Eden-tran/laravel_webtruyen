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
            $table->string('name');
            $table->string('email')->unique();
            $table->string('avatar')->default('default.jpg');
            $table->string('password');
            $table->bigInteger('user_id')->nullable()->default(0);
            $table->bigInteger('group_id')->nullable()->unsigned();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('active')->default(1);
            $table->rememberToken();
            $table->timestamps();
            // $table->foreign('group_id')
            //     ->references('id')
            //     ->on('group')
            //     ->onDelete('cascade');
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
        // Schema::table('users', function (Blueprint $table) {
        //     $table->dropForeign('users_group_id_foreign');
        // });
    }
}