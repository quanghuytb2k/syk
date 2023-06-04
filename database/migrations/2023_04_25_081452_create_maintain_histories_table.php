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
        Schema::create('maintain_histories', function (Blueprint $table) {
            $table->id();
//            $table->unsignedInteger('equipment_id')->nullable();
            $table->foreignId('equipment_id')->references('id')->on('equipments');
            $table->date('date', 255)->nullable();
            $table->unsignedInteger('money')->nullable();
            $table->string('maintenance_person_name', 255)->nullable();
            $table->text('content')->nullable();
            $table->text('files')->nullable();
            $table->date('next_maintenance_date', 255)->nullable();
            $table->date('next_alarm_date', 255)->nullable();
            $table->unsignedInteger('maintain_company_id')->nullable();
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
        Schema::dropIfExists('maintain_histories');
    }
};
