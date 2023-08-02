<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Committeeman;

class CreateCommitteemenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('committeemen', function (Blueprint $table) {
            $table->id();
            // $table->enum('role', ['super', 'college', 'assistant', 'professor'])->default('super')->comment('身分別');
            $table->string('username')->default('');
            // $table->string('line_id')->default('')->comment('LINEID');
            $table->string('email')->default('');
            $table->string('phone')->default('');
            $table->text('url')->default('');
            $table->bigInteger('now_unit_id')->comment("服務ID")->default(0);
            $table->string('now_unit')->default('');
            $table->Integer('now_title_id')->default(0);

            $table->bigInteger('old_unit_id')->comment("曾任ID")->default(0);
            $table->string('old_unit')->default('');
            $table->Integer('old_title_id')->default(0);

            $table->Integer('specialty_id')->default(0);
            $table->Integer('specialty_source')->comment("學門專長資料來源")->default(0);

            $table->string('academic_id')->default('');
            $table->Integer('academic_source')->comment("學術專長資料來源")->default(0);
            $table->Integer('member_id')->comment("推薦人")->default(0);

            $table->string('status')->default('on');

            $table->timestamps();
            $table->softDeletes();
        });

        Committeeman::create(
            [
                // 'role'          => 'super',
                'username'          => 'ROCK',
                'email'             => 'rock@gmail.com',
                'phone'             => '0929288035',
                'url'               => 'yahoo.com.tw',
                // 'department_id'     => 1,
                // 'password'          => Hash::make('1435'),
                'now_unit_id'       => '1',
                'now_unit'          => '台灣大學',
                'now_title_id'         => '1',
                'old_unit_id'       => '1',
                'old_unit'          => '交通大學',
                'old_title_id'         => '2',
                'specialty_id'         => '1',
                'specialty_source'  => '1',
                'academic_id'          => '1,2',
                'academic_source'   => '1',
                'status'            => 'on',
                'member_id'      => '1',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]
        );
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
        Schema::table('committeemen', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('committeemen');
    }
}
