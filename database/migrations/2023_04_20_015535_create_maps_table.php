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
        Schema::create('maps', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('facility_id')->nullable();
            $table->integer('drawing_type_id')->nullable();
            $table->integer('code')->nullable();
            $table->string('storage_location', 255)->nullable();
            $table->date('date_created')->nullable();
            $table->string('creator', 255)->nullable();
            $table->string('note', 255)->nullable();
            $table->text('files')->nullable();
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
        Schema::dropIfExists('maps');
    }
};
