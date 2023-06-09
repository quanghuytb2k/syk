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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('agency_id')->nullable()->change();
            $table->foreignId('company_id')->nullable()->change();
            $table->foreignId('facility_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('agency_id')->nullable(false)->change();
            $table->foreignId('company_id')->nullable(false)->change();
            $table->foreignId('facility_id')->nullable(false)->change();
        });
    }
};
