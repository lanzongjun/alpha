<?php
/**
 * Description of AdEBSyncStorageM
 *
 * @author Vincent
 */
class AdEBSyncStorageM extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
        $this->load->dbforge();
    }
    
    /**
     * 重置冻结库存
     * @param type $s_eb_id
     * @return type
     */
    public function updateFreezeStorage($s_eb_id) {
        $s_sql = "UPDATE shop_goods_eb SET sge_is_freeze=1 WHERE sge_bs_e_id='$s_eb_id' "
                . "AND sge_barcode IN (SELECT bsf_barcode FROM base_storage_freeze)";
        $this->db->query($s_sql);
        $i_rows = $this->db->affected_rows();
        return $i_rows;
    }
    
    /**
     * 冻结库存
     * @param type $s_barcode
     * @param type $s_gname
     * @return type
     */
    public function doFreezeStorage($s_barcode,$s_gname){
        $s_update_dt = date("Y-m-d H:i:s", time());        
        $s_sql1 = "INSERT INTO base_storage_freeze (bsf_barcode,bsf_gname,bsf_update_dt) "
                . "VALUES ('$s_barcode','$s_gname','$s_update_dt') ";
        log_message('debug', "SQL文:$s_sql1");
        $this->db->query($s_sql1);
//        $i_rows1 = $this->db->affected_rows();
        $s_sql2 = "UPDATE shop_goods_eb SET sge_is_freeze=1 WHERE sge_barcode='$s_barcode'";
        log_message('debug', "SQL文:$s_sql2");
        $this->db->query($s_sql2);
        $i_rows2 = $this->db->affected_rows();
        return array(
            'state' => true,
            'msg' => $i_rows2
        );
    }
    
    /**
     * 解冻库存
     * @param type $s_barcode
     * @return type
     */
    public function doUnfreezeStorage($s_barcode){
        $s_sql1 = "DELETE FROM base_storage_freeze WHERE bsf_barcode='$s_barcode' ";
        log_message('debug', "SQL文:$s_sql1");
        $this->db->query($s_sql1);
//        $i_rows1 = $this->db->affected_rows();
        $s_sql2 = "UPDATE shop_goods_eb	SET sge_is_freeze=0 WHERE sge_barcode='$s_barcode'";
        log_message('debug', "SQL文:$s_sql2");
        $this->db->query($s_sql2);
        $i_rows2 = $this->db->affected_rows();
        return array(
            'state' => true,
            'msg' => $i_rows2
        );
    }
    
    /**
     * 同步商铺SKU
     * @param type $s_eb_id
     * @return string
     */
    public function syncSkuList($s_eb_id){
        set_time_limit(0);        
        $s_sql = "SELECT bs_e_api_id FROM base_shop_info WHERE bs_e_id='$s_eb_id'";
        $o_result = $this->db->query($s_sql);
        $a_row = $o_result->result();
        if (count($a_row)<1){return '';}
        
        $s_api_id = $a_row[0]->bs_e_api_id;
        try {
            $this->load->library('ThriftEle');        
            $o_ShopSkuList = $this->thriftele->getShopSkuList($s_api_id);
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '[饿百店铺库存管理-同步商铺SKU]时发生错误！\r\n' . $e->getMessage());
            $o_return = $this->logSkuList($s_eb_id, 0, "[饿百店铺库存管理-同步商铺SKU]时发生错误！\r\n" . $e->getMessage());
            return $o_return;
        }
        //更新数据库
        $this->db->trans_start();
        
        //清空店铺列表
        $s_sql_del = $this->clearShopSku($s_eb_id);
        $this->db->query($s_sql_del);
        $i_temp = 0;
        foreach($o_ShopSkuList->sku_list as $o_ShopSku){
            $s_sql = $this->getShopSkuSQL($o_ShopSku,$s_eb_id);
            $this->db->query($s_sql);
            $i_temp++;
        }
        $this->db->trans_complete();
        $this->load->model('AdEBShopGoodsM');
        //刷新库存
        $this->AdEBShopGoodsM->refreshStorage($s_eb_id);
        //刷新价格
        $this->AdEBShopGoodsM->refreshPrice($s_eb_id);
        //重置冻结库存
        $this->updateFreezeStorage($s_eb_id);
        
        $o_return = $this->logSkuList($s_eb_id, $i_temp, "获取SKU数:$i_temp");            
        return $o_return;
    }
    
    /**
     * SKU同步日志
     * @param type $s_eb_id
     * @param type $i_row
     * @param type $s_msg
     * @return type
     */
    private function logSkuList($s_eb_id,$i_row,$s_msg) {
        $i_user_id = 0;
        $this->load->library('session');
        $s_userid = $this->session->userdata('s_id');
        if ($s_userid) { $i_user_id = $s_userid; }
        
        $i_errorNo = $i_row > 0 ? 0 : 99;
        $s_update_dt = date("Y-m-d H:i:s", time());
        $s_sql = "INSERT INTO log_sku_list_eb (lse_eb_id,lse_error_no,"
                . "lse_msg,lse_dt,lse_user) VALUES('$s_eb_id',$i_errorNo,"
                . "'$s_msg','$s_update_dt',$i_user_id)";
        $this->db->query($s_sql);
            
        return array(
            'state'=>$i_row > 0,
            'msg'=>$s_msg
        );
    }
    
    /**
     * 清空店铺列表
     * @param type $s_shop_id
     */
    public function clearShopSku($s_shop_id){        
        $s_sql = "DELETE FROM shop_goods_eb WHERE sge_bs_e_id='$s_shop_id'";
        log_message('debug', "SQL文:$s_sql");
        return $s_sql;
    }
    
    /**
     * 获得店铺SKU填充SQL文
     * @param type $o_thrift_sku
     * @param type $bs_e_id
     * @return string
     */
    public function getShopSkuSQL($o_thrift_sku,$bs_e_id){
        $i_sale_price = $o_thrift_sku->sale_price/100;
        if (count($o_thrift_sku->custom_cat_list) > 0) {
            $s_cus_name = $o_thrift_sku->custom_cat_list[0]->custom_cat_name;
        } else {
            $s_cus_name = '';
        }
        
        $s_sql = "INSERT INTO shop_goods_eb (sge_gid,sge_cid,sge_barcode,sge_gname,sge_price,"
                . "sge_count,sge_online,sge_weight,sge_bs_e_id,sge_bs_org_sn,"
                . "sge_bs_sale_sn,sge_fclass2) "
                . "SELECT '$o_thrift_sku->sku_id','$o_thrift_sku->custom_sku_id',"
                . "'$o_thrift_sku->upc','$o_thrift_sku->name',$i_sale_price,"
                . "$o_thrift_sku->left_num,'$o_thrift_sku->status',"
                . "$o_thrift_sku->weight,'$bs_e_id',bs_org_sn,bs_sale_sn,'$s_cus_name' "
                . "FROM base_shop_info WHERE bs_e_id='$bs_e_id'";
        return $s_sql;
    }
    
    /**
     * 获得所有可同步店铺
     * @return type
     */
    public function getAllSyncShop() {
        $s_sql = "SELECT bs_e_id `id`,bs_shop_name `text` FROM base_shop_info WHERE bs_e_api_id <> '' ";
        $o_query = $this->db->query($s_sql);       
        return $o_query->result();
    }
    
    /**
     * 获得所有有库存差异的门店列表
     * @return type
     */
    public function getAllDiffStorageShop(){
        $s_sql = "SELECT DISTINCT(sge_bs_e_id) `id`, bs_shop_name `text` "
                . "FROM v_shop_goods_eb_unfreeze "
                . "LEFT JOIN base_shop_info ON bs_e_id=sge_bs_e_id "
                . "WHERE sge_count_diff = 1 ";
        $o_query = $this->db->query($s_sql);       
        return $o_query->result();
    }
    
    /**
     * 获得所有有价格差异的门店列表
     * @return type
     */
    public function getAllDiffPriceShop(){
        $s_sql = "SELECT DISTINCT(sge_bs_e_id) `id`, bs_shop_name `text` "
                . "FROM v_shop_goods_eb_unfreeze "
                . "LEFT JOIN base_shop_info ON bs_e_id=sge_bs_e_id "
                . "WHERE sge_price_diff = 1 ";
        $o_query = $this->db->query($s_sql);       
        return $o_query->result();
    }
    
    /**
     * 获得所有有差异的门店列表
     * @return type
     */
    public function getAllDiffShop(){
        $s_sql = "SELECT DISTINCT(sge_bs_e_id) `id`, bs_shop_name `text` "
                . "FROM v_shop_goods_eb_unfreeze "
                . "LEFT JOIN base_shop_info ON bs_e_id=sge_bs_e_id "
                . "WHERE sge_price_diff = 1 OR sge_count_diff = 1 ";
        $o_query = $this->db->query($s_sql);       
        return $o_query->result();
    }
    
    /**
     * 同步店铺零售价
     * @param string $s_eb_id   门店ID
     * @param boolean $b_diff   是否只同步差异
     * @return string
     */
    public function syncPrice($s_eb_id, $b_diff=false) {
        set_time_limit(0);
        $s_sql = "SELECT sge_barcode,sge_price_new sge_price,bs_shop_name,"
                . "bs_e_api_id FROM v_shop_goods_eb_unfreeze "
                . "LEFT JOIN base_shop_info ON bs_e_id=sge_bs_e_id "
                . "WHERE sge_bs_e_id='$s_eb_id' ";
        if ($b_diff == true) { $s_sql .= "AND sge_price_diff=1 "; }
        $o_result = $this->db->query($s_sql);
        $a_row = $o_result->result();
        
        $s_apid = count($a_row)>0 ? $a_row[0]->bs_e_api_id : '';
        $s_shop_name = count($a_row)>0 ? $a_row[0]->bs_shop_name : '';
        $a_upc_price = array();
        foreach ($a_row as $o_sku) {
            $o_upc_price = array(
                'upc' => $o_sku->sge_barcode,
                'price' => $o_sku->sge_price
            );
            array_push($a_upc_price, $o_upc_price);
        }
        if ($s_apid == ''){ return 'error:The ApiID is NULL' ;}
        try {
            $this->load->library('ThriftEle');
            $o_res = $this->thriftele->SkuPriceUpdateBatch($s_apid, $a_upc_price);  
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '[饿百店铺库存管理-同步商铺价格]时发生错误！\r\n' . $e->getMessage());
            $o_return1 = $this->logSyncPriceEx($s_eb_id, $e->getMessage());
            return $o_return1;
        }
        $o_return = $this->logSyncPrice($s_eb_id,$o_res);
        $o_return['shop_name'] = $s_shop_name;
        return $o_return;
    }
    
    /**
     * 价格同步日志
     * @param type $s_eb_id
     * @param type $i_row
     * @param type $s_msg
     * @return type
     */
    private function logSyncPrice($s_eb_id,$a_res) {
        $i_user_id = 0;
        $this->load->library('session');
        $s_userid = $this->session->userdata('s_id');
        if ($s_userid) { $i_user_id = $s_userid; }
        
        $s_update_dt = date("Y-m-d H:i:s", time());
        $i_success = 0;
        $i_fail = 0;
        $this->db->trans_start();
        foreach($a_res as $o_res) {
            $s_sql = "INSERT INTO log_sync_price_eb (lspe_eb_id,lspe_error_no,"
                    . "lspe_msg,lspe_dt,lspe_user) VALUES('$s_eb_id',$o_res->errorNo,"
                    . "'$o_res->error','$s_update_dt',$i_user_id)";
            $this->db->query($s_sql);
            if ($o_res->errorNo == '0'){
                $i_success++;
            } else {
                $i_fail++;
            }
        }
        $this->db->trans_complete();
        return array(
            'pages'=>count($a_res),
            'suc'=>$i_success,
            'fail'=>$i_fail
        );
    }
    
    private function logSyncPriceEx($s_eb_id, $s_msg) {
        $s_update_dt = date("Y-m-d H:i:s", time());
        $i_user_id = 0;
        $this->load->library('session');
        $s_userid = $this->session->userdata('s_id');
        if ($s_userid) { $i_user_id = $s_userid; }
        $s_sql = "INSERT INTO log_sync_price_eb (lspe_eb_id,lspe_error_no,"
                    . "lspe_msg,lspe_dt,lspe_user) VALUES('$s_eb_id', 99, "
                    . "'$s_msg','$s_update_dt',$i_user_id)";
        $this->db->query($s_sql);
        return array(
            'pages'=>0,
            'suc'=>0,
            'fail'=>0
        );
    }
    
    /**
     * 清空同步日志
     * @return type
     */
    public function keepPriceTodayLog() {
        $s_today = date("Y-m-d", time());
        $s_sql = "DELETE FROM log_sync_price_eb WHERE lspe_dt NOT LIKE '$s_today%'";
        $this->db->query($s_sql);
        $i_rows = $this->db->affected_rows();
        return $i_rows;
    }
    
    public function getLogPriceList($i_page, $i_rows, $a_get){
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getLogPriceWhere($a_get);
        $s_sql = "SELECT lspe_eb_id,bs_shop_name,lspe_error_no,lspe_msg,lspe_dt,"
                . "nickname `lspe_user` FROM log_sync_price_eb "
                . "LEFT JOIN base_shop_info ON lspe_eb_id=bs_e_id "
                . "LEFT JOIN admin_user ON lspe_user=uid $s_where "
                . "ORDER BY lspe_dt DESC LIMIT $i_start,$i_rows ";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getLogPriceTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }
    
    private function getLogPriceWhere($a_get) {
        $s_where = "WHERE 1=1";
        if (isset($a_get['eid']) && $a_get['eid']) {
            $s_where .= " AND lspe_eb_id='".$a_get['eid']."'";
        }
        if (isset($a_get['ss']) && $a_get['ss'] ) {
            if ($a_get['ss'] == 'SUCCESS') {
                $s_where .= " AND lspe_error_no=0";
            } else if ($a_get['ss'] == 'FAIL') {
                $s_where .= " AND lspe_error_no<>0";
            }
        }
        return $s_where;
    }
    
    private function _getLogPriceTotal($s_where='') {
        $s_sql = "SELECT COUNT(1) t_num FROM log_sync_price_eb $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num-0;
    }
    
    /**
     * 同步店铺库存
     * @param string $s_eb_id   门店ID
     * @param boolean $b_diff   是否只同步差异
     * @return string
     */
    public function syncOnlineStorage($s_eb_id, $b_diff=false) {
        set_time_limit(0);
        $s_sql = "SELECT sge_barcode,sge_count_new sge_count,bs_shop_name,bs_e_api_id FROM v_shop_goods_eb_unfreeze "
                . "LEFT JOIN base_shop_info ON bs_e_id=sge_bs_e_id "
                . "WHERE sge_bs_e_id='$s_eb_id' ";
        if ($b_diff == true) { $s_sql .= "AND sge_count <> sge_count_new "; }
        $o_result = $this->db->query($s_sql);
        $a_row = $o_result->result();
        
        $s_apid = count($a_row)>0 ? $a_row[0]->bs_e_api_id : '';
        $s_shop_name = count($a_row)>0 ? $a_row[0]->bs_shop_name : '';
        $a_upc_count = array();
        foreach ($a_row as $o_sku) {
            $o_upc_count = array(
                'upc' => $o_sku->sge_barcode,
                'stock' => $o_sku->sge_count
            );
            array_push($a_upc_count, $o_upc_count);
        }
        
        if ($s_apid == ''){ return 'error:The ApiID is NULL' ;}
        try {
            $this->load->library('ThriftEle');
            $o_res = $this->thriftele->SkuStockUpdateBatch($s_apid, $a_upc_count);  
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '[饿百店铺库存管理-同步商铺库存]时发生错误！\r\n' . $e->getMessage());
            $o_return1 = $this->logUpdateEx($s_eb_id, $e->getMessage());
            return $o_return1;
        }
        $o_return = $this->logUpdate($s_eb_id,$o_res);
        $o_return['shop_name'] = $s_shop_name;
        return $o_return;
    }
    
    /**
     * 清空同步日志
     * @return type
     */
    public function keepUpdateTodayLog() {
        $s_today = date("Y-m-d", time());
        $s_sql = "DELETE FROM log_update_eb WHERE lue_dt NOT LIKE '$s_today%'";
        $this->db->query($s_sql);
        $i_rows = $this->db->affected_rows();
        return $i_rows;
    }
    
    private function logUpdateEx($s_eb_id, $s_msg) {
        $s_update_dt = date("Y-m-d H:i:s", time());
        $i_user_id = 0;
        $this->load->library('session');
        $s_userid = $this->session->userdata('s_id');
        if ($s_userid) { $i_user_id = $s_userid; }
        $s_sql = "INSERT INTO log_update_eb (lue_eb_id,lue_error_no,"
                    . "lue_msg,lue_dt,lue_user) VALUES('$s_eb_id', 99, "
                    . "'$s_msg','$s_update_dt',$i_user_id)";
        $this->db->query($s_sql);
        return array(
            'pages'=>0,
            'suc'=>0,
            'fail'=>0
        );
    }
    
    private function logUpdate($s_eb_id,$a_res) {
        $i_user_id = 0;
        $this->load->library('session');
        $s_userid = $this->session->userdata('s_id');
        if ($s_userid) { $i_user_id = $s_userid; }
        
        $s_update_dt = date("Y-m-d H:i:s", time());
        $i_success = 0;
        $i_fail = 0;
        $this->db->trans_start();
        foreach($a_res as $o_res) {
            $s_sql = "INSERT INTO log_update_eb (lue_eb_id,lue_error_no,"
                    . "lue_msg,lue_dt,lue_user) VALUES('$s_eb_id',$o_res->errorNo,"
                    . "'$o_res->error','$s_update_dt',$i_user_id)";
            $this->db->query($s_sql);
            if ($o_res->errorNo == '0'){
                $i_success++;
            } else {
                $i_fail++;
            }
        }
        $this->db->trans_complete();
        return array(
            'pages'=>count($a_res),
            'suc'=>$i_success,
            'fail'=>$i_fail
        );
    }

    public function getLogStockList($i_page, $i_rows, $a_get){
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getLogUpdateWhere($a_get);
        $s_sql = "SELECT lue_eb_id,bs_shop_name,lue_error_no,lue_msg,lue_dt,"
                . "nickname `lue_user` FROM log_update_eb "
                . "LEFT JOIN base_shop_info ON lue_eb_id=bs_e_id "
                . "LEFT JOIN admin_user ON lue_user=uid $s_where "
                . "ORDER BY lue_dt DESC LIMIT $i_start,$i_rows ";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getLogUpdateTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }
    
    private function getLogUpdateWhere($a_get) {
        $s_where = "WHERE 1=1";
        if (isset($a_get['eid']) && $a_get['eid']) {
            $s_where .= " AND lue_eb_id='".$a_get['eid']."'";
        }
        if (isset($a_get['ss']) && $a_get['ss'] ) {
            if ($a_get['ss'] == 'SUCCESS') {
                $s_where .= " AND lue_error_no=0";
            } else if ($a_get['ss'] == 'FAIL') {
                $s_where .= " AND lue_error_no<>0";
            }
        }
        return $s_where;
    }
    
    private function _getLogUpdateTotal($s_where='') {
        $s_sql = "SELECT COUNT(1) t_num FROM log_update_eb $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num-0;
    }
    
    /**
     * 获得SKU日志
     * @param type $i_page
     * @param type $i_rows
     * @param type $a_get
     * @return type
     */
    public function getLogSKUList($i_page, $i_rows, $a_get){
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getLogSKUWhere($a_get);
        $s_sql = "SELECT lse_eb_id,bs_shop_name,lse_error_no,lse_msg,lse_dt,"
                . "nickname `lse_user` FROM log_sku_list_eb "
                . "LEFT JOIN base_shop_info ON lse_eb_id=bs_e_id "
                . "LEFT JOIN admin_user ON lse_user=uid $s_where "
                . "ORDER BY lse_dt DESC LIMIT $i_start,$i_rows ";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getLogSKUTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }
    
    private function getLogSKUWhere($a_get) {
        $s_where = "WHERE 1=1";
        if (isset($a_get['sid']) && $a_get['sid']) {
            $s_where .= " AND lse_eb_id='".$a_get['sid']."'";
        }
        if (isset($a_get['ss']) && $a_get['ss'] ) {
            if ($a_get['ss'] == 'SUCCESS') {
                $s_where .= " AND lse_error_no=0";
            } else if ($a_get['ss'] == 'FAIL') {
                $s_where .= " AND lse_error_no<>0";
            }
        }
        return $s_where;
    }
    
    private function _getLogSKUTotal($s_where='') {
        $s_sql = "SELECT COUNT(1) t_num FROM log_sku_list_eb $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num-0;
    }
    
    /**
     * 清空SKU更新日志
     * @return type
     */
    public function keepSKUTodayLog() {
        $s_today = date("Y-m-d", time());
        $s_sql = "DELETE FROM log_sku_list_eb WHERE lse_dt NOT LIKE '$s_today%'";
        $this->db->query($s_sql);
        $i_rows = $this->db->affected_rows();
        return $i_rows;
    }
}   
