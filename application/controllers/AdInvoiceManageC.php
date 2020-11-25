<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 发票管理
 *
 * @author Vincent
 */
class AdInvoiceManageC extends CI_Controller {
    var $_s_view = 'InvoiceManage';
    var $_s_model = 'AdInvoiceManageM';
    
    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdInvoiceManageC';
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
        $o_result = $this->AdInvoiceManageM->_getList($i_page,$i_rows,$_GET);
        echo json_encode($o_result);
    }
    
    function getBRDList(){
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $this->load->model($this->_s_model);
        $o_result = $this->AdInvoiceManageM->getBRDList($i_page,$i_rows,$_GET);
        echo json_encode($o_result);
    }
    
    function getCPDList(){
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $this->load->model($this->_s_model);
        $o_result = $this->AdInvoiceManageM->getCPDList($i_page,$i_rows,$_GET);
        echo json_encode($o_result);
    }
    
    function addInvoice(){
        $this->load->model($this->_s_model);
        $o_result = $this->AdInvoiceManageM->addInvoice($_POST);
        echo json_encode($o_result);
    }
    
    function doInvoiceLink(){
        $s_vr_id = isset($_POST['ids']) ? $_POST['ids'] : '';
        $s_ir_no = isset($_POST['irno']) ? $_POST['irno'] : '';
        if ($s_vr_id == '' || $s_ir_no == '') {
            $o_result['state'] = false;
            $o_result['msg'] = "缺少关键参数";
            echo json_encode($o_result);
            return;
        }
        $a_vr_id = json_decode($s_vr_id);
        if ((!is_array($a_vr_id) || count($a_vr_id) < 1)){
            $o_result['state'] = false;
            $o_result['msg'] = "关键参数不合法";
            echo json_encode($o_result);
            return;
        }
        $this->load->model($this->_s_model);
        $o_result = $this->AdInvoiceManageM->doInvoiceLink($s_ir_no,$a_vr_id);
        echo json_encode($o_result);
    }
}
