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
        self::down();
        Schema::table('equipments', function(Blueprint $table) {
            $table->integer('equipment_type_id')->references('id')->on('m_equipment_types');
            $table->string('maker')->nullable();
            $table->string('model')->nullable();
            $table->float('capacity')->nullable();
            $table->string('installation_detail_area')->nullable();
            $table->date('installation_date')->nullable();
            $table->tinyInteger('contract_type')->nullable()->comment('1: 購入, 2:リース');
            $table->text('memo')->nullable();
            $table->bigInteger('agency_id')->references('id')->on('agencies');
            $table->bigInteger('company_id')->references('id')->on('companies');
            $table->bigInteger('facility_id')->nullable()->references('id')->on('facilities');
            $table->bigInteger('building_id')->nullable()->references('id')->on('buildings');
            $table->text('equipment_lease_company')->nullable();
            $table->text('equipment_buy_company')->nullable();
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
        Schema::table('equipments', function (Blueprint $table) {
            $table->dropColumn('buildings_id');
        });
    }
};
