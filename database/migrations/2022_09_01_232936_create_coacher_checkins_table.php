<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoacherCheckinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coacher_checkins', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->unsignedBigInteger('studio_id');
            $table->foreign('studio_id')->references('id')->on('studios')->cascadeOnDelete();
            $table->unsignedInteger('coacher_id');
            $table->foreign('coacher_id')->references('id')->on('users')->cascadeOnDelete();
            $table->date('date')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longtitude')->nullable();
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
        Schema::dropIfExists('coacher_checkins');
    }
}
