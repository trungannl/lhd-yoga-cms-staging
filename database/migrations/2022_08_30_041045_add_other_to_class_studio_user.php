<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtherToClassStudioUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('studio_user', function (Blueprint $table) {
            $table->date('start_date')->nullable()->comment('begin date of class');
            $table->date('end_date')->nullable()->comment('end date of class');
            $table->integer('price')->default(0)->comment('class price');
            $table->integer('number_of_sessions')->default('0');
            $table->boolean('approve')->default(0);
            $table->boolean('is_paid')->default(0);
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
        Schema::table('studio_user', function (Blueprint $table) {
            //
        });
    }
}
