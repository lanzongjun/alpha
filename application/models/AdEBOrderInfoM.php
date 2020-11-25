<?php

/**
 * 饿百订单信息
 *
 * @author Vincent
 */
class AdEBOrderInfoM extends CI_Model {
    
    var $_tbn_order_info = 'order';
    var $_tbn_order_discount = 'order_discount';
    var $_tbn_order_ext = 'order_ext';
    var $_tbn_order_product = 'order_product';
    var $_tbn_order_shop = 'order_shop';
    var $_tbn_order_user = 'order_user';
    var $_tbn_order_cancel = 'order_cancel';
    var $_tbn_order_prefund = 'order_prefund';
    var $_tbn_order_prefund_order_detail = 'order_prefund_order_detail';
    var $_tbn_order_prefund_refund_detail = 'order_prefund_refund_detail';
    var $_tbn_push_shop_msg = 'push_shop_msg';
    
    var $__ENUM_STATE_TODO = 1;//待确认
    var $__ENUM_STATE_CONFIRM = 5;//已确认
    var $__ENUM_STATE_RIDER_RECEIVE = 7;//骑士已接单
    var $__ENUM_STATE_RIDER_TAKE = 8;//骑士已取餐
    var $__ENUM_STATE_COMPLETE = 9;//已完成
    var $__ENUM_STATE_CANCEL = 10;//已取消
    var $__ENUM_STATE_REFUND = 15;//订单退款
    var $__ENUM_STATE_UNKNOW = -1;//未知

    function __construct() {
        parent::__construct();
        $this->db = $this->load->database('ele_if',true);        
        $this->_tbn_order_info = $this->db->dbprefix($this->_tbn_order_info);
        $this->_tbn_order_discount = $this->db->dbprefix($this->_tbn_order_discount);
        $this->_tbn_order_ext = $this->db->dbprefix($this->_tbn_order_ext);
        $this->_tbn_order_product = $this->db->dbprefix($this->_tbn_order_product);
        $this->_tbn_order_shop = $this->db->dbprefix($this->_tbn_order_shop);
        $this->_tbn_order_user = $this->db->dbprefix($this->_tbn_order_user);
        $this->_tbn_order_cancel = $this->db->dbprefix($this->_tbn_order_cancel);
        $this->_tbn_order_prefund = $this->db->dbprefix($this->_tbn_order_prefund);
        $this->_tbn_order_prefund_order_detail = $this->db->dbprefix($this->_tbn_order_prefund_order_detail);
        $this->_tbn_order_prefund_refund_detail = $this->db->dbprefix($this->_tbn_order_prefund_refund_detail);
        $this->_tbn_push_shop_msg = $this->db->dbprefix($this->_tbn_push_shop_msg);
    }
    
    /**
     * 根据编码返回对应订单状态
     * @param type $enum_state
     * @return string
     */
    function getTextByStateNum($enum_state) {
        if ($enum_state == $this->__ENUM_STATE_TODO) {
            return '待确认';
        }
        if ($enum_state == $this->__ENUM_STATE_CONFIRM) {
            return '已确认';
        }
        if ($enum_state == $this->__ENUM_STATE_RIDER_RECEIVE) {
            return '骑士已接单';
        }
        if ($enum_state == $this->__ENUM_STATE_RIDER_TAKE) {
            return '骑士已取餐';
        }
        if ($enum_state == $this->__ENUM_STATE_COMPLETE) {
            return '已完成';
        }
        if ($enum_state == $this->__ENUM_STATE_CANCEL) {
            return '已取消';
        }
        if ($enum_state == $this->__ENUM_STATE_REFUND) {
            return '订单退款';
        }
        if ($enum_state == $this->__ENUM_STATE_UNKNOW) {
            return '未知';
        }
        return '未知';
    }
        
    /**
     * 获得订单信息对象
     * @param type $s_date_begin    订单开始日期
     * @param type $s_date_end      订单结束日期
     * @return string
     */
    function getOrderObj($s_date_begin,$s_date_end){
        $s_sql = "SELECT oi.order_id,os.shop_id,os.name shop_name,"
                . "oi.business_type,oi.business_type_desc,oi.create_time,"
                . "oi.confirm_time,oi.finished_time,oi.cancel_time,oi.package_fee,"
                . "oi.send_fee,oi.total_fee,oi.user_fee,oi.shop_fee,oi.commission,"
                . "oi.order_flag,oi.`status`,oi.status_desc FROM $this->_tbn_order_info oi "
                . "LEFT JOIN $this->_tbn_order_shop os ON oi.order_id=os.order_id "
                . "WHERE oi.create_time >= '$s_date_begin 00:00:00' "
                . "AND oi.create_time <= '$s_date_end 23:59:59' ";
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
        $s_sql = "SELECT order_id,custom_sku_id,upc,product_name,product_amount,"
                . "product_price,product_fee,discount,baidu_rate,shop_rate "
                . "FROM $this->_tbn_order_product WHERE order_id='$s_order_code' ";
        $o_result = $this->db->query($s_sql);
        $a_rows = $o_result->result();
        $a_result = count($a_rows) > 0 ? $a_rows : array();
        return $a_result;
    }
    
    /**
     * 拉取订单退款订单信息
     * @param type $s_date_begin
     * @param type $s_date_end
     * @return type
     */
    function getOrderPartRefund($s_date_begin,$s_date_end){
        $s_sql = "SELECT order_id,refund_order_id,refund_price "
                . "FROM $this->_tbn_order_prefund WHERE order_id IN ("
                . "SELECT oi.order_id FROM $this->_tbn_order_info oi "
                . "WHERE oi.create_time >= '$s_date_begin 00:00:00' "
                . "AND oi.create_time <= '$s_date_end 23:59:59') ";;
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
        $s_sql = "SELECT `order_id`,`refund_id`,`sku_id`,`upc`,`custom_sku_id`,"
                . "`p_name`,`number`,`total_refund`,`shop_ele_refund` "
                . "FROM $this->_tbn_order_prefund_refund_detail "
                . "WHERE order_id='$s_order_code' ";
        $o_result = $this->db->query($s_sql);
        $a_rows = $o_result->result();
        $a_result = count($a_rows) > 0 ? $a_rows : array();
        return $a_result;
    }
    
    function getRefundList() {
        $s_sql = "SELECT order_id,refund_id,type_desc,refund_price,status_desc,"
                . "reason_type_desc,reason,addition_reason ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
//    function getRefundList(){
//        $s_sql = "SELECT R.order_id,R.type,R.total_price,R.user_fee,R.shop_fee,"
//                . "R.send_fee,R.fee,R.commission,R.refund_price,R.package_fee,"
//                . "OI.create_time,OS.name "
//                . "FROM $this->_tbn_order_prefund R "
//                . "LEFT JOIN $this->_tbn_order_info OI ON R.order_id=OI.order_id "
//                . "LEFT JOIN $this->_tbn_order_shop OS ON R.order_id=OS.order_id "
//                . "ORDER BY OI.create_time DESC ";
//        $o_result = $this->db->query($s_sql);
//        return $o_result->result();
//    }
    
    function getRefundDetail($l_order_id){
        $s_sql = "SELECT order_id,refund_id,sku_id,upc,custom_sku_id,p_name,number,"
                . "total_refund,shop_ele_refund,apply_time,type,r_status,r_desc "
                . "FROM $this->_tbn_order_prefund_refund_detail WHERE order_id=$l_order_id ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function getRefundOrderDetail($l_order_id) {
        $s_sql = "SELECT order_id,upc,custom_sku_id,p_name,product_price,number,"
                . "product_fee,discount,baidu_rate,shop_rate "
                . "FROM $this->_tbn_order_prefund_order_detail WHERE order_id=$l_order_id";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    /**
     * 获得列表
     * @return type
     */
    function getToDoList() {
        $s_sql = "SELECT oi.order_id,oi.confirm_type,os.`name` shop_name,oi.user_fee,"
                . "oi.total_fee,oi.shop_fee,ou.`name` user_name,ou.privacy_phone,"
                . "ou.address,oi.delivery_phone,oi.`status`,oi.create_time,oi.remark,"
                . "oi.create_time udiff FROM $this->_tbn_order_info oi "
                . "LEFT JOIN $this->_tbn_order_shop os ON os.order_id = oi.order_id "
                . "LEFT JOIN $this->_tbn_order_user ou ON ou.order_id = oi.order_id "
                . "WHERE oi.`status`=1 ORDER BY oi.create_time DESC ";
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
        $s_sql = "SELECT oi.order_id ck_id,oi.order_id,oi.confirm_type,oi.user_fee,"
                . "oi.total_fee,oi.shop_fee,ou.`name` user_name,ou.privacy_phone,"
                . "ou.address,oi.delivery_phone,oi.`status`,oi.create_time,oi.remark,"
                . "os.`name` shop_name,oi.update_time FROM $this->_tbn_order_info oi "
                . "LEFT JOIN $this->_tbn_order_shop os ON os.order_id = oi.order_id "
                . "LEFT JOIN $this->_tbn_order_user ou ON ou.order_id = oi.order_id "
                . $s_where 
                . " ORDER BY oi.create_time DESC LIMIT $i_start,$i_rows";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }

    function getWhere($a_get){
        $s_where = "WHERE oi.`status`<>1 AND oi.`status`<>2 ";
        if (isset($a_get['s_db']) && $a_get['s_db']){
            $s_where .= " AND oi.create_time >= '".$a_get['s_db']." 00:00:00'";
        }
        if (isset($a_get['s_de']) && $a_get['s_de']){
            $s_where .= " AND oi.create_time <= '".$a_get['s_de']." 23:59:59'";
        }
        if (isset($a_get['s_oi']) && $a_get['s_oi']) {
            $s_where .= " AND oi.order_id='".$a_get['s_oi']."'";
        }
        if (isset($a_get['s_sid']) && $a_get['s_sid']) {
            $s_where .= " AND os.shop_id='".$a_get['s_sid']."'";
        }
        return $s_where;
    }
    
    /**
     * 获得数据总数
     * @param type $s_where
     * @return type
     */
    function _getTotal($s_where='') {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->_tbn_order_info oi "
                . "LEFT JOIN $this->_tbn_order_shop os ON os.order_id = oi.order_id "
                . "$s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num-0;
    }
    
    /**
     * 获得详细信息
     * @return type
     */
    function getDetail($s_order_id) {
        $s_sql = "SELECT product_name,upc,product_price,total_fee,discount,"
                . "product_amount,baidu_rate,shop_rate FROM $this->_tbn_order_product "
                . "WHERE order_id=$s_order_id";
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
        $s_sql = "SELECT order_id FROM $this->_tbn_order_info WHERE `status`=1";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function getUnrefundOrder(){
        $s_sql = "SELECT refund_id,order_id FROM $this->_tbn_order_refund WHERE res_type=0";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
}
