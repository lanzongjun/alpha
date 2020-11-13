<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 易捷促销
 *
 * @author Vincent
 */
class AdYJOnSaleC extends CI_Controller {
    var $_s_view = 'YJOnSale';
    var $_s_model = 'AdYJOnSaleM';
    
    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdYJOnSaleC';
        $this->load->helper('url');
        $this->load->view("admin/onsale/$this->_s_view", $data);
    }

    /**
     * 获得信息列表
     */
    function getList() {
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $this->load->model($this->_s_model);
        $o_result = $this->AdYJOnSaleM->getList($i_page,$i_rows);
        echo json_encode($o_result);
    }
    
    /**
     * 追加临期促销
     * @return type
     */
    function appendOnSaleByExpire() {
        $s_id = isset($_GET['id']) ? $_GET['id'] : '';
        if ($s_id == '') { return;}
        $this->load->model($this->_s_model);
        $o_result = $this->AdYJOnSaleM->appendOnSaleByExpire($s_id);
        echo json_encode($o_result);
    }
}
