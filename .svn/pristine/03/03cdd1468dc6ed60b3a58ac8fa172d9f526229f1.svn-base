<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 饿了么订单
 *
 * @author Vincent
 */
class AdEBOrderInfoToDoC extends CI_Controller {
    var $_s_view = 'EBOrderInfoToDo';
    var $_s_model = 'AdEBOrderInfoM';
    
    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdEBOrderInfoToDoC';
        $this->load->helper('url');
        $this->load->view("admin/baseConfig/$this->_s_view", $data);
    }
    
    /**
     * 获得信息列表
     */
    function getList() {
        $this->load->model($this->_s_model);
        $o_result = $this->AdEBOrderInfoM->getToDoList();
        echo json_encode($o_result);
    }
    
    function loadDetailData() {
        $s_code = $_GET['ocode'];  
        $this->load->model($this->_s_model);
        $o_result = $this->AdEBOrderInfoM->getDetail($s_code);
        echo json_encode($o_result);
    }
    
    function doConfirmOrder() {
        $s_order_id = isset($_POST['oi']) ? $_POST['oi'] : '';
        if ($s_order_id == ''){
            $o_result['state'] = true;
            $o_result['msg'] = "订单号不合法";
            echo json_encode($o_result);
        } else {
            $this->load->model('AdEBSyncOrderM');
            $o_result = $this->AdEBSyncOrderM->doConfirmOrder($s_order_id);
            echo json_encode($o_result);
        }
    }
        
    function isOrderAutoConfirm() {
        $this->load->model('AdEBSyncOrderM');
        $o_result = $this->AdEBSyncOrderM->isOrderAutoConfirm();
        echo json_encode($o_result);
    }

    function startOrderConfirm() {
        $this->load->model('AdEBSyncOrderM');
        $o_result = $this->AdEBSyncOrderM->startOrderConfirm();
        echo json_encode($o_result);
    }

    function stopOrderConfirm() {
        $this->load->model('AdEBSyncOrderM');
        $o_result = $this->AdEBSyncOrderM->stopOrderConfirm();
        echo json_encode($o_result);
    }

}
