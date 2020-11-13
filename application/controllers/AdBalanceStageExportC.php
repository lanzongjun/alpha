<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 结算-导出
 *
 * @author Vincent
 */
class AdBalanceStageExportC extends CI_Controller {
    var $_s_view = 'BalanceStageExport';
    var $_s_model = 'AdBalanceStageExportM';
    
    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdBalanceStageExportC';
        $data['bat_id'] = isset($_GET['bid']) ? $_GET['bid'] : '';
        $this->load->helper('url');
        $this->load->view("admin/balanceCount/$this->_s_view", $data);
    }
    
    function getCollectShops(){
        $i_ba_bat_id = isset($_POST['batid']) ? $_POST['batid'] : '';
        if ($i_ba_bat_id == '') {
            echo '[]';
            return;
        }
        $this->load->model($this->_s_model);
        $o_result = $this->AdBalanceStageExportM->getCollectList($i_ba_bat_id);
        echo json_encode($o_result);
    }
    
    function getCollectGoods(){
        $i_ba_bat_id = isset($_POST['batid']) ? $_POST['batid'] : '';
        if ($i_ba_bat_id == '') {
            echo '[]';
            return;
        }
        $this->load->model($this->_s_model);
        $o_result = $this->AdBalanceStageExportM->getGoodsList($i_ba_bat_id);
        echo json_encode($o_result);
    }
    
    function exportCollect(){
        $i_ba_bat_id = isset($_POST['batid']) ? $_POST['batid'] : '';
        $s_shop_id = isset($_POST['sid']) ? $_POST['sid'] : '';
        if ($i_ba_bat_id == '' || $s_shop_id == '') {
            $o_return['state'] = false;
            $o_return['msg'] = "缺少参数";
            echo json_encode($o_return);
        }
        $this->load->model($this->_s_model);        
        $o_result = $this->AdBalanceStageExportM->exportCollect($i_ba_bat_id, $s_shop_id);
        if ($o_result['state'] == true){
            $this->AdBalanceStageExportM->updateFilepathShops($i_ba_bat_id, 
                    $s_shop_id, $o_result['file_path']);
        }
        echo json_encode($o_result);
    }
    
    function exportGoods(){
        $i_ba_bat_id = isset($_POST['batid']) ? $_POST['batid'] : '';
        if ($i_ba_bat_id == '') {
            $o_return['state'] = false;
            $o_return['msg'] = "缺少参数";
            echo json_encode($o_return);
        }
        $this->load->model($this->_s_model);        
        $o_result = $this->AdBalanceStageExportM->exportGoods($i_ba_bat_id);
        echo json_encode($o_result);
    }
    
    function downloadGoods(){
        $i_ba_bat_id = isset($_POST['batid']) ? $_POST['batid'] : '';
        if ($i_ba_bat_id == '') {
            $o_return['state'] = false;
            $o_return['msg'] = "缺少参数";
            echo json_encode($o_return);
        }
        $this->load->model($this->_s_model);        
        $s_file_path = $this->AdBalanceStageExportM->getFilepathGoods($i_ba_bat_id);
        if ($s_file_path == '') {
           $o_temp = $this->AdBalanceStageExportM->exportGoods($i_ba_bat_id);
           $s_file_path = $o_temp['file_path'];
        }
        echo $s_file_path;
    }
    
    function exportZipPackage() {
        $s_bas_id = isset($_GET['ids']) ? $_GET['ids'] : '';
        if ($s_bas_id == '') {
            $o_return['state'] = false;
            $o_return['msg'] = "缺少参数";
            echo json_encode($o_return);
        }
        $a_bas_id = json_decode($s_bas_id);
        $this->load->model($this->_s_model);        
        $this->AdBalanceStageExportM->exportZipPackage($a_bas_id);
        
    }
}
