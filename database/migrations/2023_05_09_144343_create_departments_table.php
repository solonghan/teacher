<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Department;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('');
          
            $table->timestamps();
            $table->softDeletes();
        });

        // Department::create(
        //     [
        //         'title'          => '經濟系',
        //         'created_at'    => now(),
        //         'updated_at'    => now(),
        //     ]
        // );
        $array=array('經濟系','機械系');
        for($i=0;$i<count($array);$i++){
            Department::create(
                [
                    'title'          => $array[$i],
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]
            );
        }

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('departments');
    }
}
