<?php
/**
 * Description of AdYJCashPoolM
 *
 * @author Vincent
 */
class AdMTBillInfoM extends CI_Model {

    var $__table_name = 'base_bill_info_mt';
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
        $s_sql = "SELECT bbim_shop_id,bbim_org_sn,bbim_shop_name,bbim_bill_date,"
                . "bbim_amount,bbim_status,bbim_period_begin,bbim_period_end,"
                . "bbim_balance_date,bbim_is_exist FROM temp_$s_table_name "
                . "ORDER BY bbim_is_exist DESC,bbim_org_sn ASC ";
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
        $s_sql = "SELECT * FROM $this->__table_name ORDER BY bbim_bill_date DESC "
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
            'title' => '结算id',
            'field' => 'bbim_bal_id'
        ));
        array_push($a_column, array(
            'title' => '门店id',
            'field' => 'bbim_shop_id'
        ));
        array_push($a_column, array(
            'title' => '门店名称',
            'field' => 'bbim_shop_name'
        ));
        array_push($a_column, array(
            'title' => '账单日期',
            'field' => 'bbim_bill_date'
        ));
        array_push($a_column, array(
            'title' => '账单金额',
            'field' => 'bbim_amount'
        ));
        array_push($a_column, array(
            'title' => '结算状态',
            'field' => 'bbim_status'
        ));
        array_push($a_column, array(
            'title' => '归属账期',
            'field' => 'bbim_period'
        ));
        array_push($a_column, array(
            'title' => '结算日期',
            'field' => 'bbim_balance_date'
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
            $a_period = explode('~', $o_line['bbim_period'], 2);
            $s_sql = "INSERT INTO temp_$s_table_name (bbim_shop_id,bbim_shop_name,"
                    . "bbim_bill_date,bbim_amount,bbim_status,bbim_period_begin,"
                    . "bbim_period_end,bbim_balance_date,bbim_is_exist) VALUES ("
                    . $o_line['bbim_shop_id'] .
                    ",'" . $o_line['bbim_shop_name'] .
                    "','" . $o_line['bbim_bill_date'] . 
                    "'," . $o_line['bbim_amount'] . 
                    ",'" . $o_line['bbim_status'] . 
                    "','" . $a_period[0] . 
                    "','" . $a_period[1] . 
                    "','" . $o_line['bbim_balance_date'] . 
                    "',0)";
            $this->db->query($s_sql);
            $i_rows = $i_rows+1;
        }
        $s_sql1 = "UPDATE temp_$s_table_name A1 INNER JOIN $this->__table_name A2 "
                . "ON A1.bbim_shop_id=A2.bbim_shop_id AND A1.bbim_bill_date=A2.bbim_bill_date "
                . "SET A1.bbim_is_exist=1 ";
        $this->db->query($s_sql1);
        $s_sql2 = "UPDATE temp_$s_table_name LEFT JOIN $this->__tbn_shop_info "
                . "ON bbim_shop_id=bs_m_id SET bbim_org_sn=bs_org_sn ";        
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
        $s_sql = "INSERT INTO $this->__table_name (bbim_shop_id,bbim_org_sn,"
                . "bbim_shop_name,bbim_bill_date,bbim_amount,bbim_status,"
                . "bbim_period_begin,bbim_period_end,bbim_balance_date) "
                . "SELECT bbim_shop_id,bbim_org_sn,bbim_shop_name,bbim_bill_date,"
                . "bbim_amount,bbim_status,bbim_period_begin,bbim_period_end,"
                . "bbim_balance_date FROM temp_$s_table_name "
                . "WHERE bbim_is_exist=0 AND bbim_org_sn IS NOT NULL ";
        $this->db->query($s_sql);
        //删除临时表
        $this->_dropTempTable($s_table_name);
        return $this->db->trans_complete();
    }
    
    var $fields = array(
        'bbim_shop_id' => array(
            'type' => 'INT',
            'null' => TRUE,
        ), 'bbim_org_sn' => array(
            'type' => 'INT',
            'null' => TRUE,
        ), 'bbim_shop_name' => array(
            'type' => 'VARCHAR',
            'constraint' => '60',
            'null' => TRUE,
        ), 'bbim_bill_date' => array(
            'type' => 'date',
            'null' => TRUE,
        ), 'bbim_amount' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bbim_status' => array(
            'type' => 'VARCHAR',
            'constraint' => '10',
            'null' => TRUE,
        ), 'bbim_period_begin' => array(
            'type' => 'date',
            'null' => TRUE,
        ), 'bbim_period_end' => array(
            'type' => 'date',
            'null' => TRUE,
        ), 'bbim_balance_date' => array(
            'type' => 'date',
            'null' => TRUE,
        ), 'bbim_is_exist' => array(
            'type' => 'INT',
            'null' => TRUE,
        )
    );
}
