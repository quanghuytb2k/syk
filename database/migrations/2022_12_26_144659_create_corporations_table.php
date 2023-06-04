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
        Schema::create('corporations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('agencies_id')->references('id')->on('agencies');
            $table->string('name', 255);
            $table->string('prefecture', 100);
            $table->string('address', 255);
            $table->integer('size_of_employee');
            $table->string('phone', 255)->nullable();
            $table->integer('industry_category_id');
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
        Schema::dropIfExists('corporations');
    }
};
