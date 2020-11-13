<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 延迟结算
 *
 * @author Vincent
 */
class AdDelayBalanceM extends CI_Model {

    var $__table_name_conf = 'delay_balance_conf';
    var $__table_name_shop = 'delay_balance_shop';

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

    /**
     * 获得列表
     * @return type
     */
    function getList() {
        $o_result = $this->db->query("SELECT * FROM $this->__table_name_conf");
        return $o_result->result();
    }

    /**
     * 获得详细信息
     * @return type
     */
    function getDetail($s_dbc_id) {
        $o_result = $this->db->query("SELECT * FROM $this->__table_name_shop WHERE dbs_dbc_id='$s_dbc_id'");
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
     * 导出CSV
     * @return type
     */
    function _outputCSV() {
        $this->load->dbutil();

        $query = $this->db->query("SELECT * FROM $this->__table_name");

        return $this->dbutil->csv_from_result($query);
    }

    /**
     * 导出XML
     * @return type
     */
    function _outputXML() {
        $this->load->dbutil();
        $query = $this->db->query("SELECT * FROM $this->__table_name");
        $config = array(
            'root' => 'root',
            'element' => 'element',
            'newline' => "\n",
            'tab' => "\t"
        );
        return $this->dbutil->xml_from_result($query, $config);
    }

    /**
     * 追加数据
     * @param type $bgs_barcode 条形码
     * @param type $dbc_reason  延期原因
     * @return type             
     */
    function doAppendConf($bgs_barcode, $dbc_reason) {
        //执行追加
        $s_sql = "INSERT INTO $this->__table_name_conf (dbc_bgs_code,dbc_bgs_barcode,"
                . "dbc_bgs_name,dbc_reason,dbc_update_time) "
                . "SELECT sn,tcode,tfrom,e_code,city,shop_name,shop_id,deliver,order_state,"
                . "refund_state,order_invalid,order_create,arrive_time,shop_receive,order_finish,order_amount,"
                . "cus_pay,shop_receive_amount,commission,claim_settlement,goods_price,goods_name,sku_code,"
                . "goods_cusid,onsale_before,onsale_after,buy_count,memo,deliver_fee,deliver_free,package_fee,"
                . "subsidy_shop_promotion,is_present,subsidy_shop_ticket,subsidy_shop_packet,subsidy_shop_deliver,"
                . "subsidy_shop_gift,subsidy_platform_promotion,subsidy_platform_ticket,subsidy_platform_packet,"
                . "subsidy_platform_deliver,subsidy_platform_gift,subsidy_cus_ticket,express_cmp,express_code FROM temp_$s_table_name";
        $this->db->query($s_sql);
        log_message('info', "SQL文:$s_sql \r\n 受影响记录数:".$this->db->affected_rows());
        return $this->db->insert_id();
    }
    
    /**
     * 添加延迟结算详情
     * 当前为结算模块内部调用
     * @param type $dbc_id      延迟配置ID
     * @param type $bs_org_sn   海信编码
     * @param type $bs_sale_sn  销售编码
     * @param type $dbs_count   销售数量
     * @param type $dbs_ba_id   结算ID
     * @param type $dbs_balance_date    结算日期
     * @return type             受影响记录数
     */
    function _doAppendShop($dbc_id,$bs_org_sn,$bs_sale_sn,$dbs_count,$dbs_ba_id,$dbs_balance_date) {
        $s_sql = "INSERT INTO $this->__table_name_shop (dbs_dbc_id,dbs_bgs_code,"
                . "dbs_bgs_barcode,dbs_bgs_name,dbs_bs_org_sn,dbs_bs_sale_sn,"
                . "dbs_count,dbs_ba_id,dbs_balance_date) "
                . "SELECT dbc_id,dbc_bgs_code,dbc_bgs_barcode,dbc_bgs_name,"
                . "$bs_org_sn,$bs_sale_sn,$dbs_count,$dbs_ba_id,'$dbs_balance_date' "
                . "FROM $this->__table_name_conf WHERE dbc_id=$dbc_id";
        $this->db->query($s_sql);
        $i_rows = $this->db->affected_rows();
        log_message('info', "SQL文:$s_sql \r\n 受影响记录数:".$i_rows);
        return $i_rows;
    }

}
