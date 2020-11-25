<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 美团订单-退单
 *
 * @author Vincent
 */
class AdMTOrderRefundC extends CI_Controller {
    var $_s_view = 'MTOrderRefund';
    var $_s_model = 'AdMTOrderInfoM';
    
    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdMTOrderRefundC';
        $this->load->helper('url');
        $this->load->view("admin/baseConfig/$this->_s_view", $data);
    }
    
    /**
     * 获得信息列表
     */
    function getList() {
        $this->load->model($this->_s_model);
        $o_result = $this->AdMTOrderInfoM->getApplyRefundList();
        echo json_encode($o_result);
    }
    
    function loadDetailData() {
        $l_order_id = isset($_GET['ocode']) ? $_GET['ocode'] : '';
        if ($l_order_id == ''){
            $o_result['state'] = true;
            $o_result['msg'] = "订单号不合法";
            echo json_encode($o_result);
        } else {
            $this->load->model($this->_s_model);
            $o_result = $this->AdMTOrderInfoM->getDetail($l_order_id);
            echo json_encode($o_result);
        }
    }
    
    function loadRefundDetail() {
        $l_order_id = isset($_GET['ocode']) ? $_GET['ocode'] : '';
        if ($l_order_id == ''){
            $o_result['state'] = true;
            $o_result['msg'] = "订单号不合法";
            echo json_encode($o_result);
        } else {
            $this->load->model($this->_s_model);
            $o_result = $this->AdMTOrderInfoM->getApplyRefundDetail($l_order_id);
            echo json_encode($o_result);
        }
    }
    
    function doRefundAgree() {
        $l_order_id = isset($_POST['oi']) ? $_POST['oi'] : '';
        if ($l_order_id == ''){
            $o_result['state'] = true;
            $o_result['msg'] = "订单号不合法";
            echo json_encode($o_result);
        } else {
            $this->load->model('AdMTSyncOrderM');
            $o_result = $this->AdMTSyncOrderM->doRefundAgree($l_order_id);
            echo json_encode($o_result);
        }
    }
    
    function doRefundReject() {
        $l_order_id = isset($_POST['oi']) ? $_POST['oi'] : '';
        if ($l_order_id == ''){
            $o_result['state'] = true;
            $o_result['msg'] = "订单号不合法";
            echo json_encode($o_result);
        } else {
            $this->load->model('AdMTSyncOrderM');
            $o_result = $this->AdMTSyncOrderM->doRefundReject($l_order_id);
            echo json_encode($o_result);
        }
    }
    
    function doPullPhone() {
        $this->load->model('AdMTSyncOrderM');
        $o_result = $this->AdMTSyncOrderM->doPullPhone();
        echo json_encode($o_result);
    }
}
