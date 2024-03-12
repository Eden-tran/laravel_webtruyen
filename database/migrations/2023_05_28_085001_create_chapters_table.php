<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('manga_id')->unsigned();
            $table->boolean('active');
            $table->bigInteger('user_id')->unsigned();
            $table->integer('status');
            $table->string('slug')->nullable();
            $table->foreign('manga_id')
                ->references('id')
                ->on('mangas')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
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
        Schema::table('chapters', function (Blueprint $table) {
            $table->dropForeign('chapters_manga_id_foreign');
            $table->dropForeign('chapters_user_id_foreign');
        });
        Schema::dropIfExists('chapters');
    }
}
