<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 核销记录
 *
 * @author Vincent
 */
class AdVerifyRecordC extends CI_Controller {
    var $_s_view = 'VerifyRecord';
    var $_s_model = 'AdVerifyRecordM';
    
    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdVerifyRecordC';
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
        $o_result = $this->AdVerifyRecordM->getList($i_page,$i_rows,$_GET);
        echo json_encode($o_result);
    }
    
    function getCashPoolList(){
        $s_vrid = isset($_GET['vrid']) ? $_GET['vrid'] : '';
        if ($s_vrid == ''){
            echo '[]';            
        }else{
            $this->load->model($this->_s_model);
            $o_result = $this->AdVerifyRecordM->getCashPoolList($s_vrid);
            echo json_encode($o_result);
        }
    }
    
    function getBRDList(){
        $s_vrid = isset($_GET['vrid']) ? $_GET['vrid'] : '';
        if ($s_vrid == ''){
            echo '[]';            
        }else{
            $this->load->model($this->_s_model);
            $o_result = $this->AdVerifyRecordM->getBRDList($s_vrid);
            echo json_encode($o_result);
        }
    }
    
    function doRemove(){
        $s_vrid = isset($_POST['vrid']) ? $_POST['vrid'] : '';
        if ($s_vrid == '') {
            $o_result['state'] = false;
            $o_result['msg'] = "缺少关键参数";
            echo json_encode($o_result);
            return;
        }
        $this->load->model($this->_s_model);
        $o_result = $this->AdVerifyRecordM->doRemove($s_vrid);
        echo json_encode($o_result);
    }
}
