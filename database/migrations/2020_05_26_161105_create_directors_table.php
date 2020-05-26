<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() : void
    {
        Schema::create('directors', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('firstname', 255);
            $table->string('lastname', 255);
            $table->date('dob')->nullable('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void
    {
        Schema::dropIfExists('directors');
    }
}
