<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_departments', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment("科系名稱")->default('');
            $table->Integer('member_id')->comment(0)->default('');
            $table->Integer('writer_id')->comment(0)->default('');
            $table->string('remark')->comment("備註")->default('');
            $table->enum('status', ['on', 'off'])->comment("是否開啟")->default("on");
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
        Schema::table('member_departments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('member_departments');
    }
}
