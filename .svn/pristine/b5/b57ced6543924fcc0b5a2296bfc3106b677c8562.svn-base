<?php

/**
 * AdBalanceOnSaleYJM
 * 结算临期库-每店
 * @author Vincent
 */
class AdBalanceOnSaleYJM extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
    }

    /**
     * 获得临期库列表
     * @param type $ba_id
     * @return type
     */
    function getYJList($ba_id) {
        $s_sql = "SELECT oys_count,oyc_goods_name,oyc_count,bs_shop_sn,"
                . "oyc_balance_price,oyc_reason,oyc_end_date "
                . "FROM onsale_yj_shop "
                . "LEFT JOIN onsale_yj_conf ON oyc_id = oys_oyc_id "
                . "LEFT JOIN base_shop_info ON bs_org_sn = oyc_bs_org_sn "
                . "WHERE oys_ba_id = $ba_id";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }

    /**
     * 结算临期库
     * @param type $ba_id
     */
    function balOnSaleYJ($ba_id) {
        log_message('debug', '结算临期库');
        $s_date_cur = date("Y-m-d", time());
        /* 关闭过期活动 */
        $s_sql1 = "UPDATE onsale_yj_conf SET oyc_is_close = 1 WHERE oyc_end_date < '$s_date_cur' ";
        log_message('debug', "SQL文:$s_sql1");
        $i_rows = 0;
        try {
            $this->db->query($s_sql1);
            $i_rows = $this->db->affected_rows();
        } catch (Exception $ex) {
            throw $ex;
        }
        log_message('debug', "受影响记录数:$i_rows");

        /* 获得当前易捷促销的结算价、总库存量和当天销售量 */
        log_message('debug', "获得当前易捷促销的结算价、总库存量和当天销售量");
        $s_sql2 = "SELECT oyc_id,oyc_count,oyc_balance_price,bss_count,"
                . "oyc_bs_org_sn,oyc_bgs_barcode "
                . "FROM onsale_yj_conf INNER JOIN balance_shop_storage "
                . "ON bss_bs_org_sn = oyc_bs_org_sn AND oyc_bgs_barcode = bss_bgs_barcode "
                . "WHERE oyc_is_close = 0 ";
        log_message('debug', "SQL文:$s_sql2");
        $o_result2 = $this->db->query($s_sql2);
        $a_line2 = $o_result2->result();

        foreach ($a_line2 as $o_line2) {
            //需要进行正常结算的促销品数量
            try {
                $i_sale_count = $this->_appendOnSaleYJ($ba_id, $o_line2->oyc_id, $o_line2->oyc_count, $o_line2->bss_count);
            } catch (Exception $ex) {
                throw $ex;
            }
            if ($i_sale_count > 0) {
                try {
                    $this->_modifyBalDetail($o_line2->oyc_bs_org_sn, $o_line2->oyc_bgs_barcode, $i_sale_count, $ba_id);
                } catch (Exception $ex) {
                    throw $ex;
                }
            } else {
                try {
                    $this->_delBalDetail($o_line2->oyc_bs_org_sn, $o_line2->oyc_bgs_barcode, $ba_id);
                } catch (Exception $ex) {
                    throw $ex;
                }
            }
        }
    }

    /**
     * 处理临期促销历史
     * @param type $ba_id       结算ID
     * @param type $i_oyc_id    易捷促销ID
     * @param type $i_oyc_count 促销总库存
     * @param type $i_bss_count 当日销售量
     * @return int              不可进行促销销售的数量
     */
    function _appendOnSaleYJ($ba_id, $i_oyc_id, $i_oyc_count, $i_bss_count) {
        log_message('debug', '处理临期促销历史');
        /* 获得此促销的历史销售量 */
        $s_sql1 = "SELECT SUM(oys_count) oys_c FROM onsale_yj_shop "
                . "WHERE oys_oyc_id = $i_oyc_id ";
        log_message('debug', "SQL文:$s_sql1");
        $o_result1 = $this->db->query($s_sql1);

        $a_line1 = $o_result1->result();
        $i_hist_count = $a_line1[0]->oys_c;

        //剩余库存
        $i_surplus = $i_oyc_count - $i_hist_count;
        //剩余库存足够销售
        if ($i_surplus > $i_bss_count) {
            try {
                $this->_appendHistOnSaleYJ($ba_id, $i_oyc_id, $i_bss_count);
            } catch (Exception $ex) {
                throw $ex;
            }
            return 0;
            //剩余库存不足以销售
        } else {
            try {
                $this->_appendHistOnSaleYJ($ba_id, $i_oyc_id, $i_surplus);
            } catch (Exception $ex) {
                throw $ex;
            }
            return $i_bss_count - $i_surplus;
        }
    }

    /**
     * 追加临期促销历史
     * @param type $ba_id       结算ID
     * @param type $i_oyc_id    促销ID
     * @param type $i_oys_count 销售数量
     * @return type             受影响记录数
     */
    function _appendHistOnSaleYJ($ba_id, $i_oyc_id, $i_oys_count) {
        log_message('debug', '追加临期促销历史');
        $s_sql1 = "INSERT INTO onsale_yj_shop (oys_oyc_id,oys_count,oys_sale_date_begin,"
                . "oys_sale_date_end,oys_update_time,oys_ba_id) "
                . "SELECT $i_oyc_id,$i_oys_count,ba_balance_date_begin,ba_balance_date_end,'" 
                . date("Y-m-d H:i:s", time()) . "', $ba_id FROM balance_account WHERE ba_id=$ba_id ";
        log_message('debug', "SQL文:$s_sql1");
        $i_rows = 0;
        try {
            $this->db->query($s_sql1);
            $i_rows = $this->db->affected_rows();
        } catch (Exception $ex) {
            throw $ex;
        }
        log_message('debug', "受影响记录数:$i_rows");
        return $i_rows;
    }

    /**
     * 修改原价结算数量
     * @param type $i_bs_org_sn
     * @param type $i_bgs_barcode
     * @param type $i_count
     * @param type $ba_id
     * @return type
     */
    function _modifyBalDetail($i_bs_org_sn, $i_bgs_barcode, $i_count, $ba_id) {
        log_message('debug', '修改原价结算数量');
        $s_sql = "UPDATE balance_account_detail SET bad_count=$i_count "
                . "WHERE bad_ba_id=$ba_id AND bad_bs_org_sn=$i_bs_org_sn AND bad_bgs_barcode='$i_bgs_barcode'";
        log_message('debug', "SQL文:$s_sql");
        $i_rows = 0;
        try {
            $this->db->query($s_sql);
            $i_rows = $this->db->affected_rows();
        } catch (Exception $ex) {
            throw $ex;
        }
        log_message('debug', "受影响记录数:$i_rows");
        return $i_rows;
    }

    /**
     * 删除原价结算明细
     * @param type $i_bs_org_sn
     * @param type $i_bgs_barcode
     * @param type $ba_id
     * @return type
     */
    function _delBalDetail($i_bs_org_sn, $i_bgs_barcode, $ba_id) {
        log_message('debug', '删除原价结算明细');
        $s_sql = "DELETE FROM balance_account_detail WHERE bad_ba_id=$ba_id AND bad_bs_org_sn=$i_bs_org_sn AND bad_bgs_barcode='$i_bgs_barcode' ";
        log_message('debug', "SQL文:$s_sql");
        $i_rows = 0;
        try {
            $this->db->query($s_sql);
            $i_rows = $this->db->affected_rows();
        } catch (Exception $ex) {
            throw $ex;
        }
        log_message('debug', "受影响记录数:$i_rows");
        return $i_rows;
    }

    /**
     * 汇总临期商品结算金额
     * @param type $ba_id
     * @return type
     * @throws Exception
     */
    function countAccount($ba_id) {
        $d_count2 = 0.00;
        //临期库结算金额
        $s_sql2 = "SELECT SUM(oyc_balance_price*oys_count) account_count "
                . "FROM onsale_yj_shop LEFT JOIN onsale_yj_conf ON oyc_id = oys_oyc_id "
                . "WHERE oys_ba_id = $ba_id ";
        try {
            $o_result2 = $this->db->query($s_sql2);
            $a_line2 = $o_result2->result();
            if (count($a_line2) == 1) {
                $d_count2 = $a_line2[0]->account_count;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $d_count2;
    }

    /**
     * 追加HTML表格
     * @param type $bs_org_sn
     * @param type $ba_id
     * @param type $i_no
     * @return string
     */
    function appendHTMLTable($bs_org_sn, $ba_id, $i_no = 1) {
        //商品编码，单价，数量，金额，商品条形码，商品名称，备注
        $s_sql = "SELECT oyc_bgs_code,oyc_balance_price,oys_count,oyc_bgs_barcode,"
                . "oyc_goods_name FROM onsale_yj_shop "
                . "LEFT JOIN onsale_yj_conf ON oyc_id = oys_oyc_id "
                . "LEFT JOIN base_shop_info ON bs_org_sn = oyc_bs_org_sn "
                . "WHERE oys_ba_id = $ba_id AND oyc_bs_org_sn = $bs_org_sn ";
        log_message('debug', "获取临期商品sql文:$s_sql");
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        $s_bodys = "";
        foreach ($a_line as $o_line) {
            $s_bodys .= "<tr>";
            $s_bodys .= "<td>" . $i_no++ . "</td>";
            $s_bodys .= "<td>" . $o_line->oyc_bgs_code . "</td>";
            $s_bodys .= "<td>" . $o_line->oyc_balance_price . "</td>";
            $s_bodys .= "<td>" . $o_line->oys_count . "</td>";
            $s_bodys .= "<td>" . $o_line->oyc_bgs_barcode . "</td>";
            $s_bodys .= "<td>" . $o_line->oyc_goods_name . "</td>";
            $s_bodys .= "<td>临期</td>";
            $s_bodys .= "</tr>";
        }
        log_message('debug', "临期商品表格:$s_bodys");
        return $s_bodys;
    }

}
