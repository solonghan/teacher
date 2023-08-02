<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Specialty;

class CreateSpecialtiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specialties', function (Blueprint $table) {
            $table->id();
            $table->integer('title_id')->default(0);
            $table->integer('committeeman_id')->default(0);
            $table->integer('writer_id')->default(0);
            $table->string('create_date')->default('');
            
          
            $table->timestamps();
            $table->softDeletes();
        });
        Specialty::create(
            [
             
                'title_id'          => 1,
                'committeeman_id'   => 1,
                'writer_id'   => 1,
                'create_date'   => '2023/04/01',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]
        );
        Specialty::create(
            [
             
                'title_id'          => 2,
                'committeeman_id'   => 1,
                'writer_id'   => 1,
                'create_date'   => '2023/04/02',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]
        );
        // Specialty::create(
        //     [
             
        //         'title'          => '教育學門',
        //         'created_at'    => now(),
        //         'updated_at'    => now(),
        //     ]
        // );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('specialties', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('specialties');
    }
}
