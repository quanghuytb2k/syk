<?php

use App\Enums\MaintainCompanyEnums;
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
        Schema::create('maintain_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('size_of_employee')->nullable();
            $table->string('ownership')->nullable();
            $table->string('license_number')->nullable();
            $table->string('construction_area')->nullable();
            $table->string('construction_range')->nullable();
            $table->foreignId('prefecture_id')->references('id')->on('m_prefectures');
            $table->tinyInteger('status')->default(MaintainCompanyEnums::ACTIVE);
            $table->text('address')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_person_role')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->string('email')->nullable();
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
        Schema::dropIfExists('maintain_companies');
    }
};
