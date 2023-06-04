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
            $table->dropConstrainedForeignId('corporation_id');
        });

        Schema::dropIfExists('corporations');
        Schema::dropIfExists('companies');

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name', 255)->comment('組織名称');
            $table->string('post_code', 10)->nullable();
            $table->foreignId('prefecture_id')->comment('都道府県')->constrained('m_prefectures')->cascadeOnDelete();
            $table->string('municipality', 255)->nullable();
            $table->string('street', 255)->nullable();
            $table->string('building', 255)->nullable();
            $table->smallInteger('business_type')->comment('特定事業者区分: 10: 特定事業者, 21:特定連鎖化事業者_一般企業, 22: 特定連鎖化事業者_福祉施設, 23: 特定連鎖化事業者_学校, 24: 特定連鎖化事業者_その他');
            $table->smallInteger('is_stock_listing')->comment('上場区分: 0: 非上表, 1: 上表');
            $table->string('contact_person_name', 255)->nullable();
            $table->string('job_title', 45)->nullable()->comment('役職');
            $table->string('department', 255)->nullable()->comment('部署名');
            $table->string('phone', 20)->nullable()->comment('電話番号');
            $table->string('mail', 255)->nullable()->comment('メールアドレス');
            $table->text('memo')->nullable();
            $table->smallInteger('status')->nullable()->default(1)->comment('ステータス: 0: 無効, 1:有効');
            $table->smallInteger('is_export_report')->nullable()->default(0)->comment('0: なし, 1:あり');
            $table->string('homepage', 255)->nullable()->comment('ホームページ');
            $table->foreignId('agency_id')->comment('紐付け代理店')->constrained('agencies')->cascadeOnDelete();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('company_id')->comment('紐付け企業')->constrained('companies')->cascadeOnDelete();
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
