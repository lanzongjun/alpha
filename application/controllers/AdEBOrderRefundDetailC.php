<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2020/11/20
 * Time: 3:22 PM
 */

class AdEBOrderRefundDetailC extends CI_Controller
{
    public $_s_view = 'EBOrderRefundDetail';
    public $_s_model = 'AdEBPartRefundInfoM';


    /**
     * 显示信息
     */
    public function index()
    {
        $data['c_name'] = 'AdEBOrderRefundDetailC';
        $this->load->helper('url');
        $this->load->view("admin/baseConfig/$this->_s_view", $data);
    }

    public function getList()
    {
        $getData = $this->input->get();
        $page = isset($getData['page']) ? $getData['page'] : 1;
        $rows = isset($getData['rows']) ? $getData['rows'] : 50;
        $this->load->model($this->_s_model);
        $o_result = $this->AdEBPartRefundInfoM->getRefundList($page, $rows);
        echo json_encode($o_result);
    }

    public function loadRefundDetail()
    {
        $this->load->model($this->_s_model);
        $orderId = $_GET['order_id'];
        $o_result = $this->AdEBPartRefundInfoM->getRefundDetail($orderId);
        echo json_encode($o_result);
    }
}