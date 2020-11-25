<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2020/11/24
 * Time: 11:26 AM
 */

class AdMTOrderRefundDetailC extends CI_Controller
{
    public $_s_view = 'MTOrderRefundDetail';
    public $_s_model = 'AdMTOrderInfoM';


    /**
     * 显示信息
     */
    public function index()
    {
        $data['c_name'] = 'AdMTOrderRefundDetailC';
        $this->load->helper('url');
        $this->load->view("admin/baseConfig/$this->_s_view", $data);
    }

    public function getList()
    {
        $getData = $this->input->get();
        $page = isset($getData['page']) ? $getData['page'] : 1;
        $rows = isset($getData['rows']) ? $getData['rows'] : 50;
        $this->load->model($this->_s_model);
        $o_result = $this->{$this->_s_model}->getRefundList();
        echo json_encode($o_result);
    }

    public function loadRefundDetail()
    {
        $this->load->model($this->_s_model);
        $orderId = $_GET['order_id'];
        $o_result = $this->{$this->_s_model}->getRefundDetail($orderId);
        echo json_encode($o_result);
    }
}