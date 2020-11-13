<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 美团订单
 *
 * @author Vincent
 */
class AdMTOrderInfoC extends CI_Controller {
    var $_s_view = 'MTOrderInfo';
    var $_s_model = 'AdMTOrderInfoM';
    
    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdMTOrderInfoC';
        $this->load->helper('url');
        $this->load->view("admin/baseConfig/$this->_s_view", $data);
    }
    
    function getOrderInfo(){
        $s_order_id = isset($_POST['oid']) ? $_POST['oid'] : '';
        if ($s_order_id == '') {
            exit();
        }
        $this->load->model("AdMTSyncOrderM");
        $o_result = $this->AdMTSyncOrderM->getOrderInfo($s_order_id);
        echo json_encode($o_result);
    }
    
    /**
     * 获得信息列表
     */
    function getList() {
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $this->load->model($this->_s_model);
        $o_result = $this->AdMTOrderInfoM->getList($i_page,$i_rows, $_GET);
        echo json_encode($o_result);
    }

    /**
     * 加载详细信息
     */
    function loadDetailData() {
        $s_code = $_GET['ocode'];        
        $this->load->model($this->_s_model);
        $o_result = $this->AdMTOrderInfoM->getDetail($s_code);
        echo json_encode($o_result);
    }
    
    function isOrderToDo(){
        $this->load->model($this->_s_model);
        $i_result = $this->AdMTOrderInfoM->getOrderToDo();
        echo $i_result;
    }

}
