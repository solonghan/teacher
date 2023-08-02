<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Fill;
class OrderExport implements FromCollection,WithColumnWidths, WithStyles,WithHeadings
{
    protected $collections;
    protected $title;

    function __construct($collections, $title = '')
    {
        $this->collections=$collections;
        $this->title = $title;
    }

    public function collection()
    {
        return collect($this->collections['data']);
    }

    /*設定每一行的寬度*/
    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 15,
            'C' => 50,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 15,
        ];
    }
    
    /*設定標題列*/
    public function headings(): array
    {
        //第一列為先放一個空白的資料，後面會取代掉
        return [
            ['A1'],
            ['訂單編號', '下訂會員', '產品', '總金額(未稅)', '訂單狀態', '付款狀態', '物流狀態', '下單時間']
        ];
    }

    /*資料表的各種樣式設定*/
    public function styles(Worksheet $sheet)
    {
        $rows = count($this->collections['data']) + 2;
        
        //設定所有列的共同高度
        $sheet->getDefaultRowDimension()->setRowHeight(20 * 4);

        //合併第一列
        $sheet->mergeCells("A1:H1");

        //在第一格中寫入測驗的相關資料
        $sheet->setCellValue("A1",
                             $this->title);

        // //使用迴圈來判斷每一題的結果，並填入不同的底色
        // foreach($this->collections['correct'] as $idx => $correct){
        //     if($correct==true){
        //         $sheet->getStyleByColumnAndRow(3,$idx+3)
        //               ->getFill()
        //               ->setFillType(Fill::FILL_SOLID)
        //               ->getStartColor()
        //               ->setARGB('AAFFAA');
        //     }else{
        //         $sheet->getStyleByColumnAndRow(3,$idx+3)
        //               ->getFill()
        //               ->setFillType(Fill::FILL_SOLID)
        //               ->getStartColor()
        //               ->setARGB('FF9999');
        //     }
        // }

        //設定相關欄位的樣式
        return [
            "A1:H$rows" => [
                'font' => [
                    'size' => 14,
                    // 'name' => '標楷體',
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                    'wrapText' => true
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
            "A3:C$rows"=>[
                'alignment' => [
                    'horizontal' => 'center',
                ],
            ],
            "A2:H2" => [
                'fill'=>[
                    'fillType'=>Fill::FILL_SOLID,
                    'startColor'=>['argb'=>'000000'],
                ],
                'font'=>[
                    'color'=>['argb'=>'ffffff']
                ],
                'alignment' => [
                    'horizontal' => 'center',
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => 'thin',
                        'color' => ['argb' => '999999'],
                    ],
                ],                
            ],
            // "A3:A$rows" => [
            //     'fill'=>[
            //         'fillType'=>Fill::FILL_SOLID,
            //         'startColor'=>['argb'=>'000000'],
            //     ],
            //     'font'=>[
            //         'color'=>['argb'=>'ffffff']
            //     ],
            //     'borders' => [
            //         'allBorders' => [
            //             'borderStyle' => 'thin',
            //             'color' => ['argb' => '999999'],
            //         ],
            //     ],                
            // ],
            // "B3:B$rows" => [
            //     'fill'=>[
            //         'fillType'=>Fill::FILL_SOLID,
            //         'startColor'=>['argb'=>'00EE00'],
            //     ],
            //     'font'=>[
            //         'color'=>['argb'=>'000000']
            //     ],
            //     'borders' => [
            //         'allBorders' => [
            //             'borderStyle' => 'thin',
            //             'color' => ['argb' => '000000'],
            //         ],
            //     ],                
            // ],
        ];
    }    
}
