<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 订单编辑操作
 *
 * @author Vincent
 */
class AdEBOrderInfoOpM extends CI_Model {

    var $__table_name = 'order_info_eb';
    var $__table_salve_name = 'order_detail_eb';

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
    
    function getTextByStateNum($enum_state) {
        if ($enum_state == $this->__ENUM_STATE_FINISH) {
            return '已完结';
        }
        if ($enum_state == $this->__ENUM_STATE_CANCEL) {
            return '已取消(终态)';
        }
        return '未知';
    }
    
    /**
     * 编辑订单信息
     * @param type $a_post
     * @return type
     */
    function editOrderInfo($a_post) {
        log_message('debug', '编辑订单信息');
        $s_order_code = isset($a_post['code']) ? $a_post['code'] : '';
        $s_order_state = isset($a_post['order_state']) ? $a_post['order_state'] : '';
        $s_update_memo = isset($a_post['update_memo']) ? $a_post['update_memo'] : '';
        if ($s_order_state == '') {
            return array('state' => false, 'msg' => "order_state is empty");
        }
        if ($s_order_code == '') {
            return array('state' => false, 'msg' => "order_code is empty");
        }
        if ($s_update_memo == '') {
            return array('state' => false, 'msg' => "update_memo is empty");
        }
        $s_date = date("Y-m-d H:i:s", time());
        $s_state = $this->getTextByStateNum($s_order_state);
        $s_sql = "UPDATE order_info_eb SET eoi_order_state='$s_state',"
                . "eoi_order_state_enum='$s_order_state',eoi_update_date='$s_date',"
                . "eoi_update_memo='$s_update_memo' "
                . "WHERE eoi_code='$s_order_code'";
        log_message('debug', "SQL文:$s_sql");
        $this->db->query($s_sql);
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:$i_rows");
        return array(
            'state' => $i_rows === 1,
            'msg' => "update $i_rows rows"
        );
    }

    /**
     * 删除订单
     * @param type $s_order_code
     * @param type $s_memo
     * @return type
     */
    function delOrderInfo($s_order_code, $s_memo) {
        if (!$s_order_code) {
            return array(
                'state' => false,
                'msg' => "order_code is empty"
            );
        }
        log_message('debug', '删除订单');
        $s_date = date("Y-m-d H:i:s", time());
        $s_sql = "UPDATE order_info_eb SET eoi_update_date='$s_date'"
                . ",eoi_update_memo=concat(IFNULL(eoi_update_memo,''),'$s_memo')"
                . ",eoi_is_delete=1 WHERE eoi_code='$s_order_code' ";
        log_message('debug', "SQL文:$s_sql");
        $this->db->query($s_sql);
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:$i_rows");
        return array(
            'state' => $i_rows == 1,
            'msg' => "update $i_rows rows"
        );
    }

    /**
     * 级联更新订单信息表
     * @param type $s_memo
     * @param type $i_eod_id
     * @param type $s_eoi_id
     * @return type
     */
    function cascadeUpdateOrderInfoMemo($s_memo, $i_eod_id = '', $s_eoi_id = '') {
        if ($i_eod_id == '' && $s_eoi_id == '') {
            return;
        }
        log_message('debug', '级联更新订单信息表');
        $s_date = date("Y-m-d H:i:s", time());
        $s_sql = "UPDATE order_info_eb SET eoi_update_date='$s_date',"
                . "eoi_update_memo=concat(IFNULL(eoi_update_memo,''),'$s_memo') ";
        if ($i_eod_id != '') {
            $s_sql .= "WHERE eoi_code IN (SELECT eod_eoi_code FROM order_detail_eb "
                    . "WHERE eod_id=$i_eod_id ) ";
        } else {
            $s_sql .= "WHERE eoi_code=$s_eoi_id ";
        }
        log_message('debug', "SQL文:$s_sql");
        $this->db->query($s_sql);
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:$i_rows");
        return $i_rows;
    }

    /**
     * 增加订单商品详情
     * @param type $s_eoi_code
     * @param type $s_sku_code
     * @param type $i_buy_count
     * @param type $s_memo
     * @return type
     */
    function addDetail($s_eoi_code, $s_sku_code, $i_buy_count, $s_memo) {
        log_message('debug', '增加订单商品详情');
        $s_date = date("Y-m-d H:i:s", time());
        $s_sql = "INSERT INTO order_detail_eb (eod_eoi_code,eod_goods_name,"
                . "eod_sku_code,eod_onsale_before,eod_buy_count,eod_update_time,"
                . "eod_update_memo) SELECT '$s_eoi_code',sge_gname,sge_gid,"
                . "sge_price,$i_buy_count,'$s_date','$s_memo' "
                . "FROM shop_goods_eb WHERE sge_gid='$s_sku_code'";
        log_message('debug', "SQL文:$s_sql");
        $this->db->query($s_sql);
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:$i_rows");
        $this->cascadeUpdateOrderInfoMemo("[增加详情:$s_memo]", '', $s_eoi_code);
        return array(
            'state' => $i_rows === 1,
            'msg' => "update $i_rows rows"
        );
    }

    /**
     * 编辑订单商品数量
     * @param type $s_code
     * @param type $i_count
     * @param type $s_memo
     * @return type
     */
    function editDetail($s_code, $i_count, $s_memo) {
        log_message('debug', '编辑订单商品数量');
        $s_date = date("Y-m-d H:i:s", time());
        $s_sql = "UPDATE $this->__table_salve_name SET eod_buy_count=$i_count,"
                . "eod_update_memo='" . $this->_encodeUTF8($s_memo)
                . "',eod_update_time='$s_date' WHERE eod_id=$s_code";
        log_message('debug', "SQL文:$s_sql");
        $this->db->query($s_sql);
        $i_rows = $this->db->affected_rows();        
        log_message('debug', "受影响记录数:$i_rows");
        $this->cascadeUpdateOrderInfoMemo("[编辑详情:$s_memo]", $s_code);
        return array(
            'state' => $i_rows === 1,
            'msg' => "update $i_rows rows"
        );
    }

    /**
     * 删除订单商品详情
     * @param type $s_id
     * @param type $s_memo
     * @return type
     */
    function delDetail($s_id, $s_memo) {
        log_message('debug', '删除订单商品详情');
        $s_date = date("Y-m-d H:i:s", time());
        $s_sql = "UPDATE $this->__table_salve_name SET eod_is_delete=1,"
                . "eod_update_time='$s_date',eod_update_memo='"
                . $this->_encodeUTF8($s_memo) . "' WHERE eod_id=$s_id ";
        log_message('debug', "SQL文:$s_sql");
        $this->db->query($s_sql);
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:$i_rows");
        $this->cascadeUpdateOrderInfoMemo("[删除详情:$s_memo]", $s_id);
        return array(
            'state' => $i_rows === 1,
            'msg' => "update $i_rows rows"
        );
    }

}
