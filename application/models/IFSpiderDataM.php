<?php

class IFSpiderDataM extends CI_Model {

    var $__table_name = 'spider_market_original';
    var $__out_file_root = '/output_if_spider/';
    var $__ENUM_FROM_ELE = 'ele';
    var $__ENUM_FROM_MT = 'mt';

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
        $this->load->dbforge();
    }

    function inputMarketData($s_data, $s_sname) {
        $this->load->helper('file');
        $s_datetime = date("Ymd", time());
        $s_filename = "$s_datetime.$s_sname.csv";
        if (!file_exists(".$this->__out_file_root")) {
            mkdir(".$this->__out_file_root");
        }
        $s_csv_path = ".$this->__out_file_root$s_filename";
        if (!file_exists(".$s_csv_path")) {
            $this->appendUploadInfo($s_filename, $s_csv_path);
            write_file($s_csv_path, $s_data);
        }
    }

    function outputData() {
        
    }

    /**
     * 追加上传信息
     * @param type $s_filename
     * @param type $s_filepath
     * @return type
     */
    function appendUploadInfo($s_filename, $s_filepath) {
        log_message('debug', "追加上传信息");
        $s_date = date("Y-m-d H:i:s", time());
        $s_sql = "INSERT INTO spider_upload_info (sui_upload_dt,sui_upload_from,"
                . "sui_filename,sui_filepath) VALUES('$s_date','$this->__ENUM_FROM_ELE',"
                . "'$s_filename','$s_filepath')";
        log_message('debug', "SQL文:$s_sql");
        $this->db->query($s_sql);
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:$i_rows");
        return $i_rows;
    }

    /**
     * 强制转码为UTF8
     * @param type $s_str
     * @return type
     */
    function _encodeUTF8($s_str) {
        $s_str = str_replace('"', "", $s_str);
        $s_str = str_replace("'", "", $s_str);
        $s_str = trim($s_str);
        $encode = mb_detect_encoding($s_str, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
        $str_encode = mb_convert_encoding($s_str, 'UTF-8', $encode);
        return $str_encode;
    }

    function getCSVList($i_page, $i_rows) {
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_sql = "SELECT sui_id,sui_upload_dt,sui_upload_from,sui_filename,sui_filepath,"
                . "sui_input_dt,sui_sow_id,sow_name FROM spider_upload_info "
                . "LEFT JOIN spider_original_warehouse ON sui_sow_id=sow_id "
                . "ORDER BY sui_filename DESC LIMIT $i_start,$i_rows ";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->getCSVTotal();
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }

    function getCSVTotal($s_where = '') {
        $s_sql = "SELECT COUNT(1) t_num FROM spider_upload_info	$s_where";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num - 0;
    }

    /**
     * 获得数据仓列表
     * @return type
     */
    function getOWList() {
        $s_sql = "SELECT sow_id `id`,sow_name `text` FROM spider_original_warehouse";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }

    /**
     * 获得列表
     * @return type
     */
    function getList($i_page, $i_rows, $s_shopname, $s_gname, $i_sui_id) {
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getWhere($s_shopname, $s_gname, $i_sui_id);
        $s_sql = "SELECT * FROM $this->__table_name "
                . "$s_where LIMIT $i_start,$i_rows";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }

    function getWhere($s_shopname, $s_gname, $i_sui_id) {
        $s_where = "WHERE 1=1 ";
        if ($s_gname != '') {
            $s_where .= "AND smo_gname LIKE '%$s_gname%' ";
        }
        if ($s_shopname != '') {
            $s_where .= "AND smo_catch_source LIKE '%$s_shopname%' ";
        }
        if ($i_sui_id != '') {
            $s_where .= "AND smo_sui_id=$i_sui_id ";
        }
        return $s_where;
    }

    function _getTotal($s_where = '') {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->__table_name $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num - 0;
    }

    /**
     * 获得临时表名称
     * @return string
     */
    function _getTempTableName() {
        $this->load->library('session');
        $s_userid = $this->session->userdata('s_id');
        $s_time = date("YmdHis", time());
        $s_table_name_temp = $s_userid . '_' . $s_time;
        return $s_table_name_temp;
    }

    /**
     * 创建临时预览表
     * @param type $s_table_name
     */
    function _createTempTable($s_table_name) {
        $this->dbforge->add_field($this->fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table("temp_$s_table_name");
    }

    /**
     * 加载临时表
     * @param type $s_table_name
     * @return type
     */
    function _loadTempTable($s_table_name) {
        $o_result = $this->db->query("SELECT * FROM temp_$s_table_name");
        return $o_result->result();
    }

    /**
     * 删除表
     * @param type $s_table_name    表名
     */
    function _dropTempTable($s_table_name) {
        $this->dbforge->drop_table("temp_$s_table_name");
    }

    /**
     * 备份表
     * @param type $s_table_name
     */
    function _backupTable($s_table_name) {
        $_s_backup_name = $s_table_name . '_' . date("YmdHis", time()) . '.sql';
        $prefs = array(
            'tables' => array($s_table_name), // 你要备份的表，如果留空将备份所有的表。
            'ignore' => array(), // 你要忽略备份的表。
            'format' => 'txt', // 导出文件的格式。gzip, zip, txt
            'filename' => $_s_backup_name, // 备份文件名。如果你使用了 zip 压缩这个参数是必填的。
            'add_drop' => TRUE, // 是否在导出的 SQL 文件里包含 DROP TABLE 语句
            'add_insert' => TRUE, // 是否在导出的 SQL 文件里包含 INSERT 语句
            'newline' => "\n"                     // 导出的 SQL 文件使用的换行符
        );
        $this->load->dbutil();
        $backup = $this->dbutil->backup($prefs);

        // Load the file helper and write the file to your server
        $this->load->helper('file');
        write_file("./backup/sql/$_s_backup_name", $backup);
    }

    function doInputCSV($s_filename, $i_sui_id, $i_ow_id) {
        $s_csv_path = ".$this->__out_file_root$s_filename";
        $this->load->helper('file');
        if (!file_exists($s_csv_path)) {
            return array(
                'tbn' => '',
                'success' => 0,
                'fail' => 0
            );
        }
        $this->load->library('CSVReader');
        $o_result = $this->csvreader->parse_file($s_csv_path);
        //获得临时表名
        $s_table_name_temp = $this->_getTempTableName();
        $o_res = $this->inputCSV($s_table_name_temp, $o_result, $i_sui_id, $i_ow_id);
        return array(
            'tbn' => $s_table_name_temp,
            'success' => $o_res['success'],
            'fail' => $o_res['fail'],
            'repeat' => $o_res['repeat']
        );
    }

    function doRebuildCSV($i_sui_id) {
        $s_sql = "SELECT sui_filename,sui_filepath,sui_input_dt "
                . "FROM spider_upload_info WHERE sui_id=$i_sui_id ";
        $o_result = $this->db->query($s_sql);
        $a_result = $o_result->result();
        if (count($a_result) != 1) {
            return false;
        }
        $o_line = $a_result[0];

        $s_filepath = $o_line->sui_filepath;
        if (file_exists($s_filepath)) {
            unlink($s_filepath);
        }
        $s_sql = "SELECT smo_code `编号`,smo_class1 `大类`,smo_class2 `小类`,"
                . "smo_gname `品名`,smo_sale_count `销量`,smo_price `售价`,"
                . "smo_price_old `原价`,smo_catch_dt `获取时间`,smo_catch_source `获取来源` "
                . "FROM spider_market_original WHERE smo_sui_id=$i_sui_id ";
        $o_result = $this->db->query($s_sql);
        $s_result = $this->dbutil->csv_from_result($o_result);
        $this->load->helper('file');
        write_file($s_filepath, $s_result);
        return true;
    }

    function doDeleteCSV($i_sui_id) {
        $s_sql = "SELECT sui_filename,sui_filepath,sui_input_dt "
                . "FROM spider_upload_info WHERE sui_id=$i_sui_id ";
        $o_result = $this->db->query($s_sql);
        $a_result = $o_result->result();
        if (count($a_result) != 1) {
            return false;
        }
        $o_line = $a_result[0];

        $s_filepath = $o_line->sui_filepath;
        if (file_exists($s_filepath)) {
            unlink($s_filepath);
        }
        $this->db->trans_start();
        $s_sql1 = "DELETE FROM spider_market_original WHERE smo_sui_id=$i_sui_id ";
        $this->db->query($s_sql1);
        $s_sql2 = "DELETE FROM spider_upload_info WHERE sui_id=$i_sui_id ";
        $this->db->query($s_sql2);
        return $this->db->trans_complete();
    }

    /**
     * 导入CSV
     * @param type $s_table_name    表名
     * @param type $o_result        csv数据对象
     * @param type $i_sui_id        所属ID
     * @param type $i_ow_id         数据仓ID
     * @return type
     */
    function inputCSV($s_table_name, $o_result, $i_sui_id, $i_ow_id) {
        set_time_limit(0);
        $this->_createTempTable($s_table_name);
        $a_lines = $o_result->datas;
        $i_success = 0;
        $i_fail = 0;
        $i_repeat = 0;
        $a_success = array();

        $this->db->trans_start();
        for ($i = 0; $i < count($a_lines); $i++) {
            $o_line = $a_lines[$i];
            if (!isset($o_line['3']) || !isset($o_line['4']) || !isset($o_line['5'])) {
                $i_fail++;
                continue;
            }
            $d_price = str_replace("￥", "", $o_line['5']);
            if ($o_line['3'] == '') {
                $i_fail++;
                continue;
            }

            foreach ($a_success as $o_temp) {
                if ($o_temp['3'] == $o_line['3'] && $o_temp['4'] == $o_line['4']) {
                    $i_repeat++;
                    continue;
                }
            }

            $d_price_old = $o_line['6'] == '' ? 0 : $o_line['6'] - 0;
            $s_sql = "INSERT INTO temp_$s_table_name (smo_sui_id,smo_code,smo_class1,smo_class2,"
                    . "smo_gname,smo_sale_count,smo_price,smo_price_old,smo_catch_dt,"
                    . "smo_catch_source,smo_sow_id) VALUES " .
                    "(" . $i_sui_id .
                    ",'" . $this->_clearString($this->_encodeUTF8($o_line['0'])) .
                    "','" . $this->_clearString($this->_encodeUTF8($o_line['1'])) .
                    "','" . $this->_clearString($this->_encodeUTF8($o_line['2'])) .
                    "','" . $this->_clearString($this->_encodeUTF8($o_line['3'])) .
                    "','" . $o_line['4'] .
                    "','" . $d_price .
                    "','" . $d_price_old .
                    "','" . $this->_clearString($this->_encodeUTF8($o_line['7'])) .
                    "','" . $this->_clearString($this->_encodeUTF8($o_line['8'])) .
                    "'," . $i_ow_id . ")";

            array_push($a_success, $o_line);

            $this->db->query($s_sql);
            $i_success++;
        }
        $this->db->trans_complete();
        return array(
            'success' => $i_success,
            'fail' => $i_fail,
            'repeat' => $i_repeat
        );
    }

    function _clearString($s_str) {
        $s_str = str_replace('\\', '\/', $s_str);
        return $s_str;
    }
    
    function doDecodeData($s_table_name, $i_id) {
        $s_sql = "SELECT id,smo_price,smo_sale_count FROM temp_$s_table_name";
        $o_result = $this->db->query($s_sql);
        $a_result = $o_result->result();
        
        $this->load->library('DecodeEle');
        
        $this->db->trans_start();
        foreach ($a_result as $o_data) {
            $i_id = $o_data->id;
            $s_price = $o_data->smo_price;
            $s_sale_count = $o_data->smo_sale_count;
            $i_price = $this->decodeele->decode($s_price);
            $i_sale_count = $this->decodeele->decode($s_sale_count);
            if (is_numeric($i_price-0)){
                $s_sql_update1 = "UPDATE temp_$s_table_name SET smo_price='$i_price' WHERE id=$i_id";
                log_message('error', $s_sql_update1);
                $this->db->query($s_sql_update1);
            }
            if (is_numeric($i_sale_count-0)){
                $s_sql_update2 = "UPDATE temp_$s_table_name SET smo_sale_count='$i_sale_count' WHERE id=$i_id";
                log_message('error', $s_sql_update2);
                $this->db->query($s_sql_update2);
            }
        }
        $this->db->trans_complete();
    }
    
    /**
     * 追加数据
     * @param type $s_table_name
     * @return type 受影响行数
     */
    function doAppendSQL($s_table_name, $i_id, $i_sow_id) {
        $this->db->trans_start();
        //备份数据表
        //$this->_backupTable($this->__table_name);        
        $s_date = date("Y-m-d H:i:s", time());
        $s_sql1 = "UPDATE spider_upload_info SET sui_input_dt='$s_date',"
                . "sui_sow_id=$i_sow_id WHERE sui_id=$i_id";
        $this->db->query($s_sql1);
        //执行覆盖
        $s_sql2 = "INSERT INTO $this->__table_name (smo_sui_id,smo_code,smo_class1,"
                . "smo_class2,smo_gname,smo_sale_count,smo_price,smo_price_old,"
                . "smo_catch_dt,smo_catch_date,smo_catch_source,smo_sow_id) "
                . "SELECT smo_sui_id,smo_code,smo_class1,smo_class2,smo_gname,"
                . "smo_sale_count,smo_price,smo_price_old,smo_catch_dt,"
                . "SUBSTR(smo_catch_dt FROM 1 FOR 10),smo_catch_source,smo_sow_id FROM temp_$s_table_name ";
        $this->db->query($s_sql2);
        //删除临时表
        $this->_dropTempTable($s_table_name);
        return $this->db->trans_complete();
    }

    var $fields = array(
        'id' => array(
            'type' => 'INT',
            'constraint' => 5,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
        ),
        'smo_sui_id' => array(
            'type' => 'INT',
            'null' => TRUE,
        ), 'smo_code' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'smo_class1' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'smo_class2' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'smo_gname' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'smo_sale_count' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'smo_price' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'smo_price_old' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'smo_catch_dt' => array(
            'type' => 'DATETIME',
            'null' => TRUE,
        ), 'smo_catch_source' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'smo_sow_id' => array(
            'type' => 'INT',
            'null' => FALSE,
        )
    );

}
