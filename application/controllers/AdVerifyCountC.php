<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 核销统计
 *
 * @author Vincent
 */
class AdVerifyCountC extends CI_Controller {
    var $_s_view = 'VerifyCount';
    var $_s_model = 'AdVerifyCountM';
    
    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdVerifyCountC';
        $this->load->helper('url');
        $this->load->view("admin/balanceCount/$this->_s_view", $data);
    }

    /**
     * 获得信息列表
     */
    function getBRList() {
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $this->load->model($this->_s_model);
        $o_result = $this->AdVerifyCountM->getBRList($i_page,$i_rows,$_GET);
        echo json_encode($o_result);
    }
    
    function getCashPoolList(){
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $this->load->model($this->_s_model);
        $o_result = $this->AdVerifyCountM->getCashPoolList($i_page,$i_rows,$_GET);
        echo json_encode($o_result);
    }
    
    function getVCList(){
        $this->load->model($this->_s_model);
        $o_result = $this->AdVerifyCountM->getVCList($_GET);
        echo json_encode($o_result);
    }
    
    function doVerifyCount(){
        $this->load->model($this->_s_model);
        $o_result = $this->AdVerifyCountM->doVerifyCount();
        echo json_encode($o_result);
    }
    
    function doOutput(){
        $this->load->model($this->_s_model);
        $this->AdVerifyCountM->doOutput();
    }
}
