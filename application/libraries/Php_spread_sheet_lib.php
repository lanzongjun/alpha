<?php

require_once APPPATH . 'libraries\PAutoload.php';

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
//use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Php_spread_sheet_lib {
    private $__i_column_limit_xls = 256;
    private $__i_column_limit_xlsx = 16384;
    private $__i_row_limit_xls = 65536;
    private $__i_row_limit_xlsx = 1048576;
    private $__a_column = ['A','B','C','D','E','F','G','H','I','J','K','L','M',
        'N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    private $__o_style = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ];
    
    private function object2array(&$object) {
        $object =  json_decode( json_encode( $object),true);
        return $object;
    }
    
    public function BSExportGoods($o_data,$s_filepath){
        //$spreadsheet = new Spreadsheet();
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('./output/balance_shop/template_goods.xlsx');
        
        // 获取活动工作薄
        $sheet_cg = $spreadsheet->setActiveSheetIndex(0);
        $a_column_cg = $o_data['column'];
        $a_record_cg = $o_data['record'];
        $this->export($sheet_cg, $a_column_cg, $a_record_cg);
        
        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($s_filepath);
        
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
    }
    
    public function loadData($a_column,$s_file_path,$i_first_row=2){
        $a_data = array();
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($s_file_path);
        
        // 获取活动工作薄
        $o_sheet = $spreadsheet->setActiveSheetIndex(0);
        $i_row_max = $o_sheet->getHighestRow();
        for ($j=$i_first_row; $j<=$i_row_max; $j++){
            $o_data = array();
            for ($i=0; $i<count($a_column); $i++){
                $c = $this->__a_column[$i];
                $s_field = $a_column[$i]['field'];
                $s_value = $o_sheet->getCell("$c$j")->getValue();
                $o_data[$s_field] = $s_value;
            }
            array_push($a_data, $o_data);
        }
        return $a_data;
    }
    
    /**
     * 导出门店纬度汇总表格
     * @param type $o_data_co
     * @param type $o_data_eb
     * @param type $o_data_mt
     * @param type $s_filepath
     */
    public function BSExportCollect($o_data_co,$o_data_eb,$o_data_mt,$s_filepath){
        //$spreadsheet = new Spreadsheet();
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('./output/balance_shop/template.xlsx');
            
        // 获取活动工作薄
        $sheet_co = $spreadsheet->setActiveSheetIndex(0);
        $a_column_co = $o_data_co['column'];
        $a_record_co = $o_data_co['record'];
        $this->export($sheet_co, $a_column_co, $a_record_co);
            
        $sheet_eb = $spreadsheet->setActiveSheetIndex(1);
        $a_column_eb = $o_data_eb['column'];
        $a_record_eb = $o_data_eb['record'];
        $this->export($sheet_eb, $a_column_eb, $a_record_eb,false);
        $this->mergeCells($sheet_eb,count($a_record_eb));
            
        $sheet_mt = $spreadsheet->setActiveSheetIndex(2);
        $a_column_mt = $o_data_mt['column'];
        $a_record_mt = $o_data_mt['record'];
        $this->export($sheet_mt, $a_column_mt, $a_record_mt,false);
        $this->mergeCells($sheet_mt,count($a_record_mt));
                     
        $spreadsheet->setActiveSheetIndex(0);
        
        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($s_filepath);
        
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
    }
    
    /**
     * 合并单元格
     * @param type $sheet
     * @param type $i_limit
     */    
    public function mergeCells(&$sheet,$i_limit){
        $i_c_start = 'A';
        $i_c_limit = 15;
        $i_r_start = 2;
        $i_r_limit = $i_limit+1;
        $i_merge_start_r = 2;
        $i_merge_end_r = 2;
        //列
        $c = $this->__a_column[0];
        
        $s_order_code_pre = $sheet->getCell("$i_c_start$i_r_start")->getValue();
        for($i=$i_r_start+1; $i<=$i_r_limit; $i++) {
            $s_order_code = $sheet->getCell("$i_c_start$i")->getValue();
            //如果跟上一个订单号相同
            if ($s_order_code_pre == $s_order_code) {
                //则此行可合并
                $i_merge_end_r = $i < $i_merge_start_r ? $i_merge_start_r : $i;
                if ($i == $i_r_limit){
                    for ($j=0; $j<$i_c_limit; $j++){
                        $c = $this->__a_column[$j];
                        $sheet->mergeCells("$c$i_merge_start_r:$c$i_merge_end_r");
                    }
                }
            } else {
                //如果合并截止行
                $i_merge_end_r = $i_merge_end_r > $i_merge_start_r ? 
                        $i_merge_end_r : $i_merge_start_r;
                if ($i_merge_end_r != $i) {
                    for ($j=0; $j<$i_c_limit; $j++){
                        $c = $this->__a_column[$j];
                        $sheet->mergeCells("$c$i_merge_start_r:$c$i_merge_end_r");
                    }
                }
                $i_merge_start_r = $i;
            }
            $s_order_code_pre = $s_order_code;
        }
    }
    
    public function export(&$sheet, &$a_column, &$a_record, $b_row_no=true, $b_row_title=false){
        $a_field = array();
        //提取列字段名称和列标题
        for($i=0; $i<count($a_column); $i++){
            $o_column = $a_column[$i];
            array_push($a_field,$o_column['field']);
            //采用模板模式，不用设置标题
            if ($b_row_title) {
                $s_row_xy = $this->__a_column[$i + 1] . '1';
                $sheet->setCellValue($s_row_xy, $o_column['title']);
                //当前单元格套用样式
                $sheet->getStyle($s_row_xy)->applyFromArray($this->__o_style);
            }
        }
        if ($b_row_no && $b_row_title){
            $sheet->setCellValue('A1', 'No.');
            //当前单元格套用样式
            $sheet->getStyle('A1')->applyFromArray($this->__o_style);
        }
        //对象转数组
        $a_data = $this->object2array($a_record);
        $i_c_step = $b_row_no ? 2 : 1;
        for ($r=0; $r<count($a_data); $r++) {
            $o_data = $a_data[$r];
            //计算当前单元格列坐标
            $x = $this->__a_column[0];
            //计算当前单元格行坐标
            $y = $r+2;
            if ($b_row_no) {
                //填入序号
                $sheet->setCellValueByColumnAndRow(1, $r+2, $r+1);
                //当前单元格套用样式
                $sheet->getStyle("$x$y")->applyFromArray($this->__o_style);
            }
            for ($c=0; $c<count($a_field); $c++) {
                $s_field = $a_field[$c];
                $s_value = $o_data[$s_field];
                //单元格赋值
                $sheet->setCellValueByColumnAndRow($c+$i_c_step, $r+2, "$s_value\t");
                //计算当前单元格列坐标
                $x = $this->__a_column[$c+($i_c_step-1)];
                //当前单元格套用样式
                $sheet->getStyle("$x$y")->applyFromArray($this->__o_style);
            }
        }
    }

    public function exportNewGoods($s_file_name, $a_datalist, $a_column) {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $this->export($sheet, $a_column, $a_datalist, true, true);
        
        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$s_file_name.xlsx\"");
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
//    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
//    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

}
