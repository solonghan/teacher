<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\JobTitle;

class CreateJobTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_titles', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('');
          
            $table->timestamps();
            $table->softDeletes();
        });

        $array=array('教授','副教授','助理教授');
        for($i=0;$i<count($array);$i++){
            JobTitle::create(
                [
                    'title'          => $array[$i],
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]
            );
        }

        // JobTitle::create(
        //     [
        //         'title'          => '教授',
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
        Schema::table('job_titles', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('job_titles');
    }
}
