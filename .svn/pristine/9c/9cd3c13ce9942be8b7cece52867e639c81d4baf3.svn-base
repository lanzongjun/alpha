<?php

/**
 * AdBalanceStageConfirmM
 * 
 * @author Vincent
 */
class AdBalanceStageConfirmM extends CI_Model {

    var $_tbn_order_info = 'order_info';
    var $_tbn_order_detail = 'order_detail';
    var $_tbn_order_refund = 'order_refund';
    var $_tbn_order_refund_detail = 'order_refund_detail';
    var $_tbn_bal_order_info = 'balance_order_info';
    var $_tbn_bal_order_detail = 'balance_order_detail';
    var $_tbn_bal_order_refund = 'balance_order_refund';
    var $_tbn_bal_order_refund_detail = 'balance_order_refund_detail';

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
    }
    
    /**
     * 确认订单数据
     * @param type $i_ba_bat_id
     * @return string
     */
    function doConfirm($i_ba_bat_id) {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        //删除部分退款
        $s_sql1 = "UPDATE $this->_tbn_bal_order_detail "
                . "INNER JOIN $this->_tbn_bal_order_refund_detail "
                . "ON bod_oi_code=bord_order_id AND bod_barcode=bord_barcode "
                . "SET bod_count = bod_count-bord_count,"
                . "bod_fee = (bod_count-bord_count)*bod_price "
                . "WHERE bod_ba_bat_id = $i_ba_bat_id ";
        log_message('debug', "删除部分退款.SQL文:$s_sql1");
        $this->db->query($s_sql1);
        log_message('debug', "删除部分退款.受影响记录数:" . $this->db->affected_rows());
        
        $this->db->trans_start();
        $s_sql2 = "DELETE FROM $this->_tbn_bal_order_detail "
                . "WHERE bod_ba_bat_id = $i_ba_bat_id AND bod_count<1";
        log_message('debug', "SQL文:$s_sql2");
        $this->db->query($s_sql2);
        //删除逻辑删除
        $s_sql3 = "DELETE FROM $this->_tbn_bal_order_detail "
                . "WHERE bod_ba_bat_id = $i_ba_bat_id "
                . "AND bod_modify_enum='DELETE'";
        log_message('debug', "SQL文:$s_sql3");
        $this->db->query($s_sql3);
        try {
            $b_result = $this->db->trans_complete();
            
            //阶段2完成时间
            $this->load->model('AdBalanceAccountM');
            $this->AdBalanceAccountM->setStage2Time($i_ba_bat_id);
            
            log_message('debug', "确认订单数据.事务执行结果:$b_result");            
            $o_result['state'] = true;
            $o_result['msg'] = '操作成功，执行结果:'.$b_result;
            return $o_result;
        } catch (Exception $e) {
            log_message('error', '确认订单数据-异常中断！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "确认订单数据-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }
    
    /**
     * 获得原始订单信息
     * @return type
     */
    function getOrderList($i_ba_bat_id) {
        $s_sql = "SELECT oi_code,oi_platform,oi_shop_name,oi_shop_id,oi_pick_type,"
                . "oi_pick_type_desc,oi_order_state,oi_order_state_enum,oi_create_date,"
                . "oi_create_time,oi_comfirm_time,oi_complete_date,oi_complete_time,"
                . "oi_cancel_date,oi_cancel_time,oi_package_fee,oi_shipping_fee,"
                . "oi_total_fee,oi_user_fee,oi_shop_fee,oi_commission,oi_ba_bat_id "
                . "FROM $this->_tbn_order_info WHERE oi_ba_bat_id=$i_ba_bat_id "
                . "ORDER BY oi_create_time DESC,oi_shop_id ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }

    /**
     * 获得原始订单详情(包含部分退款)
     * @param type $s_order_id
     * @return type
     */
    function getOrderDetail($s_order_id) {
        $s_sql = "SELECT od_oi_code,od_custom_sku_id,od_barcode,od_name,od_count,"
                . "od_price,od_fee,od_discount_fee,od_platform_rate,od_shop_rate,"
                . "ord_count,ord_total_refund FROM $this->_tbn_order_detail "
                . "LEFT JOIN $this->_tbn_order_refund_detail "
                . "ON od_oi_code=ord_order_id AND od_barcode=ord_barcode "
                . "WHERE od_oi_code='$s_order_id'";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }

    /**
     * 获得指定订单信息
     * @param type $s_order_id
     * @return string
     */
    function getBalOrderInfo($s_order_id) {
        $s_sql = "SELECT boi_code,boi_platform,boi_shop_name,boi_shop_id,"
                . "boi_shop_org_sn,boi_pick_type,boi_pick_type_desc,boi_order_state,"
                . "boi_order_state_enum,boi_create_date,boi_create_time,"
                . "boi_comfirm_time,boi_complete_date,boi_complete_time,"
                . "boi_cancel_date,boi_cancel_time,boi_package_fee,boi_shipping_fee,"
                . "boi_total_fee,boi_user_fee,boi_shop_fee,boi_commission,"
                . "boi_ba_bat_id FROM $this->_tbn_bal_order_info "
                . "WHERE boi_code='$s_order_id' ";
        $o_result = $this->db->query($s_sql);
        $a_list = $o_result->result();
        if (count($a_list) > 0) {
            return $a_list[0];
        } else {
            return '';
        }
    }

    /**
     * 获得待结算订单列表
     * @param type $i_ba_bat_id
     * @return type
     */
    function getBalOrderList($i_ba_bat_id) {
        $s_sql = "SELECT boi_code,boi_platform,boi_shop_name,boi_shop_id,boi_pick_type,"
                . "boi_pick_type_desc,boi_order_state,boi_order_state_enum,boi_create_date,"
                . "boi_create_time,boi_comfirm_time,boi_complete_date,boi_complete_time,"
                . "boi_cancel_date,boi_cancel_time,boi_package_fee,boi_shipping_fee,"
                . "boi_total_fee,boi_user_fee,boi_shop_fee,boi_commission,boi_ba_bat_id "
                . "FROM $this->_tbn_bal_order_info WHERE boi_ba_bat_id=$i_ba_bat_id "
                . "ORDER BY boi_create_time DESC,boi_shop_id ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }

    /**
     * 获得待结算订单详情
     * @param type $s_order_id
     * @return type
     */
    function getBalOrderDetailList($s_order_id) {
        $s_sql = "SELECT bod_oi_code,bod_custom_sku_id,bod_barcode,bod_name,bod_count,"
                . "bod_price,bod_fee,bod_discount_fee,bod_platform_rate,bod_shop_rate,"
                . "bod_modify_enum,bod_modify_memo,bod_id,bord_count,bord_total_refund "
                . "FROM $this->_tbn_bal_order_detail "
                . "LEFT JOIN $this->_tbn_bal_order_refund_detail "
                . "ON bod_oi_code=bord_order_id AND bod_barcode=bord_barcode "
                . "WHERE bod_oi_code='$s_order_id'";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }

    /**
     * 获得订单详情SQL文
     * @param type $a_rows
     * @return string
     */
    function getEBOrderDetailSQL($a_rows) {
        if (count($a_rows) > 0) {
            $s_sql_result = "INSERT INTO $this->_tbn_order_detail (od_oi_code,"
                    . "od_custom_sku_id,od_barcode,od_name,od_count,od_price,od_fee,"
                    . "od_discount_fee,od_platform_rate,od_shop_rate) VALUES ";
            $s_sql_result .= $this->getEBOrderDetailSQLValues($a_rows[0]);
            for ($i = 1; $i < count($a_rows); $i++) {
                $o_row = $a_rows[$i];
                $s_sql_result .= ',' . $this->getEBOrderDetailSQLValues($o_row);
            }
            return $s_sql_result;
        }
        return '';
    }
    
    /**
     * 获得饿百门店商品列表
     * @param type $s_shop_id
     * @return type
     */
    function getShopGoodListEB($s_shop_id, $s_code='') {
        $s_sql = "SELECT sge_barcode id,concat(bgs_code,'-',sge_gname) text "
                . "FROM shop_goods_eb "
                . "INNER JOIN base_goods_yj ON bgs_barcode=sge_barcode "
                . "WHERE sge_bs_org_sn = '$s_shop_id' ";// AND sge_count_new>0";
        if ($s_code != '') {
            $s_sql .= "AND sge_barcode='$s_code' ";
        }
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }

    /**
     * 获得美团门店商品列表
     * @param type $s_shop_id
     * @return type
     */
    function getShopGoodListMT($s_shop_id, $s_code='') {
        $s_sql = "SELECT sgm_barcode id,concat(bgs_code,'-',sgm_gname) text "
                . "FROM shop_goods_mt "
                . "INNER JOIN base_goods_yj ON bgs_barcode=sgm_barcode "
                . "WHERE sgm_bs_org_sn = '$s_shop_id' ";// AND sgm_count_new>0";
        if ($s_code != '') {
            $s_sql .= "AND sgm_barcode='$s_code' ";
        }
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function getGoodsNameByCode($s_code) {
        $s_sql = "SELECT sgm_barcode id,concat(bgs_code,'-',sgm_gname) text "
                . "FROM shop_goods_mt "
                . "LEFT JOIN base_goods_yj ON bgs_barcode=sgm_barcode "
                . "WHERE sgm_bs_org_sn = '$s_shop_id' ";// AND sgm_count_new>0";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    /**
     * 新增订单详情
     * @param type $o_data
     * @return string
     */
    function addOrderDetail($o_data) {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        $i_ba_bat_id = isset($o_data['boi_ba_bat_id']) ? $o_data['boi_ba_bat_id'] : '';
        $s_order_id = isset($o_data['boi_code']) ? $o_data['boi_code'] : '';
        $s_platform = isset($o_data['boi_platform']) ? $o_data['boi_platform'] : '';
        $s_shop_id = isset($o_data['boi_shop_id']) ? $o_data['boi_shop_id'] : '';
        $s_barcode = isset($o_data['bod_barcode']) ? $o_data['bod_barcode'] : '';
        $s_count = isset($o_data['bod_count']) ? $o_data['bod_count'] : '0';
        $s_memo = isset($o_data['bod_modify_memo']) ? $o_data['bod_modify_memo'] : '';
        
        if (is_null($i_ba_bat_id) || $i_ba_bat_id == '') {
            $o_result['state'] = false;
            $o_result['msg'] = '批处理号为空，终止操作';
            return $o_result;
        }
        
        if (is_null($s_order_id) || $s_order_id == '') {
            $o_result['state'] = false;
            $o_result['msg'] = '订单号为空，终止操作';
            return $o_result;
        }
        
        if (is_null($s_barcode) || $s_barcode == '') {
            $o_result['state'] = false;
            $o_result['msg'] = '条形码为空，终止操作';
            return $o_result;
        }
        
        if (is_null($s_shop_id) || $s_shop_id == '') {
            $o_result['state'] = false;
            $o_result['msg'] = '门店ID为空，终止操作';
            return $o_result;
        }
        
        if (is_null($s_platform) || $s_platform == '' || 
                ($s_platform != 'ELE' && $s_platform != 'MT')) {
            $o_result['state'] = false;
            $o_result['msg'] = '平台识别码不合法，终止操作';
            return $o_result;
        }
        
        if (is_null($s_count) || $s_count == '' 
                || !is_numeric($s_count-0)) {
            $o_result['state'] = false;
            $o_result['msg'] = '数量不合法，终止操作';
            return $o_result;
        } else if ($s_count-0 < 1) {
            $o_result['state'] = false;
            $o_result['msg'] = '数量必须为正数，终止操作';
            return $o_result;
        }
        
        $i_count = $s_count - 0;
        
        $s_sql = "INSERT INTO $this->_tbn_bal_order_detail (bod_oi_code,"
                . "bod_custom_sku_id,bod_barcode,bod_name,bod_count,bod_price,"
                . "bod_fee,bod_modify_enum,bod_modify_memo,bod_ba_bat_id,bod_shop_org_sn) ";
        if ($s_platform == 'ELE') {
            $s_sql .= "SELECT '$s_order_id','$s_barcode','$s_barcode',sge_gname,"
                    . "$i_count,sge_price,sge_price*$i_count,'NEW','$s_memo',$i_ba_bat_id,'$s_shop_id' "
                    . "FROM shop_goods_eb WHERE sge_bs_org_sn='$s_shop_id' "
                    . "AND sge_barcode='$s_barcode' ";
            
        } else if ($s_platform == 'MT') {
            $s_sql .= "SELECT '$s_order_id',sgm_gid,'$s_barcode',sgm_gname,"
                    . "$i_count,sgm_price,sgm_price*$i_count,'NEW','$s_memo',$i_ba_bat_id,'$s_shop_id' "
                    . "FROM shop_goods_mt WHERE sgm_bs_org_sn='$s_shop_id' "
                    . "AND sgm_barcode='$s_barcode' ";
        }
        try {
            log_message('debug', "SQL文:$s_sql");
            $this->db->query($s_sql);
            $i_rows = $this->db->affected_rows();
            $o_result['state'] = true;
            $o_result['msg'] = '操作成功，受影响记录数:'.$i_rows;
            return $o_result;
        } catch (Exception $e) {
            $o_result['state'] = false;
            $o_result['msg'] = '操作失败，信息:'.$e->getMessage();
            return $o_result;
        }
    }
    
    /**
     * 编辑订单详情
     * @param type $o_data
     * @return string
     */
    function editOrderDetail($o_data) {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        $s_id = isset($o_data['bod_id']) ? $o_data['bod_id'] : '';
        $s_count = isset($o_data['bod_count']) ? $o_data['bod_count'] : '0';
        $s_memo = isset($o_data['bod_modify_memo']) ? $o_data['bod_modify_memo'] : '';
        
        if (is_null($s_id) || $s_id == '') {
            $o_result['state'] = false;
            $o_result['msg'] = '详情ID为空，终止操作';
            return $o_result;
        }
                
        if (is_null($s_count) || $s_count == '' 
                || !is_numeric($s_count-0)) {
            $o_result['state'] = false;
            $o_result['msg'] = '数量不合法，终止操作';
            return $o_result;
        } else if ($s_count-0 < 1) {
            $o_result['state'] = false;
            $o_result['msg'] = '数量必须为正数，终止操作';
            return $o_result;
        }
        
        $i_count = $s_count - 0;
        
        $s_sql = "UPDATE $this->_tbn_bal_order_detail SET "
                . "bod_modify_enum='EDIT',bod_count=$i_count,"
                . "bod_fee = bod_price*$i_count,bod_modify_memo='$s_memo' "
                . "WHERE bod_id=$s_id";
        
        try {
            log_message('debug', "SQL文:$s_sql");
            $this->db->query($s_sql);
            $i_rows = $this->db->affected_rows();
            $o_result['state'] = true;
            $o_result['msg'] = '操作成功，受影响记录数:'.$i_rows;
            return $o_result;
        } catch (Exception $e) {
            $o_result['state'] = false;
            $o_result['msg'] = '操作失败，信息:'.$e->getMessage();
            return $o_result;
        }
    }
    
    function delOrderDetail($o_data) {
        $s_id = isset($o_data['bod_id']) ? $o_data['bod_id'] : '';
        $s_memo = isset($o_data['bod_modify_memo']) ? $o_data['bod_modify_memo'] : '';
        
        if (is_null($s_id) || $s_id == '') {
            $o_result['state'] = false;
            $o_result['msg'] = '详情ID为空，终止操作';
            return $o_result;
        }
        
        $s_sql = "UPDATE $this->_tbn_bal_order_detail SET "
                . "bod_modify_enum='DELETE',bod_modify_memo='$s_memo' "
                . "WHERE bod_id=$s_id";
        
        try {
            log_message('debug', "SQL文:$s_sql");
            $this->db->query($s_sql);
            $i_rows = $this->db->affected_rows();
            $o_result['state'] = true;
            $o_result['msg'] = '操作成功，受影响记录数:'.$i_rows;
            return $o_result;
        } catch (Exception $e) {
            $o_result['state'] = false;
            $o_result['msg'] = '操作失败，信息:'.$e->getMessage();
            return $o_result;
        }
    }
}
