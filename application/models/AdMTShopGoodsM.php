<?php
/**
 * Description of AdMTShopGoodsM
 *
 * @author Vincent
 */
class AdMTShopGoodsM extends CI_Model {

    var $__table_name = 'shop_goods_mt';
    var $__tbn_cache_goods = "cache_shop_goods_mt";
    

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
        $this->load->dbforge();
        
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

    /**
     * 获得临时表名称
     * @return string
     */
    function _getTempTableName() {
        $this->load->library('session');
        $s_userid = $this->session->userdata('s_id');
        $s_time = date("YmdHis", time());
        $s_table_name_temp = $s_userid . '_' . $s_time;
        return $s_table_name_temp;
    }

    /**
     * 创建临时预览表
     * @param type $s_table_name
     */
    function _createTempTable($s_table_name) {
        $this->dbforge->add_field($this->fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table("temp_$s_table_name");
    }

    /**
     * 加载临时表
     * @param type $s_table_name
     * @return type
     */
    function _loadTempTable($s_table_name) {
        $o_result = $this->db->query("SELECT * FROM temp_$s_table_name");
        return $o_result->result();
    }

    /**
     * 删除表
     * @param type $s_table_name    表名
     */
    function _dropTempTable($s_table_name) {
        $this->dbforge->drop_table("temp_$s_table_name");
    }

    /**
     * 获得列表
     * @return type
     */
    function getList($i_page, $i_rows, $s_org_id, $s_goods_name, $s_barcode, $s_filter_storage, $s_filter_up) {
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getWhere($s_org_id, $s_goods_name, $s_barcode, $s_filter_storage, $s_filter_up);
        $s_sql = "SELECT *,bs_shop_name sgm_shop_name "
                . "FROM $this->__table_name LEFT JOIN base_shop_info ON sgm_bs_m_id=bs_m_id "
                . "$s_where ORDER BY sgm_bs_m_id ASC,sgm_count_new DESC LIMIT $i_start,$i_rows";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }

    function getWhere($s_org_id, $s_goods_name, $s_barcode, $s_filter_storage, $s_filter_up) {
        $s_where = "WHERE 1=1 ";
        if ($s_org_id != '') {
            $s_where .= "AND sgm_bs_m_id = $s_org_id ";
        }
        if ($s_goods_name != '') {
            $s_where .= "AND sgm_gname LIKE '%$s_goods_name%' ";
        }
        if ($s_barcode != '') {
            $b_pos = strpos($s_barcode, ',');
            if ($b_pos) { 
                $s_barcode = str_replace(",", "','", $s_barcode);
                $s_where .= "AND sgm_barcode IN ('$s_barcode') ";
            } else {
                $s_where .= "AND sgm_barcode LIKE '%$s_barcode%' ";
            }
        }
        if ($s_filter_storage == 'NON_ZERO'){
            $s_where .= "AND sgm_count > 0 ";
        }else if ($s_filter_storage == 'DIFF'){
            $s_where .= "AND sgm_count <> sgm_count_new ";
        }
        if ($s_filter_up == '1'){
            $s_where .= "AND sgm_online=1 ";
        }else if ($s_filter_up == '0'){
            $s_where .= "AND sgm_online=0 ";            
        }
        return $s_where;
    }

    function _getTotal($s_where = '') {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->__table_name $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num - 0;
    }

    function outputNewGoods($s_mt_id) {
        $s_where = $s_mt_id === null || $s_mt_id === '' ? '' : "WHERE csgm_shop_id='$s_mt_id'";
        $s_sql = "SELECT csgm_code,csgm_barcode,csgm_name,csgm_count,csgm_sale_price,"
                . "csgm_settlement_price,csgm_datetime,csgm_shop_name,csgm_shop_id "
                . "FROM $this->__tbn_cache_goods $s_where "
                . "ORDER BY csgm_shop_id,csgm_settlement_price ASC,csgm_sale_price ASC ";
        $o_datalist = $this->db->query($s_sql);        
        $a_datalist = $o_datalist->result();
        $s_shop_name = $s_mt_id === null || $s_mt_id === '' ? '所有' : $a_datalist[0]->csgm_shop_name;
        $s_date = date("Y-m-d", time());
        $s_filename = "美团-[$s_shop_name]-未上线商品($s_date)";
        
        $a_column = array();
        array_push($a_column, array(
            'title' => '门店ID',
            'field' => 'csgm_shop_id'
        ));
        array_push($a_column, array(
            'title' => '门店名称',
            'field' => 'csgm_shop_name'
        ));
        array_push($a_column, array(
            'title' => '条形码',
            'field' => 'csgm_barcode'
        ));
        array_push($a_column, array(
            'title' => '商品名称',
            'field' => 'csgm_name'
        ));
        array_push($a_column, array(
            'title' => '数量',
            'field' => 'csgm_count'
        ));
        array_push($a_column, array(
            'title' => '零售价',
            'field' => 'csgm_sale_price'
        ));
        array_push($a_column, array(
            'title' => '结算价',
            'field' => 'csgm_settlement_price'
        ));
        
        $this->load->library('Php_spread_sheet_lib');
        $this->php_spread_sheet_lib->exportNewGoods($s_filename,$a_datalist,$a_column);
    }
        
    function getNewGoods($s_mt_id) {
        // 超级慢查询！！！！
//        $s_sql = "SELECT bssy_org_name,bgs_name,bgs_barcode,bssy_count,"
//                . "bbp_yj_sale_price,bbp_settlement_price FROM base_shop_storage_yj "
//                . "LEFT JOIN base_shop_info ON bs_org_sn = bssy_org_code "
//                . "LEFT JOIN base_goods_yj ON bssy_barcode=bgs_barcode "
//                . "LEFT JOIN base_balance_price ON bssy_barcode=bbp_bar_code "
//                . "WHERE bs_e_id='$s_mt_id' AND bgs_sale_online=1 "
//                . "AND bssy_barcode NOT IN (SELECT sgm_barcode FROM shop_goods_mt "
//                . "WHERE sgm_bs_m_id='$s_mt_id')";
        $s_where = $s_mt_id === null || $s_mt_id === '' ? '' : "WHERE csgm_shop_id='$s_mt_id'";
        $s_sql = "SELECT csgm_code,csgm_barcode,csgm_name,csgm_count,csgm_sale_price,"
                . "csgm_settlement_price,csgm_datetime,csgm_shop_name,csgm_shop_id "
                . "FROM $this->__tbn_cache_goods $s_where "
                . "ORDER BY csgm_shop_id,csgm_settlement_price ASC,csgm_sale_price ASC ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
        
    /**
     * 获得店铺商品
     * @param type $s_shop_id
     * @return type
     */
    function getShopGoods($s_shop_id) {
        $s_sql = "SELECT sgm_gid gid,sgm_gname gname FROM $this->__table_name "
                . "WHERE sgm_bs_m_id='$s_shop_id' ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }

    /**
     * 备份表
     * @param type $s_table_name
     */
    function _backupTable($s_table_name) {
        $_s_backup_name = $s_table_name . '_' . date("YmdHis", time()) . '.sql';
        $prefs = array(
            'tables' => array($s_table_name), // 你要备份的表，如果留空将备份所有的表。
            'ignore' => array(), // 你要忽略备份的表。
            'format' => 'txt', // 导出文件的格式。gzip, zip, txt
            'filename' => $_s_backup_name, // 备份文件名。如果你使用了 zip 压缩这个参数是必填的。
            'add_drop' => TRUE, // 是否在导出的 SQL 文件里包含 DROP TABLE 语句
            'add_insert' => TRUE, // 是否在导出的 SQL 文件里包含 INSERT 语句
            'newline' => "\n"                     // 导出的 SQL 文件使用的换行符
        );
        $this->load->dbutil();
        $backup = $this->dbutil->backup($prefs);

        // Load the file helper and write the file to your server
        $this->load->helper('file');
        write_file("./backup/sql/$_s_backup_name", $backup);
    }

    /**
     * 更新库存
     * @param type $s_shop_id
     * @return string
     */
    function updateStorage($s_shop_id = '') {
        log_message('debug', "美团店铺库存管理-更新库存");
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        $s_where = $s_shop_id == '' ? '' : "WHERE sgm_bs_m_id='$s_shop_id' ";
        $s_sql = "UPDATE v_shop_goods_mt_unfreeze SET sgm_count=sgm_count_new $s_where";
        log_message('debug', "SQL文:$s_sql");
        try {
            $this->db->query($s_sql);
        } catch (Exception $e) {
            log_message('error', '[美团店铺库存管理-更相信库存]时发生错误！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "[美团店铺库存管理-刷新库存]时发生错误！\r\n" . $e->getMessage();
            return $o_result;
        }
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:" . $i_rows);
        $o_result['state'] = true;
        $o_result['msg'] = "美团店铺库存管理 <br>更新库存-完成<br>受影响记录数:$i_rows";
        return $o_result;
    }

    /**
     * 刷新库存
     * @param string $s_shop_id
     * @return array
     */
    function refreshStorage($s_shop_id = '') {
        log_message('debug', "美团店铺库存管理-刷新库存");
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        $s_where = $s_shop_id == '' ? '' : "WHERE sgm_bs_m_id='$s_shop_id' ";
        $s_sql_update0 = "UPDATE $this->__table_name SET sgm_count_new=0 $s_where";
        log_message('debug', "SQL文:$s_sql_update0");
        try {
            $this->db->query($s_sql_update0);
        } catch (Exception $e) {
            log_message('error', '[美团店铺库存管理-刷新库存-重置库存]时发生错误！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "[美团店铺库存管理-刷新库存-重置库存]时发生错误！\r\n" . $e->getMessage();
            return $o_result;
        }
        $s_sql_update = "UPDATE $this->__table_name INNER JOIN base_shop_storage_yj "
                . "ON bssy_org_code = sgm_bs_org_sn AND bssy_barcode = sgm_barcode "
                . "SET sgm_count_new = bssy_count $s_where";
        log_message('debug', "SQL文:$s_sql_update");
        try {
            $this->db->query($s_sql_update);
        } catch (Exception $e) {
            log_message('error', '[美团店铺库存管理-刷新库存-更新库存]时发生错误！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "[美团店铺库存管理-刷新库存-更新库存]时发生错误！\r\n" . $e->getMessage();
            return $o_result;
        }
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:" . $i_rows);
        $o_result['state'] = true;
        $o_result['msg'] = "美团店铺库存管理 <br>刷新库存-完成<br>受影响记录数:$i_rows";
        $this->refreshStorageDiffFlag();
        return $o_result;
    }

    /**
     * 刷新库存差异标志
     */
    private function refreshStorageDiffFlag()
    {
        $s_sql_reset = "UPDATE shop_goods_mt SET sgm_count_diff=0 ";
        log_message('debug', "SQL文:$s_sql_reset");
        try {
            $this->db->query($s_sql_reset);
        } catch (Exception $e) {
            log_message('error', '[美团店铺库存管理-刷新库存差异标志-重置]时发生错误！\r\n' . $e->getMessage());
        }
        $i_rows_reset = $this->db->affected_rows();
        log_message('debug', "受影响记录数:" . $i_rows_reset);

        $s_sql_diff = "UPDATE shop_goods_mt SET sgm_count_diff=1 WHERE sgm_count <> sgm_count_new ";
        log_message('debug', "SQL文:$s_sql_diff");
        try {
            $this->db->query($s_sql_diff);
        } catch (Exception $e) {
            log_message('error', '[美团店铺库存管理-刷新库存差异标志-有差异]时发生错误！\r\n' . $e->getMessage());
        }
        $i_rows_diff = $this->db->affected_rows();
        log_message('debug', "受影响记录数:" . $i_rows_diff);
    }

    /**
     * 刷新零售价
     * @param string $s_shop_id
     * @return array
     */
    public function refreshPrice($s_shop_id = '')
    {
        log_message('debug', "美团店铺库存管理-刷新库存");
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        $s_where = $s_shop_id == '' ? '' : "WHERE sgm_bs_m_id='$s_shop_id' ";
        $s_sql_update0 = "UPDATE $this->__table_name SET sgm_price_new=0 $s_where";
        log_message('debug', "SQL文:$s_sql_update0");
        try {
            $this->db->query($s_sql_update0);
        } catch (Exception $e) {
            log_message('error', '[美团店铺库存管理-刷新零售价-重置库存]时发生错误！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "[美团店铺库存管理-刷新零售价-重置库存]时发生错误！\r\n" . $e->getMessage();
            return $o_result;
        }
        $s_sql_update = "UPDATE $this->__table_name INNER JOIN base_shop_storage_yj "
            . "ON bssy_org_code = sgm_bs_org_sn AND bssy_barcode = sgm_barcode "
            . "SET sgm_price_new = bssy_sale_price $s_where";
        log_message('debug', "SQL文:$s_sql_update");
        try {
            $this->db->query($s_sql_update);
        } catch (Exception $e) {
            log_message('error', '[美团店铺库存管理-刷新零售价-更新库存]时发生错误！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "[美团店铺库存管理-刷新零售价-更新库存]时发生错误！\r\n" . $e->getMessage();
            return $o_result;
        }
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:" . $i_rows);
        $o_result['state'] = true;
        $o_result['msg'] = "美团店铺库存管理 <br>刷新零售价-完成<br>受影响记录数:$i_rows";
        $this->refreshPriceDiffFlag();
        return $o_result;
    }

    /**
     * 刷新零售价差异标志
     */
    public function refreshPriceDiffFlag()
    {
        $s_sql_reset = "UPDATE shop_goods_mt SET sgm_price_diff=0 ";
        log_message('debug', "SQL文:$s_sql_reset");
        try {
            $this->db->query($s_sql_reset);
        } catch (Exception $e) {
            log_message('error', '[美团店铺库存管理-刷新零售价差异标志-重置]时发生错误！\r\n' . $e->getMessage());
        }
        $i_rows_reset = $this->db->affected_rows();
        log_message('debug', "受影响记录数:" . $i_rows_reset);

        $s_sql_diff = "UPDATE shop_goods_mt SET sgm_price_diff=1 WHERE sgm_price <> sgm_price_new AND sgm_price_new > 0";
        log_message('debug', "SQL文:$s_sql_diff");
        try {
            $this->db->query($s_sql_diff);
        } catch (Exception $e) {
            log_message('error', '[美团店铺库存管理-刷新零售价差异标志-有差异]时发生错误！\r\n' . $e->getMessage());
        }
        $i_rows_diff = $this->db->affected_rows();
        log_message('debug', "受影响记录数:" . $i_rows_diff);
    }


}
