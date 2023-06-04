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
        Schema::create('energy_usages', function (Blueprint $table) {
            $table->id();
            $table->string('energy_type_id', 45);
            $table->string('usage_year', 4)->nullable();
            $table->string('usage_month', 2)->nullable();
            $table->integer('amount')->nullable();
            $table->integer('money')->nullable();
            $table->string('converted_co2_amount', 45)->nullable();
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
        Schema::dropIfExists('energy_usages');
    }
};
