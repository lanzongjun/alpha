<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 结算-每门店销售汇总
 *
 * @author Vincent
 */
class AdBalanceStageCollectC extends CI_Controller {
    var $_s_view = 'BalanceStageCollect';
    var $_s_model = 'AdBalanceStageCollectM';
    
    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdBalanceStageCollectC';
        $data['bat_id'] = isset($_GET['bid']) ? $_GET['bid'] : '';
        $this->load->helper('url');
        $this->load->view("admin/balanceCount/$this->_s_view", $data);
    }
    
    function doCollect(){
        $i_ba_bat_id = isset($_POST['batid']) ? $_POST['batid'] : '';
        if ($i_ba_bat_id == '') {
            echo '[]';
            return;
        }
        $this->load->model($this->_s_model);        
        $this->AdBalanceStageCollectM->clearCollect($i_ba_bat_id);
        $o_result = $this->AdBalanceStageCollectM->doCollect($i_ba_bat_id);
        echo json_encode($o_result);
    }
    
    function getCollectShopList(){
        $i_ba_bat_id = isset($_POST['batid']) ? $_POST['batid'] : '';
        if ($i_ba_bat_id == '') {
            echo '[]';
            return;
        }
        $this->load->model($this->_s_model);
        $o_result = $this->AdBalanceStageCollectM->getCollectShopList($i_ba_bat_id);
        echo json_encode($o_result);
    }
    
    function getCollectListByShop(){
        $i_ba_bat_id = isset($_POST['batid']) ? $_POST['batid'] : '';
        $i_shop_id = isset($_POST['sid']) ? $_POST['sid'] : '';
        if ($i_ba_bat_id == '' || $i_shop_id == '') {
            echo '[]';
            return;
        }
        $this->load->model($this->_s_model);
        $o_result = $this->AdBalanceStageCollectM->getCollectListByShop($i_ba_bat_id,$i_shop_id);
        echo json_encode($o_result);
    }
    
    function getCollectGoodsList(){
        $i_ba_bat_id = isset($_POST['batid']) ? $_POST['batid'] : '';
        if ($i_ba_bat_id == '') {
            echo '[]';
            return;
        }
        $this->load->model($this->_s_model);
        $o_result = $this->AdBalanceStageCollectM->getCollectGoodsList($i_ba_bat_id);
        echo json_encode($o_result);
    }
}
