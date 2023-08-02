<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;  //指定檔案使用Collection匯入
use Maatwebsite\Excel\Concerns\WithMapping;   //把欄位進行對應
use Maatwebsite\Excel\Concerns\WithHeadingRow;  //指定檔案有標題列
use Maatwebsite\Excel\Imports\HeadingRowFormatter;  //指定檔案標題列的格式

/* 預設的WithHeadinRow時會自動對標題進行命名格式化的動作，
 * 但這個動作不支援中文，會變成亂碼或消失，
 * 所以要設定不對標題列做任何處理，中文欄位名才能正確匯入*/
HeadingRowFormatter::default('none');

class ProductImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
    }
}
