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
        Schema::create('energy_contracts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('agency_id')->references('id')->on('agencies')->comment('代理店ID');
            $table->bigInteger('company_id')->references('id')->on('companies')->comment('企業ID');
            $table->bigInteger('facility_id')->nullable()->references('id')->on('facilities')->comment('施設ID');
            $table->bigInteger('building_id')->nullable()->references('id')->on('buildings')->comment('建屋ID');
            $table->bigInteger('equipment_id')->nullable()->references('id')->on('equipments')->comment('設備ID');
            $table->bigInteger('energy_type_id')->references('id')->on('m_energy_types')->comment('エネルギ種類');
            $table->double('co2_convert_coefficient')->nullable()->comment('CO2変換変数');
            $table->string('contract_company_name')->nullable();
            $table->text('memo')->nullable();
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
        Schema::dropIfExists('energy_contracts');
    }
};
