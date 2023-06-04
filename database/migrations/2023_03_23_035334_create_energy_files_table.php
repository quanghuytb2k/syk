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
        Schema::dropIfExists('energy_contract_files');
        Schema::create('energy_files', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('contract_id')->nullable()->references('id')->on('energy_contracts');
            $table->bigInteger('usage_id')->nullable()->references('id')->on('energy_usages');
            $table->string('name');
            $table->string('path');
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
        Schema::dropIfExists('energy_files');
    }
};
