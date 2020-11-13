<?php

/**
 * Description of AdYJOnSaleM
 *
 * @author Vincent
 */
class AdYJOnSaleM extends CI_Model {

    var $__table_name_conf = 'onsale_yj_conf';
    var $__table_name_shop = 'onsale_yj_shop';
    var $__i_expire_day = 5;

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
    function getList($i_page,$i_rows) {        
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_sql = "SELECT * FROM $this->__table_name_conf ORDER BY oyc_bs_org_sn ASC, oyc_update_time DESC "
                . "LIMIT $i_start,$i_rows";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getTotal();
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }
        
    function _getTotal() {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->__table_name_conf ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num-0;
    }
    
    /**
     * 追加临期促销
     * @param type $s_id
     * @return string
     */
    function appendOnSaleByExpire($s_id) {
        $o_result = array('state' => true, 'msg' => '');
        $a_list = $this->getExpire($s_id);
        if (count($a_list) != 1) {
            $o_result['state'] = false;
            $o_result['msg'] = "此商品缺少条形码";
            return $o_result;
        }

        $o_line = $a_list[0];
        $gey_org_sn = $o_line->gey_org_sn;
        $bs_sale_sn = $o_line->bs_sale_sn;
        $gey_shop_name = $o_line->gey_shop_name;
        $gey_yj_code = $o_line->gey_yj_code;
        $bgs_barcode = $o_line->bgs_barcode;
        $gey_goods_name = $o_line->gey_goods_name;
        $gey_count = $o_line->gey_count;
        $gey_price = $o_line->gey_price;
        $gey_expiration_date = $o_line->gey_expiration_date;
        $gey_deal_way = '临期促销-'.$o_line->gey_deal_way;

        $a_osyj = $this->getOnSaleYJ($gey_org_sn, $bgs_barcode);
        if (count($a_osyj) > 0){
            $o_result['state'] = false;
            $o_result['msg'] = "此店铺的商品已经存在";
            return $o_result;
        }
        
        //活动截止日期
        $onsale_end_date = date('Y-m-d',strtotime("-$this->__i_expire_day day", strtotime($gey_expiration_date)));

        //活动价格
        $os_price = $gey_price / $gey_count;
        //舍去法取两位小数
        $onsale_price = floor($os_price / 0.01) / 100;

        $s_sql = "INSERT INTO onsale_yj_conf (oyc_bs_org_sn,oyc_bs_sale_sn,"
                . "oyc_shop_name,oyc_bgs_code,oyc_bgs_barcode,oyc_goods_name,oyc_count,"
                . "oyc_balance_price,oyc_reason,oyc_end_date,oyc_is_close,"
                . "oyc_update_time) VALUES($gey_org_sn,$bs_sale_sn,'$gey_shop_name',"
                . "'$gey_yj_code','$bgs_barcode','$gey_goods_name',$gey_count,"
                . "$onsale_price,'$gey_deal_way','$onsale_end_date',"
                . "1,'" . date("Y-m-d H:i:s", time()) . "')";
        
        $this->db->query($s_sql);
        $i_rows = $this->db->affected_rows();
        $o_result['state'] = true;
        $o_result['msg'] = "affected rows is $i_rows";
        return $o_result;
    }

    /**
     * 获得指定店指定商品的活动
     * @param type $i_org_sn
     * @param type $s_barcode
     * @return type
     */
    function getOnSaleYJ($i_org_sn, $s_barcode) {
        $s_sql = "SELECT * FROM onsale_yj_conf WHERE oyc_bs_org_sn=$i_org_sn "
                . "AND oyc_bgs_barcode='$s_barcode'";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    /**
     * 获得临期商品
     * @param type $s_id
     * @return type
     */
    function getExpire($s_id) {
        $s_sql = "SELECT gey_org_sn,bs_sale_sn,gey_shop_name,gey_yj_code,bgs_barcode,"
                . "gey_goods_name,gey_count,gey_price,gey_keep_month,gey_expiration_date,"
                . "gey_deal_way FROM goods_expire_yj "
                . "LEFT JOIN base_shop_info ON gey_org_sn = bs_org_sn "
                . "LEFT JOIN base_goods_yj ON gey_yj_code = bgs_code "
                . "WHERE bgs_barcode IS NOT NULL AND GEY_ID = $s_id";
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

}
