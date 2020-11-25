<?php

/**
 * 饿百部分退款订单信息
 *
 * @author Vincent
 */
class AdEBPartRefundInfoM extends CI_Model {

    var $_tbn_order_info = 'order';
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
        $this->_tbn_order_info = $this->db->dbprefix($this->_tbn_order_info);
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
    
    public function getRefundList($page, $rows)
    {
        $query = $this->db;
        $query->join("{$this->_tbn_order_info} OI", "{$this->_tbn_order_prefund}.order_id = OI.order_id", 'left');
        $query->join("{$this->_tbn_order_shop} OS", "{$this->_tbn_order_prefund}.order_id = OS.order_id", 'left');

        $queryTotal = clone $query;
        $queryList  = clone $query;

        // 获取总数
        $queryTotal->select('count(1) as total');
        $total = $queryTotal->get($this->_tbn_order_prefund)->first_row();
        if (empty($total->total)) {
            return array(
                'total' => 0,
                'rows'  => []
            );
        }

        // 获取分页数据
        $queryList->select("{$this->_tbn_order_prefund}.order_id,{$this->_tbn_order_prefund}.type,{$this->_tbn_order_prefund}.total_price,{$this->_tbn_order_prefund}.user_fee,{$this->_tbn_order_prefund}.shop_fee,{$this->_tbn_order_prefund}.send_fee,{$this->_tbn_order_prefund}.fee,{$this->_tbn_order_prefund}.commission,{$this->_tbn_order_prefund}.refund_price,{$this->_tbn_order_prefund}.package_fee,OI.create_time,OS.name");
        $queryList->order_by('OI.create_time DESC');
        $offset = ($page - 1) * $rows;
        $queryList->limit($rows, $offset);
        $result = $queryList->get($this->_tbn_order_prefund)->result_array();

        foreach ($result as &$item) {
            if ($item['type'] == 1) {
                $typeText = '商户发起部分退款';
            } elseif ($item['type'] == 2) {
                $typeText = '用户发起部分退款';
            } elseif ($item['type'] == 3) {
                $typeText = '客服发起的部分退款';
            } else {
                $typeText = '';
            }
            $item['type_text'] = $typeText;
        }

        return array(
            'total' => intval($total->total),
            'rows'  => $result
        );
    }
    
    function getRefundDetail($l_order_id){


        $query = $this->db;
        $query->where('order_id', $l_order_id);
        $query->select('order_id,refund_id,sku_id,upc,custom_sku_id,p_name,number,total_refund,shop_ele_refund,apply_time,type,r_status,r_desc');
        $rows = $query->get($this->_tbn_order_prefund_refund_detail)->result_array();

        foreach ($rows as &$row) {
            if ($row['r_status'] == '10') {
                $statusText = '商家/用户发起部分退款申请';
            } elseif ($row['r_status'] == '20') {
                $statusText = '部分退款成功';
            } elseif ($row['r_status'] == '30') {
                $statusText = '用户申请仲裁,客服介入';
            } elseif ($row['r_status'] == '40') {
                $statusText = '部分退款失败';
            } elseif ($row['r_status'] == '50') {
                $statusText = '商家拒绝用户发起的部分退款申请';
            } else {
                $statusText = '';
            }
            $row['status_text'] = $statusText;
        }

        return $rows;
    }
    
    function getRefundOrderDetail($l_order_id) {
        $s_sql = "SELECT order_id,upc,custom_sku_id,p_name,product_price,number,"
                . "product_fee,discount,baidu_rate,shop_rate "
                . "FROM $this->_tbn_order_prefund_order_detail WHERE order_id=$l_order_id";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }

}
