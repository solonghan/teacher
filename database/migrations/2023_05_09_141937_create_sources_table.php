<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Source;

class CreateSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('');
          
            $table->timestamps();
            $table->softDeletes();
        });
        $array=array('教育部','國科會','個人網頁','其他');
        for($i=0;$i<count($array);$i++){
            Source::create(
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
        Schema::table('sources', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('sources');
    }
}
