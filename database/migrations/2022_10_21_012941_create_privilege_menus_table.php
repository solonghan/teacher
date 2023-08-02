<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePrivilegeMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('privilege_menus', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sort')->comment("排序")->default(0);
            $table->bigInteger('parent_id')->comment("父層ID;0為父層")->default(0);
            $table->string('name')->default('')->comment('選單名稱');
            $table->string('icon')->default('')->comment('icon');
            $table->string('url')->default('')->comment('route url');
            $table->string('function')->default('')->comment('function,active用');
            $table->string('action')->default('1,2,3,4')->comment('可做動作');
            $table->string('badge')->default('')->comment('Badge');
            $table->enum('status', ['on','off'])->default('on')->comment('狀態');
            $table->timestamps();
        });

        Schema::create('privileges_action', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('')->comment('權限類別');
            $table->timestamps();
        });
        DB::table('privileges_action')->insert([
            'id'         => '1',
            'title'      => '瀏覽',
            'created_at' => now()
        ]);
        DB::table('privileges_action')->insert([
            'id'         => '2',
            'title'      => '新增',
            'created_at' => now()
        ]);
        DB::table('privileges_action')->insert([
            'id'         => '3',
            'title'      => '編輯',
            'created_at' => now()
        ]);
        DB::table('privileges_action')->insert([
            'id'         => '4',
            'title'      => '刪除',
            'created_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('privilege_menus');
        Schema::dropIfExists('privileges_action');
    }
}
