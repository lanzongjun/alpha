<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 饿百店铺信息
 *
 * @author Vincent
 */
class AdShopInfoYJC extends CI_Controller {

    var $_s_view = 'ShopInfoYJ';
    var $_s_model = 'AdShopInfoYJM';

    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdShopInfoYJC';
        $this->load->helper('url');
        $this->load->view("admin/baseConfig/$this->_s_view", $data);
    }

    /**
     * 获得信息列表
     */
    function getList() {
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $this->load->model($this->_s_model);
        $o_result = $this->AdShopInfoYJM->getList($i_page,$i_rows,$_GET);
        echo json_encode($o_result);
    }
    
    /**
     * 获得店铺列表
     */
    function getShopInfo() {
        $i_bs_id = isset($_GET['id']) ? $_GET['id'] : '';
        if ($i_bs_id != '') {
            $this->load->model($this->_s_model);
            $o_result = $this->AdShopInfoYJM->getShopInfo($i_bs_id);
            echo json_encode($o_result);
        } else {
            echo '';
        }
    }
    
    function getShopOrgList() {
        $this->load->model($this->_s_model);
        $o_result = $this->AdShopInfoYJM->getShopOrgList();
        if (count($o_result) > 0) {
            $o_result[0]->selected = 'true';
        }
        echo json_encode($o_result);
    }
    
    function getShopEbIdList() {
        $this->load->model($this->_s_model);
        $o_result = $this->AdShopInfoYJM->getShopEbIdList();
        if (count($o_result) > 0) {
            $o_result[0]->selected = 'true';
        }
        echo json_encode($o_result);
    }
    
    function getShopMtIdList(){
        $this->load->model($this->_s_model);
        $o_result = $this->AdShopInfoYJM->getShopMtIdList();
        if (count($o_result) > 0) {
            $o_result[0]->selected = 'true';
        }
        echo json_encode($o_result);
    }
    
    /**
     * 新增商铺信息
     */
    function addShopInfo() {
        $this->load->model($this->_s_model);
        $o_result = $this->AdShopInfoYJM->addShopInfo($_POST);
        echo json_encode($o_result);
    }
    
    /**
     * 保存商铺信息
     */
    function editShopInfo() {
        $this->load->model($this->_s_model);
        $o_result = $this->AdShopInfoYJM->editShopInfo($_POST);
        echo json_encode($o_result);
    }
    
    function getDistrict(){
        $this->load->model($this->_s_model);
        $o_result = $this->AdShopInfoYJM->getDistrict();
        echo json_encode($o_result);
    }
    
    function getEDeliveryType() {
        $this->load->model($this->_s_model);
        $o_result = $this->AdShopInfoYJM->getEDeliveryType();
        echo json_encode($o_result);
    }
    
    function doSyncInfo(){
        $this->load->model($this->_s_model);
        $b_result = $this->AdShopInfoYJM->doSyncInfo();
        $o_result = array('state'=>$b_result,'msg'=>$b_result?'success':'fault');
        echo json_encode($o_result);
    }
}
