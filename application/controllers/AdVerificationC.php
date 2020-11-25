<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 核销
 *
 * @author Vincent
 */
class AdVerificationC extends CI_Controller {
    var $_s_view = 'Verification';
    var $_s_model = 'AdVerificationM';
    
    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdVerificationC';
        $this->load->helper('url');
        $this->load->view("admin/balanceCount/$this->_s_view", $data);
    }

    /**
     * 获得信息列表
     */
    function getList() {
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $this->load->model($this->_s_model);
        $o_result = $this->AdVerificationM->_getList($i_page,$i_rows,$_GET);
        echo json_encode($o_result);
    }
    
    function getCashPoolList(){
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $this->load->model($this->_s_model);
        $o_result = $this->AdVerificationM->getCashPoolList($i_page,$i_rows,$_GET);
        echo json_encode($o_result);
    }
    
    function doAutoLink(){
        $this->load->model($this->_s_model);
        $o_result = $this->AdVerificationM->doAutoLink();
        echo json_encode($o_result);
    }
    
    function doAimVerify(){
        $s_data = isset($_POST['ads']) ? $_POST['ads'] : '';
        if ($s_data == '') {
            $o_result['state'] = false;
            $o_result['msg'] = "缺少关键参数";
            echo json_encode($o_result);
            return;
        }
        $a_data = json_decode($s_data);
        if (!is_array($a_data) || count($a_data) < 1){
            $o_result['state'] = false;
            $o_result['msg'] = "关键参数不合法";
            echo json_encode($o_result);
            return;
        }
        $this->load->model($this->_s_model);
        $o_result = $this->AdVerificationM->doAimVerify($a_data);
        echo json_encode($o_result);
    }
    
    function doCusVerify(){
        $s_cpd = isset($_POST['cpd']) ? $_POST['cpd'] : '';
        $s_br = isset($_POST['br']) ? $_POST['br'] : '';
        $s_vr = isset($_POST['vr']) ? $_POST['vr'] : '';
        if ($s_cpd == '' || $s_br == '' || $s_vr == '') {
            $o_result['state'] = false;
            $o_result['msg'] = "缺少关键参数";
            echo json_encode($o_result);
            return;
        }
        $a_cpd = json_decode($s_cpd);
        $a_br = json_decode($s_br);
        if ((!is_array($a_cpd) || count($a_cpd) < 1)
            || (!is_array($a_br) || count($a_br) < 1)){
            $o_result['state'] = false;
            $o_result['msg'] = "关键参数不合法";
            echo json_encode($o_result);
            return;
        }
        $this->load->model($this->_s_model);
        $o_result = $this->AdVerificationM->doCusVerify($a_cpd,$a_br,$s_vr);
        echo json_encode($o_result);
    }

}
