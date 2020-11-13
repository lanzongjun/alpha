<?php
/**
 * Description of AdYJCashPoolM
 *
 * @author Vincent
 */
class AdEBBillInfoM extends CI_Model {

    var $__table_name = 'base_bill_info_eb';
    var $__tbn_shop_info = 'base_shop_info';
    
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
        //if ($s_str === '') {return $s_str;}
        $s_str = str_replace('"', "", $s_str);
        $s_str = str_replace("'", "", $s_str);
        $s_str = trim($s_str);
        $encode = mb_detect_encoding($s_str, array("ASCII", 'UTF-8', "GB2312", "GB18030", "GBK", "HZ", 'BIG5'));
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
    function _loadTempTable($s_table_name) {
        $s_sql = "SELECT bbie_date,bbie_shop_name,bbie_shop_id,bbie_org_sn,"
                . "bbie_shop_id_eb,bbie_order_count,bbie_balance_amount,"
                . "bbie_cus_pay,bbie_shop_pay,bbie_agent_pay,bbie_send_fee,"
                . "bbie_commission,bbie_floors,bbie_order_amount,bbie_package_fee,"
                . "bbie_ele_pay,bbie_is_exist FROM temp_$s_table_name "
                . "ORDER BY bbie_is_exist DESC,bbie_org_sn ASC ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }

    /**
     * 获得列表
     * @return type
     */
    function _getList($i_page, $i_rows) {        
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_sql = "SELECT * FROM $this->__table_name ORDER BY bbie_date DESC "
                . "LIMIT $i_start,$i_rows";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getTotal();
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }
    
    function _getTotal() {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->__table_name ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num-0;
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

    function inputXls($s_table_name,$s_file_path){
        $a_column = array();
        array_push($a_column, array(
            'title' => '账单日期',
            'field' => 'bbie_date'
        ));
        array_push($a_column, array(
            'title' => '门店名称',
            'field' => 'bbie_shop_name'
        ));
        array_push($a_column, array(
            'title' => '门店id',
            'field' => 'bbie_shop_id'
        ));
        array_push($a_column, array(
            'title' => 'ele门店id',
            'field' => 'bbie_shop_id_eb'
        ));
        array_push($a_column, array(
            'title' => '单量',
            'field' => 'bbie_order_count'
        ));
        array_push($a_column, array(
            'title' => '结算金额',
            'field' => 'bbie_balance_amount'
        ));
        array_push($a_column, array(
            'title' => '用户支付',
            'field' => 'bbie_cus_pay'
        ));
        array_push($a_column, array(
            'title' => '商户补贴',
            'field' => 'bbie_shop_pay'
        ));
        array_push($a_column, array(
            'title' => '代理商',
            'field' => 'bbie_agent_pay'
        ));
        array_push($a_column, array(
            'title' => '配送费',
            'field' => 'bbie_send_fee'
        ));
        array_push($a_column, array(
            'title' => '实收佣金',
            'field' => 'bbie_commission'
        ));
        array_push($a_column, array(
            'title' => '保底',
            'field' => 'bbie_floors'
        ));
        array_push($a_column, array(
            'title' => '订单金额',
            'field' => 'bbie_order_amount'
        ));
        array_push($a_column, array(
            'title' => '包装费',
            'field' => 'bbie_package_fee'
        ));
        array_push($a_column, array(
            'title' => '平台补贴',
            'field' => 'bbie_ele_pay'
        ));
        $this->load->library('Php_spread_sheet_lib');
        $a_data = $this->php_spread_sheet_lib->loadData($a_column,$s_file_path);
        return $this->inputTempData($s_table_name, $a_data);
    }
    
    function inputTempData($s_table_name, $a_data){
        set_time_limit(0);
        $i_rows = 0;
        $this->_createTempTable($s_table_name);
        $this->db->trans_start();
        for ($i = 0; $i < count($a_data); $i++) {
            $o_line = $a_data[$i];
            $s_sql = "INSERT INTO temp_$s_table_name (bbie_date,bbie_shop_name,"
                    . "bbie_shop_id,bbie_shop_id_eb,bbie_order_count,bbie_balance_amount,"
                    . "bbie_cus_pay,bbie_shop_pay,bbie_agent_pay,bbie_send_fee,"
                    . "bbie_commission,bbie_floors,bbie_order_amount,bbie_package_fee,"
                    . "bbie_ele_pay,bbie_is_exist) VALUES ('"
                    . $o_line['bbie_date'] .
                    "','" . $o_line['bbie_shop_name'] .
                    "','" . $o_line['bbie_shop_id'] .
                    "','" . $o_line['bbie_shop_id_eb'] .
                    "'," . $o_line['bbie_order_count'] .
                    "," . $o_line['bbie_balance_amount'] .
                    "," . $o_line['bbie_cus_pay'] .
                    "," . $o_line['bbie_shop_pay'] .
                    "," . $o_line['bbie_agent_pay'] .
                    "," . $o_line['bbie_send_fee'] .
                    "," . $o_line['bbie_commission'] .
                    "," . $o_line['bbie_floors'] .
                    "," . $o_line['bbie_order_amount'] .
                    "," . $o_line['bbie_package_fee'] .
                    "," . $o_line['bbie_ele_pay'] .
                    ",0)";
            $this->db->query($s_sql);
            $i_rows = $i_rows + 1;
        }
        $s_sql1 = "UPDATE temp_$s_table_name A1 INNER JOIN $this->__table_name A2 "
                . "ON A1.bbie_shop_id=A2.bbie_shop_id AND A1.bbie_date=A2.bbie_date "
                . "SET A1.bbie_is_exist=1 ";
        $this->db->query($s_sql1);
        $s_sql2 = "UPDATE temp_$s_table_name LEFT JOIN $this->__tbn_shop_info "
                . "ON bbie_shop_id=bs_elm_id SET bbie_org_sn=bs_org_sn ";        
        $this->db->query($s_sql2);
        
        $this->db->trans_complete();
        return $i_rows;
    }
    
    /**
     * 追加数据
     * @param type $s_table_name
     * @return type 受影响行数
     */
    function doAppendSQL($s_table_name) {
        $this->db->trans_start();
        //备份数据表
        $this->_backupTable($this->__table_name);
        //执行追加
        $s_sql = "INSERT INTO $this->__table_name (bbie_date,bbie_shop_name,"
                . "bbie_shop_id,bbie_org_sn,bbie_shop_id_eb,bbie_order_count,"
                . "bbie_balance_amount,bbie_cus_pay,bbie_shop_pay,bbie_agent_pay,"
                . "bbie_send_fee,bbie_commission,bbie_floors,bbie_order_amount,"
                . "bbie_package_fee,bbie_ele_pay) "
                . "SELECT bbie_date,bbie_shop_name,bbie_shop_id,bbie_org_sn,"
                . "bbie_shop_id_eb,bbie_order_count,bbie_balance_amount,"
                . "bbie_cus_pay,bbie_shop_pay,bbie_agent_pay,bbie_send_fee,"
                . "bbie_commission,bbie_floors,bbie_order_amount,bbie_package_fee,"
                . "bbie_ele_pay FROM temp_$s_table_name "
                . "WHERE bbie_is_exist=0 AND bbie_org_sn IS NOT NULL ";
        $this->db->query($s_sql);
        //删除临时表
        $this->_dropTempTable($s_table_name);
        return $this->db->trans_complete();
    }
    
    var $fields = array(
        'bbie_date' => array(
            'type' => 'DATE',
            'null' => TRUE,
        ), 'bbie_shop_name' => array(
            'type' => 'VARCHAR',
            'constraint' => '60',
            'null' => TRUE,
        ), 'bbie_shop_id' => array(
            'type' => 'VARCHAR',
            'constraint' => '20',
            'null' => TRUE,
        ), 'bbie_org_sn' => array(
            'type' => 'INT',
            'null' => TRUE,
        ), 'bbie_shop_id_eb' => array(
            'type' => 'VARCHAR',
            'constraint' => '20',
            'null' => TRUE,
        ), 'bbie_order_count' => array(
            'type' => 'INT',
            'null' => TRUE,
        ), 'bbie_balance_amount' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bbie_cus_pay' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bbie_shop_pay' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bbie_agent_pay' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bbie_send_fee' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bbie_commission' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bbie_floors' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bbie_order_amount' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bbie_package_fee' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bbie_ele_pay' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bbie_is_exist' => array(
            'type' => 'INT',
            'null' => TRUE,
        )
    );
}
