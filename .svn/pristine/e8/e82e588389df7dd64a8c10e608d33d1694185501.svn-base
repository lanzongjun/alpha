<?php

/**
 * AdBalanceDelayM
 * 结算延期库-每商品
 * @author Vincent
 */
class AdBalanceDelayM extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
    }

    function getDelayList($ba_id,$bs_shop_sn=0) {
        $s_sql = "SELECT dbs_bgs_name,dbs_bgs_barcode,bs_shop_sn,dbs_count FROM delay_balance_shop "
                . "LEFT JOIN base_shop_info ON bs_org_sn = dbs_bs_org_sn "
                . "WHERE dbs_ba_id=$ba_id ";
        if ($bs_shop_sn != 0) {
            $s_sql .= " AND dbs_bs_org_sn=$bs_shop_sn";
            log_message('debug', "getDelayList SQL文:$s_sql");
        }
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }

    /**
     * 结算延期库
     * @param type $ba_id
     */
    function balDelay($ba_id) {
        log_message('debug', '结算延期库');
        $this->db->trans_start();
        $s_sql1 = "INSERT INTO delay_balance_shop (dbs_dbc_id,dbs_bgs_barcode,"
                . "dbs_bgs_name,dbs_bs_org_sn,dbs_count,dbs_ba_id) "
                . "SELECT dbc_id,dbc_bgs_barcode,dbc_bgs_name,bad_bs_org_sn,"
                . "bad_count,bad_ba_id FROM balance_account_detail "
                . "INNER JOIN delay_balance_conf ON dbc_bgs_barcode=bad_bgs_barcode "
                . "WHERE bad_ba_id=$ba_id";
        log_message('debug', "SQL文:$s_sql1");
        $this->db->query($s_sql1);
        
        $s_sql2 = "DELETE FROM balance_account_detail WHERE bad_ba_id=$ba_id "
                . "AND bad_bgs_barcode IN (SELECT dbc_bgs_barcode FROM delay_balance_conf)";
        log_message('debug', "SQL文:$s_sql2");
        $this->db->query($s_sql2);
        
        $b_result = false;
        try {
            $b_result = $this->db->trans_complete();
        } catch (Exception $ex) {
            throw $ex;
        }
        log_message('debug', "事务执行结果:$b_result");
        return $b_result;       
    }
    
    /**
     * 获得延迟库表格
     * @param type $ba_id
     * @param type $bs_shop_sn
     * @return string
     */
    function getHTMLTable($ba_id, $bs_shop_sn){
        $a_line = $this->getDelayList($ba_id, $bs_shop_sn);
        $s_bodys = "";
        $i_no = 1;
        foreach ($a_line as $o_line) {
            $s_bodys .= "<tr>";
            $s_bodys .= "<td>".$i_no++."</td>";
            $s_bodys .= "<td>".$o_line->dbs_bgs_name."</td>";
            $s_bodys .= "<td>".$o_line->dbs_count."</td>";
            $s_bodys .= "<td>".$o_line->dbs_bgs_barcode."</td>";
            $s_bodys .= "</tr>";
        }
        return $s_bodys;
    }
}
