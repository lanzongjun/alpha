<?php

/**
 * AdBalanceStageExportM
 * 
 * @author Vincent
 */
class AdBalanceStageExportM extends CI_Model {

    var $_tbn_ba = 'balance_account';
    var $_tbn_ba_collect = 'balance_account_collect';
    var $_tbn_ba_shop = 'balance_account_shop';
    var $_tbn_ba_goods = 'balance_account_goods';
    var $_tbn_o_info = 'order_info';
    var $_tbn_o_detail = 'order_detail';

    var $__out_file_root = '/output/balance_shop/';
    var $__out_file_pre = '易捷结算表';
    var $__out_file_pre_goods = '易捷站点总结算表';
    
    var $__balance_account = '160885369';

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
    }
    
    /**
     * 获得各站结算列表
     * @param type $i_ba_bat_id
     * @return type
     */
    function getCollectList($i_ba_bat_id) {
        $s_sql = "SELECT bas_id ck,bas_id,bas_bs_org_sn,bas_bs_sale_sn,bas_bs_shop_name,"
                . "bas_file_path,bas_mail_file,bas_mail_name,bas_mail_to,"
                . "bas_mail_send,bas_order_count,bas_order_amount,bas_ba_bat_id,"
                . "bas_balance_time,ba_balance_date_begin,ba_balance_date_end "
                . "FROM $this->_tbn_ba_shop "
                . "LEFT JOIN $this->_tbn_ba ON bas_ba_bat_id=ba_bat_id "
                . "WHERE bas_ba_bat_id=$i_ba_bat_id ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    /**
     * 获得易捷站点总结算表信息
     * @param type $i_ba_bat_id
     * @return type
     */
    function getGoodsList($i_ba_bat_id) {
        $s_sql = "SELECT bag_barcode,bag_bgs_code,bag_goods_name,bag_count,"
                . "bag_settlement_price,bag_amount FROM $this->_tbn_ba_goods "
                . "WHERE bag_ba_bat_id=$i_ba_bat_id";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
        
    /**
     * 获得文件名称
     * @param type $s_date_begin
     * @param type $s_date_end
     * @param type $s_shop_name
     * @return type
     */
    function getFileName($s_date_begin, $s_date_end, $s_shop_name=''){
        $s_date = $s_date_begin == $s_date_end ? $s_date_begin : "$s_date_begin~$s_date_end";
        
        if ($s_shop_name == '') {
            return "$this->__out_file_pre_goods($s_date).xlsx";
        } else {
            return "$this->__out_file_pre-$s_shop_name($s_date).xlsx";
        }
    }
    
    /**
     * 获得文件路径
     * @param type $s_date_begin
     * @param type $s_date_end
     * @param type $s_shop_name
     * @return type
     */
    function getFilePath($s_date_begin, $s_date_end, $s_shop_name=''){
        $s_file_name = $this->getFileName($s_date_begin, $s_date_end, $s_shop_name);
        if ($s_date_begin == $s_date_end) {
            $s_date = $s_date_begin;
        } else {
            $s_date = "$s_date_begin~$s_date_end";
        }
        $s_out_file_root = "$this->__out_file_root$s_date/";
        if (!file_exists(".$s_out_file_root")) {
            mkdir(".$s_out_file_root");
        }
        return ".$s_out_file_root$s_file_name";
    }
    
    function updateFilepathShops($i_ba_bat_id, $s_shop_id, $s_file_path) {
        $o_return = array(
            'state' => true,
            'msg' => 'SUCCESS'
        );
        $s_sql = "UPDATE $this->_tbn_ba_shop SET bas_file_path='$s_file_path' "
                . "WHERE bas_ba_bat_id=$i_ba_bat_id AND bas_bs_org_sn=$s_shop_id";
        log_message('debug', "SQL文:$s_sql");
        try {
            $this->db->query($s_sql);
            return $o_return;
        } catch (Exception $e) {
            log_message('error', '[导出结算数据-更新总结算表文件路径]时发生错误！\r\n' . $e->getMessage());
            $o_return['state'] = false;
            $o_return['msg'] = "[导出结算数据-更新总结算表文件路径]时发生错误！\r\n" . $e->getMessage();
            return $o_return;
        }
    }
    
    function updateFilepathGoods($i_ba_bat_id, $s_file_path) {
        $o_return = array(
            'state' => true,
            'msg' => 'SUCCESS'
        );
        $s_sql = "UPDATE $this->_tbn_ba SET ba_collect_goods_file='$s_file_path' "
                . "WHERE ba_bat_id=$i_ba_bat_id";
        log_message('debug', "SQL文:$s_sql");
        try {
            $this->db->query($s_sql);
            return $o_return;
        } catch (Exception $e) {
            log_message('error', '[导出结算数据-更新总结算表文件路径]时发生错误！\r\n' . $e->getMessage());
            $o_return['state'] = false;
            $o_return['msg'] = "[导出结算数据-更新总结算表文件路径]时发生错误！\r\n" . $e->getMessage();
            return $o_return;
        }
    }
    
    function getFilepathGoods($i_ba_bat_id) {
        $s_sql = "SELECT ba_collect_goods_file FROM $this->_tbn_ba "
                . "WHERE ba_bat_id=$i_ba_bat_id ";
        $o_datalist = $this->db->query($s_sql);
        $a_datalist = $o_datalist->result();
        if ($a_datalist == null || count($a_datalist) < 1){
            return '';
        } else {
            return $a_datalist[0]->ba_collect_goods_file;
        }
    }
    
    function exportZipPackage($a_bas_id){
        $s_ids = $a_bas_id[0];
        for ($i=1; $i<count($a_bas_id); $i++) {
            $s_ids .= ",$a_bas_id[$i]";
        }
        $s_sql = "SELECT bas_file_path FROM $this->_tbn_ba_shop WHERE bas_id IN ($s_ids)";
        $o_datalist = $this->db->query($s_sql);
        $a_datalist = $o_datalist->result();
        $a_file_path = array();
        $a_file_name = array();
        for ($i=0; $i<count($a_datalist); $i++) {
            $o_data = $a_datalist[$i];
            $a_file_path[$i] = $o_data->bas_file_path;
            preg_match_all("/$this->__out_file_pre(.*)\.xlsx/U",$o_data->bas_file_path,$pat_array);
            $a_file_name[$i] = $pat_array[0][0];
        }
        $s_date = date("Y-m-d h:i:s");
        $s_output_name = "打包下载($s_date)";
        $this->load->library('LibZipStream');
        $this->libzipstream->outputHTTP($a_file_name,$a_file_path,$s_output_name);
    }
    
    /**
     * 导出门店纬度汇总表格
     * @param type $i_ba_bat_id
     * @param type $s_shop_id
     * @return string
     */
    function exportCollect($i_ba_bat_id, $s_shop_id){
        $o_return = array(
            'state' => true,
            'msg' => "SUCCESS"
        );
        $o_result_co = $this->getCollectShop($i_ba_bat_id, $s_shop_id);
        $a_record = $o_result_co['record'];
        if (null == $a_record || count($a_record)<1 ){
            $o_return['state'] = false;
            $o_return['msg'] = '记录为空';
            return $o_return;
        }
        $o_record = $a_record[0];
        $s_date_begin = $o_record->ba_balance_date_begin;
        $s_date_end = $o_record->ba_balance_date_end;
        $s_shop_name = $o_record->bac_shop_name;
        
        try {
            $s_file_path = $this->getFilePath($s_date_begin, $s_date_end, $s_shop_name);
            $this->load->library('Php_spread_sheet_lib');
            $o_result_ebo = $this->getOrdersInfo($i_ba_bat_id, $s_shop_id, 'ELE');
            $o_result_mto = $this->getOrdersInfo($i_ba_bat_id, $s_shop_id, 'MT');
            $this->php_spread_sheet_lib->BSExportCollect($o_result_co, $o_result_ebo,
                    $o_result_mto, $s_file_path);
            $o_return['file_path'] = $s_file_path;
            
            //阶段4完成时间
            $this->load->model('AdBalanceAccountM');
            $this->AdBalanceAccountM->setStage4Time($i_ba_bat_id);
            
            return $o_return;
        } catch (Exception $e) {
            log_message('error', '[导出结算数据-门店结算表]时发生错误！\r\n' . $e->getMessage());
            $o_return['state'] = false;
            $o_return['msg'] = "[导出结算数据-门店结算表]时发生错误！\r\n" . $e->getMessage();
            return $o_return;
        }
    }
    
    /**
     * 导出商品纬度汇总表格
     * @param type $i_ba_bat_id
     * @return string
     */
    function exportGoods($i_ba_bat_id){
        $o_return = array(
            'state' => true,
            'msg' => "SUCCESS"
        );
        $o_datalist = $this->getCollectGoods($i_ba_bat_id);
        $a_record = $o_datalist['record'];
        if (null == $a_record || count($a_record)<1 ){
            $o_return['state'] = false;
            $o_return['msg'] = '记录为空';
            return $o_return;
        }
        $o_record = $a_record[0];
        $s_date_begin = $o_record->ba_balance_date_begin;
        $s_date_end = $o_record->ba_balance_date_end;
        
        try {
            $s_file_path = $this->getFilePath($s_date_begin,$s_date_end);        
            $this->load->library('Php_spread_sheet_lib');
            $this->php_spread_sheet_lib->BSExportGoods($o_datalist,$s_file_path);
            $o_return['file_path'] = $s_file_path;
            $this->updateFilepathGoods($i_ba_bat_id, $s_file_path);
            return $o_return;
        } catch (Exception $e) {
            log_message('error', '[导出结算数据-总结算表]时发生错误！\r\n' . $e->getMessage());
            $o_return['state'] = false;
            $o_return['msg'] = "[导出结算数据-总结算表]时发生错误！\r\n" . $e->getMessage();
            return $o_return;
        }
    }
    
    /**
     * 获得易捷站点总结算表信息
     * @param type $i_ba_bat_id
     * @return type
     */
    function getCollectGoods($i_ba_bat_id) {
        $s_sql = "SELECT bag_barcode,bag_settlement_price,bag_amount,bag_count,"
                . "bag_goods_name,ba_balance_date_begin,ba_balance_date_end,"
                . "LPAD(bag_bgs_code,5,'0') bag_bgs_code "
                . "FROM $this->_tbn_ba_goods "
                . "LEFT JOIN $this->_tbn_ba ON bag_ba_bat_id=ba_bat_id "
                . "WHERE bag_ba_bat_id=$i_ba_bat_id";
        $o_result = $this->db->query($s_sql);
        $a_column = array(
            0=> array(
                'title'=>'商品编码',
                'field'=>'bag_bgs_code'
            ),1=> array(
                'title'=>'商品条形码',
                'field'=>'bag_barcode'
            ),2=> array(
                'title'=>'单价',
                'field'=>'bag_settlement_price'
            ),3=> array(
                'title'=>'数量',
                'field'=>'bag_count'
            ),4=> array(
                'title'=>'金额',
                'field'=>'bag_amount'
            ),5=> array(
                'title'=>'商品名称',
                'field'=>'bag_goods_name'
            )
        );
        return array(
            'column' => $a_column,
            'record' => $o_result->result()
        );
    }
    
    /**
     * 获得各站结算信息列表
     * @param type $i_ba_bat_id
     * @param type $s_shop_id
     * @return type
     */
    function getCollectShop($i_ba_bat_id, $s_shop_id) {
        $s_sql = "SELECT bac_shop_org_sn,bac_shop_name,$this->__balance_account bac_account,"
                . "bac_bgs_code,bac_barcode,bac_name,bac_count,"
                . "bac_settlement_price,bac_amount,bac_balance_time,"
                . "bac_update_time,ba_balance_date_begin,"
                . "ba_balance_date_end FROM $this->_tbn_ba_collect "
                . "LEFT JOIN $this->_tbn_ba ON bac_ba_bat_id=ba_bat_id "
                . "WHERE bac_ba_bat_id=$i_ba_bat_id AND bac_shop_org_sn=$s_shop_id";
        $o_result = $this->db->query($s_sql);
        $a_column = array(
            0=> array(
                'title'=>'站点名称',
                'field'=>'bac_shop_name'
            ),1=> array(
                'title'=>'付款方账号',
                'field'=>'bac_account'
            ),2=> array(
                'title'=>'商品编码',
                'field'=>'bac_bgs_code'
            ),3=> array(
                'title'=>'单价',
                'field'=>'bac_settlement_price'
            ),4=> array(
                'title'=>'数量',
                'field'=>'bac_count'
            ),5=> array(
                'title'=>'金额',
                'field'=>'bac_amount'
            ),6=> array(
                'title'=>'商品条形码',
                'field'=>'bac_barcode'
            ),7=> array(
                'title'=>'商品名称',
                'field'=>'bac_name'
            )
        );
        return array(
            'column' => $a_column,
            'record' => $o_result->result()
        );
    }
    
    /**
     * 获得订单详情列表
     * @param type $i_ba_bat_id
     * @param type $s_shop_id
     * @param type $s_platform
     * @return type
     */
    function getOrdersInfo($i_ba_bat_id, $s_shop_id, $s_platform){
        $s_sql = "SELECT oi_code,oi_platform,oi_shop_name,oi_shop_id,"
                . "oi_pick_type_desc,oi_order_state,oi_create_time,"
                . "oi_comfirm_time,oi_complete_time,oi_total_fee,oi_user_fee,"
                . "oi_shop_fee,oi_commission,oi_package_fee,oi_shipping_fee,"
                . "od_barcode,od_name,od_count,od_price,od_fee,"
                . "od_discount_fee,od_platform_rate,od_shop_rate "
                . "FROM $this->_tbn_o_detail LEFT JOIN $this->_tbn_o_info "
                . "ON od_oi_code=oi_code WHERE oi_ba_bat_id=$i_ba_bat_id "
                . "AND oi_shop_org_sn=$s_shop_id AND oi_platform='$s_platform'";
        $o_result = $this->db->query($s_sql);
        $a_column = array();
        array_push($a_column, array(
            'title' => '订单编号',
            'field' => 'oi_code'
        ));
        array_push($a_column, array(
            'title' => '订单来源',
            'field' => 'oi_platform'
        ));
        array_push($a_column, array(
            'title' => '商户名称',
            'field' => 'oi_shop_name'
        ));
        array_push($a_column, array(
            'title' => '商户ID',
            'field' => 'oi_shop_id'
        ));
        array_push($a_column, array(
            'title' => '配送方式',
            'field' => 'oi_pick_type_desc'
        ));
        array_push($a_column, array(
            'title' => '订单状态',
            'field' => 'oi_order_state'
        ));
        array_push($a_column, array(
            'title' => '下单时间',
            'field' => 'oi_create_time'
        ));
        array_push($a_column, array(
            'title' => '商户接单时间',
            'field' => 'oi_comfirm_time'
        ));
        array_push($a_column, array(
            'title' => '订单完成时间',
            'field' => 'oi_complete_time'
        ));
        array_push($a_column, array(
            'title' => '订单总金额',
            'field' => 'oi_total_fee'
        ));
        array_push($a_column, array(
            'title' => '用户实付金额',
            'field' => 'oi_user_fee'
        ));
        array_push($a_column, array(
            'title' => '商户应收金额',
            'field' => 'oi_shop_fee'
        ));
        array_push($a_column, array(
            'title' => '平台佣金',
            'field' => 'oi_commission'
        ));
        array_push($a_column, array(
            'title' => '配送费',
            'field' => 'oi_shipping_fee'
        ));
        array_push($a_column, array(
            'title' => '包装费',
            'field' => 'oi_package_fee'
        ));
        array_push($a_column, array(
            'title' => '商品名称',
            'field' => 'od_name'
        ));
        array_push($a_column, array(
            'title' => '商品条形码',
            'field' => 'od_barcode'
        ));
        array_push($a_column, array(
            'title' => '数量',
            'field' => 'od_count'
        ));
        array_push($a_column, array(
            'title' => '单价',
            'field' => 'od_price'
        ));
        array_push($a_column, array(
            'title' => '总价',
            'field' => 'od_fee'
        ));
        array_push($a_column, array(
            'title' => '总优惠金额',
            'field' => 'od_discount_fee'
        ));
        array_push($a_column, array(
            'title' => '平台承担',
            'field' => 'od_platform_rate'
        ));
        array_push($a_column, array(
            'title' => '商户承担',
            'field' => 'od_shop_rate'
        ));
        
        return array(
            'column' => $a_column,
            'record' => $o_result->result()
        );
    }
    
    
}
