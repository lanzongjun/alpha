<?php

/**
 * AdBalanceStageSelectM
 * 
 * @author Vincent
 */
class AdBalanceStageSelectM extends CI_Model {

    var $_tbn_shop_info = 'base_shop_info';
    var $_tbn_order_info = 'order_info';
    var $_tbn_order_detail = 'order_detail';
    var $_tbn_order_refund = 'order_refund';
    var $_tbn_order_refund_detail = 'order_refund_detail';
    var $_tbn_bal = 'balance_account';
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
     * 获得列表
     * @return type
     */
    function getList($i_page, $i_rows, $a_get) {
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getWhere($a_get);
        $s_sql = "SELECT oi_code ck,oi_code,oi_platform,oi_shop_name,oi_shop_id,"
                . "oi_pick_type,oi_pick_type_desc,oi_order_state,oi_order_state_enum,"
                . "oi_create_date,oi_create_time,oi_comfirm_time,oi_complete_date,"
                . "oi_complete_time,oi_cancel_date,oi_cancel_time,oi_package_fee,"
                . "oi_shipping_fee,oi_total_fee,oi_user_fee,oi_shop_fee,"
                . "oi_commission,oi_ba_bat_id,oi_update_time,oi_balance_time "
                . "FROM $this->_tbn_order_info $s_where "
                . "ORDER BY oi_create_time DESC,oi_shop_id LIMIT $i_start,$i_rows";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }

    /**
     * 获得数据总数
     * @param type $s_where
     * @return type
     */
    function _getTotal($s_where = '') {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->_tbn_order_info $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num - 0;
    }

    function getWhere($a_get) {
        $s_where = "WHERE 1=1 ";
        if (isset($a_get['db']) && $a_get['db']) {
            $s_where .= " AND oi_create_date >= '" . $a_get['db'] . "'";
        }
        if (isset($a_get['de']) && $a_get['de']) {
            $s_where .= " AND oi_create_date <= '" . $a_get['de'] . "'";
        }
        if (isset($a_get['f']) && $a_get['f'] && $a_get['f'] != 'ALL') {
            $s_where .= " AND oi_platform='" . $a_get['f'] . "'";
        }
        if (isset($a_get['bs']) && $a_get['bs'] && $a_get['bs'] != 'ALL') {
            if ($a_get['bs'] == 'TODO') {
                $s_where .= " AND oi_ba_bat_id = -1";
            } else if ($a_get['bs'] == 'DONE') {
                $s_where .= " AND app_poi_code <> -1";
            }
        }
        return $s_where;
    }

    function getEBStatusE($i_status, $b_part_refund = false) {
        //判断的顺序不可修改
        if ($i_status == 5) {
            return 'CONFIRM';
        }
        if ($i_status == 10) {
            return 'CANCEL';
        }
        if ($b_part_refund) {
            return 'PART_REFUND';
        }
        if ($i_status == 9) {
            return 'COMPLETE';
        }
        if ($i_status == 7 || $i_status == 8 || $i_status == 15 || $i_status == 1) {
            return 'OTHER';
        }
        return 'UNKNOW';
    }

    function getMTStatusE($i_status, $b_part_refund = false) {
        //判断的顺序不可修改
        if ($i_status == 4) {
            return 'CONFIRM';
        }
        if ($i_status == 9) {
            return 'CANCEL';
        }
        if ($b_part_refund) {
            return 'PART_REFUND';
        }
        if ($i_status == 8) {
            return 'COMPLETE';
        }
        if ($i_status == 1 || $i_status == 2) {
            return 'OTHER';
        }
        return 'UNKNOW';
    }

    /**
     * 拉取美团订单信息
     * @param type $s_date_begin
     * @param type $s_date_end
     * @return type
     */
    function pullMTOrder($s_date_begin, $s_date_end) {
        $i_success = 0;
        $i_fail = 0;
        $this->load->model('AdMTOrderInfoM');
        $a_info = $this->AdMTOrderInfoM->getOrderObj($s_date_begin, $s_date_end);
        $this->db->trans_start();
        $this->clearOrder($s_date_begin, $s_date_end, 'MT');
        foreach ($a_info as $o_info) {
            $s_sql_info = $this->getMTOrderInfoSQL($o_info);
            log_message('debug', "SQL文:$s_sql_info");
            $this->db->query($s_sql_info);
            $i_rows1 = $this->db->affected_rows();
            log_message('debug', "受影响记录数:$i_rows1");
            if ($i_rows1 != 1) {
                $i_fail++;
                log_message('debug', "订单已存在，放弃新增详情");
                continue;
            }
            $i_success++;
            $s_sql_detail = $this->getMTOrderDetailSQL($o_info->a_products);
            log_message('debug', "SQL文:$s_sql_detail");
            $this->db->query($s_sql_detail);
            $i_rows2 = $this->db->affected_rows();
            log_message('debug', "受影响记录数:$i_rows2");
        }
        $s_sql_update = "UPDATE $this->_tbn_order_info LEFT JOIN base_shop_info "
                . "ON bs_m_id=oi_shop_id SET oi_shop_org_sn=bs_org_sn"
                . ",oi_shop_name=bs_shop_name WHERE oi_create_date >= '$s_date_begin' "
                . "AND oi_create_date <= '$s_date_end' AND oi_platform='MT' ";
        log_message('debug', "SQL文:$s_sql_update");
        $this->db->query($s_sql_update);
        $b_result = $this->db->trans_complete();
        $o_result = array(
            'result' => $b_result,
            'suc' => $i_success,
            'fail' => $i_fail
        );
        return $o_result;
    }

    /**
     * 清理订单信息
     * @param type $s_date_begin
     * @param type $s_date_end
     * @param type $s_platform
     */
    function clearOrder($s_date_begin, $s_date_end, $s_platform) {
        $s_sql_detail = "DELETE FROM $this->_tbn_order_detail WHERE od_oi_code IN "
                . "(SELECT oi_code FROM $this->_tbn_order_info WHERE oi_create_date >= '$s_date_begin' "
                . "AND oi_create_date <= '$s_date_end' AND oi_platform='$s_platform')";
        log_message('debug', "SQL文:$s_sql_detail");
        $this->db->query($s_sql_detail);
        $s_sql_info = "DELETE FROM $this->_tbn_order_info WHERE oi_create_date >= '$s_date_begin' "
                . "AND oi_create_date <= '$s_date_end' AND oi_platform='$s_platform' ";
        log_message('debug', "SQL文:$s_sql_info");
        $this->db->query($s_sql_info);
    }

    /**
     * 清理订单退款
     * @param type $s_date_begin
     * @param type $s_date_end
     * @param type $s_platform
     */
    function clearOrderRefund($s_date_begin, $s_date_end, $s_platform) {
        $s_sql_detail = "DELETE FROM $this->_tbn_order_refund_detail WHERE ord_order_id IN "
                . "(SELECT oi_code FROM $this->_tbn_order_info WHERE oi_create_date >= '$s_date_begin' "
                . "AND oi_create_date <= '$s_date_end' AND oi_platform='$s_platform')";
        log_message('debug', "SQL文:$s_sql_detail");
        $this->db->query($s_sql_detail);
        $s_sql_info = "DELETE FROM $this->_tbn_order_refund WHERE or_order_id IN "
                . "(SELECT oi_code FROM $this->_tbn_order_info WHERE oi_create_date >= '$s_date_begin' "
                . "AND oi_create_date <= '$s_date_end' AND oi_platform='$s_platform')";
        log_message('debug', "SQL文:$s_sql_info");
        $this->db->query($s_sql_info);
    }

    /**
     * 获得订单信息SQL值
     * @param type $o_row
     * @return string
     */
    function getMTOrderInfoSQL($o_row) {
        $i_business_type = $o_row->pick_type - 0;
        $b_part_refund = !is_null($o_row->refund_type_desc);
        $e_status = $this->getMTStatusE($o_row->status, $b_part_refund);
        $s_result = "INSERT INTO $this->_tbn_order_info (oi_code,oi_platform,oi_shop_name,"
                . "oi_shop_id,oi_pick_type,oi_pick_type_desc,oi_order_state,"
                . "oi_order_state_enum,oi_create_date,oi_create_time,oi_comfirm_time,"
                . "oi_complete_date,oi_complete_time,oi_cancel_date,oi_cancel_time,"
                . "oi_package_fee,oi_shipping_fee,oi_total_fee,oi_user_fee,oi_shop_fee,"
                . "oi_commission) SELECT '$o_row->order_id','MT','$o_row->shop_name',"
                . "'$o_row->app_poi_code',$i_business_type,'$o_row->pick_type_desc',"
                . "'$o_row->status_desc','$e_status','$o_row->ctime',"
                . "'$o_row->ctime','$o_row->order_confirm_time','$o_row->order_completed_time',"
                . "'$o_row->order_completed_time','$o_row->order_cancel_time','$o_row->order_cancel_time',"
                . "$o_row->package_bag_money,$o_row->shipping_fee,$o_row->original_price,"
                . "$o_row->total,$o_row->shop_fee,$o_row->commission "
                . "FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM ORDER_INFO WHERE oi_code='$o_row->order_id') ";
        return $s_result;
    }

    /**
     * 获得订单详情SQL文
     * @param type $s_tn_detail
     * @param type $s_order_code
     */
    function getMTOrderDetailSQL($a_rows) {
        if (count($a_rows) > 0) {
            $s_sql_result = "INSERT INTO $this->_tbn_order_detail (od_oi_code,"
                    . "od_custom_sku_id,od_barcode,od_name,od_count,od_price,od_fee,"
                    . "od_discount_fee,od_platform_rate,od_shop_rate) VALUES ";
            $s_sql_result .= $this->getMTOrderDetailSQLValues($a_rows[0]);
            for ($i = 1; $i < count($a_rows); $i++) {
                $o_row = $a_rows[$i];
                $s_sql_result .= ',' . $this->getMTOrderDetailSQLValues($o_row);
            }
            return $s_sql_result;
        }
        return '';
    }

    /**
     * 获得订单详情值SQL文
     * @param type $o_row
     */
    function getMTOrderDetailSQLValues($o_row) {
        $s_sql = "('$o_row->order_id','$o_row->sku_id','$o_row->upc',"
                . "'$o_row->food_name',$o_row->quantity,$o_row->price,"
                . "$o_row->product_fee,$o_row->discount,$o_row->mt_rate,"
                . "$o_row->shop_rate)";
        return $s_sql;
    }

    /**
     * 拉取饿百订单
     * @param type $s_date_begin
     * @param type $s_date_end
     * @return int
     */
    function pullEBOrder($s_date_begin, $s_date_end) {
        $i_success = 0;
        $i_fail = 0;
        $this->load->model('AdEBOrderInfoM');
        $a_info = $this->AdEBOrderInfoM->getOrderObj($s_date_begin, $s_date_end);
        $this->db->trans_start();
        $this->clearOrder($s_date_begin, $s_date_end, 'ELE');
        foreach ($a_info as $o_info) {
            $s_sql_info = $this->getEBOrderInfoSQL($o_info);
            log_message('debug', "SQL文:$s_sql_info");
            $this->db->query($s_sql_info);
            $i_rows1 = $this->db->affected_rows();
            log_message('debug', "受影响记录数:$i_rows1");
            if ($i_rows1 != 1) {
                $i_fail++;
                log_message('debug', "订单已存在，放弃新增详情");
                continue;
            }
            $i_success++;
            $s_sql_detail = $this->getEBOrderDetailSQL($o_info->a_products);
            log_message('debug', "SQL文:$s_sql_detail");
            $this->db->query($s_sql_detail);
            $i_rows2 = $this->db->affected_rows();
            log_message('debug', "受影响记录数:$i_rows2");
        }
        $s_sql_update = "UPDATE $this->_tbn_order_info LEFT JOIN base_shop_info "
                . "ON bs_e_id=oi_shop_id SET oi_shop_org_sn=bs_org_sn"
                . ",oi_shop_name=bs_shop_name WHERE oi_create_date >= '$s_date_begin' "
                . "AND oi_create_date <= '$s_date_end' AND oi_platform='ELE' ";
        log_message('debug', "SQL文:$s_sql_update");
        $this->db->query($s_sql_update);
        $b_result = $this->db->trans_complete();
        $o_result = array(
            'result' => $b_result,
            'suc' => $i_success,
            'fail' => $i_fail
        );
        return $o_result;
    }

    /**
     * 获得订单信息SQL值
     * @param type $o_row
     * @return string
     */
    function getEBOrderInfoSQL($o_row) {
        $i_business_type = $o_row->business_type - 0;
        $b_part_refund = $o_row->order_flag - 0 == 1;
        $e_status = $this->getEBStatusE($o_row->status, $b_part_refund);
        $s_result = "INSERT INTO $this->_tbn_order_info (oi_code,oi_platform,oi_shop_name,"
                . "oi_shop_id,oi_pick_type,oi_pick_type_desc,oi_order_state,"
                . "oi_order_state_enum,oi_create_date,oi_create_time,oi_comfirm_time,"
                . "oi_complete_date,oi_complete_time,oi_cancel_date,oi_cancel_time,"
                . "oi_package_fee,oi_shipping_fee,oi_total_fee,oi_user_fee,oi_shop_fee,"
                . "oi_commission) SELECT '$o_row->order_id','ELE','$o_row->shop_name',"
                . "'$o_row->shop_id',$i_business_type,'$o_row->business_type_desc',"
                . "'$o_row->status_desc','$e_status','$o_row->create_time',"
                . "'$o_row->create_time','$o_row->confirm_time','$o_row->finished_time',"
                . "'$o_row->finished_time','$o_row->cancel_time','$o_row->cancel_time',"
                . "$o_row->package_fee,$o_row->send_fee,$o_row->total_fee,"
                . "$o_row->user_fee,$o_row->shop_fee,$o_row->commission "
                . "FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM ORDER_INFO WHERE oi_code='$o_row->order_id') ";
        return $s_result;
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
     * 获得订单详情值SQL文
     * @param type $o_row
     */
    function getEBOrderDetailSQLValues($o_row) {
        $s_sql = "('$o_row->order_id','$o_row->custom_sku_id','$o_row->upc',"
                . "'$o_row->product_name',$o_row->product_amount,$o_row->product_price,"
                . "$o_row->product_fee,$o_row->discount,$o_row->baidu_rate,"
                . "$o_row->shop_rate)";
        return $s_sql;
    }

    /**
     * 拉取饿百订单退款信息
     * @param type $s_date_begin
     * @param type $s_date_end
     * @return int
     */
    function pullEBOrderRefund($s_date_begin, $s_date_end) {
        $i_success = 0;
        $i_fail = 0;
        $this->load->model('AdEBOrderInfoM');
        $a_info = $this->AdEBOrderInfoM->getOrderPartRefund($s_date_begin, $s_date_end);
        $this->db->trans_start();
        $this->clearOrderRefund($s_date_begin, $s_date_end, 'ELE');
        foreach ($a_info as $o_info) {
            $s_sql_info = $this->getEBOrderPartRefundSQL($o_info);
            log_message('debug', "SQL文:$s_sql_info");
            $this->db->query($s_sql_info);
            $i_rows1 = $this->db->affected_rows();
            log_message('debug', "受影响记录数:$i_rows1");
            if ($i_rows1 != 1) {
                $i_fail++;
                log_message('debug', "部分退款信息已存在，放弃新增详情");
                continue;
            }
            $i_success++;
            if (is_array($o_info->a_products) && count($o_info->a_products)>0) {
                $s_sql_detail = $this->getEBOrderPartRefundDetailSQL($o_info->a_products);
                log_message('debug', "SQL文:$s_sql_detail");
                $this->db->query($s_sql_detail);
                $i_rows2 = $this->db->affected_rows();
                log_message('debug', "受影响记录数:$i_rows2");
            }
        }
        $b_result = $this->db->trans_complete();
        $o_result = array(
            'result' => $b_result,
            'suc' => $i_success,
            'fail' => $i_fail
        );
        return $o_result;
    }

    /**
     * 获得增加饿百部分退款退款订单SQL文
     * @param type $o_row
     * @return string
     */
    function getEBOrderPartRefundSQL($o_row) {
        $s_result = "INSERT INTO $this->_tbn_order_refund (or_refund_id,or_order_id,"
                . "or_platform,or_refund_money,or_refund_type) "
                . "SELECT '$o_row->refund_order_id','$o_row->order_id','ELE',"
                . "$o_row->refund_price,'PART' "
                . "FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM $this->_tbn_order_refund "
                . "WHERE or_refund_id='$o_row->refund_order_id') ";
        return $s_result;
    }

    /**
     * 获得订单部分退款详情SQL文
     * @param type $a_rows
     * @return string
     */
    function getEBOrderPartRefundDetailSQL($a_rows) {
        if (count($a_rows) > 0) {
            $s_sql_result = "INSERT INTO $this->_tbn_order_refund_detail ("
                    . "ord_order_id,ord_refund_id,ord_platform,ord_sku_id,"
                    . "ord_barcode,ord_custom_sku_id,ord_p_name,ord_count,"
                    . "ord_total_refund) VALUES ";
            $s_sql_result .= $this->getEBOrderPartRefundDetailSQLValues($a_rows[0]);
            for ($i = 1; $i < count($a_rows); $i++) {
                $o_row = $a_rows[$i];
                $s_sql_result .= ',' . $this->getEBOrderPartRefundDetailSQLValues($o_row);
            }
            return $s_sql_result;
        }
        return '';
    }

    /**
     * 获得订单部分退款详情值SQL文
     * @param type $o_row
     * @return string
     */
    function getEBOrderPartRefundDetailSQLValues($o_row) {
        $i_total_refund = $o_row->total_refund + $o_row->shop_ele_refund;
        $s_sql = "('$o_row->order_id','$o_row->refund_id','ELE',"
                . "'$o_row->sku_id','$o_row->upc','$o_row->custom_sku_id',"
                . "'$o_row->p_name',$o_row->number,$i_total_refund)";
        return $s_sql;
    }

    /**
     * 拉取美团退款订单信息
     * @param type $s_date_begin
     * @param type $s_date_end
     * @return int
     */
    function pullMTOrderRefund($s_date_begin, $s_date_end) {
        $i_success = 0;
        $i_fail = 0;
        $this->load->model('AdMTOrderInfoM');
        $a_info = $this->AdMTOrderInfoM->getOrderPartRefund($s_date_begin, $s_date_end);
        $this->db->trans_start();
        $this->clearOrderRefund($s_date_begin, $s_date_end, 'MT');
        foreach ($a_info as $o_info) {
            $s_sql_info = $this->getMTOrderPartRefundSQL($o_info);
            log_message('debug', "SQL文:$s_sql_info");
            $this->db->query($s_sql_info);
            $i_rows1 = $this->db->affected_rows();
            log_message('debug', "受影响记录数:$i_rows1");
            if ($i_rows1 != 1) {
                $i_fail++;
                log_message('debug', "订单已存在，放弃新增详情");
                continue;
            }
            $i_success++;
            $s_sql_detail = $this->getMTOrderPartRefundDetailSQL($o_info->a_products);
            log_message('debug', "SQL文:$s_sql_detail");
            $this->db->query($s_sql_detail);
            $i_rows2 = $this->db->affected_rows();
            log_message('debug', "受影响记录数:$i_rows2");
        }
        $b_result = $this->db->trans_complete();
        $o_result = array(
            'result' => $b_result,
            'suc' => $i_success,
            'fail' => $i_fail
        );
        return $o_result;
    }

    /**
     * 获得订单部分退款信息SQL文
     * @param type $o_row
     * @return string
     */
    function getMTOrderPartRefundSQL($o_row) {
        $s_result = "INSERT INTO $this->_tbn_order_refund (or_refund_id,or_order_id,"
                . "or_platform,or_refund_money,or_refund_type) "
                . "SELECT '$o_row->refund_id','$o_row->order_id','MT',"
                . "$o_row->money,'PART' "
                . "FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM $this->_tbn_order_refund "
                . "WHERE or_refund_id='$o_row->refund_id') ";
        return $s_result;
    }

    /**
     * 获得订单部分退款详情SQL文
     * @param type $a_rows
     * @return string
     */
    function getMTOrderPartRefundDetailSQL($a_rows) {
        if (count($a_rows) > 0) {
            $s_sql_result = "INSERT INTO $this->_tbn_order_refund_detail ("
                    . "ord_order_id,ord_refund_id,ord_platform,ord_sku_id,"
                    . "ord_barcode,ord_custom_sku_id,ord_p_name,ord_count,"
                    . "ord_total_refund) VALUES ";
            $s_sql_result .= $this->getMTOrderPartRefundDetailSQLValues($a_rows[0]);
            for ($i = 1; $i < count($a_rows); $i++) {
                $o_row = $a_rows[$i];
                $s_sql_result .= ',' . $this->getMTOrderPartRefundDetailSQLValues($o_row);
            }
            return $s_sql_result;
        }
        return '';
    }

    /**
     * 获得订单部分退款详情值SQL文
     * @param type $o_row
     * @return string
     */
    function getMTOrderPartRefundDetailSQLValues($o_row) {
        $s_sql = "('$o_row->order_id','$o_row->refund_id','MT',"
                . "'$o_row->sku_id','$o_row->upc','$o_row->app_food_code',"
                . "'$o_row->food_name',$o_row->count,$o_row->refund_price)";
        return $s_sql;
    }

    function aCode2Codes($a_order_codes) {
        $s_codes = "'$a_order_codes[0]'";
        for ($i = 1; $i < count($a_order_codes); $i++) {
            $s_codes .= ",'$a_order_codes[$i]'";
        }
        return $s_codes;
    }

    /**
     * 选定
     * @param type $s_date_begin
     * @param type $s_date_end
     * @param type $a_order_codes
     * @return string
     */
    function doSelected($s_date_begin, $s_date_end, $a_order_codes, $i_bat_id='') {
        set_time_limit(0);
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        
        $this->load->model('AdBalanceAccountM');
            
        //如果是针对已有的结算记录进行修改
        if (is_numeric($i_bat_id)){
            $i_ba_bat_id = $i_bat_id-0;
            log_message('debug', "已有批处理流水号:$i_ba_bat_id");
            //删除之前的已有结算信息，重新开始结算
            $this->AdBalanceAccountM->doDelBalance($i_ba_bat_id);
        } else {
            //生成批处理流水号
            $i_ba_bat_id = time();
            log_message('debug', "生成批处理流水号:$i_ba_bat_id");
        }
        
        if ($this->isOrdersBalanced($a_order_codes)) {
            $o_result['state'] = false;
            $o_result['msg'] = '存在已结算订单，请重新选择';
            return $o_result;
        }
        
        try {
            $b_result = $this->appendBalanceOrder($a_order_codes, $i_ba_bat_id);
            if (!$b_result) {
                log_message('error', '添加待结算订单失败，停止结算');
                $o_result['state'] = false;
                $o_result['msg'] = '添加待结算订单失败，停止结算';
                return $o_result;
            }
        } catch (Exception $e) {
            log_message('error', '添加待结算订单-异常中断！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "添加待结算订单-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
        
        try {
            $i_ba_id = $this->newBalance($s_date_begin, $s_date_end, $i_ba_bat_id);
            if ($i_ba_id == 0) {
                log_message('error', '新增结算记录失败，停止结算');
                $o_result['state'] = false;
                $o_result['msg'] = '新增结算记录失败，停止结算';
                return $o_result;
            }
        } catch (Exception $e) {
            log_message('error', '新增结算记录-异常中断！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "新增结算记录-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
        
        //阶段1完成时间
        $this->AdBalanceAccountM->setStage1Time($i_ba_bat_id);
        
        $o_result['state'] = $b_result;
        $o_result['msg'] = $b_result ? "成功" : "失败";
        $o_result['bat_id'] = $i_ba_bat_id;
        return $o_result;
    }
    
    /**
     * 是否存在已结算订单
     * @param type $a_order_codes
     * @return type
     */
    function isOrdersBalanced($a_order_codes) {
        $s_codes = $this->aCode2Codes($a_order_codes);
        $s_sql = "SELECT COUNT(1) t_num FROM $this->_tbn_bal_order_info "
                . "WHERE boi_code IN ($s_codes)";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num - 0 > 0;
        
    }
    
    /**
     * 追加至待结算订单
     * @param type $a_order_codes
     * @param type $i_ba_bat_id
     */
    function appendBalanceOrder($a_order_codes, $i_ba_bat_id) {
        $s_codes = $this->aCode2Codes($a_order_codes);
        $this->db->trans_start();
        //订单信息
        $s_sql_info = "INSERT INTO $this->_tbn_bal_order_info (boi_code,boi_platform,"
                . "boi_shop_name,boi_shop_id,boi_shop_org_sn,boi_pick_type,boi_pick_type_desc,"
                . "boi_order_state,boi_order_state_enum,boi_create_date,boi_create_time,"
                . "boi_comfirm_time,boi_complete_date,boi_complete_time,boi_cancel_date,"
                . "boi_cancel_time,boi_package_fee,boi_shipping_fee,boi_total_fee,"
                . "boi_user_fee,boi_shop_fee,boi_commission,boi_ba_bat_id) "
                . "SELECT oi_code,oi_platform,oi_shop_name,oi_shop_id,oi_shop_org_sn,oi_pick_type,"
                . "oi_pick_type_desc,oi_order_state,oi_order_state_enum,oi_create_date,"
                . "oi_create_time,oi_comfirm_time,oi_complete_date,oi_complete_time,"
                . "oi_cancel_date,oi_cancel_time,oi_package_fee,oi_shipping_fee,"
                . "oi_total_fee,oi_user_fee,oi_shop_fee,oi_commission,$i_ba_bat_id "
                . "FROM $this->_tbn_order_info WHERE oi_code IN ($s_codes)";
        log_message('debug', "SQL文:$s_sql_info");
        $this->db->query($s_sql_info);
        
        //订单详情
        $s_sql_detail = "INSERT INTO $this->_tbn_bal_order_detail (bod_oi_code,"
                . "bod_custom_sku_id,bod_barcode,bod_name,bod_count,bod_price,"
                . "bod_fee,bod_discount_fee,bod_platform_rate,bod_shop_rate,"
                . "bod_shop_id,bod_shop_org_sn,bod_ba_bat_id) "
                . "SELECT od_oi_code,od_custom_sku_id,od_barcode,od_name,od_count,"
                . "od_price,od_fee,od_discount_fee,od_platform_rate,od_shop_rate,"
                . "oi_shop_id,oi_shop_org_sn,$i_ba_bat_id "
                . "FROM $this->_tbn_order_detail LEFT JOIN $this->_tbn_order_info "
                . "ON od_oi_code=oi_code WHERE od_oi_code IN ($s_codes)";
        log_message('debug', "SQL文:$s_sql_detail");
        $this->db->query($s_sql_detail);
        
        //订单退款
        $s_sql_refund = "INSERT INTO $this->_tbn_bal_order_refund (bor_refund_id,"
                . "bor_order_id,bor_platform,bor_refund_money,bor_refund_type,bor_ba_bat_id) "
                . "SELECT or_refund_id,or_order_id,or_platform,or_refund_money,or_refund_type,$i_ba_bat_id "
                . "FROM $this->_tbn_order_refund WHERE or_order_id IN ($s_codes)";
        log_message('debug', "SQL文:$s_sql_refund");
        $this->db->query($s_sql_refund);
        
        //订单退款详情
        $s_sql_refund_detail = "INSERT INTO $this->_tbn_bal_order_refund_detail "
                . "(bord_order_id,bord_refund_id,bord_platform,bord_sku_id,bord_barcode,"
                . "bord_custom_sku_id,bord_p_name,bord_count,bord_total_refund,bord_ba_bat_id) "
                . "SELECT ord_order_id,ord_refund_id,ord_platform,ord_sku_id,ord_barcode,"
                . "ord_custom_sku_id,ord_p_name,ord_count,ord_total_refund,$i_ba_bat_id "
                . "FROM $this->_tbn_order_refund_detail WHERE ord_order_id IN ($s_codes)";
        log_message('debug', "SQL文:$s_sql_refund_detail");
        $this->db->query($s_sql_refund_detail);
        
        //更新原始订单批处理流水号
        $s_sql_order = "UPDATE $this->_tbn_order_info SET oi_ba_bat_id=$i_ba_bat_id "
                . "WHERE oi_code IN ($s_codes)";
        log_message('debug', "SQL文:$s_sql_order");
        $this->db->query($s_sql_order);
        
        try {
            $b_result = $this->db->trans_complete();
            log_message('debug', "事务执行结果:$b_result");
            return $b_result;
        } catch (Exception $e) {
            log_message('error', '添加待结算订单-异常中断！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "添加待结算订单-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }

    /**
     * 更新订单原始信息结算ID
     */
    function updateOrderBalID($i_ba_bat_id, $s_codes) {
        $s_sql = "UPDATE $this->_tbn_order_info SET oi_ba_bat_id=$i_ba_bat_id "
                . "WHERE oi_code IN ($s_codes)";
        log_message('debug', "SQL文:$s_sql");
        $this->db->query($s_sql);
        return $this->db->affected_rows();
    }

    /**
     * 新增结算
     * @return type 总结算ID
     */
    function newBalance($s_date_begin, $s_date_end, $i_ba_bat_id) {
        $s_sql = "INSERT INTO $this->_tbn_bal (ba_balance_date_begin,"
                . "ba_balance_date_end,ba_bat_id) "
                . "VALUES ('$s_date_begin','$s_date_end',$i_ba_bat_id)";
        log_message('debug', "SQL文:$s_sql");
        try {
            $this->db->query($s_sql);
        } catch (Exception $ex) {
            throw $ex;
        }
        log_message('debug', "受影响记录数:" . $this->db->affected_rows());
        return $this->db->insert_id();
    }
    
    /**
     * 根据批处理流水号获得结算记录
     * @param type $i_ba_bat_id
     * @return type
     */
    function getBalance($i_ba_bat_id) {
        $s_sql = "SELECT ba_balance_date_begin,ba_balance_date_end "
                . "FROM $this->_tbn_bal WHERE ba_bat_id=$i_ba_bat_id";
        $o_datalist = $this->db->query($s_sql);
        $a_line = $o_datalist->result();
        if (null != $a_line || count($a_line) > 0){
            return $a_line[0];
        } else {
            return null;
        }
    }
    
    /**
     * 获得已选定的订单记录
     * @param type $i_ba_bat_id
     * @return type
     */
    function getSelectedOrders($i_ba_bat_id) {
        $s_sql = "SELECT boi_code,boi_platform,boi_create_date,boi_create_time,"
                . "boi_shop_name,boi_total_fee,boi_order_state_enum "
                . "FROM $this->_tbn_bal_order_info WHERE boi_ba_bat_id=$i_ba_bat_id";
        $o_datalist = $this->db->query($s_sql);
        return $o_datalist->result();
    }
 }
