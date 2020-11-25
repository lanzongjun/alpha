<?php
/**
 * Description of AdVerifyRecordM
 *
 * @author Vincent
 */
class AdVerifyRecordM extends CI_Model {

    var $_tbn_ba_daily = 'balance_record_daily';
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
    function getList($i_page, $i_rows, $a_get) {        
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getWhere($a_get);
        $s_sql = "SELECT vr_id ck,vr_id,vr_verify_date,vr_org_sn,vr_shop_name,"
                . "vr_verify_amount,vr_unique,vr_state,vr_time,nickname vr_user "
                . "FROM $this->_tbn_vr LEFT JOIN admin_user ON vr_user=uid $s_where "
                . "ORDER BY vr_verify_date DESC,vr_org_sn ASC LIMIT $i_start,$i_rows";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }
    
    function _getTotal($s_where) {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->_tbn_vr $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num-0;
    }
    
    function getWhere($a_get){
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
        if (isset($a_get['s_vrid']) && $a_get['s_vrid']) {
            $s_where .= " AND vr_id LIKE '%".$a_get['s_vrid']."%'";
        }
        return $s_where;
    }
    
    /**
     * 获得列表
     * @return type
     */
    function getCashPoolList($s_vrid) {
        $s_sql = "SELECT cpd_bill_code ck,cpd_bill_code,cpd_date,cpd_time,"
                . "cpd_shop,cpd_bs_sale_sn,cpd_bs_org_sn,cpd_amount,cpd_biz_type,"
                . "cpd_remaining_sum,cpd_trade_state,cpd_pay_account "
                . "FROM $this->_tbn_cash_pool WHERE cpd_vr_id='$s_vrid' "
                . "ORDER BY cpd_date DESC,cpd_bs_org_sn ASC ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }

    /**
     * 获得列表
     * @return type
     */
    function getBRDList($s_vrid) {
        $s_sql = "SELECT brd_id ck,brd_id,brd_date_begin,brd_date_end,"
                . "brd_shop_name,brd_org_sn,brd_balance_amount,brd_memo "
                . "FROM $this->_tbn_ba_daily WHERE brd_vr_id='$s_vrid' "
                . "ORDER BY brd_date_end DESC,brd_org_sn ASC";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function doRemove($s_vrid) {
        $o_result['state'] = false;
        $o_result['msg'] = 'FAULT';
        $this->db->trans_start();
        
        $s_sql_1 = "UPDATE $this->_tbn_cash_pool SET cpd_vr_id='',"
                . "cpd_vr_unique=-1,cpd_vr_state=0 WHERE cpd_vr_id='$s_vrid'";
        log_message('debug', "SQL文:$s_sql_1");
        $this->db->query($s_sql_1);
        $s_sql_2 = "UPDATE $this->_tbn_ba_daily SET brd_vr_id='',"
                . "brd_vr_unique=-1,brd_vr_state=0 WHERE brd_vr_id='$s_vrid'";
        log_message('debug', "SQL文:$s_sql_2");
        $this->db->query($s_sql_2);
        $s_sql_3 = "DELETE FROM $this->_tbn_vr WHERE vr_id='$s_vrid'";
        log_message('debug', "SQL文:$s_sql_3");
        $this->db->query($s_sql_3);
        
        try{
            $b_result = $this->db->trans_complete();
            log_message('debug', "事务执行结果:$b_result");
            $o_result['state'] = $b_result;
            $o_result['msg'] = "删除核销:".($b_result?'success':'fault');
            return $o_result;
        } catch (Exception $e) {
            log_message('error', '删除核销-异常中断！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "删除核销-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
        
    }
    
}
