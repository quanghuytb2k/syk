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
        Schema::create('m_maintain_company_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('order');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('m_maintain_company_detail_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('order');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('maintain_companies', function(Blueprint $table) {
            $table->foreignId('maintain_company_type_id')->references('id')->on('m_maintain_company_types');
            $table->foreignId('maintain_company_detail_type_id')->references('id')->on('m_maintain_company_detail_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
