<?php
/**
 * Description of AdYJShopStorageM
 *
 * @author Vincent
 */
class AdYJShopStorageM extends CI_Model {

    var $__table_name = 'base_shop_storage_yj';

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
    function _loadTempTable($s_table_name, $i_page, $i_rows) {
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $o_result = $this->db->query("SELECT * FROM temp_$s_table_name LIMIT $i_start,$i_end ");
        $i_total = $this->_getTempTotal($s_table_name);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }

    function _getTempTotal($s_table_name) {
        $s_sql = "SELECT COUNT(1) t_num FROM temp_$s_table_name ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num - 0;
    }

    /**
     * 获得列表
     * @return type
     */
    function getList($i_page, $i_rows, $o_get) {
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->_getWhere($o_get);
        $s_sql = "SELECT bssy_org_code,bssy_org_name,bssy_yj_code,"
                . "bssy_goods_name,bssy_barcode,bssy_unit,bssy_specs,bssy_count,"
                . "bssy_cost_duty,bssy_cost_free,bssy_settlement_type,"
                . "bssy_supplier_code,bssy_supplier_name,bssy_update_dt "
                . "FROM $this->__table_name $s_where LIMIT $i_start,$i_rows ";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }
    
    function _getWhere($o_get) {
        $bssy_org_code = isset($o_get['oc']) ? $o_get['oc'] : '';
        $bssy_org_name = isset($o_get['on']) ? $o_get['on'] : '';
        $bssy_yj_code = isset($o_get['yjc']) ? $o_get['yjc'] : '';
        $bssy_goods_name = isset($o_get['gn']) ? $o_get['gn'] : '';
        $bssy_barcode = isset($o_get['bc']) ? $o_get['bc'] : '';
        $bssy_count = isset($o_get['c']) ? $o_get['c'] : '';
        $bssy_supplier_code = isset($o_get['sc']) ? $o_get['sc'] : '';
        $bssy_supplier_name = isset($o_get['sn']) ? $o_get['sn'] : '';
        
        $s_where = "WHERE 1=1";
        if ($bssy_org_code != '') {
            $s_where .= " AND bssy_org_code = '$bssy_org_code'";
        }
        if ($bssy_org_name != '') {
            $s_where .= " AND bssy_org_name LIKE '%$bssy_org_name%'";
        }
        if ($bssy_yj_code != '') {
            $s_where .= " AND bssy_yj_code = '$bssy_yj_code'";
        }
        if ($bssy_goods_name != '') {
            $s_where .= " AND bssy_goods_name LIKE '%$bssy_goods_name%'";
        }
        if ($bssy_count != '') {
            $count_type = '=';
            $bssy_count_type = isset($o_get['ct']) ? $o_get['ct'] : '';
            if ($bssy_count_type != ''){
                if ($bssy_count_type == 'MORE') {
                    $count_type = '>';
                } else if ($bssy_count_type == 'LESS') {
                    $count_type = '<';
                }
            }
            $s_where .= " AND bssy_count $count_type $bssy_count";
        }
        if ($bssy_barcode != '') {
            $s_where .= " AND bssy_barcode = '$bssy_barcode'";
        }
        if ($bssy_supplier_code != '') {
            $s_where .= " AND bssy_supplier_code = '$bssy_supplier_code'";
        }
        if ($bssy_supplier_name != '') {
            $s_where .= " AND bssy_supplier_name LIKE '%$bssy_supplier_name%'";
        }
        
        return $s_where;
    }
    
    function _getTotal($s_where) {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->__table_name $s_where";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num - 0;
    }

    /**
     * 更新基础商品库
     * @return type
     */
    function updateBaseGoods() {
        $s_time = date("Y-m-d H:i:s", time());
        $s_sql0 = "UPDATE base_goods_yj SET bgs_state='NORMAL' WHERE bgs_state<>'DELETE' ";
        $this->db->query($s_sql0);
        $this->db->affected_rows();
        //新增商品
        $s_sql1 = "INSERT INTO base_goods_yj (bgs_barcode,bgs_name,bgs_code,bgs_biz_pattern,"
                . "bgs_sales_min,bgs_package,bgs_sale_online,bgs_state,bgs_update_dt) "
                . "SELECT bssy_barcode,bssy_goods_name,bssy_yj_code,bssy_settlement_type,"
                . "bssy_unit,bssy_specs,-1,'NEW','$s_time' FROM v_bssy_goods "
                . "WHERE bssy_barcode NOT IN (SELECT bgs_barcode FROM base_goods_yj)";
        $this->db->query($s_sql1);
        $i_rows1 = $this->db->affected_rows();
        //删除商品
        $s_sql2 = "UPDATE base_goods_yj SET bgs_state='DELETE',bgs_update_dt='$s_time' "
                . "WHERE bgs_state <> 'DELETE' AND bgs_barcode NOT IN "
                . "(SELECT bssy_barcode FROM v_bssy_goods)";
        $this->db->query($s_sql2);
        $i_rows2 = $this->db->affected_rows();
        //恢复商品
        $s_sql3 = "UPDATE base_goods_yj INNER JOIN v_bssy_goods ON bgs_barcode=bssy_barcode "
                . "SET bgs_state='NEW',bgs_update_dt='$s_time' WHERE bgs_state='DELETE' "
                . "AND bssy_count>0";
        $this->db->query($s_sql3);
        $i_rows3 = $this->db->affected_rows();
        return array(
            'new_count'=>$i_rows1,
            'del_count'=>$i_rows2,
            're_count'=>$i_rows3
        );
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
     * 导入CSV
     * @param type $o_result
     */
    function inputCSV($s_table_name, $o_result) {
        set_time_limit(0);
        $this->_createTempTable($s_table_name);
        $a_lines = $o_result->datas;

        $this->db->trans_start();
        for ($i = 0; $i < count($a_lines); $i++) {
            $o_line = $a_lines[$i];
            if ($this->_encodeUTF8($o_line['2']) == '' || 
                    $this->_encodeUTF8($o_line['4']) == '') {continue;}
            $s_barcode = $this->_encodeUTF8($o_line['4']);
            if (strpos($s_barcode,'E\+') || strpos($s_barcode,'0000000') 
                    || strlen($s_barcode) < 8){
                continue;
            }
            $s_sql = "INSERT INTO temp_$s_table_name (bssy_org_code,bssy_org_name,"
                    . "bssy_yj_code,bssy_goods_name,bssy_barcode,bssy_unit,bssy_specs,"
                    . "bssy_count,bssy_cost_duty,bssy_cost_free,bssy_settlement_type,"
                    . "bssy_supplier_code,bssy_supplier_name) VALUES " .
                    "('" . $this->_encodeUTF8($o_line['0']) .
                    "','" . $this->_encodeUTF8($o_line['1']) .
                    "','" . $this->_encodeUTF8($o_line['2']) .
                    "','" . $this->_encodeUTF8($o_line['3']) .
                    "','" . $this->_encodeUTF8($o_line['4']) .
                    "','" . $this->_encodeUTF8($o_line['5']) .
                    "','" . $this->_encodeUTF8($o_line['6']) .
                    "'," . $o_line['7'] .
                    "," . $o_line['8'] .
                    "," . $o_line['9'] .
                    ",'" . $this->_encodeUTF8($o_line['10']) .
                    "','" . $this->_encodeUTF8($o_line['11']) .
                    "','" . $this->_encodeUTF8($o_line['12']) . "')";
            $this->db->query($s_sql);
        }
        return $this->db->trans_complete();
    }

    /**
     * 追加数据
     * @param type $s_table_name
     * @return type 受影响行数
     */
    function doCoverSQL($s_table_name) {
        log_message('debug', "易捷库存-覆盖导入");
        $this->db->trans_start();
        //备份数据表 废弃此调用 2020-05-12 11:17
//        $this->_backupTable($this->__table_name);
        $s_sql = "DELETE FROM $this->__table_name";
        $this->db->query($s_sql);
        //执行追加
        $s_sql2 = "INSERT INTO $this->__table_name (bssy_org_code,bssy_org_name,"
                . "bssy_yj_code,bssy_goods_name,bssy_barcode,bssy_unit,bssy_specs,"
                . "bssy_count,bssy_cost_duty,bssy_cost_free,bssy_settlement_type,"
                . "bssy_supplier_code,bssy_supplier_name) "
                . "SELECT bssy_org_code,bssy_org_name,"
                . "bssy_yj_code,bssy_goods_name,bssy_barcode,bssy_unit,bssy_specs,"
                . "bssy_count,bssy_cost_duty,bssy_cost_free,bssy_settlement_type,"
                . "bssy_supplier_code,bssy_supplier_name FROM temp_$s_table_name "
                . "WHERE bssy_count>0 ";
        log_message('debug', "SQL文:$s_sql2");
        $this->db->query($s_sql2);
        $b_result = false;
        try {
            $b_result = $this->db->trans_complete();
        } catch (Exception $ex) {
            throw $ex;
        }
        //删除临时表
        $this->_dropTempTable($s_table_name);
        log_message('debug', "事务执行结果:$b_result");
        return $b_result;
    }

    var $fields = array(
        'bssy_yj_code' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bssy_cost_duty' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bssy_supplier_name' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bssy_count' => array(
            'type' => 'INT',
            'null' => TRUE,
        ), 'bssy_goods_name' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bssy_org_code' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bssy_org_name' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bssy_barcode' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bssy_unit' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bssy_specs' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bssy_cost_free' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bssy_settlement_type' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bssy_supplier_code' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bssy_update_dt' => array(
            'type' => 'DATE',
            'null' => TRUE,
        ),
    );

}
