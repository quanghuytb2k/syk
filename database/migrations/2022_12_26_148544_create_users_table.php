<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->bigInteger('corporations_id')->references('id')->on('corporations')->nullable();
            $table->bigInteger('facilities_id')->references('id')->on('facilities')->nullable();
            $table->bigInteger('agencies_id')->references('id')->on('agencies')->nullable();
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('first_name', 45);
            $table->string('last_name', 45);
            $table->string('first_name_kana', 45);
            $table->string('last_name_kana', 45);
            $table->string('role', 45);
            $table->string('phone_number', 45)->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('department', 255)->nullable();
            $table->dateTime('last_login_at');
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
};
