<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('maintain_companies', function (Blueprint $table) {
            $table->integer('code')->nullable();
            $table->string('street', 255)->nullable();
            $table->string('building', 255)->nullable();
            $table->string('municipality', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maintain_companies', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('street');
            $table->dropColumn('building');
            $table->dropColumn('municipality');
        });
    }
};
