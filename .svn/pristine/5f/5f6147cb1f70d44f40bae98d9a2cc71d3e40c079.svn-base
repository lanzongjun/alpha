<?php

/**
 * AdBalanceStageCollectM
 * 
 * @author Vincent
 */
class AdBalanceStageCollectM extends CI_Model {

    var $_tbn_ba_collect = 'balance_account_collect';
    var $_tbn_ba_shop = 'balance_account_shop';
    var $_tbn_ba_goods = 'balance_account_goods';
    var $_tbn_bo_detail = 'balance_order_detail';
    var $_tbn_ba = 'balance_account';
    
    var $_tbn_shop_info = 'base_shop_info';
    var $_tbn_goods_yj = 'base_goods_yj';
    var $_tbn_balance_price = 'base_balance_price';
    
    var $__ENUM_COLLECT_STATE_ABNORMAL = 'ABNORMAL';

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
    }
    
    function getCollectShopList($i_ba_bat_id) {
        $s_sql = "SELECT bas_bs_org_sn,bas_bs_sale_sn,bas_bs_shop_name,"
                . "bas_order_count,bas_order_amount,bas_balance_time,bas_state,"
                . "bas_update_time,bas_ba_bat_id FROM $this->_tbn_ba_shop "
                . "WHERE bas_ba_bat_id=$i_ba_bat_id ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function getCollectGoodsList($i_ba_bat_id) {
        $s_sql = "SELECT bag_barcode,bag_bgs_code,bag_goods_name,bag_count,"
                . "bag_settlement_price,bag_amount FROM $this->_tbn_ba_goods "
                . "WHERE bag_ba_bat_id=$i_ba_bat_id";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function getCollectListByShop($i_ba_bat_id,$i_shop_id) {
        $s_sql = "SELECT bac_shop_org_sn,bac_shop_name,bac_bgs_code,bac_barcode,"
                . "bac_name,bac_count,bac_settlement_price,bac_amount,"
                . "bac_balance_time,bac_update_time "
                . "FROM $this->_tbn_ba_collect WHERE bac_ba_bat_id=$i_ba_bat_id "
                . "AND bac_shop_org_sn=$i_shop_id ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    /**
     * 清除指定汇总信息
     * @param type $i_ba_bat_id
     * @return string
     */
    function clearCollect($i_ba_bat_id) {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        $this->db->trans_start();
        $s_sql_c = "DELETE FROM $this->_tbn_ba_collect WHERE bac_ba_bat_id=$i_ba_bat_id";
        log_message('debug', "SQL文:$s_sql_c");
        $this->db->query($s_sql_c);
        $s_sql_s = "DELETE FROM $this->_tbn_ba_shop WHERE bas_ba_bat_id=$i_ba_bat_id";
        log_message('debug', "SQL文:$s_sql_s");
        $this->db->query($s_sql_s);
        $s_sql_g= "DELETE FROM $this->_tbn_ba_goods WHERE bag_ba_bat_id=$i_ba_bat_id";
        log_message('debug', "SQL文:$s_sql_g");
        $this->db->query($s_sql_g);
        try {
            $b_result = $this->db->trans_complete();
            log_message('debug', "清除指定汇总信息.事务执行结果:$b_result");            
            $o_result['state'] = true;
            $o_result['msg'] = '操作成功，执行结果:'.$b_result;
            return $o_result;
        } catch (Exception $e) {
            log_message('error', '清除指定汇总信息-异常中断！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "清除指定汇总信息-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }
    
    /**
     * 汇总销售信息
     * @param type $i_ba_bat_id
     * @return type
     */
    function doCollect($i_ba_bat_id) {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        $this->db->trans_start();
        //按每门店每商品 汇总销售信息
        $s_sql = "INSERT INTO $this->_tbn_ba_collect (bac_shop_org_sn,bac_barcode,"
                . "bac_name,bac_count,bac_ba_bat_id) SELECT bod_shop_org_sn,"
                . "bod_barcode,bod_name,SUM(bod_count) bod_count,$i_ba_bat_id "
                . "FROM $this->_tbn_bo_detail WHERE bod_ba_bat_id=$i_ba_bat_id "
                . "GROUP BY bod_shop_org_sn,bod_barcode ";
        log_message('debug', "SQL文:$s_sql");
        $this->db->query($s_sql);
        
        //补充销售信息
        $s_sql_update = "UPDATE $this->_tbn_ba_collect "
                . "LEFT JOIN $this->_tbn_shop_info ON bac_shop_org_sn = bs_org_sn "
                . "LEFT JOIN $this->_tbn_goods_yj ON bac_barcode = bgs_barcode "
                . "LEFT JOIN $this->_tbn_balance_price ON bac_barcode = bbp_bar_code "
                . "SET bac_shop_name=bs_shop_name,bac_bgs_code = bgs_code,"
                . "bac_settlement_price=bbp_settlement_price,"
                . "bac_amount=bbp_settlement_price * bac_count "
                . "WHERE bac_ba_bat_id=$i_ba_bat_id ";
        log_message('debug', "SQL文:$s_sql_update");
        $this->db->query($s_sql_update);
        
        //结算价为空的设置为-1
        $s_sql_bbp = "UPDATE $this->_tbn_ba_collect "
                . "SET bac_settlement_price=-1,bac_amount=-1 "
                . "WHERE bac_settlement_price IS NULL AND bac_ba_bat_id=$i_ba_bat_id ";
        log_message('debug', "SQL文:$s_sql_bbp");
        $this->db->query($s_sql_bbp);
        try {
            $b_result = $this->db->trans_complete();
            
            log_message('debug', "汇总销售信息.事务执行结果:$b_result");            
            $o_result['state'] = true;
            $o_result['msg'] = '操作成功，执行结果:'.$b_result;
            $this->doCollectShop($i_ba_bat_id);
            $this->doCollectGoods($i_ba_bat_id);
            $this->updateCollectShopState($i_ba_bat_id);
            
            //阶段3完成时间
            $this->load->model('AdBalanceAccountM');
            $this->AdBalanceAccountM->setStage3Time($i_ba_bat_id);
            $this->AdBalanceAccountM->setBalanceYJ($i_ba_bat_id,
                    $this->getTotalShopAmount($i_ba_bat_id));
            return $o_result;
        } catch (Exception $e) {
            log_message('error', '汇总销售信息-异常中断！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "汇总销售信息-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }
    
    /**
     * 获得当前所有门店金额总和
     * @param type $i_ba_bat_id
     * @return int
     */
    function getTotalShopAmount($i_ba_bat_id) {
        $s_sql = "SELECT SUM(bas_order_amount) bas_order_amount FROM $this->_tbn_ba_shop WHERE bas_ba_bat_id=$i_ba_bat_id ";
        $o_datalist = $this->db->query($s_sql);
        $a_datalist = $o_datalist->result();
        if ($a_datalist == null || count($a_datalist)<1){
            return 0;
        } else {
            return $a_datalist[0]->bas_order_amount-0;
        }
    }
    
    /**
     * 按每门店 汇总销售信息
     * @param type $i_ba_bat_id
     */
    function doCollectShop($i_ba_bat_id){
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        //按每门店 汇总销售信息
        $s_sql_shop = "INSERT INTO $this->_tbn_ba_shop (bas_bs_org_sn,"
                . "bas_bs_sale_sn,bas_bs_shop_name,bas_order_amount,bas_ba_bat_id) "
                . "SELECT bac_shop_org_sn,bs_sale_sn,bac_shop_name,"
                . "SUM(bac_amount) bac_amount,$i_ba_bat_id FROM $this->_tbn_ba_collect "
                . "LEFT JOIN $this->_tbn_shop_info ON bac_shop_org_sn=bs_org_sn "
                . "WHERE bac_ba_bat_id=$i_ba_bat_id GROUP BY bac_shop_org_sn ";
        log_message('debug', "SQL文:$s_sql_shop");
        try {            
            $this->db->query($s_sql_shop);
            $i_rows = $this->db->affected_rows();
            log_message('debug', "按每门店 汇总销售信息.受影响记录数:$i_rows");            
            $o_result['state'] = true;
            $o_result['msg'] = '操作成功，受影响记录数:'.$i_rows;
            return $o_result;
        } catch (Exception $e) {
            log_message('error', '按每门店 汇总销售信息-异常中断！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "按每门店 汇总销售信息-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }
    
    /**
     * 更新缺少结算价的门店为异常
     * @param type $i_ba_bat_id
     * @return string
     */
    function updateCollectShopState($i_ba_bat_id) {
        $s_sql = "UPDATE $this->_tbn_ba_shop SET bas_state='$this->__ENUM_COLLECT_STATE_ABNORMAL' "
                . "WHERE bas_ba_bat_id=$i_ba_bat_id AND bas_bs_org_sn IN ("
                . "SELECT bac_shop_org_sn FROM $this->_tbn_ba_collect "
                . "WHERE bac_settlement_price<=0)";
        log_message('debug', "SQL文:$s_sql");
        try {            
            $this->db->query($s_sql);
            $i_rows = $this->db->affected_rows();
            log_message('debug', "更新缺少结算价的门店.受影响记录数:$i_rows");            
            $o_result['state'] = true;
            $o_result['msg'] = '操作成功，受影响记录数:'.$i_rows;
            return $o_result;
        } catch (Exception $e) {
            log_message('error', '更新缺少结算价的门店-异常中断！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "更新缺少结算价的门店-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }    
    }
    
    /**
     * 按每商品 汇总销售信息
     * @param type $i_ba_bat_id
     * @return string
     */
    function doCollectGoods($i_ba_bat_id){
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        //按每商品 汇总销售信息
        $s_sql_goods = "INSERT INTO $this->_tbn_ba_goods (bag_barcode,bag_bgs_code,"
                . "bag_goods_name,bag_count,bag_settlement_price,bag_amount,"
                . "bag_ba_bat_id) SELECT bac_barcode,bac_bgs_code,bac_name,"
                . "SUM(bac_count),bac_settlement_price,SUM(bac_amount),$i_ba_bat_id "
                . "FROM $this->_tbn_ba_collect "
                . "WHERE bac_ba_bat_id=$i_ba_bat_id GROUP BY bac_barcode ";
        log_message('debug', "SQL文:$s_sql_goods");
        try {            
            $this->db->query($s_sql_goods);
            $i_rows = $this->db->affected_rows();
            log_message('debug', "按每商品 汇总销售信息.受影响记录数:$i_rows");            
            $o_result['state'] = true;
            $o_result['msg'] = '操作成功，受影响记录数:'.$i_rows;
            return $o_result;
        } catch (Exception $e) {
            log_message('error', '按每商品 汇总销售信息-异常中断！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "按每商品 汇总销售信息-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }
}
