<?php
/**
 * Description of AdBalanceStageSendM
 *  此类为导出结算数据之后进行邮件发送的类
 *  当前未实现此类，只保留邮件发送的范例方法
 * @author Vincent
 */
class AdBalanceStageSendM extends CI_Model {

    var $__ENUM_SEND_STATE_TODO = 'todo';        //未知
    var $__ENUM_SEND_STATE_SUCCESS = 'success';  //已完结
    var $__ENUM_SEND_STATE_FAIL = 'fail';        //已取消

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
    }

    /**
     * 更新邮件发送状态
     * @param type $i_bas_id
     * @param type $b_state
     * @return type
     * @throws Exception
     */
    function updateSendState($i_bas_id, $enum_state) {
        log_message('debug', "更新邮件发送状态:[ID:$i_bas_id,STATE:$enum_state]");
        $s_sql = "UPDATE balance_account_shop SET bas_mail_send='$enum_state' "
                . "WHERE bas_id=$i_bas_id ";
        try {
            $this->db->query($s_sql);
        } catch (Exception $ex) {
            throw $ex;
        }
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:" . $i_rows);
        return $i_rows;
    }
    
    /**
     * 批量发送邮件
     * @param type $a_bas_id
     * @return string
     */
    function sendMails($a_bas_id){
        $o_res = array(
            'state' => true,
            'msg' => array()
        );
        $s_bas_ids = $a_bas_id[0];
        for ($i = 1; $i < count($a_bas_id); $i++) {
            $s_bas_ids .= ",$a_bas_id[$i]";
        }
        $s_sql = "SELECT bas_id,bas_mail_file,bas_mail_name,bas_mail_to FROM balance_account_shop "
                . "WHERE bas_id IN ($s_bas_ids) ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        $a_msg = array();
        foreach ($a_line as $o_line) {
            try {
                $b_send_state = $this->_sendMail($o_line->bas_mail_file,$o_line->bas_mail_name,
                        $o_line->bas_mail_to);
                $enum_state = $b_send_state ? $this->__ENUM_SEND_STATE_SUCCESS : $this->__ENUM_SEND_STATE_FAIL;
                array_push($a_msg,"{[$o_line->bas_mail_name] 发送状态:$enum_state}");
                $this->updateSendState($o_line->bas_id, $enum_state);
            } catch (Exception $ex) {
                log_message('error', '批量发送邮件-异常中断！\r\n' . $e->getMessage());
                $o_res['state'] = false;
                $o_res['msg'] = "批量发送邮件-异常中断！\r\n" . $e->getMessage();
                return $o_result;
            }
        }
        $o_res['msg'] = $a_msg;
        return $o_res;
    }
    
    /**
     * 发送邮件
     * @param type $s_mail_file 邮件文件
     * @param type $s_mail_name 邮件标题
     * @param type $s_mail_to   发送目标
     * @return type
     */
    function _sendMail($s_mail_file,$s_mail_name,$s_mail_to) {
        $s_mail_to = 'wangmin@iuoo.onaliyun.com,sunmengchen@iuoo.onaliyun.com';
        
        $this->load->helper('file');
        $s_html = read_file(".$s_mail_file");
        
        log_message('debug', "$s_mail_name");
        
        $this->load->library('email');
        
        $config['useragent']= "CodeIgniter";
        $config['protocol'] = 'sendmail';
        $config['mailtype'] = 'html';
        $config['mailpath'] = 'D:\\xampp\\sendmail\\sendmail.exe -t';
        $config['charset'] = 'utf-8';
        
        $config['smtp_host'] = 'smtp.mxhichina.com';
        $config['smtp_user'] = 'wangmin@iuoo.onaliyun.com';
        $config['smtp_pass'] = 'vincent@163';
        $config['smtp_port'] = '25';

        $this->email->initialize($config);        
        
        $this->email->from('wangmin@iuoo.onaliyun.com', 'Vincent Wong');
        $this->email->to($s_mail_to);
        $this->email->cc('wangmin@iuoo.onaliyun.com');//孙梦晨

        $this->email->subject($s_mail_name);
        $this->email->message($s_html);

        $b_result = $this->email->send();
        log_message('debug', $this->email->print_debugger());
        return $b_result;
    }
    
    public $__pay_account = '802320201183858';
    public $__body_empty = '<tr><td colspan=7>没有需要结算的商品</td></tr>';
    public $__body_empty_ab = '<tr><td colspan=5>无商品</td></tr>';
    public $__body_empty_delay = '<tr><td colspan=4>无商品</td></tr>';
    
    /**
     * 创建邮件文件
     * @param type $ba_id
     * @param type $s_date_begin
     * @param type $s_date_end
     */
    function newMailHTML($ba_id,$s_date_begin, $s_date_end) {        
        $this->load->helper('file');
        if ($s_date_begin == $s_date_end) {
            $s_date = $s_date_begin;
        } else {
            $s_date = "$s_date_begin~$s_date_end";
        }
        
        //TODO 目录结构应重新设置
        if(!file_exists("./output_bal/$s_date/")) {
            mkdir("./output_bal/$s_date/");
        }
        
        $a_shops = $this->_getDetailShops($ba_id);
        foreach ($a_shops as $o_line) {            
            $this->_newMailDetail($ba_id, $o_line->bss_bs_org_sn,$o_line->bs_shop_sn
                    ,$o_line->bss_bs_shop_name,$ba_id,$s_date_begin, $s_date_end);
        }
    }
    
    /**
     * 
     * @param type $ba_id
     * @return type
     */
    function _getDetailShops($ba_id) {
        $s_sql = "SELECT bss_bs_org_sn,bs_shop_sn,bss_bs_shop_name FROM balance_shop_storage "
                . "LEFT JOIN base_shop_info ON bs_org_sn = bss_bs_org_sn "
                . "WHERE bss_ba_id=$ba_id GROUP BY bss_bs_org_sn";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    /**
     * 创建邮件详情
     * @param type $i_ba_id
     * @param type $bs_org_sn
     * @param type $bs_shop_sn
     * @param type $bs_shop_name
     * @param type $ba_id
     * @param type $s_date_begin
     * @param type $s_date_end
     */
    function _newMailDetail($i_ba_id, $bs_org_sn,$bs_shop_sn,$bs_shop_name,$ba_id,$s_date_begin, $s_date_end){
        if ($s_date_begin == $s_date_end) {
            $s_date = $s_date_begin;
        } else {
            $s_date = "$s_date_begin~$s_date_end";
        }
        $s_bodys = "";
        $i_no = 1;
        
        $s_sql = "SELECT bad_pay_account,bad_bgs_code,bad_bbp_settlement_price,"
                . "bad_count,bad_bgs_barcode,bad_bgs_name FROM balance_account_detail "
                . "WHERE bad_ba_id=$ba_id AND bad_bs_org_sn = $bs_org_sn "
                . "ORDER BY bad_bs_org_sn ";
        log_message('debug', "SQL文:$s_sql");
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        foreach ($a_line as $o_line) {
                $s_bodys .= "<tr>";
                $s_bodys .= "<td>".$i_no++."</td>";
                $s_bodys .= "<td>".$o_line->bad_bgs_code."</td>";
                $s_bodys .= "<td>".$o_line->bad_bbp_settlement_price."</td>";
                $s_bodys .= "<td>".$o_line->bad_count."</td>";
                $s_bodys .= "<td>".$o_line->bad_bgs_barcode."</td>";
                $s_bodys .= "<td>".$o_line->bad_bgs_name."</td>";
                $s_bodys .= "<td></td>";
                $s_bodys .= "</tr>";
        }
        
        //追加临期销售
        $this->load->model('AdBalanceOnSaleYJM');
        $s_bodys .= $this->AdBalanceOnSaleYJM->appendHTMLTable($bs_org_sn, $ba_id,$i_no);
        
        //写本地邮件文件
        $this->load->helper('file');
        $s_template = read_file('./output_bal/template.html');
        
        //标题
        $s_mail_title = "易捷结算表(".$bs_shop_sn."站)($s_date)";
        
        $s_html = str_replace( 't_var_title', $s_mail_title, $s_template);        
        //付款账号
        $s_html = str_replace( 't_pay_account', $this->__pay_account, $s_html);
        //站点名称
        $s_html = str_replace( 't_shop_name', $bs_shop_name, $s_html);
        //时间
        $s_html = str_replace( 't_bal_date', $s_date, $s_html);
        //结算信息
        if ($s_bodys != ''){
            $s_html = str_replace('t_var_balance_content', $s_bodys, $s_html);        
        } else {
            $s_html = str_replace('t_var_balance_content', $this->__body_empty, $s_html);        
        }
        
        //AB库
        $this->load->model('AdBalanceOnSaleStM');
        $s_body_ab = $this->AdBalanceOnSaleStM->getHTMLTable($ba_id,$bs_org_sn);
        if ($s_body_ab != ''){
            $s_html = str_replace('t_var_bal_ab', $s_body_ab, $s_html);        
        } else {
            $s_html = str_replace('t_var_bal_ab', $this->__body_empty_ab, $s_html);        
        }
        
        //延期库
        $this->load->model('AdBalanceDelayM');
        $s_body_delay = $this->AdBalanceDelayM->getHTMLTable($ba_id,$bs_org_sn);
        if ($s_body_delay != ''){
            $s_html = str_replace('t_var_bal_delay', $s_body_delay, $s_html);        
        } else {
            $s_html = str_replace('t_var_bal_delay', $this->__body_empty_delay, $s_html);        
        }
        
        $s_mail_path = "/output_bal/$s_date/$bs_shop_name.$s_date.html";
        write_file(".$s_mail_path", $s_html);
        
        //更新结算邮件报告
        $this->load->model('AdBalanceAccountShopM');
        try {
            $this->AdBalanceAccountShopM->updateMailInfo($i_ba_id, $bs_org_sn, $s_mail_title,$s_mail_path);
        } catch (Exception $e) {
            log_message('error', '汇总店铺结算信息发生错误，停止结算！\r\n' . $e->getMessage());
        }
        
        //发送邮件
        //$this->sendMail($bs_shop_name.$s_date.'结算表',$s_html);
    }
    
    function sendMail($s_subject,$s_html){
        log_message('debug', "$s_subject");
        $this->load->library('email');
        
        $config['useragent']= "CodeIgniter";
        $config['protocol'] = 'sendmail';
        $config['mailtype'] = 'html';
        $config['mailpath'] = 'D:\\xampp\\sendmail\\sendmail.exe -t';
        $config['charset'] = 'utf-8';
        
        $config['smtp_host'] = 'smtp.mxhichina.com';
        $config['smtp_user'] = 'wangmin@iuoo.onaliyun.com';
        $config['smtp_pass'] = 'vincent@163';
        $config['smtp_port'] = '25';

        $this->email->initialize($config);        
        
//        $this->email->from('wangmin7391@dingtalk.com', 'Vincent Wong');
        $this->email->from('wangmin@iuoo.onaliyun.com', 'Vincent Wong');
        $this->email->to('wangmin@iuoo.onaliyun.com,huzizhuo@iuoo.onaliyun.com');//孙梦晨
//        $this->email->cc('yn8_qbm0kggqi@dingtalk.com,j8cs5fq@dingtalk.com');//CZY

        $this->email->subject($s_subject);
        $this->email->message($s_html);

        $this->email->send();
        log_message('debug', $this->email->print_debugger());
        
    }
    
}
