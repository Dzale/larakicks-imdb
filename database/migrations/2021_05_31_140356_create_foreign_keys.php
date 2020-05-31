<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('creator_id', 'comments_users_creator_id_fk')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
        Schema::table('favorite_movies', function (Blueprint $table) {
            $table->foreign('user_id', 'favorite_movie_users_user_id_fk')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            $table->foreign('movie_id', 'favorite_movie_movies_movie_id_fk')
                  ->references('id')
                  ->on('movies')
                  ->onDelete('cascade');
        });
        Schema::table('movies_actors', function (Blueprint $table) {
            $table->foreign('movie_id', 'movies_actors_movies_movie_id_fk')
                  ->references('id')
                  ->on('movies')
                  ->onDelete('cascade');
            $table->foreign('actor_id', 'movies_actors_actors_actor_id_fk')
                  ->references('id')
                  ->on('actors')
                  ->onDelete('cascade');
        });
        Schema::table('movies_directors', function (Blueprint $table) {
            $table->foreign('movie_id', 'movies_directors_movies_movie_id_fk')
                  ->references('id')
                  ->on('movies')
                  ->onDelete('cascade');
            $table->foreign('director_id', 'movies_directors_directors_director_id_fk')
                  ->references('id')
                  ->on('directors')
                  ->onDelete('cascade');
        });
        Schema::table('profiles', function (Blueprint $table) {
            $table->foreign('user_id', 'profiles_users_user_id_fk')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
        Schema::table('wishlist_movies', function (Blueprint $table) {
            $table->foreign('user_id', 'wishlist_movie_users_user_id_fk')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            $table->foreign('movie_id', 'wishlist_movie_movies_movie_id_fk')
                  ->references('id')
                  ->on('movies')
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
            $table->dropForeign('comments_users_creator_id_fk');
        });
        Schema::table('favorite_movies', function (Blueprint $table) {
            $table->dropForeign('favorite_movie_users_user_id_fk');
            $table->dropForeign('favorite_movie_movies_movie_id_fk');
        });
        Schema::table('movies_actors', function (Blueprint $table) {
            $table->dropForeign('movies_actors_movies_movie_id_fk');
            $table->dropForeign('movies_actors_actors_actor_id_fk');
        });
        Schema::table('movies_directors', function (Blueprint $table) {
            $table->dropForeign('movies_directors_movies_movie_id_fk');
            $table->dropForeign('movies_directors_directors_director_id_fk');
        });
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropForeign('profiles_users_user_id_fk');
        });
        Schema::table('wishlist_movies', function (Blueprint $table) {
            $table->dropForeign('wishlist_movie_users_user_id_fk');
            $table->dropForeign('wishlist_movie_movies_movie_id_fk');
        });
    }
}
