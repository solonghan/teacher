<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\ChangeRecord;

class CreateChangeRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_records', function (Blueprint $table) {
            $table->id();
            $table->string('username')->default('');
            $table->Integer('user_id')->default(0);
            $table->string('action')->default('');
            $table->Integer('committeeman_id')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        ChangeRecord::create(
            [
             
                'username'          => '王大文',
                'user_id'           => '1',
                'action'            => '新增專家',
                'committeeman_id'   =>'1',
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
        Schema::table('change_records', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('change_records');
    }
}
