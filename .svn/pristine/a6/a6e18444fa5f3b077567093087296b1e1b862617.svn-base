<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 美团订单
 *
 * @author Vincent
 */
class AdMTOrderInfoToDoC extends CI_Controller {
    var $_s_view = 'MTOrderInfoToDo';
    var $_s_model = 'AdMTOrderInfoM';
    
    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdMTOrderInfoToDoC';
        $this->load->helper('url');
        $this->load->view("admin/baseConfig/$this->_s_view", $data);
    }
    
    /**
     * 获得信息列表
     */
    function getList() {
        $this->load->model($this->_s_model);
        $o_result = $this->AdMTOrderInfoM->getToDoList();
        echo json_encode($o_result);
    }
    
    function loadDetailData() {
        $s_code = $_GET['ocode'];  
        $this->load->model($this->_s_model);
        $o_result = $this->AdMTOrderInfoM->getDetail($s_code);
        echo json_encode($o_result);
    }
    
    function doConfirmOrder() {
        $l_order_id = isset($_POST['oi']) ? $_POST['oi'] : '';
        if ($l_order_id == ''){
            $o_result['state'] = true;
            $o_result['msg'] = "订单号不合法";
            echo json_encode($o_result);
        } else {
            $this->load->model('AdMTSyncOrderM');
            $o_result = $this->AdMTSyncOrderM->doConfirmOrder($l_order_id);
            echo json_encode($o_result);
        }
    }
    
    function doCancelOrder() {
        $l_order_id = isset($_POST['oi']) ? $_POST['oi'] : '';
        if ($l_order_id == ''){
            $o_result['state'] = true;
            $o_result['msg'] = "订单号不合法";
            echo json_encode($o_result);
        } else {
            $this->load->model('AdMTSyncOrderM');
            $o_result = $this->AdMTSyncOrderM->doCancelOrder($l_order_id);
            echo json_encode($o_result);
        }
    }
    
    function isOrderAutoConfirm() {
        $this->load->model('AdMTSyncOrderM');
        $o_result = $this->AdMTSyncOrderM->isOrderAutoConfirm();
        echo json_encode($o_result);
    }

    function startOrderConfirm() {
        $this->load->model('AdMTSyncOrderM');
        $o_result = $this->AdMTSyncOrderM->startOrderConfirm();
        echo json_encode($o_result);
    }

    function stopOrderConfirm() {
        $this->load->model('AdMTSyncOrderM');
        $o_result = $this->AdMTSyncOrderM->stopOrderConfirm();
        echo json_encode($o_result);
    }

}
