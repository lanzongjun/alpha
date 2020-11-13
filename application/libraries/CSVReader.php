<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 结果集对象
 * fields   字段名称, 可用于判断数据列数
 * datas    数据内容, 二维数组
 */
class ResultData {

    var $fields = array();
    var $datas = array();

}

/**
 * Description of CSVReader
 *
 * @author Vincent
 */
class CSVReader {

    var $fields;/** columns names retrieved after parsing */
    var $separator = "/[,]/s";/** separator used to explode each line */

    /**
     * Parse a text containing CSV formatted data. 
     * 
     * @access    public 
     * @param    string 
     * @return    array 
     */
    function parse_text($p_Text) {
        $lines = explode("\n", $p_Text);
        return $this->parse_lines($lines);
    }

    /**
     * Parse a file containing CSV formatted data. 
     * 
     * @access    public 
     * @param    string 
     * @return    array 
     */
    function parse_file($p_Filepath) {
        $lines = file($p_Filepath);
        return $this->parse_lines($lines);
    }

    /**
     * Parse an array of text lines containing CSV formatted data. 
     * 
     * @access    public 
     * @param    array 
     * @return    array 
     */
    function parse_lines($p_CSVLines) {
        $o_result = new ResultData();
        $content = FALSE;
        foreach ($p_CSVLines as $line_num => $line) {
            if ($line != '') { // skip empty lines
                //$elements = str_split(',', $line);
                $mode = "/[,]/s";
                $elements = preg_split($mode, $line, -1);

                if (!is_array($content)) { // the first line contains fields names  
                    $o_result->fields = $elements;
                    $content = array();
                } else {
                    $item = array();
                    foreach ($o_result->fields as $id => $field) {
                        if (isset($elements[$id])) {
                            $item[$id] = $elements[$id];
                        }
                    }
                    $o_result->datas[] = $item;
                }
            }
        }
        return $o_result;
    }

}
