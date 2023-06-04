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
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->rememberToken();
            $table->string('email', 255)->comment('メールアドレス');
            $table->string('password', 255)->comment('パスワード');
            $table->string('name', 255)->comment('氏名');
            $table->string('name_kana', 255)->comment('氏名 かな');
            $table->string('role', 45)->comment('ロール: admin, agency_owner, agency_staff, corporation_owner, coporation_staff, facility_staff');
            $table->unsignedSmallInteger('status')->comment('ステータス 1: 仮登録、2: 本登録、0: 無効');
            $table->string('energy_related_job_title', 100)->nullable()->comment('担当区分');
            $table->string('job_title', 255)->nullable()->comment('役職');
            $table->string('department', 255)->nullable()->comment('所属部署');
            $table->timestamp('last_login_at')->nullable()->comment('最終ログイン日時');
            $table->string('phone', 20)->nullable();
            $table->foreignId('agency_id')->comment('紐付け代理店')->constrained('agencies')->cascadeOnDelete();
            $table->foreignId('corporation_id')->comment('紐付け企業')->constrained('corporations')->cascadeOnDelete();
            $table->foreignId('facility_id')->comment('紐付け施設')->constrained('facilities')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
