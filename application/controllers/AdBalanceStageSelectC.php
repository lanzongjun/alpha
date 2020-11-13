<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 结算-选择订单
 *
 * @author Vincent
 */
class AdBalanceStageSelectC extends CI_Controller {
    var $_s_view = 'BalanceStageSelect';
    var $_s_model = 'AdBalanceStageSelectM';
    
    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdBalanceStageSelectC';
        $data['bat_id'] = isset($_GET['bid']) ? $_GET['bid'] : '';
        $this->load->helper('url');
        $this->load->view("admin/balanceCount/$this->_s_view", $data);
    }
    
    function loadInfo() {
        $i_ba_bat_id = isset($_POST['batid']) ? $_POST['batid'] : '';
        if ($i_ba_bat_id == '') {
            $o_return['state'] = false;
            $o_return['msg'] = "缺少参数";
            echo json_encode($o_return);
        }
    }
    
    /**
     * 获得订单列表
     */
    function getOrderList() {
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $this->load->model($this->_s_model);
        $o_result = $this->AdBalanceStageSelectM->getList($i_page,$i_rows, $_GET);
        echo json_encode($o_result);
    }
    
    /**
     * 拉取订单
     * @return type
     */    
    function doPullOrder(){
        $s_date_begin = isset($_GET['db']) ? $_GET['db'] : '';
        $s_date_end = isset($_GET['de']) ? $_GET['de'] : '';
        $s_from = isset($_GET['f']) ? $_GET['f'] : '';
        if ($s_date_begin=='' || $s_date_end==''){
            echo 'error';
            return;
        }
        
        if ($s_from == 'ELE') {
            $this->load->model($this->_s_model);
            $o_result = $this->AdBalanceStageSelectM->pullEBOrder($s_date_begin, $s_date_end);
            $this->AdBalanceStageSelectM->pullEBOrderRefund($s_date_begin, $s_date_end);
            echo json_encode($o_result);
        } else if ($s_from == 'MT') {
            $this->load->model($this->_s_model);
            $o_result = $this->AdBalanceStageSelectM->pullMTOrder($s_date_begin, $s_date_end);
            $this->AdBalanceStageSelectM->pullMTOrderRefund($s_date_begin, $s_date_end);
            echo json_encode($o_result);
        } else {
            echo '';
        }
    }
    
    /**
     * 获得已选定订单列表
     */
    function getSelectedList() {
        $i_ba_bat_id = isset($_GET['batid']) ? $_GET['batid'] : '';
        if ($i_ba_bat_id == '') {
            echo '[]';
        }
        $this->load->model($this->_s_model);
        $o_result = $this->AdBalanceStageSelectM->getSelectedOrders($i_ba_bat_id);
        echo json_encode($o_result);
    }
    
    /**
     * 获得当前结算起止日期
     */
    function getSearchInfo(){
        $o_return = array('state' => true,'msg'=>"SUCCESS");
        $i_ba_bat_id = isset($_POST['batid']) ? $_POST['batid'] : '';
        if ($i_ba_bat_id == '') {
            $o_return['state'] = false;
            $o_return['msg'] = "缺少参数";
            echo json_encode($o_return);
        }
        $this->load->model($this->_s_model);
        $o_data = $this->AdBalanceStageSelectM->getBalance($i_ba_bat_id);
        $o_return['date_begin'] = $o_data->ba_balance_date_begin;
        $o_return['date_end'] = $o_data->ba_balance_date_end;
        echo json_encode($o_return);
    }
    
    /**
     * 执行选定
     */
    function doSelected(){
        $s_date_begin = isset($_POST['db']) ? $_POST['db'] : '';
        $s_date_end = isset($_POST['de']) ? $_POST['de'] : '';
        $a_code = isset($_POST['codes']) ? $_POST['codes'] : '';
        $i_bat_id = isset($_POST['batid']) ? $_POST['batid'] : '';
        $this->load->model($this->_s_model);
        if ($i_bat_id == '') {
            $o_result = $this->AdBalanceStageSelectM->doSelected($s_date_begin, 
                    $s_date_end, $a_code);
        } else {
            $o_result = $this->AdBalanceStageSelectM->doSelected($s_date_begin, 
                    $s_date_end, $a_code, $i_bat_id);            
        }
        echo json_encode($o_result);
    }
    
}
