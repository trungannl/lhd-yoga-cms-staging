<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('studios', function (Blueprint $table) {
            $table->id();
            $table->string('name',255)->comment('name of yoga studio');
            $table->text('description')->nullable();
            $table->string('address',255)->nullable();
            $table->integer('created_by')->default(0)->comment('created by user id');
            $table->boolean('status')->default(0)->comment('0:close|1:open');
            $table->integer('coach_id')->nullable()->comment('responsible coach');
            $table->date('start_date')->nullable()->comment('begin date of class');
            $table->date('end_date')->nullable()->comment('end date of class');
            $table->time('start_time')->nullable()->comment('start time of class');
            $table->time('end_time')->nullable()->comment('end time of class');
            $table->integer('price')->default(0)->comment('class price');
            $table->json('schedule')->nullable();
            $table->index('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('studios');
    }
}
