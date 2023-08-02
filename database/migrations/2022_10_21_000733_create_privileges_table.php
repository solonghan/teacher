<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePrivilegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('privileges', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('')->comment('權限群組名稱');
            $table->enum('op_user', ['all','specific'])->default('all')->comment('使用者操作權限');
            $table->enum('op_product', ['all','specific'])->default('all')->comment('產品操作權限');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('privileges')->insert([
            'title'      => '最高權限',
            'op_user'    => 'all',
            'op_product' => 'all'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('privileges', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('privileges');
    }
}
