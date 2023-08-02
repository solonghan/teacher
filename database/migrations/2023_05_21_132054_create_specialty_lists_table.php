<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\SpecialtyList;

class CreateSpecialtyListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specialty_lists', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('');
          
            $table->timestamps();
            $table->softDeletes();
        });
        $array=array('教育學門','藝術學門','人文學門','語文學門','社會及行為科學學門','新聞學及圖書館資訊學門',
        '商業及管理學門','法律學門','生命科學學門','環境學門','物理、化學及地球科學學門','數學及統計學門',
        '資訊通訊科技學門','工程及工程業學門','製造及加工學門','建築及營建工程學門','農業學門','林業學門',
        '漁業學門','獸醫學門','醫藥衛生學門','社會福利學門','餐旅及民生服務學門','衛生及職業衛生學門',
        '安全服務學門','運輸服務學門','其他學門');

        for($i=0;$i<count($array);$i++){
            SpecialtyList::create(
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
        Schema::dropIfExists('specialty_lists');
    }
}
