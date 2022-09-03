<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staffs', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('name',255);
            $table->string('email')->unique();
            $table->string('password',255);
            $table->boolean('active')->default(1);
            $table->timestamp('last_login')->nullable();
            $table->timestamp('joined_date')->nullable();
            $table->integer('role_id')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('staffs');
    }
}
