<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdEBShop
 *
 * @author Vincent
 */
class AdYJSPriceM extends CI_Model {

    var $__table_name = 'base_balance_price';
    var $__tbn_cache_bal_price = 'cache_goods_balance_price';
    
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
        $this->load->dbforge();
    }

    /**
     * 强制转码为UTF8
     * @param type $s_str
     * @return type
     */
    function _encodeUTF8($s_str) {
        $encode = mb_detect_encoding($s_str, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
        $str_encode = mb_convert_encoding($s_str, 'UTF-8', $encode);
        $str_encode = str_replace("'","\'",$str_encode);
        return $str_encode;
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
    
    
    function getNewList() {
        $s_sql = "SELECT cgbp_code,cgbp_barcode,cgbp_name,cgbp_sale_price,cgbp_settlement_price "
                . "FROM $this->__tbn_cache_bal_price WHERE cgbp_settlement_price<=0 ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    /**
     * 获得列表
     * @return type
     */
    function _getList($i_page, $i_rows, $s_goods_name, $s_barcode) {
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getWhere($s_goods_name, $s_barcode);
        $s_sql = "SELECT * FROM $this->__table_name $s_where "
                . "ORDER BY bbp_goods_name DESC LIMIT $i_start,$i_rows";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }

    function getWhere($s_goods_name, $s_barcode) {
        $s_where = "WHERE 1=1 ";
        if ($s_goods_name != '') {
            $s_where .= "AND bbp_goods_name LIKE '%$s_goods_name%' ";
        }
        if ($s_barcode != '') {
            $s_barcode = str_replace(",","','",$s_barcode);
            $s_where .= "AND bbp_bar_code IN ('$s_barcode') ";
        }
        return $s_where;
    }
    
    function _getTotal($s_where) {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->__table_name $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num - 0;
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

    /**
     * 删除表
     * @param type $s_table_name    表名
     */
    function _dropTempTable($s_table_name) {
        $this->dbforge->drop_table("temp_$s_table_name");
    }

    /**
     * 导出CSV
     * @return type
     */
    function _outputCSV() {
        $this->load->dbutil();
        $query = $this->db->query("SELECT * FROM $this->__table_name");
        return $this->dbutil->csv_from_result($query);
    }

    /**
     * 导出XML
     * @return type
     */
    function _outputXML() {
        $this->load->dbutil();
        $query = $this->db->query("SELECT * FROM $this->__table_name");
        $config = array(
            'root' => 'root',
            'element' => 'element',
            'newline' => "\n",
            'tab' => "\t"
        );
        return $this->dbutil->xml_from_result($query, $config);
    }

    /**
     * 导入CSV
     * @param type $o_result
     */
    function inputCSV($s_table_name, $o_result) {
        set_time_limit(0);
        $this->_createTempTable($s_table_name);
        //$i_field_count = count($o_result->fields);
        $a_lines = $o_result->datas;
        $s_datetime = date("Y-m-d H:i:s", time());
        $this->db->trans_start();
        for ($i = 0; $i < count($a_lines); $i++) {
            $o_line = $a_lines[$i];
            if (is_null($o_line['1'])) {continue;}
            $s_sql = "INSERT INTO temp_$s_table_name (bbp_bar_code,bbp_yj_code,"
                    . "bbp_goods_name,bbp_settlement_price,bbp_yj_sale_price,bbp_specs,bbp_dt) VALUES " .
                    "('" . $this->_encodeUTF8($o_line['1']) .
                    "','" . $this->_encodeUTF8($o_line['2']) .
                    "','" . $this->_encodeUTF8($o_line['3']) .
                    "'," . (is_numeric($o_line['4']) ? $o_line['4'] : '0') .
                    "," . (is_numeric($o_line['5']) ? $o_line['5'] : '0') .
                    ",'" . $this->_encodeUTF8($o_line['6']) . 
                    "','" . $s_datetime . "')";
            $this->db->query($s_sql);
        }
        return $this->db->trans_complete();
    }

    /**
     * 差异化更新
     * @param type $s_table_name
     * @return string
     */
    function doDiffUpdate($s_table_name) {
        log_message('debug', '[易捷结算价-差异化更新]开始');
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        $this->_backupTable($this->__table_name);

        $i_rows2 = 0;
        try {
            $i_rows2 = $this->doUpdate($s_table_name);
        } catch (Exception $e) {
            log_message('error', '更新结算价时发生错误\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "更新结算价发生错误\r\n" . $e->getMessage();
            return $o_result;
        }
        
        $i_rows1 = 0;
        try {
            $i_rows1 = $this->doAppend($s_table_name);
        } catch (Exception $e) {
            log_message('error', '追加结算价时发生错误\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "追加结算价发生错误\r\n" . $e->getMessage();
            return $o_result;
        }

        //删除临时表
        $this->_dropTempTable($s_table_name);
        
        //更新缓存表
        $this->load->model('AdCacheGoodsM');
        $this->AdCacheGoodsM->updateGoodsBalancePrice();
        
        if ($i_rows1 == 0 && $i_rows2 == 0) {
            $o_result['state'] = false;
            $o_result['msg'] = "数据无变化，取消更新";
            return $o_result;
        } else {
            $o_result['state'] = true;
            $o_result['msg'] = "追加数据: $i_rows1 条 <br>更新数据: $i_rows2 条";
            return $o_result;
        }
    }

    /**
     * 追加结算价
     * @param type $s_table_name
     * @return type
     * @throws Exception
     */
    function doAppend($s_table_name) {
        log_message('debug', "易捷结算价-差异化更新-追加");
        //执行追加
        $s_sql = "INSERT INTO $this->__table_name (bbp_bar_code,bbp_yj_code,"
                . "bbp_goods_name,bbp_settlement_price,bbp_yj_sale_price,"
                . "bbp_specs,bbp_append_dt) "
                . "SELECT bbp_bar_code,bbp_yj_code,bbp_goods_name,"
                . "bbp_settlement_price,bbp_yj_sale_price,bbp_specs,bbp_dt "
                . "FROM temp_$s_table_name WHERE bbp_bar_code NOT IN "
                . "(SELECT bbp_bar_code FROM $this->__table_name ) ";
        log_message('debug', "SQL文:$s_sql");
        try {
            $this->db->query($s_sql);
        } catch (Exception $ex) {
            throw $ex;
        }
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:$i_rows");
        return $i_rows;
    }

    /**
     * 更新结算价
     * @param type $s_table_name
     * @return type
     * @throws Exception
     */
    function doUpdate($s_table_name) {
        log_message('debug', "易捷结算价-差异化更新-更新");
        $s_sql = "UPDATE $this->__table_name A INNER JOIN temp_$s_table_name B "
                . "ON A.bbp_bar_code=B.bbp_bar_code SET A.bbp_goods_name = B.bbp_goods_name,"
                . "A.bbp_settlement_price = B.bbp_settlement_price,"
                . "A.bbp_yj_sale_price = B.bbp_yj_sale_price,A.bbp_specs = B.bbp_specs,"
                . "A.bbp_update_dt=B.bbp_dt";
        log_message('debug', "SQL文:$s_sql");
        try {
            $this->db->query($s_sql);
        } catch (Exception $ex) {
            throw $ex;
        }
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:$i_rows");
        return $i_rows;
    }

    var $fields = array(
        'bbp_bar_code' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bbp_yj_code' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bbp_goods_name' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bbp_settlement_price' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bbp_yj_sale_price' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bbp_specs' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bbp_dt' => array(
            'type' => 'DATE',
            'null' => TRUE,
        ),
    );

}
