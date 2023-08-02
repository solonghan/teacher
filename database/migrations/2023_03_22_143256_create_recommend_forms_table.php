<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecommendFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recommend_forms', function (Blueprint $table) {
            $table->id();
            // $table->enum('role', ['super', 'college', 'assistant', 'professor'])->default('super')->comment('身分別');
            $table->string('username')->default('');
            // $table->string('line_id')->default('')->comment('LINEID');
            $table->string('email')->default('');
            $table->string('phone')->default('');
            $table->text('url')->default('');
            $table->bigInteger('now_unit_id')->comment("權限ID")->default(0);
            $table->string('now_unit')->default('');
            $table->string('now_title')->default('');

            $table->bigInteger('old_unit_id')->comment("部門ID")->default(0);
            $table->string('old_unit')->default('');
            $table->string('old_title')->default('');

            $table->Integer('specialty_id')->default(0);
            $table->Integer('specialty_source')->comment("學們專長資料來源")->default(0);

            $table->string('academic')->default('');
            $table->Integer('academic_source')->comment("學術專長資料來源")->default(0);

            $table->timestamps();
            $table->softDeletes();
        });


        // //要修改欄位屬性
        // Schema::table('users', function ($table) {
        //     $table->string('name', 50)->change();
        // });
        

        // //重新命名欄位：
        // Schema::table('users', function ($table) {
        //     $table->renameColumn('from', 'to');
        // });
        

        // //移除欄位
        // Schema::table('users', function ($table) {
        //     $table->dropColumn('votes');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recommend_forms');
    }
}
