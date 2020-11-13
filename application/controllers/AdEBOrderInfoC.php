<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 饿百订单
 *
 * @author Vincent
 */
class AdEBOrderInfoC extends CI_Controller {
    var $_s_view = 'EBOrderInfo';
    var $_s_model = 'AdEBOrderInfoM';
    
    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdEBOrderInfoC';
        $this->load->helper('url');
        $this->load->view("admin/baseConfig/$this->_s_view", $data);
    }
    
    function getHistoryList() {
        
    }
    
    function getOrderInfo(){
        $s_order_id = isset($_POST['oid']) ? $_POST['oid'] : '';
        if ($s_order_id == '') {
            exit();
        }
        $this->load->model("AdEBSyncOrderM");
        $o_result = $this->AdEBSyncOrderM->getOrderInfo($s_order_id);
        echo json_encode($o_result);
    }
    
    /**
     * 获得信息列表
     */
    function getList() {
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $this->load->model($this->_s_model);
        $o_result = $this->AdEBOrderInfoM->getList($i_page,$i_rows, $_GET);
        echo json_encode($o_result);
    }
    
    /**
     * 核对订单信息
     */
    function checkOrders(){
        $this->load->model('AdEBOrderInfoCheckM');
        $o_result = $this->AdEBOrderInfoCheckM->checkOrder();
        echo json_encode($o_result);
    }
    
    /**
     * 加载异常信息列表
     */
    function loadExceptionList() {
        $s_order_code = isset($_GET['oc']) ? $_GET['oc'] : '';
        if ($s_order_code == '') {
            echo '[]'; exit();
        }
        $this->load->model('AdEBOrderInfoCheckM');
        $o_result = $this->AdEBOrderInfoCheckM->getExceptionList($s_order_code);
        echo json_encode($o_result);
    }
    
    /**
     * 解析新订单
     */
    function getNewOrders() {
        $this->load->model($this->_s_model);
        $o_result = $this->AdEBOrderInfoM->getNewOrders();
        echo json_encode($o_result);
    }
    
    /**
     * 加载详细信息
     */
    function loadDetailData() {
        $s_code = $_GET['ocode'];        
        $this->load->model($this->_s_model);
        $o_result = $this->AdEBOrderInfoM->getDetail($s_code);
        echo json_encode($o_result);
    }
    
    /**
     * 编辑订单信息
     */
    function editOrderInfo() {
        $this->load->model('AdEBOrderInfoOpM');
        $o_result = $this->AdEBOrderInfoOpM->editOrderInfo($_POST);
        echo json_encode($o_result);
    }
    
    /**
     * 删除订单
     */
    function delOrderInfo() {
        $s_eoi_code = isset($_POST['code'])?$_POST['code']:'';
        $s_eoi_memo = isset($_POST['memo'])?$_POST['memo']:'';
        $this->load->model('AdEBOrderInfoOpM');
        $o_result = $this->AdEBOrderInfoOpM->delOrderInfo($s_eoi_code,$s_eoi_memo);
        echo json_encode($o_result);
    }
    
    /**
     * 增加详情
     */
    function addDetail(){
        $s_eoi_code = isset($_POST['eod_eoi_code'])?$_POST['eod_eoi_code']:'';
        $s_sku_code = isset($_POST['eod_sku_code'])?$_POST['eod_sku_code']:'';
        $i_buy_count = isset($_POST['eod_buy_count'])?$_POST['eod_buy_count']:'';
        $s_memo = isset($_POST['eod_update_memo'])?$_POST['eod_update_memo']:'';
        if ($s_eoi_code === '' || $s_sku_code === '' || $i_buy_count === ''){
            echo 'error';
        } else {
            $this->load->model('AdEBOrderInfoOpM');
            $o_result = $this->AdEBOrderInfoOpM->addDetail($s_eoi_code,$s_sku_code,$i_buy_count,$s_memo);
            echo json_encode($o_result);
        }
    }
    
    /**
     * 编辑详情
     */
    function editDetail() {
        $s_code = isset($_POST['eod_id'])?$_POST['eod_id']:'';
        $i_count = isset($_POST['eod_buy_count'])?$_POST['eod_buy_count']:'';
        $s_memo = isset($_POST['eod_update_memo'])?$_POST['eod_update_memo']:'';
        if ($s_code === '' || $i_count === '' || $s_memo === ''){
            echo 'error';            
        } else {
            $this->load->model('AdEBOrderInfoOpM');
            $o_result = $this->AdEBOrderInfoOpM->editDetail($s_code,$i_count,$s_memo);
            echo json_encode($o_result);
        }
    }
    
    /**
     * 删除详情
     */
    function delDetail() {
        $s_id = isset($_POST['s_id'])?$_POST['s_id']:'';
        $s_memo = isset($_POST['s_memo'])?$_POST['s_memo']:'';
        if ($s_id === ''){
            echo 'error';
        } else {
            $this->load->model('AdEBOrderInfoOpM');
            $o_result = $this->AdEBOrderInfoOpM->delDetail($s_id,$s_memo);
            echo json_encode($o_result);
        }
    }
}
