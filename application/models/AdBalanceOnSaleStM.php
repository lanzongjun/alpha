<?php

/**
 * AdBalanceOnSaleStM
 * 结算促销库-每商品
 * @author Vincent
 */
class AdBalanceOnSaleStM extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
    }

    function getABList($ba_id, $bs_shop_sn = 0) {
        $s_sql = "SELECT oss_bgs_name,oss_bgs_barcode,bs_shop_sn,oss_trigger_count,oss_free_count "
                . "FROM onsale_storage_shop "
                . "LEFT JOIN base_shop_info ON bs_org_sn = oss_bs_org_sn "
                . "WHERE oss_ba_id=$ba_id ";
        if ($bs_shop_sn != 0) {
            $s_sql .= " AND oss_bs_org_sn=$bs_shop_sn";
            log_message('debug', "getABList SQL文:$s_sql");
        }
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }

    /**
     * 结算促销库
     * @param type $ba_id
     */
    function balOnSaleSt($ba_id) {
        log_message('debug', '结算AB库');
        $s_sql = "SELECT bad_id,bad_bs_org_sn,bad_bgs_barcode,bad_count "
                . "FROM balance_account_detail WHERE bad_ba_id=$ba_id ORDER BY bad_bs_org_sn ";
        log_message('debug', "SQL文:$s_sql");
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();

        foreach ($a_line as $o_line) {
            try {
                $this->_balOnSaleStShop($o_line->bad_id, $o_line->bad_bs_org_sn,
                        $o_line->bad_bgs_barcode, $o_line->bad_count, $ba_id);
            } catch (Exception $ex) {
                throw $ex;
            }
        }
    }

    /**
     * 结算每商户促销产品
     * @param type $bad_id
     * @param type $bs_org_sn
     * @param type $bgs_barcode
     * @param type $i_count
     * @param type $ba_id
     */
    function _balOnSaleStShop($bad_id, $bs_org_sn, $bgs_barcode, $i_count, $ba_id) {
        log_message('debug', "结算每商户促销产品。商户:$bs_org_sn 条码:$bgs_barcode 数量:$i_count ");
        $s_sql = "SELECT osc_id,osc_bgs_barcode,osc_trigger_count,osc_free_count,"
                . "SUM(oss_trigger_count) trigger_count,SUM(oss_free_count) free_count "
                . "FROM onsale_storage_conf "
                . "LEFT JOIN onsale_storage_shop ON oss_osc_id=osc_id "
                . "WHERE osc_bgs_barcode = '$bgs_barcode' "
                . "GROUP BY oss_bgs_barcode ORDER BY osc_trigger_count ASC";
        log_message('debug', "SQL文:$s_sql");
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();

        foreach ($a_line as $o_line) {
            $i_osc_trigger_count = $o_line->osc_trigger_count;
            $i_osc_free_count = $o_line->osc_free_count;
            //为NULL则置0
            $i_trigger_count = $o_line->trigger_count ? $o_line->trigger_count : 0;
            //为NULL则置0
            $i_free_count = $o_line->free_count ? $o_line->free_count : 0;

            //如果B库销售完成，则退出
            if ($i_osc_free_count <= $i_free_count) {
                log_message('debug', "B库销售完成，退出");
                continue;
            }

            //如果A库不达标，则进行A库累计
            if ($i_osc_trigger_count >= $i_trigger_count + $i_count) {
                log_message('debug', "A库不达标，进行A库累计");
                //A库累计
                $this->_addABStorage($o_line->osc_id, $o_line->osc_bgs_barcode,
                        $bs_org_sn, $i_count, 0, $ba_id, $bad_id);
                continue;
            }

            //如果A库达标，则进行B库累计
            $i_b_count = $i_trigger_count + $i_count - $i_osc_trigger_count;
            $i_a_count = $i_count - $i_b_count;
            try {
                $this->_addABStorage($o_line->osc_id, $o_line->osc_bgs_barcode,
                        $bs_org_sn, $i_a_count, $i_b_count, $ba_id, $bad_id);
            } catch (Exception $ex) {
                throw $ex;
            }
            if ($i_count > $i_b_count) {
                try {
                    //修改结算明细
                    $this->_setBalDetail($bad_id, $i_b_count);
                } catch (Exception $ex) {
                    throw $ex;
                }
            } else {
                try {
                    //如果所售商品总数等于B库销售量，则删除
                    $this->_delBalDetail($bad_id);
                } catch (Exception $ex) {
                    throw $ex;
                }
            }
            if ($i_count < $i_b_count) {
                log_message('error', 'B库销售量大于详情销售量，异常！！');
            }
        }
    }

    /**
     * 增加促销库记录
     * @param type $osc_id
     * @param type $bgs_barcode
     * @param type $bs_org_sn
     * @param type $i_trigger_count
     * @param type $i_free_count
     * @param type $ba_id
     * @param type $bad_id
     * @return type
     */
    function _addABStorage($osc_id, $bgs_barcode, $bs_org_sn, $i_trigger_count,
            $i_free_count, $ba_id, $bad_id) {
        log_message('debug', '增加AB库记录');
        $s_sql = "INSERT INTO onsale_storage_shop (oss_osc_id,oss_bgs_barcode,"
                . "oss_bgs_name,oss_bs_org_sn,oss_trigger_count,"
                . "oss_free_count,oss_bad_id,oss_ba_id) "
                . "SELECT $osc_id,'$bgs_barcode',osc_bgs_name,$bs_org_sn,"
                . "$i_trigger_count,$i_free_count,$bad_id,$ba_id "
                . "FROM onsale_storage_conf WHERE osc_id=$osc_id ";
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
     * 重设结算详情
     * @param type $bad_id
     * @param type $i_b_count
     * @return type
     */
    function _setBalDetail($bad_id, $i_b_count) {
        log_message('debug', '修改结算详情信息');
        $s_sql = "UPDATE balance_account_detail	SET bad_count=bad_count-$i_b_count "
                . "WHERE bad_id=$bad_id";
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
     * 重设结算详情
     * @param type $bad_id
     * @return type
     */
    function _delBalDetail($bad_id) {
        log_message('debug', '所售商品总数等于B库销售量,删除结算详情信息');
        $s_sql = "DELETE FROM balance_account_detail WHERE bad_id=$bad_id ";
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
     * 获得AB库表格
     * @param type $ba_id
     * @param type $bs_shop_sn
     * @return string
     */
    function getHTMLTable($ba_id, $bs_shop_sn) {
        $a_line = $this->getABList($ba_id, $bs_shop_sn);
        $s_bodys = "";
        $i_no = 1;
        foreach ($a_line as $o_line) {
            $s_bodys .= "<tr>";
            $s_bodys .= "<td>" . $i_no++ . "</td>";
            $s_bodys .= "<td>" . $o_line->oss_bgs_name . "</td>";
            $s_bodys .= "<td>" . $o_line->oss_trigger_count . "</td>";
            $s_bodys .= "<td>" . $o_line->oss_free_count . "</td>";
            $s_bodys .= "<td>" . $o_line->oss_bgs_barcode . "</td>";
            $s_bodys .= "</tr>";
        }
        return $s_bodys;
    }

}
