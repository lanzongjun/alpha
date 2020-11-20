<?php
/**
 * Description of AdMTSyncStorageM
 *
 * @author Vincent
 */
class AdMTSyncStorageM extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
        $this->load->dbforge();
    }
    
    /**
     * 重置冻结库存
     * @param type $s_mt_id
     * @return type
     */
    public function updateFreezeStorage($s_mt_id) {
        $s_sql = "UPDATE shop_goods_mt SET sgm_is_freeze=1 WHERE sgm_bs_m_id='$s_mt_id' "
                . "AND sgm_barcode IN (SELECT bsf_barcode FROM base_storage_freeze)";
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
        $s_sql2 = "UPDATE shop_goods_mt SET sgm_is_freeze=1 WHERE sgm_barcode='$s_barcode'";
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
        $s_sql2 = "UPDATE shop_goods_mt	SET sgm_is_freeze=0 WHERE sgm_barcode='$s_barcode'";
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
     * @param $s_mt_id
     * @return string
     */
    public function syncSkuList($s_mt_id){
        set_time_limit(0);        
        $s_sql = "SELECT bs_m_api_id FROM base_shop_info WHERE bs_m_id=$s_mt_id ";
        $o_result = $this->db->query($s_sql);
        $a_row = $o_result->result();
        if (count($a_row)<1){return '';}
        
        $s_api_id = $a_row[0]->bs_m_api_id;
        try {
            $this->load->library('ThriftMt');        
            $o_ShopSkuList = $this->thriftmt->getShopSkuList($s_api_id);
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '[美团店铺库存管理-同步商铺SKU]时发生错误！\r\n' . $e->getMessage());
            $o_return = $this->logSkuList($s_mt_id, 0, "[美团店铺库存管理-同步商铺SKU]时发生错误！\r\n" . $e->getMessage());
            return $o_return;
        }
        //更新数据库
        $this->db->trans_start();
        
        //清空店铺列表
        $s_sql_del = $this->clearShopSku($s_mt_id);
        $this->db->query($s_sql_del);
        $i_temp = 0;
        foreach($o_ShopSkuList->sku_list as $o_ShopSku){
            $s_sql = $this->getShopSkuSQL($o_ShopSku,$s_mt_id);
            $this->db->query($s_sql);
            $i_temp++;
        }
        $this->db->trans_complete();
        $this->load->model('AdMTShopGoodsM');
        //刷新库存
        $this->AdMTShopGoodsM->refreshStorage($s_mt_id);        
        //重置冻结库存
        $this->updateFreezeStorage($s_mt_id);
        
        $o_return = $this->logSkuList($s_mt_id, $i_temp, "获取SKU数:$i_temp");            
        return $o_return;
    }
    
    /**
     * SKU同步日志
     * @param type $s_mt_id
     * @param type $i_row
     * @param type $s_msg
     * @return type
     */
    private function logSkuList($s_mt_id,$i_row,$s_msg) {
        $i_user_id = 0;
        $this->load->library('session');
        $s_userid = $this->session->userdata('s_id');
        if ($s_userid) { $i_user_id = $s_userid; }
        
        $i_errorNo = $i_row > 0 ? 0 : 99;
        $s_update_dt = date("Y-m-d H:i:s", time());
        $s_sql = "INSERT INTO log_sku_list_mt (lsm_mt_id,lsm_error_no,"
                . "lsm_msg,lsm_dt,lsm_user) VALUES('$s_mt_id',$i_errorNo,"
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
        $s_sql = "DELETE FROM shop_goods_mt WHERE sgm_bs_m_id='$s_shop_id'";
        log_message('debug', "SQL文:$s_sql");
        return $s_sql;
    }
    
    /**
     * 获得店铺SKU填充SQL文
     * @param type $o_thrift_sku
     * @param type $bs_m_id
     * @return string
     */
    public function getShopSkuSQL($o_thrift_sku,$bs_m_id){
        $s_sql = "INSERT INTO shop_goods_mt (sgm_gid,sgm_cid,sgm_barcode,sgm_gname,sgm_band,"
                . "sgm_price,sgm_count,sgm_online,sgm_weight,sgm_bs_m_id,sgm_bs_org_sn) "
                . "SELECT '$o_thrift_sku->sku_id','$o_thrift_sku->app_food_code',"
                . "'$o_thrift_sku->upc','$o_thrift_sku->name','$o_thrift_sku->zh_name',"
                . "$o_thrift_sku->price,$o_thrift_sku->stock,$o_thrift_sku->is_sold_out,"
                . "$o_thrift_sku->weight,$bs_m_id,bs_org_sn FROM base_shop_info "
                . "WHERE bs_m_id=$bs_m_id ";
        return $s_sql;
    }
    
    /**
     * 获得所有可同步店铺
     * @return type
     */
    public function getAllSyncShop() {
        $s_sql = "SELECT bs_m_id `id`,bs_shop_name `text` FROM base_shop_info WHERE bs_m_api_id <> '' ";
        $o_query = $this->db->query($s_sql);       
        return $o_query->result();
    }
    
    /**
     * 同步店铺库存
     * @param string $s_eb_id   门店ID
     * @param boolean $b_diff   是否只同步差异
     * @return string
     */
    public function syncOnlineStorage($s_mt_id, $b_diff=false) {
        set_time_limit(0);
        $s_sql = "SELECT sgm_gid,sgm_cid,sgm_barcode,sgm_count_new sgm_count,sgm_online,"
                . "bs_m_api_id,bs_shop_name FROM v_shop_goods_mt_unfreeze "
                . "LEFT JOIN base_shop_info ON bs_m_id=sgm_bs_m_id WHERE sgm_bs_m_id=$s_mt_id";
        if ($b_diff == true) { $s_sql .= " AND sgm_count <> sgm_count_new "; }
        $o_result = $this->db->query($s_sql);
        $a_row = $o_result->result();
        $s_apid = count($a_row)>0 ? $a_row[0]->bs_m_api_id : '';
        $s_shop_name = count($a_row)>0 ? $a_row[0]->bs_shop_name : '';        
        if ($s_apid == ''){ return 'error:The ApiID is NULL' ;}
        try {
            $this->load->library('ThriftMt');
            $o_res = $this->thriftmt->SkuStockStatusUpdate($s_apid, $a_row);  
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '[美团店铺库存管理-同步商铺库存]时发生错误！\r\n' . $e->getMessage());
            $o_return1 = $this->logUpdateEx($s_mt_id, $e->getMessage());
            $o_return1['shop_name'] = $s_shop_name;
            return $o_return1;
        }
        $o_return_update = $this->logUpdate($s_mt_id,$o_res->stock_update);
        $o_return_status_up = $this->logStatus($s_mt_id,$o_res->status_up,'下架');
        $o_return_status_down = $this->logStatus($s_mt_id,$o_res->status_down,'上架');
        $o_return['shop_name'] = $s_shop_name;
        $o_return['update'] = $o_return_update;
        $o_return['status_up'] = $o_return_status_up;
        $o_return['status_down'] = $o_return_status_down;
        return $o_return;
    }
    
    /**
     * 上下架日志
     * @param type $s_mt_id
     * @param type $a_res
     * @param type $s_status
     * @return type
     */
    private function logStatus($s_mt_id,$a_res,$s_status='上下架') {
        $i_user_id = 0;
        $this->load->library('session');
        $s_userid = $this->session->userdata('s_id');
        if ($s_userid) { $i_user_id = $s_userid; }
        
        $i_success = 0;
        $i_fail = 0;
        $this->db->trans_start();
        foreach($a_res as $o_res) {
            $s_sql = "INSERT INTO log_update_mt (lum_mt_id,lum_error_no,"
                    . "lum_msg,lum_user) VALUES('$s_mt_id',$o_res->errorNo,"
                    . "'$o_res->message',$i_user_id)";
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
    
    /**
     * 异常日志
     * @param type $s_mt_id
     * @param string $s_msg
     * @return type
     */
    private function logUpdateEx($s_mt_id, $s_msg) {
        $i_user_id = 0;
        $this->load->library('session');
        $s_userid = $this->session->userdata('s_id');
        if ($s_userid) { $i_user_id = $s_userid; }
        $s_sql = "INSERT INTO log_update_mt (lum_mt_id,lum_error_no,"
                    . "lum_msg,lum_user) VALUES('$s_mt_id',99,'$s_msg',$i_user_id)";
        $this->db->query($s_sql);
        return array(
            'update' => array(                
                'pages'=>0,
                'suc'=>0,
                'fail'=>0
            ),
            'status_up' => array(                
                'pages'=>0,
                'suc'=>0,
                'fail'=>0
            ),
            'status_down' => array(                
                'pages'=>0,
                'suc'=>0,
                'fail'=>0
            )
        );
    }
    
    /**
     * 库存更新日志
     * @param type $s_mt_id
     * @param type $a_res
     * @return type
     */
    private function logUpdate($s_mt_id,$a_res) {
        $i_user_id = 0;
        $this->load->library('session');
        $s_userid = $this->session->userdata('s_id');
        if ($s_userid) { $i_user_id = $s_userid; }
        
        $i_success = 0;
        $i_fail = 0;
        $this->db->trans_start();
        foreach($a_res as $o_res) {
            $s_sql = "INSERT INTO log_update_mt (lum_mt_id,lum_error_no,"
                    . "lum_msg,lum_user) VALUES('$s_mt_id',$o_res->errorNo,"
                    . "'$o_res->message',$i_user_id)";
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
        
    /**
     * 获得更新日志
     * @param type $i_page
     * @param type $i_rows
     * @param type $a_get
     * @return mixed
     */
    public function getLogStockList($i_page, $i_rows, $a_get){
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getLogStockWhere($a_get);
        $s_sql = "SELECT lum_mt_id,bs_shop_name,lum_error_no,lum_msg,lum_dt,"
                . "uname `lum_user` FROM log_update_mt "
                . "LEFT JOIN base_shop_info ON lum_mt_id=bs_m_id "
                . "LEFT JOIN admin_user ON lum_user=uid $s_where "
                . "ORDER BY lum_dt DESC LIMIT $i_start,$i_rows ";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getLogStockTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }
    
    private function getLogStockWhere($a_get) {
        $s_where = "WHERE 1=1";
        if (isset($a_get['sid']) && $a_get['sid']) {
            $s_where .= " AND lum_mt_id='".$a_get['sid']."'";
        }
        if (isset($a_get['ss']) && $a_get['ss'] ) {
            if ($a_get['ss'] == 'SUCCESS') {
                $s_where .= " AND lum_error_no=0";
            } else if ($a_get['ss'] == 'FAIL') {
                $s_where .= " AND lum_error_no<>0";
            }
        }
        return $s_where;
    }
    
    private function _getLogStockTotal($s_where='') {
        $s_sql = "SELECT COUNT(1) t_num FROM log_update_mt $s_where ";
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
        $s_sql = "SELECT lsm_mt_id,bs_shop_name,lsm_error_no,lsm_msg,lsm_dt,"
                . "uname `lsm_user` FROM log_sku_list_mt "
                . "LEFT JOIN base_shop_info ON lsm_mt_id=bs_m_id "
                . "LEFT JOIN admin_user ON lsm_user=uid $s_where "
                . "ORDER BY lsm_dt DESC LIMIT $i_start,$i_rows ";
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
            $s_where .= " AND lsm_mt_id='".$a_get['sid']."'";
        }
        if (isset($a_get['ss']) && $a_get['ss'] ) {
            if ($a_get['ss'] == 'SUCCESS') {
                $s_where .= " AND lsm_error_no=0";
            } else if ($a_get['ss'] == 'FAIL') {
                $s_where .= " AND lsm_error_no<>0";
            }
        }
        return $s_where;
    }
    
    private function _getLogSKUTotal($s_where='') {
        $s_sql = "SELECT COUNT(1) t_num FROM log_sku_list_mt $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num-0;
    }
    
    /**
     * 清空库存更新日志
     * @return type
     */
    public function keepStockTodayLog() {
        $s_today = date("Y-m-d", time());
        $s_sql = "DELETE FROM log_update_mt WHERE lum_dt NOT LIKE '$s_today%'";
        $this->db->query($s_sql);
        $i_rows = $this->db->affected_rows();
        return $i_rows;
    }
    
    /**
     * 清空SKU更新日志
     * @return type
     */
    public function keepSKUTodayLog() {
        $s_today = date("Y-m-d", time());
        $s_sql = "DELETE FROM log_sku_list_mt WHERE lsm_dt NOT LIKE '$s_today%'";
        $this->db->query($s_sql);
        $i_rows = $this->db->affected_rows();
        return $i_rows;
    }

    /**
     * 获得所有有差异的门店列表
     * @return mixed
     * @author zongjun.lan
     */
    public function getAllDiffShop()
    {
        $s_sql = "SELECT DISTINCT(sgm_bs_m_id) `id`, bs_shop_name `text` "
            . "FROM v_shop_goods_mt_unfreeze "
            . "LEFT JOIN base_shop_info ON bs_m_id=sgm_bs_m_id "
            . "WHERE sgm_price_diff = 1 OR sgm_count_diff = 1 ";
        $o_query = $this->db->query($s_sql);
        return $o_query->result();
    }
}
