<?php

/**
 * 饿百订单数据核对
 *
 * @author Vincent
 */
class AdEBOrderInfoCheckM extends CI_Model {

    var $__ENUM_STATE_UNKNOW = 'unknow';        //未知
    var $__ENUM_STATE_REFUNDP = 'refund_part';
    var $__TEXT_STATE_REFUNDP = "部分退单";
    
    var $__ENUM_EXP_NULL_SHOP = 'null_shop';
    var $__ENUM_EXP_NULL_STATE = 'null_state';
    var $__ENUM_EXP_UNKNOW_SHOP = 'unknow_shop';
    var $__ENUM_EXP_UNKNOW_STATE = 'unknow_state';
    var $__ENUM_EXP_INVALID_BARCODE = 'invalid_barcode';
    var $__ENUM_EXP_INVALID_BCOUNT = 'invalid_bcount';
    var $__ENUM_EXP_INVALID_SPRICE = 'invalid_sprice';
    
    var $__ENUM_REPAIR_TODO = 'todo';       //待修复
    var $__ENUM_REPAIR_DONE = 'done';       //已修复
    var $__ENUM_REPAIR_NORMAL = 'normal';   //正常

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
    }

    function getTextByStateNum($enum_state) {
        $this->load->model('AdEBOrderInfoM');
        return $this->AdEBOrderInfoM->getTextByStateNum($enum_state);
    }

    /**
     * 根据异常枚举获得对应描述
     * @param type $enum_exp
     * @return string
     */
    function getTextByExpEnum($enum_exp) {
        if ($enum_exp == $this->__ENUM_EXP_NULL_SHOP) {
            return '店铺ID为空';
        }
        if ($enum_exp == $this->__ENUM_EXP_NULL_STATE) {
            return '订单状态为空';
        }
        if ($enum_exp == $this->__ENUM_EXP_UNKNOW_SHOP) {
            return '店铺ID找不到对应站点';
        }
        if ($enum_exp == $this->__ENUM_EXP_UNKNOW_STATE) {
            return '订单状态未知';
        }
        if ($enum_exp == $this->__ENUM_EXP_INVALID_BARCODE) {
            return '条码无效或无对应条码';
        }
        if ($enum_exp == $this->__ENUM_EXP_INVALID_BCOUNT) {
            return '购买数量无效';
        }
        if ($enum_exp == $this->__ENUM_EXP_INVALID_SPRICE) {
            return '结算价格无效';
        }
        return '未知异常';
    }

    /**
     * 核对订单
     * @param type $a_code
     */
    function checkOrder($a_code = '') {
        $s_date = date("Y-m-d H:i:s", time());
        $this->checkOrderRefundPart($a_code);
        $this->checkOrderInfo($s_date, $a_code);
        $this->checkOrderDetail($s_date, $a_code);
        $this->updateRepairToDo($s_date);
        return array(
            'state' => true,
            'msg' => "success"
        );
    }

    /**
     * 核对订单信息
     * @param type $a_code
     */
    function checkOrderInfo($s_date, $a_code = '') {
        $a_exp = array();
        $o_exp_data = array('order_code' => '', 'exp_enum' => '', 'exp_memo' => '');
        $s_sql = "SELECT eoi_code,eoi_shop_id,bs_org_sn,eoi_order_state_enum "
                . "FROM order_info_eb "
                . "LEFT JOIN v_base_shop_info ON bs_e_id=eoi_shop_id ";
        if (is_array($a_code) && count($a_code) > 0) {
            $s_code = "'$a_code[0]'";
            for ($i = 1; i < count($a_code); $i++) {
                $s_code .= ",'$a_code[$i]'";
            }
            $s_sql .= "WHERE eoi_code IN ($s_code) ";
        }
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();

        foreach ($a_line as $o_line) {
            $s_order_code = $o_line->eoi_code;
            $o_exp_data['order_code'] = $s_order_code;
            //店铺 必须 有效
            $s_shop_id = $o_line->eoi_shop_id;
            $s_org_sn = $o_line->bs_org_sn;
            if ($s_shop_id == '') {
                $o_exp_data['exp_enum'] = $this->__ENUM_EXP_NULL_SHOP;
                $o_exp_data['exp_memo'] = $this->getTextByExpEnum($this->__ENUM_EXP_NULL_SHOP);
                array_push($a_exp, $o_exp_data);
            } else if (is_null($s_org_sn)) {
                $o_exp_data['exp_enum'] = $this->__ENUM_EXP_UNKNOW_SHOP;
                $o_exp_data['exp_memo'] = $this->getTextByExpEnum($this->__ENUM_EXP_UNKNOW_SHOP);
                array_push($a_exp, $o_exp_data);
            }
            //订单状态 必须 有效
            $s_state = $o_line->eoi_order_state_enum;
            if ($s_state == $this->__ENUM_STATE_UNKNOW) {
                $o_exp_data['exp_enum'] = $this->__ENUM_EXP_UNKNOW_STATE;
                $o_exp_data['exp_memo'] = $this->getTextByExpEnum($this->__ENUM_EXP_UNKNOW_STATE);
                array_push($a_exp, $o_exp_data);
            }
        }        
        $this->addException($a_exp, $s_date);
    }
    
    /**
     * 设置订单为待修复状态
     * @param type $s_date
     * @return type
     */
    function updateRepairToDo($s_date) {
        log_message('debug', "设置订单为待修复状态");
        $s_sql = "UPDATE order_info_eb SET eoi_repair_enum='$this->__ENUM_REPAIR_TODO' "
                . "WHERE eoi_code IN (SELECT DISTINCT(eoe_eoi_code) e_code "
                . "FROM order_exception_eb WHERE eoe_append_dt = '$s_date' )";
        log_message('debug', "SQL文:$s_sql");
        $this->db->query($s_sql);
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:$i_rows");
        return $i_rows;
    }
    
    function getExceptionList($s_order_code) {
        $s_sql = "SELECT * FROM order_exception_eb WHERE eoe_eoi_code='$s_order_code'";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    /**
     * 新增异常
     * @param type $a_exp
     * @return type
     */
    function addException($a_exp, $s_date) {
        if (!is_array($a_exp) || count($a_exp) < 1) {
            return;
        }
        log_message('debug', "新增订单异常记录");
        $this->db->trans_start();
        foreach ($a_exp as $o_exp_data) {
            $s_order_code = $o_exp_data['order_code'];
            $enum_exp = $o_exp_data['exp_enum'];
            $s_exp_memo = $o_exp_data['exp_memo'];
            $s_sql = "INSERT INTO order_exception_eb (eoe_eoi_code,eoe_exception_enum,"
                    . "eoe_exception_memo,eoe_append_dt) VALUES ('$s_order_code','$enum_exp','$s_exp_memo','$s_date') ";
//            log_message('debug', "SQL文:$s_sql");
            $this->db->query($s_sql);
        }
        return $this->db->trans_complete();
    }

    /**
     * 检查订单详情
     * @param type $a_code
     */
    function checkOrderDetail($s_date, $a_code = '') {
        $a_exp = array();
        $o_exp_data = array('order_code' => '', 'exp_enum' => '', 'exp_memo' => '');
        $s_sql = "SELECT eod_eoi_code,sge_barcode,eod_buy_count,bbp_settlement_price "
                . "FROM order_detail_eb LEFT JOIN shop_goods_eb ON sge_gid=eod_sku_code "
                . "LEFT JOIN base_balance_price ON bbp_bar_code = sge_barcode ";
        if (is_array($a_code) && count($a_code) > 0) {
            $s_code = "'$a_code[0]'";
            for ($i = 1; i < count($a_code); $i++) {
                $s_code .= ",'$a_code[$i]'";
            }
            $s_sql .= "WHERE eod_eoi_code IN ($s_code) ";
        }
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();

        foreach ($a_line as $o_line) {
            $s_ocode = $o_line->eod_eoi_code;
            $s_barcode = $o_line->sge_barcode;
            $i_buy_count = $o_line->eod_buy_count;
            $f_s_price = $o_line->bbp_settlement_price;
            
            $o_exp_data['order_code'] = $s_ocode;
            //条码 必须
            if (is_null($s_barcode) || $s_barcode == '') {
                $o_exp_data['exp_enum'] = $this->__ENUM_EXP_INVALID_BARCODE;
                $o_exp_data['exp_memo'] = $this->getTextByExpEnum($this->__ENUM_EXP_INVALID_BARCODE);
                array_push($a_exp, $o_exp_data);
            }
            //数量 必须 有效
            if (is_null($i_buy_count) || !is_int($i_buy_count-0)) {
                $o_exp_data['exp_enum'] = $this->__ENUM_EXP_INVALID_BCOUNT;
                $o_exp_data['exp_memo'] = $this->getTextByExpEnum($this->__ENUM_EXP_INVALID_BCOUNT);
                array_push($a_exp, $o_exp_data);
            }
            //结算价 有效
            if (is_null($f_s_price) || !is_float($f_s_price-0)) {
                $o_exp_data['exp_enum'] = $this->__ENUM_EXP_INVALID_SPRICE;
                $o_exp_data['exp_memo'] = $this->getTextByExpEnum($this->__ENUM_EXP_INVALID_SPRICE);
                array_push($a_exp, $o_exp_data);
            }
        }
        $this->addException($a_exp, $s_date);
    }

    /**
     * 匹配饿百账单中的部分退款订单
     * @return type
     */
    function checkOrderRefundPart($a_code = '') {
        log_message('debug', "匹配饿百账单中的部分退款订单");
        $s_sql = "UPDATE order_info_eb INNER JOIN bill_info_eb ON eoi_code = bie_order_code "
                . "SET eoi_order_state_enum='$this->__ENUM_STATE_REFUNDP',"
                . "eoi_order_state='$this->__TEXT_STATE_REFUNDP' "
                . "WHERE bie_biz_type='$this->__TEXT_STATE_REFUNDP' ";
        if (is_array($a_code) && count($a_code) > 0) {
            $s_code = "'$a_code[0]'";
            for ($i = 1; i < count($a_code); $i++) {
                $s_code .= ",'$a_code[$i]'";
            }
            $s_sql .= "AND eoi_code IN ($s_code) ";
        }
        log_message('debug', "SQL文:$s_sql");
        $this->db->query($s_sql);
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:$i_rows");
        return $i_rows;
    }

}
