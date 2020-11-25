<?php
/**
 * Description of AdInvoiceManageM
 *
 * @author Vincent
 */
class AdInvoiceManageM extends CI_Model {

    var $_tbn_invoice = 'invoice_record';
    var $_tbn_vr = 'verify_record';
    var $_tbn_shop = 'base_shop_info';
    var $_tbn_ba_daily = 'balance_record_daily';
    var $_tbn_cash_pool = 'base_cash_pool';
    
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
        $s_sql = "SELECT ir_no,ir_amount,ir_district,ir_org_sn,ir_shop_name,"
                . "ir_date_issued,nickname ir_user,ir_update_time,ir_memo,ir_balance_amount "
                . "FROM $this->_tbn_invoice LEFT JOIN admin_user ON ir_user=uid $s_where "
                . "ORDER BY ir_date_issued DESC LIMIT $i_start,$i_rows";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }
    
    function _getTotal($s_where) {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->_tbn_invoice $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num-0;
    }
    
    function getWhere($a_get){
        $s_where = "WHERE 1=1";
        if (isset($a_get['s_db']) && $a_get['s_db']){
            $s_where .= " AND ir_date_issued >= '".$a_get['s_db']."'";
        }
        if (isset($a_get['s_de']) && $a_get['s_de']){
            $s_where .= " AND ir_date_issued <= '".$a_get['s_de']."'";
        }
        if (isset($a_get['s_sid']) && $a_get['s_sid']) {
            $s_where .= " AND ir_org_sn=".$a_get['s_sid'];
        }
        if (isset($a_get['s_d']) && $a_get['s_d']) {
            $s_where .= " AND ir_district='".$a_get['s_d']."'";
        }
        return $s_where;
    }
    
    /**
     * 获得列表
     * @return type
     */
    function getCPDList($i_page, $i_rows, $a_get) {
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getCPDWhere($a_get);
        $s_sql = "SELECT cpd_bill_code ck,cpd_bill_code,cpd_date,cpd_time,"
                . "cpd_shop,cpd_bs_sale_sn,cpd_bs_org_sn,cpd_amount,cpd_biz_type,"
                . "cpd_remaining_sum,cpd_trade_state,cpd_pay_account,cpd_vr_state,"
                . "brd_ir_no,bs_district FROM $this->_tbn_cash_pool "
                . "INNER JOIN $this->_tbn_shop ON cpd_bs_org_sn=bs_org_sn "
                . "INNER JOIN $this->_tbn_ba_daily ON cpd_vr_id=brd_vr_id $s_where "
                . "ORDER BY cpd_date DESC,cpd_bs_org_sn ASC LIMIT $i_start,$i_rows";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->getCPDTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }
    
    function getCPDTotal($s_where) {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->_tbn_cash_pool "
                . "INNER JOIN $this->_tbn_shop ON cpd_bs_org_sn=bs_org_sn $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num-0;
    }
    
    function getCPDWhere($a_get){
        $s_where = "WHERE 1=1";
        if (isset($a_get['s_db']) && $a_get['s_db']){
            $s_where .= " AND cpd_date >= '".$a_get['s_db']."'";
        }
        if (isset($a_get['s_de']) && $a_get['s_de']){
            $s_where .= " AND cpd_date <= '".$a_get['s_de']."'";
        }
        if (isset($a_get['s_sid']) && $a_get['s_sid']) {
            $s_where .= " AND cpd_bs_org_sn=".$a_get['s_sid'];
        }
        if (isset($a_get['s_d']) && $a_get['s_d']) {
            $s_where .= " AND bs_district='".$a_get['s_d']."'";
        }
        return $s_where;
    }
    
    /**
     * 获得列表
     * @return type
     */
    function getBRDList($i_page, $i_rows, $a_get) {
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getBRDWhere($a_get);
        $s_sql = "SELECT brd_id ck,brd_id,brd_date_begin,brd_date_end,"
                . "brd_shop_name,brd_org_sn,brd_balance_amount,brd_memo,"
                . "brd_vr_state,brd_ir_no,bs_district FROM $this->_tbn_ba_daily "
                . "INNER JOIN $this->_tbn_shop ON brd_org_sn=bs_org_sn $s_where "
                . "ORDER BY brd_date_end DESC,brd_org_sn ASC LIMIT $i_start,$i_rows";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->getBRDTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }
        
    function getBRDTotal($s_where) {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->_tbn_ba_daily "
                . "INNER JOIN $this->_tbn_shop ON brd_org_sn=bs_org_sn $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num-0;
    }
    
    function getBRDWhere($a_get){
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
        if (isset($a_get['s_d']) && $a_get['s_d']) {
            $s_where .= " AND bs_district='".$a_get['s_d']."'";
        }
        return $s_where;
    }
    
    /**
     * 新增发票信息
     * @param type $a_post
     * @return string
     */
    function addInvoice($a_post){
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        if (!isset($a_post['ir_no']) || !is_numeric($a_post['ir_no']) 
            || !isset($a_post['ir_amount'])|| !is_numeric($a_post['ir_amount'])
            || !isset($a_post['ir_org_sn'])|| !is_numeric($a_post['ir_org_sn'])
            || !isset($a_post['ir_district'])|| !isset($a_post['ir_date_issued'])){
            $o_result['state'] = false;
            $o_result['msg'] = "缺少关键参数";
            return $o_result;
        }
        $i_user_id = 0;
        $this->load->library('session');
        $s_userid = $this->session->userdata('s_id');
        if ($s_userid) { $i_user_id = $s_userid; }
        
        $s_sql = "INSERT INTO $this->_tbn_invoice (ir_no,ir_amount,ir_district,"
                . "ir_org_sn,ir_shop_name,ir_date_issued,ir_user,ir_memo) VALUES ("
                . "'" . $a_post['ir_no']
                . "'," . $a_post['ir_amount']
                . ",'" . $a_post['ir_district']
                . "'," . $a_post['ir_org_sn']
                . ",'" . $a_post['ir_shop_name']
                . "','" . $a_post['ir_date_issued']
                . "'," . $i_user_id
                . ",'" . $a_post['ir_memo']
                . "')";
        log_message('debug', "SQL文:$s_sql");
        try {
            $this->db->query($s_sql);
            $i_rows = $this->db->affected_rows();
        } catch (Exception $ex) {
            log_message('error', '新增发票信息-异常中断！\r\n' . $ex->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "新增发票信息-异常中断！\r\n" . $ex->getMessage();
            return $o_result;
        }
        log_message('debug', "受影响记录数:$i_rows");
        $o_result['state'] = $i_rows == 1;
        $o_result['msg'] = "受影响记录数 : $i_rows 条";
        return $o_result;
    }
    
    /**
     * 获得列表
     * @return type
     */
    function getVerifyList($i_page, $i_rows, $a_get) {        
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getVerifyWhere($a_get);
        $s_sql = "SELECT vr_id ck,vr_id,vr_verify_date,vr_org_sn,vr_shop_name,"
                . "vr_verify_amount,vr_unique,vr_state,vr_time,bs_district vr_district "
                . "FROM $this->_tbn_vr INNER JOIN $this->_tbn_shop "
                . "ON vr_org_sn=bs_org_sn $s_where "
                . "ORDER BY vr_verify_date DESC,vr_org_sn ASC LIMIT $i_start,$i_rows";
//        echo $s_sql;die();
        $o_result = $this->db->query($s_sql);
        $i_total = $this->getVerifyTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }
    
    function getVerifyTotal($s_where) {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->_tbn_vr "
                . "INNER JOIN $this->_tbn_shop ON vr_org_sn=bs_org_sn $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num-0;
    }

    function getVerifyWhere($a_get){
        $s_where = "WHERE vr_state=1 ";
        if (isset($a_get['s_db']) && $a_get['s_db']){
            $s_where .= " AND vr_verify_date >= '".$a_get['s_db']."'";
        }
        if (isset($a_get['s_de']) && $a_get['s_de']){
            $s_where .= " AND vr_verify_date <= '".$a_get['s_de']."'";
        }
        if (isset($a_get['s_sid']) && $a_get['s_sid']) {
            $s_where .= " AND vr_org_sn=".$a_get['s_sid'];
        }
        if (isset($a_get['s_d']) && $a_get['s_d']) {
            $s_where .= " AND bs_district='".$a_get['s_d']."'";
        }
        return $s_where;
    }
    
    function aCode2Codes($a_codes) {
        if (null == $a_codes || count($a_codes) < 1 || $a_codes==''){return '';}
        $s_codes = "'".$a_codes[0]."'";
        for ($i = 1; $i < count($a_codes); $i++) {
            $s_codes .= ",'$a_codes[$i]'";
        }
        return $s_codes;
    }
    
    function doInvoiceLink($s_ir_no,$a_brd_id){
        $o_result['state'] = false;
        $o_result['msg'] = 'FAULT';
        $this->db->trans_start();
        
        $s_brd_id = $this->aCode2Codes($a_brd_id);
        $s_sql1 = "UPDATE $this->_tbn_ba_daily SET brd_ir_no='$s_ir_no' WHERE brd_id IN ($s_brd_id)";
        log_message('debug', "SQL文:$s_sql1");
        $this->db->query($s_sql1);
        
        $s_sql2 = "UPDATE $this->_tbn_invoice LEFT JOIN (SELECT SUM(brd_balance_amount) "
                . "brd_balance_amount,brd_ir_no FROM $this->_tbn_ba_daily "
                . "WHERE brd_ir_no='$s_ir_no') AA "
                . "ON AA.brd_ir_no=ir_no SET ir_balance_amount=brd_balance_amount WHERE ir_no='$s_ir_no'";
        log_message('debug', "SQL文:$s_sql2");
        $this->db->query($s_sql2);
        
        try{
            $b_result = $this->db->trans_complete();
            log_message('debug', "事务执行结果:$b_result");
            $o_result['state'] = $b_result;
            $o_result['msg'] = "发票关联:".($b_result?'success':'fault');
            return $o_result;
        } catch (Exception $e) {
            log_message('error', '发票关联-异常中断！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "发票关联-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }
}
