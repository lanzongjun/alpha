<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 结算-确认订单
 *
 * @author Vincent
 */
class AdBalanceStageConfirmC extends CI_Controller {
    var $_s_view = 'BalanceStageConfirm';
    var $_s_model = 'AdBalanceStageConfirmM';
    
    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdBalanceStageConfirmC';
        $data['bat_id'] = isset($_GET['bid']) ? $_GET['bid'] : '';
        $this->load->helper('url');
        $this->load->view("admin/balanceCount/$this->_s_view", $data);
    }
    
    function doConfirm(){
        $i_ba_bat_id = isset($_POST['batid']) ? $_POST['batid'] : '';
        if ($i_ba_bat_id == '') {
            echo '[]';
            return;
        }
        $this->load->model($this->_s_model);
        $o_result = $this->AdBalanceStageConfirmM->doConfirm($i_ba_bat_id);
        echo json_encode($o_result);
    }
    
    function getOrderList() {
        $i_ba_bat_id = isset($_POST['batid']) ? $_POST['batid'] : '';
        if ($i_ba_bat_id == '') {
            echo '[]';
            return;
        }
        $this->load->model($this->_s_model);
        $o_result = $this->AdBalanceStageConfirmM->getOrderList($i_ba_bat_id);
        echo json_encode($o_result);
    }
    
    function getOrderDetail() {
        $s_order_id = isset($_POST['id']) ? $_POST['id'] : '';
        if ($s_order_id == '') {
            echo '[]';
            return;
        }
        $this->load->model($this->_s_model);
        $o_result = $this->AdBalanceStageConfirmM->getOrderDetail($s_order_id);
        echo json_encode($o_result);
    }
    
    function getBalOrderInfo() {
        $s_order_id = isset($_GET['oid']) ? $_GET['oid'] : '';
        if ($s_order_id == '') {
            echo '{}';
            return;
        }
        $this->load->model($this->_s_model);
        $o_result = $this->AdBalanceStageConfirmM->getBalOrderInfo($s_order_id);
        if ($o_result == '') {
            echo '{}';
        } else {
            echo json_encode($o_result);
        }
    }
    
    function getBalOrderList() {
        $i_ba_bat_id = isset($_POST['batid']) ? $_POST['batid'] : '';
        if ($i_ba_bat_id == '') {
            echo '[]';
            return;
        }
        $this->load->model($this->_s_model);
        $o_result = $this->AdBalanceStageConfirmM->getBalOrderList($i_ba_bat_id);
        echo json_encode($o_result);
    }
    
    function getBalOrderDetailList() {
        $s_order_id = isset($_POST['id']) ? $_POST['id'] : '';
        if ($s_order_id == '') {
            echo '[]';
            return;
        }
        $this->load->model($this->_s_model);
        $o_result = $this->AdBalanceStageConfirmM->getBalOrderDetailList($s_order_id);
        echo json_encode($o_result);
    }
    
    function getShopGoodList(){
        $s_shop_id = isset($_POST['s']) ? $_POST['s'] : '';
        $s_platform = isset($_POST['p']) ? $_POST['p'] : '';
        if ($s_shop_id == '') {
            echo '[]';
            return;
        }
        $this->load->model($this->_s_model);
        if ($s_platform == 'ELE') {
            $o_result = $this->AdBalanceStageConfirmM->getShopGoodListEB($s_shop_id);
            echo json_encode($o_result);
        } else if ($s_platform == 'MT') {
            $o_result = $this->AdBalanceStageConfirmM->getShopGoodListMT($s_shop_id);
            echo json_encode($o_result);
        } else {
            echo '[]';
            return;
        }
    }
    
    function getGoodsNameByBarcode(){
        $s_shop_id = isset($_POST['s']) ? $_POST['s'] : '';
        $s_platform = isset($_POST['p']) ? $_POST['p'] : '';
        $s_code = isset($_POST['v']) ? $_POST['v'] : '';
        if ($s_shop_id == '' || $s_platform == '' || $s_code == '') {
            echo '';
            return;
        }
        $this->load->model($this->_s_model);
        if ($s_platform == 'ELE') {
            $o_result = $this->AdBalanceStageConfirmM->getShopGoodListEB($s_shop_id, $s_code);
            echo json_encode($o_result[0]);
        } else if ($s_platform == 'MT') {
            $o_result = $this->AdBalanceStageConfirmM->getShopGoodListMT($s_shop_id, $s_code);
            echo json_encode($o_result[0]);
        } else {
            echo '';
            return;
        }
    }
    /**
     * 增加订单商品
     */
    function addOrderDetail() {
        $this->load->model($this->_s_model);
        $o_result = $this->AdBalanceStageConfirmM->addOrderDetail($_POST);
        echo json_encode($o_result);
    }
    
    /**
     * 编辑订单商品
     */
    function editOrderDetail() {
        $this->load->model($this->_s_model);
        $o_result = $this->AdBalanceStageConfirmM->editOrderDetail($_POST);
        echo json_encode($o_result);
    }
    
    /**
     * 编辑订单商品
     */
    function delOrderDetail() {
        $this->load->model($this->_s_model);
        $o_result = $this->AdBalanceStageConfirmM->delOrderDetail($_POST);
        echo json_encode($o_result);
    }
    
}
