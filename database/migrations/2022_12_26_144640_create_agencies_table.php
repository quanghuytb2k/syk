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
        Schema::create('agencies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->integer('prefecture_id')->unsigned();
            $table->string('building', 255)->nullable();
            $table->string('street', 255)->nullable();
            $table->string('municipality', 255)->nullable();
            $table->string('contact_person_name', 255)->nullable();
            $table->string('job_title', 45)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('mail', 255)->nullable();
            $table->tinyInteger('status')->nullable();
            $table->text('memo')->nullable();
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
        Schema::dropIfExists('agencies');
    }
};
