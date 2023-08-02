<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Member;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->enum('role', ['super', 'college', 'assistant', 'professor'])->default('super')->comment('身分別');
            $table->string('username')->default('');
            $table->string('line_id')->default('')->comment('LINEID');
            $table->string('email')->default('');
            $table->string('avatar')->default('');
            $table->string('password')->default('');
            $table->string('privilege_id')->comment("權限ID")->default('');
            $table->bigInteger('department_id')->comment("部門ID")->default(0);
            $table->bigInteger('is_mail')->comment("是否收到mail")->default(1);
            $table->bigInteger('is_line')->comment("是否收到Line通知")->default(1);
            $table->enum('status', ['on', 'off']);
            $table->bigInteger('create_by')->comment("建立者ID")->default(0);
            $table->bigInteger('update_by')->comment("異動者ID")->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        Member::create(
            [
                'role'          => 'super',
                'username'      => 'admin',
                'email'         => 'admin',
                'avatar'        => 'avatar.svg',
                'privilege_id'  => 1,
                'department_id' => 1,
                'password'      => Hash::make('1435'),
                'status'        => 'on',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('members');
    }
}
