<?php
/**
 * Description of AdBalanceRecordM
 *
 * @author Vincent
 */
class AdBalanceRecordM extends CI_Model {

    var $__table_name = 'balance_record_daily';    
    var $_tbn_ba_shop = 'balance_account_shop';
    var $_tbn_ba = 'balance_account';
    var $_tbn_cash_pool = 'base_cash_pool';
    var $_tbn_vr = 'verify_record';
    
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
        $this->load->dbforge();
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
        $s_sql = "SELECT brd_date_begin,brd_date_end,brd_shop_name,brd_org_sn,"
                . "brd_balance_amount,brd_memo,brd_is_exist FROM temp_$s_table_name "
                . "ORDER BY brd_date_end DESC,brd_org_sn ASC ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }

    /**
     * 获得列表
     * @return type
     */
    function _getList($i_page, $i_rows, $a_get) {        
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getWhere($a_get);
        $s_sql = "SELECT brd_id,brd_date_begin,brd_date_end,brd_shop_name,brd_org_sn,"
                . "brd_balance_amount,brd_memo,brd_vr_id,brd_vr_unique,brd_vr_state "
                . "FROM $this->__table_name $s_where "
                . "ORDER BY brd_date_end DESC,brd_org_sn ASC LIMIT $i_start,$i_rows";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }
    
    function _getTotal($s_where) {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->__table_name $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num-0;
    }
    
    function getWhere($a_get){
        $s_where = "WHERE 1=1";
        if (isset($a_get['s_db']) && $a_get['s_db']){
            $s_where .= " AND brd_date_begin >= '".$a_get['s_db']."'";
        }
        if (isset($a_get['s_de']) && $a_get['s_de']){
            $s_where .= " AND brd_date_end <= '".$a_get['s_de']."'";
        }
        if (isset($a_get['s_sid']) && $a_get['s_sid']) {
            $s_where .= " AND brd_org_sn=".$a_get['s_sid'];
        }
        return $s_where;
    }
    
    /**
     * 获得列表
     * @return type
     */
    function getBalList($i_page, $i_rows, $a_get) {        
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getBalWhere($a_get);
        $s_sql = "SELECT bas_id ck,bas_bs_org_sn,bas_bs_sale_sn,bas_bs_shop_name,"
                . "bas_order_count,bas_order_amount,bas_state,bas_ba_bat_id,"
                . "bas_balance_time,bas_update_time,ba_balance_date_begin,"
                . "ba_balance_date_end FROM $this->_tbn_ba_shop "
                . "LEFT JOIN $this->_tbn_ba ON ba_bat_id=bas_ba_bat_id $s_where "
                . "ORDER BY ba_balance_date_begin DESC,bas_bs_org_sn ASC "
                . "LIMIT $i_start,$i_rows";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->getBalTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }
    
    function getBalTotal($s_where) {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->_tbn_ba_shop "
                . "LEFT JOIN $this->_tbn_ba ON ba_bat_id=bas_ba_bat_id $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num-0;
    }

    function getBalWhere($a_get){
        $s_where = "WHERE 1=1";
        if (isset($a_get['s_db']) && $a_get['s_db']){
            $s_where .= " AND ba_balance_date_begin >= '".$a_get['s_db']."'";
        }
        if (isset($a_get['s_de']) && $a_get['s_de']){
            $s_where .= " AND ba_balance_date_end <= '".$a_get['s_de']."'";
        }
        if (isset($a_get['s_sid']) && $a_get['s_sid']) {
            $s_where .= " AND bas_bs_org_sn=".$a_get['s_sid'];
        }
        return $s_where;
    }
    
    function aCode2Codes($a_codes) {
        if (null == $a_codes || count($a_codes) < 1 || $a_codes==''){return '';}
        $s_codes = $a_codes[0];
        for ($i = 1; $i < count($a_codes); $i++) {
            $s_codes .= ",$a_codes[$i]";
        }
        return $s_codes;
    }
    
    function doDaliy2BalList($a_ids) {
        $o_result = array('state' => false,'msg' => '');
        if (null == $a_ids || count($a_ids) < 1 || $a_ids==''){
            $o_result['msg'] = 'ID为空';
            return $o_result;            
        }
        $s_ids = $this->aCode2Codes($a_ids);
        $s_sql = "INSERT INTO $this->__table_name (brd_date_begin,brd_date_end,"
                . "brd_shop_name,brd_org_sn,brd_balance_amount) "
                . "SELECT ba_balance_date_begin,ba_balance_date_end,"
                . "bas_bs_shop_name,bas_bs_org_sn,bas_order_amount "
                . "FROM $this->_tbn_ba_shop "
                . "LEFT JOIN $this->_tbn_ba ON ba_bat_id=bas_ba_bat_id "
                . "WHERE bas_id IN ($s_ids)";
        log_message('debug', "SQL文:$s_sql");
        $this->db->query($s_sql);
        $i_rows = $this->db->affected_rows();
        $o_result['state'] = true;
        $o_result['msg'] = '受影响记录数：'+$i_rows;
        return $o_result;
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
            'title' => '序号',
            'field' => 'brd_no'
        ));
        array_push($a_column, array(
            'title' => '开始日期',
            'field' => 'brd_date_begin'
        ));
        array_push($a_column, array(
            'title' => '结束日期',
            'field' => 'brd_date_end'
        ));
        array_push($a_column, array(
            'title' => '门店名称',
            'field' => 'brd_shop_name'
        ));
        array_push($a_column, array(
            'title' => '组织编码',
            'field' => 'brd_org_sn'
        ));
        array_push($a_column, array(
            'title' => '结算金额',
            'field' => 'brd_balance_amount'
        ));
        array_push($a_column, array(
            'title' => '备注',
            'field' => 'brd_memo'
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
            $s_sql = "INSERT INTO temp_$s_table_name (brd_date_begin,brd_date_end,"
                    . "brd_shop_name,brd_org_sn,brd_balance_amount,brd_memo,"
                    . "brd_is_exist) VALUES ('"
                    . $o_line['brd_date_begin'] .
                    "','" . $o_line['brd_date_end'] .
                    "','" . $o_line['brd_shop_name'] .
                    "'," . $o_line['brd_org_sn'] .
                    "," . $o_line['brd_balance_amount'] .
                    ",'" . $o_line['brd_memo'] .
                    "',0)";
            $this->db->query($s_sql);
            $i_rows = $i_rows + 1;
        }
        $s_sql1 = "UPDATE temp_$s_table_name A1 INNER JOIN $this->__table_name A2 "
                . "ON A1.brd_org_sn=A2.brd_org_sn "
                . "AND A1.brd_date_begin=A2.brd_date_begin "
                . "AND A1.brd_date_end=A2.brd_date_end "
                . "SET A1.brd_is_exist=1 ";
        $this->db->query($s_sql1);
        
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
        $s_sql = "INSERT INTO $this->__table_name (brd_date_begin,brd_date_end,"
                . "brd_shop_name,brd_org_sn,brd_balance_amount,brd_memo) "
                . "SELECT brd_date_begin,brd_date_end,brd_shop_name,brd_org_sn,"
                . "brd_balance_amount,brd_memo FROM temp_$s_table_name "
                . "WHERE brd_is_exist=0 AND brd_org_sn IS NOT NULL AND brd_org_sn<>'' ";
        $this->db->query($s_sql);
        //删除临时表
        $this->_dropTempTable($s_table_name);
        return $this->db->trans_complete();
    }
    
    /**
     * 追加数据
     * @param type $s_table_name
     * @return type 受影响行数
     */
    function doCoverSQL($s_table_name) {
        $this->db->trans_start();
        //备份数据表
        $this->_backupTable($this->__table_name);
        //清空数据
        $s_sql_clear = "DELETE FROM $this->__table_name";
        $this->db->query($s_sql_clear);
        //执行追加
        $s_sql = "INSERT INTO $this->__table_name (brd_date_begin,brd_date_end,"
                . "brd_shop_name,brd_org_sn,brd_balance_amount,brd_memo) "
                . "SELECT brd_date_begin,brd_date_end,brd_shop_name,brd_org_sn,"
                . "brd_balance_amount,brd_memo FROM temp_$s_table_name "
                . "WHERE brd_org_sn IS NOT NULL AND brd_org_sn<>'' ";
        $this->db->query($s_sql);
        //删除临时表
        $this->_dropTempTable($s_table_name);
        return $this->db->trans_complete();
    }

    function doRemoveRecord($s_id,$s_vrid){
        $o_result['state'] = false;
        $o_result['msg'] = 'FAULT';
        $this->db->trans_start();
        $s_sql1 = "UPDATE $this->_tbn_cash_pool SET cpd_vr_id='',cpd_vr_unique=-1,"
                . "cpd_vr_state=0 WHERE cpd_vr_id='$s_vrid'";
        log_message('debug', "SQL文:$s_sql1");
        $this->db->query($s_sql1);
        $s_sql2 = "UPDATE $this->__table_name SET brd_vr_id='',brd_vr_unique=-1,"
                . "brd_vr_state=0 WHERE brd_vr_id='$s_vrid' ";
        log_message('debug', "SQL文:$s_sql2");
        $this->db->query($s_sql2);
        $s_sql3 = "DELETE FROM $this->__table_name WHERE brd_id='$s_id'";
        log_message('debug', "SQL文:$s_sql3");
        $this->db->query($s_sql3);
        $s_sql4 = "DELETE FROM $this->_tbn_vr WHERE vr_id='$s_vrid'";
        log_message('debug', "SQL文:$s_sql4");
        $this->db->query($s_sql4);
        try{
            $b_result = $this->db->trans_complete();
            log_message('debug', "事务执行结果:$b_result");
            $o_result['state'] = $b_result;
            $o_result['msg'] = "删除结算记录:".($b_result?'success':'fault');
            return $o_result;
        } catch (Exception $e) {
            log_message('error', '删除结算记录-异常中断！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "删除结算记录-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }
    
    var $fields = array(
        'brd_date_begin' => array(
            'type' => 'DATE',
            'null' => TRUE,
        ), 'brd_date_end' => array(
            'type' => 'DATE',
            'null' => TRUE,
        ), 'brd_shop_name' => array(
            'type' => 'VARCHAR',
            'constraint' => '60',
            'null' => TRUE,
        ), 'brd_org_sn' => array(
            'type' => 'INT',
            'null' => TRUE,
        ), 'brd_balance_amount' => array(
            'type' => 'decimal',
            'constraint' => [12, 2],
            'null' => TRUE,
        ), 'brd_memo' => array(
            'type' => 'VARCHAR',
            'constraint' => '255',
            'null' => TRUE,
        ), 'brd_is_exist' => array(
            'type' => 'INT',
            'null' => TRUE,
        )
    );
}
