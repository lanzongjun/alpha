<?php

/**
 * 综合结算
 * Description of AdBalanceAccountM
 *
 * @author Vincent
 */
class AdBalanceAccountM extends CI_Model {

    var $__pay_account = '802320201183858';
    var $__table_name = 'balance_account';

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
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

    /**
     * 获得列表
     * @return type
     */
    function getList() {
        $s_sql = "SELECT ba_id,ba_balance_date_begin,ba_balance_date_end,"
                . "ba_balance_eb,ba_balance_mt,ba_balance_jd,ba_balance_yj,"
                . "ba_cpd_remaining_sum,ba_cpd_time,ba_cpd_bill_code,ba_bat_id,"
                . "ba_balance_time,ba_stage1_time,ba_stage2_time,ba_stage3_time,"
                . "ba_stage4_time FROM $this->__table_name "
                . "ORDER BY ba_balance_date_begin DESC";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    private function getDatetime(){
        return date("Y-m-d H:i:s", time());
    }
    
    /**
     * 获得所有阶段完成时间
     * @param type $i_ba_bat_id
     * @return type
     */
    function getStageTime($i_ba_bat_id) {
        $s_sql = "SELECT ba_stage1_time,ba_stage2_time,ba_stage3_time,ba_stage4_time "
                . "FROM $this->__table_name WHERE ba_bat_id=$i_ba_bat_id";
        $o_datalist = $this->db->query($s_sql);
        $a_datalist = $o_datalist->result();
        return null!=$a_datalist && count($a_datalist)>0 ? $a_datalist[0] : '';
    }
    
    /**
     * 阶段1时间
     */
    function setStage1Time($i_ba_bat_id){
        $s_time = $this->getDatetime();
        $s_sql = "UPDATE $this->__table_name SET ba_stage1_time='$s_time' "
                . "WHERE ba_bat_id=$i_ba_bat_id ";
        log_message('debug', "SQL文:$s_sql");
        $this->db->query($s_sql);
        return $this->db->affected_rows() == 1;
    }
    
    /**
     * 阶段2时间
     */
    function setStage2Time($i_ba_bat_id){
        $s_time = $this->getDatetime();
        $s_sql = "UPDATE $this->__table_name SET ba_stage2_time='$s_time' "
                . "WHERE ba_bat_id=$i_ba_bat_id ";
        log_message('debug', "SQL文:$s_sql");
        $this->db->query($s_sql);
        return $this->db->affected_rows() == 1;
    }
    
    /**
     * 阶段3时间
     */
    function setStage3Time($i_ba_bat_id){
        $s_time = $this->getDatetime();
        $s_sql = "UPDATE $this->__table_name SET ba_stage3_time='$s_time' "
                . "WHERE ba_bat_id=$i_ba_bat_id ";
        log_message('debug', "SQL文:$s_sql");
        $this->db->query($s_sql);
        return $this->db->affected_rows() == 1;
    }
    
    /**
     * 阶段4时间
     */
    function setStage4Time($i_ba_bat_id){
        $s_time = $this->getDatetime();
        $s_sql = "UPDATE $this->__table_name SET ba_stage4_time='$s_time' "
                . "WHERE ba_bat_id=$i_ba_bat_id ";
        log_message('debug', "SQL文:$s_sql");
        $this->db->query($s_sql);
        return $this->db->affected_rows() == 1;
    }
    
    /**
     * 设置易捷总金额
     * @param type $i_ba_bat_id
     * @param type $d_amount
     * @return type
     */
    function setBalanceYJ($i_ba_bat_id, $d_amount) {
        $s_sql = "UPDATE $this->__table_name SET ba_balance_yj=$d_amount "
                . "WHERE ba_bat_id=$i_ba_bat_id ";
        log_message('debug', "SQL文:$s_sql");
        $this->db->query($s_sql);
        return $this->db->affected_rows() == 1;
    }
    
    /**
     * 删除结算
     * @param type $i_ba_id
     * @param type $i_ba_bat_id
     */
    function doDelBalance($i_ba_bat_id) {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        log_message('debug', "删除结算，批处理号：$i_ba_bat_id");
        $this->db->trans_start();
        //汇总统计
        $s_sql8 = "DELETE FROM balance_account_collect WHERE bac_ba_bat_id=$i_ba_bat_id";
        log_message('debug', "SQL文:$s_sql8");
        $this->db->query($s_sql8);
        $s_sql7 = "DELETE FROM balance_account_shop WHERE bas_ba_bat_id=$i_ba_bat_id";
        log_message('debug', "SQL文:$s_sql7");
        $this->db->query($s_sql7);
        $s_sql6= "DELETE FROM balance_account_goods WHERE bag_ba_bat_id=$i_ba_bat_id";
        log_message('debug', "SQL文:$s_sql6");
        $this->db->query($s_sql6);
        //订单退款详情
        $s_sql5 = "DELETE FROM balance_order_refund_detail WHERE bord_order_id IN "
                . "(SELECT boi_code FROM balance_order_info WHERE boi_ba_bat_id=$i_ba_bat_id)";
        log_message('debug', "SQL文:$s_sql5");
        $this->db->query($s_sql5);
        //订单退款信息
        $s_sql4 = "DELETE FROM balance_order_refund WHERE bor_order_id IN "
                . "(SELECT boi_code FROM balance_order_info WHERE boi_ba_bat_id=$i_ba_bat_id)";
        log_message('debug', "SQL文:$s_sql4");
        $this->db->query($s_sql4);
        //订单详情
        $s_sql3 = "DELETE FROM balance_order_detail WHERE bod_oi_code IN "
                . "(SELECT boi_code FROM balance_order_info WHERE boi_ba_bat_id=$i_ba_bat_id)";
        log_message('debug', "SQL文:$s_sql3");
        $this->db->query($s_sql3);
        //订单信息
        $s_sql2 = "DELETE FROM balance_order_info WHERE boi_ba_bat_id=$i_ba_bat_id ";
        log_message('debug', "SQL文:$s_sql2");
        $this->db->query($s_sql2);
        //结算记录
        $s_sql1 = "DELETE FROM balance_account WHERE ba_bat_id=$i_ba_bat_id ";
        log_message('debug', "SQL文:$s_sql1");
        $this->db->query($s_sql1);
        //原始订单结算标志
        $s_sql0 = "UPDATE order_info SET oi_ba_bat_id=-1 WHERE oi_ba_bat_id=$i_ba_bat_id ";
        log_message('debug', "SQL文:$s_sql0");
        $this->db->query($s_sql0);
        
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

}
