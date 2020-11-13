<?php

/**
 * 缓存-商品相关
 * Description of AdCacheGoodsM
 *
 * @author Vincent
 */
class AdCacheGoodsM extends CI_Model {

    var $__tbn_cache_bal_price = 'cache_goods_balance_price';
    var $__tbn_cache_shop_ele = 'cache_shop_goods_ele';
    var $__tbn_cache_shop_mt = 'cache_shop_goods_mt';
    var $__tbn_base_goods = 'base_goods_yj';
    var $__tbn_base_price = 'base_balance_price';
    var $__tbn_base_shop_storage = 'base_shop_storage_yj';
    var $__tbn_shop_ele = 'shop_goods_eb';
    var $__tbn_shop_mt = 'shop_goods_mt';
    var $__tbn_base_shop_info = 'base_shop_info';
    

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
    }
    
    /**
     * 更新可售商品列表
     * @return type
     */
    function updateSaleOnlineGoods() {
        $this->db->trans_start();
        $s_sql0 = "DELETE FROM $this->__tbn_cache_bal_price";
        log_message('debug', "SQL文:$s_sql0");
        $this->db->query($s_sql0);
        $s_sql = "INSERT INTO $this->__tbn_cache_bal_price (cgbp_code,cgbp_barcode,cgbp_name)"
                . "SELECT bgs_code,bgs_barcode,bgs_name FROM $this->__tbn_base_goods "
                . "WHERE bgs_sale_online=1 AND (bgs_state='NORMAL' OR bgs_state='NEW');";
        log_message('debug', "SQL文:$s_sql");
        $this->db->query($s_sql);
        return $this->db->trans_complete();
    }
    
    /**
     * 更新结算价
     * @return type
     */
    function updateGoodsBalancePrice(){
        $s_sql = "UPDATE $this->__tbn_cache_bal_price INNER JOIN $this->__tbn_base_price "
                . "ON bbp_bar_code = cgbp_barcode SET cgbp_sale_price=bbp_yj_sale_price,"
                . "cgbp_settlement_price=bbp_settlement_price ";
        $this->db->query($s_sql);
        return $this->db->affected_rows();
    }
    
    /**
     * 更新饿了么商品缓存
     */
    function updateGoodsEle(){
        $this->clearGoodsEle();
        $s_sql = "SELECT bs_org_sn,bs_e_id,bs_shop_name FROM $this->__tbn_base_shop_info "
                . "WHERE bs_e_api_id IS NOT NULL AND bs_e_api_id<>'' ";
        $o_datalist = $this->db->query($s_sql);
        $a_dataline = $o_datalist->result();
        foreach ($a_dataline as $o_data) {
//            echo "$o_data->bs_org_sn, $o_data->bs_e_id,$o_data->bs_shop_name";
            $this->addGoodsEle($o_data->bs_org_sn, $o_data->bs_e_id,$o_data->bs_shop_name);
        }
        return true;
    }
    
    /**
     * 更新美团商品缓存
     */
    function updateGoodsMT(){
        $this->clearGoodsMt();
        $s_sql = "SELECT bs_org_sn,bs_m_id,bs_shop_name FROM $this->__tbn_base_shop_info "
                . "WHERE bs_m_api_id IS NOT NULL AND bs_m_api_id<>'' ";
        $o_datalist = $this->db->query($s_sql);
        $a_dataline = $o_datalist->result();
        foreach ($a_dataline as $o_data) {
//            echo "$o_data->bs_org_sn, $o_data->bs_m_id,$o_data->bs_shop_name";
            $this->addGoodsMT($o_data->bs_org_sn, $o_data->bs_m_id,$o_data->bs_shop_name);
        }
        return true;
    }
    
    /**
     * 清空饿了么商品缓存
     * @return type
     */
    function clearGoodsEle() {
        $s_sql = "DELETE FROM $this->__tbn_cache_shop_ele";
        $this->db->query($s_sql);
        return $this->db->affected_rows();
    }
    
    /**
     * 清空美团商品缓存
     * @return type
     */
    function clearGoodsMt() {
        $s_sql = "DELETE FROM $this->__tbn_cache_shop_mt";
        $this->db->query($s_sql);
        return $this->db->affected_rows();
    }
    
    /**
     * 更新饿了么指定门店商品缓存
     * @param type $i_org_sn
     * @param type $s_shop_id
     * @param type $s_shop_name
     * @return type
     */
    function addGoodsEle($i_org_sn, $s_shop_id, $s_shop_name) {
        $s_sql = "INSERT INTO $this->__tbn_cache_shop_ele (csge_code,csge_barcode,"
                . "csge_name,csge_count,csge_sale_price,csge_settlement_price,"
                . "csge_org_code,csge_shop_id,csge_shop_name) "
                . "SELECT bssy_yj_code,bssy_barcode,bssy_goods_name,bssy_count,"
                . "cgbp_sale_price,cgbp_settlement_price,$i_org_sn,'$s_shop_id','$s_shop_name' "
                . "FROM $this->__tbn_base_shop_storage "
                . "INNER JOIN $this->__tbn_cache_bal_price ON cgbp_barcode = bssy_barcode "
                . "WHERE bssy_org_code='$i_org_sn' AND bssy_barcode NOT IN "
                . "(SELECT sge_barcode FROM $this->__tbn_shop_ele WHERE sge_bs_org_sn='$i_org_sn') ";
        $this->db->query($s_sql);
        return $this->db->affected_rows();
    }
    
    /**
     * 更新美团指定门店商品缓存
     * @param type $i_org_sn
     * @param type $s_shop_id
     * @param type $s_shop_name
     * @return type
     */
    function addGoodsMT($i_org_sn, $s_shop_id, $s_shop_name) {
        $s_sql = "INSERT INTO $this->__tbn_cache_shop_mt (csgm_code,csgm_barcode,"
                . "csgm_name,csgm_count,csgm_sale_price,csgm_settlement_price,"
                . "csgm_org_code,csgm_shop_id,csgm_shop_name) "
                . "SELECT bssy_yj_code,bssy_barcode,bssy_goods_name,bssy_count,"
                . "cgbp_sale_price,cgbp_settlement_price,$i_org_sn,'$s_shop_id','$s_shop_name' "
                . "FROM $this->__tbn_base_shop_storage "
                . "INNER JOIN $this->__tbn_cache_bal_price ON cgbp_barcode = bssy_barcode "
                . "WHERE bssy_org_code='$i_org_sn' AND bssy_barcode NOT IN "
                . "(SELECT sgm_barcode FROM $this->__tbn_shop_mt WHERE sgm_bs_org_sn='$i_org_sn') ";
        $this->db->query($s_sql);
        return $this->db->affected_rows();
    }
}
