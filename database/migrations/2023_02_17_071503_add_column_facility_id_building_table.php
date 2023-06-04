<?php

use App\Enums\BuildingEnums;
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
            $table->bigInteger('facility_id')->references('id')->on('facilities');
            $table->tinyInteger('status')->default(BuildingEnums::ACTIVE_STATUS);
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
