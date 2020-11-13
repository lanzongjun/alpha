<?php

/**
 * 饿百部分退款订单信息
 *
 * @author Vincent
 */
class AdEBPartRefundInfoM extends CI_Model {
    
    var $_vn_order_refund = 'v_order_refund';
    var $_tbn_order_shop = 'order_shop';
    var $_tbn_order_cancel = 'order_cancel';
    var $_tbn_order_prefund = 'order_prefund';
    var $_tbn_order_prefund_order_detail = 'order_prefund_order_detail';
    var $_tbn_order_prefund_refund_detail = 'order_prefund_refund_detail';
    var $_tbn_order_prefund_apply = 'push_order_prefund';
    var $_tbn_order_prefund_apply_product = 'push_order_prefund_product';
    
    function __construct() {
        parent::__construct();
        $this->db = $this->load->database('ele_if',true);        
        $this->_vn_order_refund = $this->db->dbprefix($this->_vn_order_refund);
        $this->_tbn_order_shop = $this->db->dbprefix($this->_tbn_order_shop);
        $this->_tbn_order_cancel = $this->db->dbprefix($this->_tbn_order_cancel);
        $this->_tbn_order_prefund = $this->db->dbprefix($this->_tbn_order_prefund);
        $this->_tbn_order_prefund_order_detail = $this->db->dbprefix($this->_tbn_order_prefund_order_detail);
        $this->_tbn_order_prefund_refund_detail = $this->db->dbprefix($this->_tbn_order_prefund_refund_detail);
        $this->_tbn_order_prefund_apply = $this->db->dbprefix($this->_tbn_order_prefund_apply);
        $this->_tbn_order_prefund_apply_product = $this->db->dbprefix($this->_tbn_order_prefund_apply_product);
    }
    
    function getApplyRefundList() {
        $s_sql = "SELECT PR.order_id,PR.refund_id,PR.refund_type,PR.refund_price,"
                . "PR.status_desc,PR.reason_type_desc,PR.reason,PR.addition_reason,"
                . "PR.update_dt,PR.`status`,S.name FROM $this->_vn_order_refund PR "
                . "LEFT JOIN $this->_tbn_order_shop S ON PR.order_id=S.order_id "
                . "ORDER BY PR.update_dt DESC ";        
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function getApplyRefundProductList($s_order_id) {
        $s_sql = "SELECT sku_id,upc,custom_sku_id,number,p_name,total_refund,"
                . "shop_ele_refund FROM $this->_tbn_order_prefund_apply_product "
                . "WHERE order_id='$s_order_id'";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function getRefundList(){
        $s_sql = "SELECT R.order_id,R.type,R.total_price,R.user_fee,R.shop_fee,"
                . "R.send_fee,R.fee,R.commission,R.refund_price,R.package_fee,"
                . "OI.create_time,OS.name "
                . "FROM $this->_tbn_order_prefund R "
                . "LEFT JOIN $this->_tbn_order_info OI ON R.order_id=OI.order_id "
                . "LEFT JOIN $this->_tbn_order_shop OS ON R.order_id=OS.order_id "
                . "ORDER BY OI.create_time DESC ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
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

}
