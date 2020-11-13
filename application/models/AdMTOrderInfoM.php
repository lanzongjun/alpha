<?php

/**
 * 美团订单信息
 *
 * @author Vincent
 */
class AdMTOrderInfoM extends CI_Model {
    //1-用户已提交订单；2-向商家推送订单；4-商家已确认；8-订单已完成；9-订单已取消。
    var $__ENUM_STATE_SUBMIT = 1;   //用户已提交订单
    var $__ENUM_STATE_PUSH = 2;     //向商家推送订单
    var $__ENUM_STATE_CONFIRM = 4;  //商家已确认
    var $__ENUM_STATE_COMPLETE = 8; //订单已完成
    var $__ENUM_STATE_CANCEL = 9;   //订单已取消
    
    var $_tbn_order_info = 'order_info';
    var $_tbn_order_detail = 'order_detail';
    var $_tbn_order_refund = 'apply_order_refund';
    var $_tbn_order_refund_detail = 'apply_order_refund_detail';
    
    function __construct() {
        parent::__construct();        
        $this->db = $this->load->database('mt_if',true);
        $this->_tbn_order_info = $this->db->dbprefix($this->_tbn_order_info);
        $this->_tbn_order_detail = $this->db->dbprefix($this->_tbn_order_detail);
        $this->_tbn_order_refund = $this->db->dbprefix($this->_tbn_order_refund);
        $this->_tbn_order_refund_detail = $this->db->dbprefix($this->_tbn_order_refund_detail);
    }

    function getTextByStateNum($enum_state) {
        if ($enum_state == $this->__ENUM_STATE_SUBMIT) {
            return '已提交';
        }
        if ($enum_state == $this->__ENUM_STATE_PUSH) {
            return '已推送';
        }
        if ($enum_state == $this->__ENUM_STATE_CONFIRM) {
            return '已确认';
        }
        if ($enum_state == $this->__ENUM_STATE_COMPLETE) {
            return '已完成';
        }
        if ($enum_state == $this->__ENUM_STATE_CANCEL) {
            return '已取消';
        }
        return '未知';
    }
    
     /**
      * 获得订单对象
      * @param type $s_date_begin
      * @param type $s_date_end
      * @return type
      */
    function getOrderObj($s_date_begin, $s_date_end) {
        $s_sql = "SELECT oi.order_id,oi.app_poi_code,oi.wm_poi_name shop_name,"
                . "oi.pick_type,'' pick_type_desc,oi.`status`,status_desc,oi.ctime,"
                . "oi.order_confirm_time,oi.order_completed_time,oi.order_cancel_time,oi.package_bag_money,"
                . "oi.shipping_fee,oi.original_price,oi.total,0.0 shop_fee,0.0 commission,"
                . "orr.refund_type_desc FROM $this->_tbn_order_info oi "
                . "LEFT JOIN i_mt_qd_order_refund orr ON oi.order_id=orr.order_id  "
                . "WHERE oi.ctime >= '$s_date_begin 00:00:00' "
                . "AND oi.ctime <= '$s_date_end 23:59:59' ";
        $o_result = $this->db->query($s_sql);
        $a_rows = $o_result->result();
        if (count($a_rows)>0) {
            for ($i=0; $i<count($a_rows); $i++) {
                $o_row = $a_rows[$i];
                $o_row->a_products = $this->getOrderObjDetail($o_row->order_id);
            }
        }
        $a_result = count($a_rows) > 0 ? $a_rows : array();
        return $a_result;
    }
    
     /**
     * 获得订单详情对象
     * @param type $s_tn_detail
     * @param type $s_order_code
     */
    function getOrderObjDetail($s_order_code) {
        $s_sql = "SELECT order_id,sku_id,upc,food_name,quantity,"
                . "price,price*quantity product_fee,0 discount,0 mt_rate,0 shop_rate "
                . "FROM $this->_tbn_order_detail WHERE order_id='$s_order_code' ";
        $o_result = $this->db->query($s_sql);
        $a_rows = $o_result->result();
        $a_result = count($a_rows) > 0 ? $a_rows : array();
        return $a_result;
    }
    
    /**
     * 获取部分退款订单对象
     * @param type $s_date_begin
     * @param type $s_date_end
     * @return type
     */
    function getOrderPartRefund($s_date_begin, $s_date_end){
        $s_sql = "SELECT `order_id`,`refund_id`,`money` "
                . "FROM $this->_tbn_order_refund WHERE order_id IN ("
                . "SELECT oi.order_id FROM $this->_tbn_order_info oi "
                . "WHERE oi.ctime >= '$s_date_begin 00:00:00' "
                . "AND oi.ctime <= '$s_date_end 23:59:59') "
                . "AND refund_type_desc='PART' ";
        $o_result = $this->db->query($s_sql);
        $a_rows = $o_result->result();
        if (count($a_rows)>0) {
            for ($i=0; $i<count($a_rows); $i++) {
                $o_row = $a_rows[$i];
                $o_row->a_products = $this->getOrderPartRefundDetail($o_row->order_id);
            }
        }
        $a_result = count($a_rows) > 0 ? $a_rows : array();
        return $a_result;
    }
    
    /**
     * 拉取订单退款订单信息详情
     * @param type $s_order_code
     * @return type
     */
    function getOrderPartRefundDetail($s_order_code) {
        $s_sql = "SELECT `order_id`,`refund_id`,`sku_id`,`upc`,`app_food_code`,"
                . "`food_name`,`count`,`refund_price` "
                . "FROM $this->_tbn_order_refund_detail "
                . "WHERE order_id='$s_order_code' ";
        $o_result = $this->db->query($s_sql);
        $a_rows = $o_result->result();
        $a_result = count($a_rows) > 0 ? $a_rows : array();
        return $a_result;
    }
    
    function getRefundList(){
        $s_sql = "SELECT R.order_id,R.notify_type,I.wm_order_id_view,I.wm_poi_name,"
                . "R.refund_type,R.reason,R.money,R.res_type,R.is_appeal "
                . "FROM $this->_tbn_order_refund R LEFT JOIN $this->_tbn_order_info I "
                . "ON R.order_id=I.order_id ORDER BY R.update_datetime DESC, R.res_type ASC";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function getRefundDetail($l_order_id){
        $s_sql = "SELECT order_id,food_name,upc,count,food_price,origin_food_price,refund_price "
                . "FROM $this->_tbn_order_refund_detail WHERE order_id=$l_order_id ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    /**
     * 获得列表
     * @return type
     */
    function getToDoList() {
        $s_sql = "SELECT order_id,wm_order_id_view,day_seq,wm_poi_name,total,"
                . "original_price,caution,recipient_name,recipient_phone,"
                . "recipient_address,shipper_phone,`status`,ctime,utime,utime udiff "
                . "FROM $this->_tbn_order_info WHERE `status`=1 OR `status`=2 "
                . "ORDER BY ctime DESC ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    /**
     * 获得列表
     * @return type
     */
    function getList($i_page, $i_rows, $a_get) {
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getWhere($a_get);
        $s_sql = "SELECT order_id ck_id,order_id,wm_order_id_view,app_poi_code,day_seq,"
                . "wm_poi_name,total,original_price,caution,recipient_name,"
                . "recipient_phone,recipient_address,shipper_phone,`status`,"
                . "ctime,utime,confirm_type "
                . "FROM $this->_tbn_order_info $s_where "
                . "ORDER BY ctime DESC LIMIT $i_start,$i_rows";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }

    function getWhere($a_get){
        $s_where = "WHERE `status`<>1 AND `status`<>2 ";
        if (isset($a_get['s_db']) && $a_get['s_db']){
            $s_where .= " AND ctime >= '".$a_get['s_db']." 00:00:00'";
        }
        if (isset($a_get['s_de']) && $a_get['s_de']){
            $s_where .= " AND ctime <= '".$a_get['s_de']." 23:59:59'";
        }
        if (isset($a_get['s_sid']) && $a_get['s_sid']) {
            $s_where .= " AND app_poi_code='".$a_get['s_sid']."'";
        }
        return $s_where;
    }
    
    /**
     * 获得数据总数
     * @param type $s_where
     * @return type
     */
    function _getTotal($s_where='') {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->_tbn_order_info $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num-0;
    }
    
    /**
     * 获得详细信息
     * @return type
     */
    function getDetail($l_order_id) {
        $s_sql = "SELECT order_id,food_name,upc,price,food_discount,"
                . "quantity,app_food_code FROM $this->_tbn_order_detail "
                . "WHERE order_id=$l_order_id";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function getOrderToDo(){
        $a_unconfirm = $this->getUnconfirmOrder();
        $a_unrefund = $this->getUnrefundOrder();
        $i_result = 0;
        if (count($a_unconfirm) > 0){
            $i_result = 1;
        }
        if (count($a_unrefund) > 0){
            $i_result = 2;
        }
        if (count($a_unconfirm) > 0 && count($a_unrefund) > 0){
            $i_result = 3;
        }
        return $i_result;
    }
    
    function getUnconfirmOrder(){
        $s_sql = "SELECT order_id FROM $this->_tbn_order_info WHERE `status`=1 OR `status`=2";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function getUnrefundOrder(){
        $s_sql = "SELECT refund_id,order_id FROM $this->_tbn_order_refund WHERE res_type=0";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
}
