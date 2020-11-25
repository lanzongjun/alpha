<?php
/**
 * Description of AdVerifyCountM
 *
 * @author Vincent
 */
class AdVerifyCountM extends CI_Model {

    var $_tbn_ba_daily = 'balance_record_daily';
    var $_tbn_ba = 'balance_account';
    var $_tbn_cash_pool = 'base_cash_pool';
    var $_tbn_vr = 'verify_record';
    var $_tbn_vc = 'verify_count';
    var $_tbn_cuc = 'cache_unverify_cpd';
    var $_tbn_cub = 'cache_unverify_brd';
    var $_tbn_shop = 'base_shop_info';
    
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
        $this->load->dbforge();
    }

    /**
     * 获得列表
     * @return type
     */
    function getBRList($i_page, $i_rows, $a_get) {        
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getBRWhere($a_get);
        $s_sql = "SELECT brd_id ck,brd_id,brd_date_begin,brd_date_end,"
                . "brd_shop_name,brd_org_sn,brd_balance_amount,brd_memo "
                . "FROM $this->_tbn_ba_daily $s_where "
                . "ORDER BY brd_date_end DESC,brd_org_sn ASC LIMIT $i_start,$i_rows";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->getBRTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }
    
    function getBRTotal($s_where) {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->_tbn_ba_daily $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num-0;
    }
    
    function getBRWhere($a_get){
        $s_where = "WHERE brd_vr_state=0 ";
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
    function getCashPoolList($i_page, $i_rows, $a_get) {        
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getCPWhere($a_get);
        $s_sql = "SELECT cpd_bill_code ck,cpd_bill_code,cpd_date,cpd_time,cpd_shop,"
                . "cpd_bs_sale_sn,cpd_bs_org_sn,cpd_amount,cpd_biz_type,"
                . "cpd_remaining_sum,cpd_trade_state,'' v_id "
                . "FROM $this->_tbn_cash_pool $s_where "
                . "ORDER BY cpd_date DESC,cpd_bs_org_sn ASC "
                . "LIMIT $i_start,$i_rows";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->getCPTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }
    
    function getCPTotal($s_where) {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->_tbn_cash_pool $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num-0;
    }

    function getCPWhere($a_get){
        $s_where = "WHERE cpd_vr_state=0 ";
        if (isset($a_get['s_db']) && $a_get['s_db']){
            $s_where .= " AND cpd_date >= '".$a_get['s_db']."'";
        }
        if (isset($a_get['s_de']) && $a_get['s_de']){
            $s_where .= " AND cpd_date <= '".$a_get['s_de']."'";
        }
        if (isset($a_get['s_sid']) && $a_get['s_sid']) {
            $s_where .= " AND cpd_bs_org_sn=".$a_get['s_sid'];
        }
        return $s_where;
    }
    
    function getVCList($a_get){
        $s_where = $this->getVCWhere($a_get);
        $s_order = $this->getVCOrder($a_get);
        $s_sql = "SELECT vc_org_sn,vc_shop_name,vc_verify_amount,vc_unverify_brd,"
                . "vc_unverify_cpd,vc_unverify_diff,vc_update_time "
                . "FROM $this->_tbn_vc INNER JOIN $this->_tbn_shop "
                . "ON vc_org_sn=bs_org_sn $s_where $s_order";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function getVCWhere($a_get){
        $s_where = "WHERE 1=1 ";
        if (isset($a_get['s_sid']) && $a_get['s_sid']) {
            $s_where .= " AND bs_district='".$a_get['s_sid']."'";
        }
        return $s_where;
    }
    
    function getVCOrder($a_get){
        $s_order = "ORDER BY vc_org_sn ASC";
        if (isset($a_get['sort']) && $a_get['sort']){
            if (isset($a_get['order']) && $a_get['order']){
                $s_order = "ORDER BY ".$a_get['sort']." ".$a_get['order'];
            }else{
                $s_order = "ORDER BY ".$a_get['sort']." ASC";
            }
        }
        return $s_order;
    }
    
    /**
     * 核销统计
     * @return string
     */
    function doVerifyCount(){
        $o_result['state'] = false;
        $o_result['msg'] = 'FAULT';
        $this->db->trans_start();
        
        $s_sql_c_1 = "DELETE FROM $this->_tbn_vc";
        log_message('debug', "SQL文:$s_sql_c_1");
        $this->db->query($s_sql_c_1);
        $s_sql_c_2 = "DELETE FROM $this->_tbn_cuc";
        log_message('debug', "SQL文:$s_sql_c_2");
        $this->db->query($s_sql_c_2);
        $s_sql_c_3 = "DELETE FROM $this->_tbn_cub";
        log_message('debug', "SQL文:$s_sql_c_3");
        $this->db->query($s_sql_c_3);
        
        $s_sql1 = "INSERT INTO $this->_tbn_vc (vc_org_sn,vc_shop_name,vc_verify_amount) "
                . "SELECT brd_org_sn,brd_shop_name,SUM(brd_balance_amount) bba "
                . "FROM $this->_tbn_ba_daily WHERE brd_vr_state=1 GROUP BY brd_org_sn";
        log_message('debug', "SQL文:$s_sql1");
        $this->db->query($s_sql1);
        $s_sql2 = "INSERT INTO $this->_tbn_cuc (cuvc_org_sn,cuvc_shop_name,cuvc_unverify_amount) "
                . "SELECT cpd_bs_org_sn,cpd_shop,SUM(cpd_amount) cpd_a "
                . "FROM $this->_tbn_cash_pool WHERE cpd_vr_state=0 GROUP BY cpd_bs_org_sn";
        log_message('debug', "SQL文:$s_sql2");
        $this->db->query($s_sql2);
        $s_sql3 = "INSERT INTO $this->_tbn_cub (cuvb_org_sn,cuvb_shop_name,cuvb_unverify_amount) "
                . "SELECT brd_org_sn,brd_shop_name,SUM(brd_balance_amount) brd_a "
                . "FROM $this->_tbn_ba_daily WHERE brd_vr_state=0 GROUP BY brd_org_sn";
        log_message('debug', "SQL文:$s_sql3");
        $this->db->query($s_sql3);
        $s_sql_4 = "INSERT INTO $this->_tbn_vc (vc_org_sn,vc_shop_name,vc_unverify_cpd) "
                . "SELECT cuvc_org_sn,cuvc_shop_name,cuvc_unverify_amount "
                . "FROM $this->_tbn_cuc WHERE cuvc_org_sn NOT IN "
                . "(SELECT vc_org_sn FROM $this->_tbn_vc)";
        log_message('debug', "SQL文:$s_sql_4");
        $this->db->query($s_sql_4);
        $s_sql_5 = "UPDATE $this->_tbn_vc INNER JOIN $this->_tbn_cuc "
                . "ON vc_org_sn=cuvc_org_sn SET vc_unverify_cpd=cuvc_unverify_amount ";
        log_message('debug', "SQL文:$s_sql_5");
        $this->db->query($s_sql_5);
        $s_sql_6 = "INSERT INTO $this->_tbn_vc (vc_org_sn,vc_shop_name,vc_unverify_brd) "
                . "SELECT cuvb_org_sn,cuvb_shop_name,cuvb_unverify_amount "
                . "FROM $this->_tbn_cub WHERE cuvb_org_sn NOT IN "
                . "(SELECT vc_org_sn FROM $this->_tbn_vc)";
        log_message('debug', "SQL文:$s_sql_6");
        $this->db->query($s_sql_6);
        $s_sql_7 = "UPDATE $this->_tbn_vc INNER JOIN $this->_tbn_cub "
                . "ON vc_org_sn=cuvb_org_sn SET vc_unverify_brd=cuvb_unverify_amount ";
        log_message('debug', "SQL文:$s_sql_7");
        $this->db->query($s_sql_7);
        $s_sql_8 = "UPDATE $this->_tbn_vc SET vc_unverify_diff=vc_unverify_brd-vc_unverify_cpd ";
        log_message('debug', "SQL文:$s_sql_8");
        $this->db->query($s_sql_8);
        try{
            $b_result = $this->db->trans_complete();
            log_message('debug', "事务执行结果:$b_result");
            $o_result['state'] = $b_result;
            $o_result['msg'] = "核销统计:".($b_result?'success':'fault');
            return $o_result;
        } catch (Exception $e) {
            log_message('error', '核销统计-异常中断！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "核销统计-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }    
    
    function doOutput(){
        $s_sql1 = "SELECT vc_org_sn,vc_shop_name,vc_verify_amount,vc_unverify_brd,"
                . "vc_unverify_cpd,vc_unverify_diff,vc_update_time FROM $this->_tbn_vc ";
        $o_return1 = $this->db->query($s_sql1);
        $a_c_vr = $this->getVCColumn();
        $o_vr = array(
            'sheetName'=>'核销统计',
            'column'=>$a_c_vr,
            'record'=>$o_return1->result()
        );
        $s_sql2 = "SELECT brd_date_begin,brd_date_end,brd_shop_name,brd_org_sn,"
                . "brd_balance_amount FROM $this->_tbn_ba_daily WHERE brd_vr_state=0";
        $o_return2 = $this->db->query($s_sql2);
        $a_c_brd = $this->getBRDColumn();
        $o_brd = array(
            'sheetName'=>'未核销-结算记录',
            'column'=>$a_c_brd,
            'record'=>$o_return2->result()
        );
        $s_sql3 = "SELECT cpd_bill_code ck,cpd_bill_code,cpd_date,cpd_time,"
                . "cpd_shop,cpd_bs_sale_sn,cpd_bs_org_sn,cpd_amount,cpd_biz_type,"
                . "cpd_remaining_sum,cpd_trade_state,cpd_pay_account "
                . "FROM $this->_tbn_cash_pool WHERE cpd_vr_state=0 ";
        $o_return3 = $this->db->query($s_sql3);
        $a_c_cpd = $this->getCPDColumn();
        $o_cpd = array(
            'sheetName'=>'未核销-资金流水',
            'column'=>$a_c_cpd,
            'record'=>$o_return3->result()
        );
        $s_date = date("Y-m-d", time());
        $this->load->library('Php_spread_sheet_lib');
        $this->php_spread_sheet_lib->outputMultipleSheet(array($o_vr,$o_brd,$o_cpd),"核销统计($s_date)");
    }
    
    function getCPDColumn(){
        $a_column = array();
        array_push($a_column, array(
            'title' => '交易日期',
            'field' => 'cpd_date'
        ));
        array_push($a_column, array(
            'title' => '网点',
            'field' => 'cpd_shop'
        ));
        array_push($a_column, array(
            'title' => '网点编码',
            'field' => 'cpd_bs_sale_sn'
        ));
        array_push($a_column, array(
            'title' => '组织编码',
            'field' => 'cpd_bs_org_sn'
        ));        
        array_push($a_column, array(
            'title' => '交易金额',
            'field' => 'cpd_amount'
        ));
        array_push($a_column, array(
            'title' => '付款方账号',
            'field' => 'cpd_pay_account'
        ));
        array_push($a_column, array(
            'title' => '业务类型',
            'field' => 'cpd_biz_type'
        ));        
        array_push($a_column, array(
            'title' => '资金池流水号',
            'field' => 'cpd_bill_code'
        ));
        array_push($a_column, array(
            'title' => '账户余额',
            'field' => 'cpd_remaining_sum'
        ));
        array_push($a_column, array(
            'title' => '交易状态',
            'field' => 'cpd_trade_state'
        ));
        return $a_column;
    }
    
    function getBRDColumn(){
        $a_column = array();
        array_push($a_column, array(
            'title' => '开始日期',
            'field' => 'brd_date_begin'
        ));
        array_push($a_column, array(
            'title' => '结束日期',
            'field' => 'brd_date_end'
        ));
        array_push($a_column, array(
            'title' => '组织编码',
            'field' => 'brd_org_sn'
        ));
        array_push($a_column, array(
            'title' => '门店名称',
            'field' => 'brd_shop_name'
        ));
        array_push($a_column, array(
            'title' => '结算金额',
            'field' => 'brd_balance_amount'
        ));
        return $a_column;
    }
    
    function getVCColumn(){
        $a_column = array();
        array_push($a_column, array(
            'title' => '组织编码',
            'field' => 'vc_org_sn'
        ));
        array_push($a_column, array(
            'title' => '门店名称',
            'field' => 'vc_shop_name'
        ));
        array_push($a_column, array(
            'title' => '核销金额',
            'field' => 'vc_verify_amount'
        ));
        array_push($a_column, array(
            'title' => '未核销-结算金额',
            'field' => 'vc_unverify_brd'
        ));
        array_push($a_column, array(
            'title' => '未核销-资金流水',
            'field' => 'vc_unverify_cpd'
        ));
        array_push($a_column, array(
            'title' => '未核销-差异',
            'field' => 'vc_unverify_diff'
        ));
        return $a_column;
    }
}
