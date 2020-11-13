<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 易捷商品库
 *
 * @author Vincent
 */
class AdYJGoodsInfoC extends CI_Controller {

    var $_s_view = 'GoodsInfoYJ';
    var $_s_model = 'AdYJGoodsInfoM';

    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdYJGoodsInfoC';
        $this->load->helper('url');
        $this->load->view("admin/baseConfig/$this->_s_view", $data);
    }

    /**
     * 获得信息列表
     */
    function getList() {
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $s_goods_name = isset($_GET['gn']) ? $_GET['gn'] : '';
        $s_barcode = isset($_GET['bc']) ? $_GET['bc'] : '';
        $s_dt = isset($_GET['dt']) ? $_GET['dt'] : '';
        $s_sale = isset($_GET['sa']) ? $_GET['sa'] : '';
        $s_state = isset($_GET['ss']) ? $_GET['ss'] : '';

        $this->load->model($this->_s_model);
        $o_result = $this->AdYJGoodsInfoM->getList($i_page, $i_rows, $s_goods_name,
                $s_barcode, $s_dt, $s_sale, $s_state);
        echo json_encode($o_result);
    }

    function loadPriceInfo() {
        $s_barcode = isset($_GET['bc']) ? $_GET['bc'] : '';
        if ($s_barcode == '') {
            echo '[]';
        } else {
            $this->load->model($this->_s_model);
            $o_result = $this->AdYJGoodsInfoM->getPriceInfo($s_barcode);
            if (is_array($o_result) && count($o_result)==1 ) {
                echo json_encode($o_result[0]);
            } else {
                echo "{\"bbp_settlement_price\":\"0.00\",\"bbp_yj_sale_price\":\"0.00\"}";
            }            
        }
    }
    
    function loadOfflineInfo() {
        $s_barcode = isset($_GET['bc']) ? $_GET['bc'] : '';
        if ($s_barcode == '') {
            echo '[]';
        } else {
            $this->load->model($this->_s_model);
            $o_result = $this->AdYJGoodsInfoM->getOfflineInfo($s_barcode);
            echo json_encode($o_result);
        }
    }

    function loadOnlineInfo() {
        $s_barcode = isset($_GET['bc']) ? $_GET['bc'] : '';
        if ($s_barcode == '') {
            echo '[]';
        } else {
            $this->load->model($this->_s_model);
            $o_result = $this->AdYJGoodsInfoM->getOnlineInfo($s_barcode);
            echo json_encode($o_result);
        }
    }

    function doChangeCanSale() {
        $this->load->model($this->_s_model);
        $o_result = $this->AdYJGoodsInfoM->doChangeCanSale($_POST);
        echo json_encode($o_result);
    }
    
    /**
     * 保存商品信息
     */
    function editGoodsInfo() {
        $this->load->model($this->_s_model);
        $o_result = $this->AdYJGoodsInfoM->editGoodsInfo($_POST);
        echo json_encode($o_result);
    }
    
    
    /**
     * 获得信息列表
     */
    function doOutput() {
        $s_goods_name = isset($_GET['gn']) ? $_GET['gn'] : '';
        $s_barcode = isset($_GET['bc']) ? $_GET['bc'] : '';
        $s_dt = isset($_GET['dt']) ? $_GET['dt'] : '';
        $s_sale = isset($_GET['sa']) ? $_GET['sa'] : '';
        $s_state = isset($_GET['ss']) ? $_GET['ss'] : '';

        $this->load->model($this->_s_model);
        $s_url = $this->AdYJGoodsInfoM->outputCSV($s_goods_name, $s_barcode, $s_dt, $s_sale, $s_state);
        $o_result['state'] = true;
        $o_result['msg'] = $s_url;
        echo json_encode($o_result);
    }

}
