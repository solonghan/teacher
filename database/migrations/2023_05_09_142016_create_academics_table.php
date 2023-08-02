<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Academic;

class CreateAcademicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academics', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('');
            $table->integer('committeeman_id')->default(0);
            $table->integer('writer_id')->default(0);
            $table->string('create_date')->default('');
            
          
            $table->timestamps();
            $table->softDeletes();
        });

        Academic::create(
            [
             
                'title'          => '教育',
                'committeeman_id'   => 1,
                'writer_id'   => 1,
                'create_date'   => '2023/04/01',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]
        );
        Academic::create(
            [
             
                'title'          => '化學',
                'committeeman_id'   => 1,
                'writer_id'   => 1,
                'create_date'   => '2023/04/02',
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
        Schema::table('academics', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('academics');
    }
}
