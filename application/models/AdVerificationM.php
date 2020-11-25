<?php
/**
 * Description of AdVerificationM
 *
 * @author Vincent
 */
class AdVerificationM extends CI_Model {

    var $_tbn_ba_daily = 'balance_record_daily';
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
     * 获得列表
     * @return type
     */
    function _getList($i_page, $i_rows, $a_get) {        
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getWhere($a_get);
        $s_sql = "SELECT brd_id ck,brd_id,brd_date_begin,brd_date_end,"
                . "brd_shop_name,brd_org_sn,brd_balance_amount,brd_memo "
                . "FROM $this->_tbn_ba_daily $s_where "
                . "ORDER BY brd_date_end DESC,brd_org_sn ASC LIMIT $i_start,$i_rows";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }
    
    /**
     * 获得数据量
     * @param type $s_where
     * @return type
     */
    function _getTotal($s_where) {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->_tbn_ba_daily $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num-0;
    }
    
    /**
     * 获得过滤条件
     * @param type $a_get
     * @return string
     */
    function getWhere($a_get){
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
     * 获得流水列表
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
    
    /**
     * 获得流水总数
     * @param type $s_where
     * @return type
     */
    function getCPTotal($s_where) {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->_tbn_cash_pool $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num-0;
    }

    /**
     * 获得流水过滤条件
     * @param type $a_get
     * @return string
     */
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
    
    /**
     * 精确关联-获取数据
     * @return type
     */
    function doAutoLink(){
        $s_sql_cp = "SELECT cpd_bill_code,cpd_date,cpd_shop,cpd_bs_org_sn,cpd_amount,"
                . "cpd_remaining_sum,cpd_vr_id,cpd_biz_type,cpd_trade_state,cpd_vr_state "
                . "FROM $this->_tbn_cash_pool WHERE cpd_vr_state=0 "
                . "ORDER BY cpd_bs_org_sn ASC,cpd_date DESC ";
        $o_result_cp = $this->db->query($s_sql_cp);
        $a_list_cp = $o_result_cp->result();
        
        $s_sql_bal = "SELECT brd_id,brd_date_begin,brd_date_end,brd_shop_name,"
                . "brd_vr_id,brd_org_sn,brd_balance_amount,brd_vr_state "
                . "FROM $this->_tbn_ba_daily WHERE brd_vr_state=0 "
                . "ORDER BY brd_org_sn ASC,brd_date_end DESC";
        $o_result_bal = $this->db->query($s_sql_bal);
        $a_list_bal = $o_result_bal->result();
        $a_return_cp = $this->getCPOrgListByList($a_list_cp);
        $a_return_br = $this->getBROrgListByList($a_list_bal);
        $a_result = $this->_doAutoLink($a_return_cp, $a_return_br);
        return $a_result;
    }
    
    /**
     * 精确关联-每门店迭代判断
     * @param type $a_cp
     * @param type $a_br
     * @return array
     */
    function _doAutoLink($a_cp,$a_br){
        $a_result = array();
        foreach ($a_cp as $key => $a_cp_detail) {
            $i_org_sn = $key;
            if (!isset($a_br[$i_org_sn])){
                continue;
            }
            $a_br_detail = $a_br[$i_org_sn];
            $this->_doLinkOrg($a_cp_detail,$a_br_detail,$a_result);
        }
        return $a_result;
    }
    
    /**
     * 精确关联-关联判断(主要方法)
     * @param type $a_cp_detail
     * @param type $a_br_detail
     * @param type $a_result
     */
    function _doLinkOrg($a_cp_detail, $a_br_detail, &$a_result) {
        foreach ($a_cp_detail as $o_cp_detail) {
            if ($o_cp_detail->cpd_vr_state == 1){continue;}
            $s_cpd_date = $o_cp_detail->cpd_date;
            $d_cpd_amount = $o_cp_detail->cpd_amount-0;
            $s_cpd_bill_code = $o_cp_detail->cpd_bill_code;
            foreach ($a_br_detail as $o_br_detail) {
                if ($o_br_detail->brd_vr_state == 1){continue;}
                $s_brd_date_end = $o_br_detail->brd_date_end;
                $d_brd_balance_amount = $o_br_detail->brd_balance_amount-0;
                $s_brd_id = $o_br_detail->brd_id;
                //流水日期大于结算日期，并且结算金额相等
                if ($s_cpd_date > $s_brd_date_end 
                        && $d_cpd_amount == $d_brd_balance_amount) {
                    //精确核销号组成
                    $s_vr_id = "A_$s_brd_id"."_$s_cpd_bill_code";
                    $o_cp_detail->cpd_vr_id = $s_vr_id;
                    $o_br_detail->brd_vr_id = $s_vr_id;
                    $o_cp_detail->cpd_vr_state = 1;
                    $o_br_detail->brd_vr_state = 1;
                    array_push($a_result, $this->_mergeObj($o_cp_detail, $o_br_detail));
                    break;
                }
            }
        }
    }
    
    /**
     * 合并流水信息和结算信息对象
     * @param type $o_cp_detail
     * @param type $o_br_detail
     * @return type
     */
    function _mergeObj($o_cp_detail,$o_br_detail){
        $o_result = array(
            'ck_id'=>$o_br_detail->brd_vr_id,
            'cpd_bill_code'=>$o_cp_detail->cpd_bill_code,
            'cpd_date'=>$o_cp_detail->cpd_date,
            'cpd_shop'=>$o_cp_detail->cpd_shop,
            'cpd_bs_org_sn'=>$o_cp_detail->cpd_bs_org_sn,
            'cpd_amount'=>$o_cp_detail->cpd_amount,
            'cpd_remaining_sum'=>$o_cp_detail->cpd_remaining_sum,
            'cpd_biz_type'=>$o_cp_detail->cpd_biz_type,
            'cpd_trade_state'=>$o_cp_detail->cpd_trade_state,
            'brd_id'=>$o_br_detail->brd_id,
            'brd_date_begin'=>$o_br_detail->brd_date_begin,
            'brd_date_end'=>$o_br_detail->brd_date_end,
            'brd_shop_name'=>$o_br_detail->brd_shop_name,
            'vr_id'=>$o_br_detail->brd_vr_id,
            'vr_unique'=>1,
            'brd_org_sn'=>$o_br_detail->brd_org_sn,
            'brd_balance_amount'=>$o_br_detail->brd_balance_amount
        );
        return $o_result;
    }

    /**
     * 重构每门店流水信息对象
     * @param type $a_list_cp
     * @return array
     */
    function getCPOrgListByList($a_list_cp){
        $a_result = array();
        for($i=0; $i<count($a_list_cp); $i++){
            $i_org_sn = $a_list_cp[$i]->cpd_bs_org_sn;
            if (isset($a_result[$i_org_sn])){
                array_push($a_result[$i_org_sn],$a_list_cp[$i]);
            }else{
                $a_result_detail = array();
                array_push($a_result_detail,$a_list_cp[$i]);
                $a_result[$i_org_sn] = $a_result_detail;
            }
        }
        return $a_result;
    }
    
    /**
     * 重构每门店结算信息对象
     * @param type $a_list_bal
     * @return array
     */
    function getBROrgListByList($a_list_bal){
        $a_result = array();
        for($i=0; $i<count($a_list_bal); $i++){
            $i_org_sn = $a_list_bal[$i]->brd_org_sn;
            if (isset($a_result[$i_org_sn])){
                array_push($a_result[$i_org_sn],$a_list_bal[$i]);
            }else{
                $a_result_detail = array();
                array_push($a_result_detail,$a_list_bal[$i]);
                $a_result[$i_org_sn] = $a_result_detail;
            }
        }
        return $a_result;
    }
    
    /**
     * 精确核销
     * @param type $a_data
     * @return string
     */
    function doAimVerify($a_data) {
        $o_result['state'] = false;
        $o_result['msg'] = 'FAULT';
        
        $i_user_id = 0;
        $this->load->library('session');
        $s_userid = $this->session->userdata('s_id');
        if ($s_userid) { $i_user_id = $s_userid; }
        
        $this->db->trans_start();
        foreach ($a_data as $o_data) {
            $vr_id = $o_data->vr_id;
            $cpd_bill_code = $o_data->cpd_bill_code;
            $brd_id = $o_data->brd_id;
            $s_sql1 = "UPDATE $this->_tbn_cash_pool SET cpd_vr_id='$vr_id',"
                    . "cpd_vr_unique=1,cpd_vr_state=1 WHERE cpd_bill_code='$cpd_bill_code' ";
            log_message('debug', "SQL文:$s_sql1");
            $this->db->query($s_sql1);
            $s_sql2 = "UPDATE $this->_tbn_ba_daily SET brd_vr_id='$vr_id',"
                    . "brd_vr_unique=1,brd_vr_state=1 WHERE brd_id=$brd_id ";
            log_message('debug', "SQL文:$s_sql2");
            $this->db->query($s_sql2);
            $s_sql3 = "INSERT INTO $this->_tbn_vr (vr_id,vr_verify_date,vr_org_sn,"
                . "vr_shop_name,vr_verify_amount,vr_unique,vr_state,vr_user) SELECT '$vr_id',"
                . "brd_date_end,brd_org_sn,brd_shop_name,brd_balance_amount,1,1,$i_user_id "
                . "FROM $this->_tbn_ba_daily WHERE brd_vr_id='$vr_id'";
            log_message('debug', "SQL文:$s_sql3");
            $this->db->query($s_sql3);
        }
        try{
            $b_result = $this->db->trans_complete();
            log_message('debug', "事务执行结果:$b_result");
            $o_result['state'] = $b_result;
            $o_result['msg'] = "精确核销:".($b_result?'success':'fault');
            return $o_result;
        } catch (Exception $e) {
            log_message('error', '精确核销-异常中断！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "精确核销-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }
    
    /**
     * 手工核销
     * @param type $a_cpd
     * @param type $a_br
     * @param type $s_vr
     * @return string
     */
    function doCusVerify($a_cpd,$a_br,$s_vr){
        $o_result['state'] = false;
        $o_result['msg'] = 'FAULT';
        
        $i_user_id = 0;
        $this->load->library('session');
        $s_userid = $this->session->userdata('s_id');
        if ($s_userid) { $i_user_id = $s_userid; }
        
        $this->db->trans_start();
        foreach($a_br as $s_br){
            $s_sql1 = "UPDATE $this->_tbn_ba_daily SET brd_vr_id='$s_vr',"
                    . "brd_vr_unique=0,brd_vr_state=1 WHERE brd_id=$s_br ";
            log_message('debug', "SQL文:$s_sql1");
            $this->db->query($s_sql1);
        }
        foreach($a_cpd as $s_cpd){
            $s_sql2 = "UPDATE $this->_tbn_cash_pool SET cpd_vr_id='$s_vr',"
                    . "cpd_vr_unique=0,cpd_vr_state=1 WHERE cpd_bill_code='$s_cpd' ";
            log_message('debug', "SQL文:$s_sql2");
            $this->db->query($s_sql2);
        }
        $s_sql3 = "INSERT INTO $this->_tbn_vr (vr_id,vr_verify_date,vr_org_sn,"
                . "vr_shop_name,vr_verify_amount,vr_unique,vr_state,vr_user) "
                . "SELECT MAX('$s_vr'),MAX(brd_date_end),MAX(brd_org_sn),"
                . "MAX(brd_shop_name),SUM(brd_balance_amount),MAX(0),MAX(1)"
                . ",MAX($i_user_id) FROM $this->_tbn_ba_daily WHERE brd_vr_id='$s_vr'";      
        log_message('debug', "SQL文:$s_sql3");
        $this->db->query($s_sql3);
        try{
            $b_result = $this->db->trans_complete();
            log_message('debug', "事务执行结果:$b_result");
            $o_result['state'] = $b_result;
            $o_result['msg'] = "手工核销:".($b_result?'success':'fault');
            return $o_result;
        } catch (Exception $e) {
            log_message('error', '手工核销-异常中断！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "手工核销-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }
}
