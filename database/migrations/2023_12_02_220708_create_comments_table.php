<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->bigInteger('manga_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('parent_comment_id')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('manga_id')
                ->references('id')
                ->on('mangas')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('parent_comment_id')
                ->references('id')
                ->on('comments')
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
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign('comments_manga_id_foreign');
            $table->dropForeign('comments_user_id_foreign');
            $table->dropForeign('comments_parent_comment_id_foreign');
        });
        Schema::dropIfExists('comments');
    }
}
