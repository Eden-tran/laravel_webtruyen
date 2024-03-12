<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMangasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mangas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('name');
            $table->string('another_name')->nullable();
            $table->string('author');
            $table->mediumText('describe')->nullable();
            // $table->string('categories');
            $table->boolean('is_finished');
            $table->boolean('active');
            $table->string('slug');
            $table->string('image_cover');
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mangas', function (Blueprint $table) {
            $table->dropForeign('mangas_user_id_foreign');
        });
        Schema::dropIfExists('mangas');
    }
}
