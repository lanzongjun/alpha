<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdEBShop
 *
 * @author Vincent
 */
class AdYJGoodsInfoM extends CI_Model {

    var $__table_name = 'base_goods_yj';
    var $__out_file_root = '/output/output_yjgi_csv/';
        
    var $__ENUM_DISPATCHING_WEEK = 'WEEK';         //周配
    var $__ENUM_DISPATCHING_HMONTH = 'H_MONTH';    //半月配
    
    var $__ENUM_SALE_CAN = '1';                 //可销售
    var $__ENUM_SALE_NOT = '0';                 //不可售
    var $__ENUM_SALE_UNKNOW = '-1';             //未知
    
    var $__ENUM_STATE_NORMAL = 'NORMAL';        //正常
    var $__ENUM_STATE_DELETE = 'DELETE';        //删除
    var $__ENUM_STATE_NEW = 'NEW';              //新增

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
        $this->load->dbforge();
        $s_date = date("Ymd", time());
        $s_out_file_root = "$this->__out_file_root$s_date/";
        if (!file_exists(".$s_out_file_root")) {
            mkdir(".$s_out_file_root");            
        }
        $this->__out_file_root = $s_out_file_root;
    }

    /**
     * 强制转码为UTF8
     * @param type $s_str
     * @return type
     */
    function _encodeUTF8($s_str) {
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
     * 获得列表
     * @return type
     */
    function getList($i_page, $i_rows,$s_goods_name,$s_barcode,$s_dt,$s_sale,$s_state) {
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getWhere($s_goods_name,$s_barcode,$s_dt,$s_sale,$s_state);
        $s_sql = "SELECT bgs_id ck,bgs_code,bgs_barcode,bgs_dispatching,bgs_name,"
                ."bgs_sale_online,bgs_state,bgs_update_dt,bgs_band_id,bgs_sales_min,"
                ."bgs_package,bgs_purchase_min,bgs_production,bgs_biz_pattern,"
                ."bgs_expire_exchange,bgs_storage_time,bgs_storage_form FROM $this->__table_name $s_where "
                . "ORDER BY bgs_update_dt DESC LIMIT $i_start,$i_rows ";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }

    function getWhere($s_goods_name,$s_barcode,$s_dt,$s_sale,$s_state){
        $s_where = "WHERE 1=1";
        if ($s_goods_name != '') {
            $s_where .= " AND bgs_name LIKE '%$s_goods_name%'";
        }
        if ($s_barcode != '') {
            $s_where .= " AND bgs_barcode LIKE '%$s_barcode%'";
        }
        if ($s_dt != '') {
            if ($s_dt == $this->__ENUM_DISPATCHING_WEEK){                
                $s_where .= " AND bgs_dispatching='周配'";
            }else if ($s_dt == $this->__ENUM_DISPATCHING_HMONTH){                
                $s_where .= " AND bgs_dispatching='半月配'";
            }
        }
        if ($s_sale != '') {
            if ($s_sale == $this->__ENUM_SALE_CAN || 
                    $s_sale == $this->__ENUM_SALE_NOT ||
                    $s_sale == $this->__ENUM_SALE_UNKNOW){                
                $s_where .= " AND bgs_sale_online=$s_sale";
            }
        }
        if ($s_state != '') {
            if ($s_state == $this->__ENUM_STATE_NORMAL || 
                    $s_state == $this->__ENUM_STATE_DELETE || 
                    $s_state == $this->__ENUM_STATE_NEW){                
                $s_where .= " AND bgs_state='$s_state'";
            }
        }        
        return $s_where;
    }
    
    function _getTotal($s_where) {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->__table_name $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num - 0;
    }
    
    function getPriceInfo($s_barcode) {
        $s_sql = "SELECT bbp_settlement_price,bbp_yj_sale_price FROM base_balance_price WHERE bbp_bar_code='$s_barcode'";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function getOfflineInfo($s_barcode) {
        $s_sql = "SELECT bssy_org_name,bssy_count FROM base_shop_storage_yj WHERE bssy_barcode='$s_barcode'";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function getOnlineInfo($s_barcode) {
        $s_sql = "SELECT bs_shop_name sge_shop_name,sge_price,sge_count "
                . "FROM shop_goods_eb LEFT JOIN base_shop_info ON bs_org_sn=sge_bs_org_sn "
                . "WHERE sge_barcode='$s_barcode'";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function doChangeCanSale($a_post) {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        log_message('debug', '批量设置商品可售状态');
        $s_sql = "UPDATE $this->__table_name SET "
                . "bgs_sale_online=".$a_post['bgs_sale_online']
                ." WHERE bgs_id IN (".$a_post['bat_sale_ids'].")";
        log_message('debug', "SQL文:$s_sql");
        try {
            $this->db->query($s_sql);
            $i_rows = $this->db->affected_rows();
        } catch (Exception $ex) {
            log_message('error', '批量设置商品可售状态-异常中断！\r\n' . $ex->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "批量设置商品可售状态-异常中断！\r\n" . $ex->getMessage();
            return $o_result;
        }
        log_message('debug', "受影响记录数:$i_rows");
        $o_result['state'] = $i_rows > 0;
        $o_result['msg'] = "更新记录数 : $i_rows 条";
        return $o_result;
    }
    
    function editGoodsInfo($a_post) {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        $s_time = date("Y-m-d H:i:s", time());
        log_message('debug', '编辑总商品库商品信息');
        $s_sql = "UPDATE $this->__table_name SET "
                . "bgs_barcode='".$a_post['bgs_barcode']
                . "',bgs_name='".$this->_encodeUTF8($a_post['bgs_name'])
                . "',bgs_sale_online=".$a_post['bgs_sale_online']
                . " ,bgs_update_dt='$s_time' "
                . "WHERE bgs_code='".$a_post['bgs_code']."'";
        log_message('debug', "SQL文:$s_sql");
        try {
            $this->db->query($s_sql);
            $i_rows = $this->db->affected_rows();
        } catch (Exception $ex) {
            log_message('error', '编辑商品库信息-异常中断！\r\n' . $ex->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "编辑商品库信息-异常中断！\r\n" . $ex->getMessage();
            return $o_result;
        }
        log_message('debug', "受影响记录数:$i_rows");
        $o_result['state'] = $i_rows == 1;
        $o_result['msg'] = "更新记录数 : $i_rows 条";
        return $o_result;
    }
    /**
     * 易捷总商品库
     * @param type $s_goods_name
     * @param type $s_barcode
     * @param type $s_dt
     * @param type $s_sale
     * @param type $s_state
     * @return type
     */
    function outputCSV($s_goods_name,$s_barcode,$s_dt,$s_sale,$s_state) {
        $o_res = array('filename' => '','filepath' => '');
        $s_where = $this->getWhere($s_goods_name,$s_barcode,$s_dt,$s_sale,$s_state);        
        $s_sql = "SELECT bgs_id `id`,bgs_class1 `商品大类`,bgs_class2 `商品中类`,	"
                . "bgs_class3 `商品小类`,bgs_band_id `品牌`,bgs_code `商品编码`,"
                . "bgs_name `商品名称`,bgs_sales_min `最小销售单位`,bgs_package `包装规格`,"
                . "bgs_purchase_min `最小要货量`,bgs_production `产地`,"
                . "bgs_biz_pattern `经营方式`,bgs_expire_exchange `临期是否退换货`,"
                . "bgs_storage_time `保质期`,bgs_dispatching `商品配送类型`,"
                . "bgs_storage_form `商品存储方式`,bgs_barcode `条形码`,"
                . "bgs_sale_online `线上可销售`,bgs_state `状态(新增,正常,删除)`,"
                . "bgs_update_dt `更新时间` FROM $this->__table_name $s_where ";
        
        $o_result = $this->db->query($s_sql);

        $s_result = $this->dbutil->csv_from_result($o_result);

        $this->load->helper('file');
        $s_datetime = date("ymd_His", time());
        $s_filename = "$s_datetime.易捷商品库.csv";
        if (!file_exists(".$this->__out_file_root")) {
            mkdir(".$this->__out_file_root");
        }
        $s_csv_path = ".$this->__out_file_root$s_filename";
        write_file($s_csv_path, $s_result);
        $o_res['filename'] = $s_filename;
        $o_res['filepath'] = $s_csv_path;
        return $o_res;
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
     * 删除表
     * @param type $s_table_name    表名
     */
    function _dropTempTable($s_table_name) {
        $this->dbforge->drop_table("temp_$s_table_name");
    }

}
