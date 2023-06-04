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
        $this->down();
        Schema::create('energy_usages', function (Blueprint $table) {
            $table->id();
            $table->date('usage_from_date')->nullable()->comment('使用日from');
            $table->date('usage_to_date')->nullable()->comment('使用日to');
            $table->date('billing_date')->nullable()->comment('請求日');
            $table->float('money')->nullable()->comment('金額');
            $table->double('amount')->comment('使用量');
            $table->string('converted_co2_amount')->nullable()->comment('CO2変換された量');
            $table->bigInteger('energy_contract_id')->references('id')->on('energy_contracts');
            $table->bigInteger('agency_id')->references('id')->on('agencies');
            $table->bigInteger('company_id')->references('id')->on('companies');
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
        Schema::dropIfExists('energy_usages');
    }
};
