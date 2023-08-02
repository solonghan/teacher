<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\ServiceUnit;

class CreateServiceUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_units', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('');
          
            $table->timestamps();
            $table->softDeletes();
        });
        
        $array=array('公立學校','研究機構','企業(含私立學校)');
        for($i=0;$i<count($array);$i++){
            ServiceUnit::create(
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
        Schema::table('service_units', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('service_units');
    }
}
