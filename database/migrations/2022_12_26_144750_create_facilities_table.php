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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->bigInteger('prefecture_id')->unsigned();
            $table->bigInteger('agency_id')->references('id')->on('agencies');
            $table->bigInteger('corporation_id')->references('id')->on('corporations');
            $table->string('address', 255)->nullable();
            $table->integer('size_of_employee')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('job_title', 45)->nullable();
            $table->text('memo')->nullable();
            $table->text('concern')->nullable();
            $table->tinyInteger('status')->default(\App\Enums\FacilityEnums::ACTIVE_STATUS);
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
        Schema::dropIfExists('facilities');
    }
};
