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
        Schema::table('buildings', function(Blueprint $table) {
            $table->string('name');
            $table->tinyInteger('building_type_id')->nullable()->references('id')->on('m_building_types');
            $table->tinyInteger('building_construction_type_id')->nullable()->references('id')->on('building_construction_types');
            $table->integer('floor_size')->unsigned()->nullable()->change();
            $table->tinyInteger('is_direct_profit')->comment('0: 間接, 1: 直接')->default(0);
            $table->integer('build_area_square')->unsigned()->nullable()->comment('建屋面積');
            $table->integer('area_square')->unsigned()->nullable()->comment('敷地面積');
            $table->integer('employee_count')->unsigned()->nullable()->comment('従業員数');
            $table->text('memo')->nullable()->comment('メモ');
            $table->dropColumn('facilities_id');
            $table->bigInteger('agency_id')->unsigned()->references('id')->on('agencies');
            $table->bigInteger('company_id')->unsigned()->references('id')->on('companies');
            $table->softDeletes();
            $table->renameColumn('floor_size', 'floor_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
