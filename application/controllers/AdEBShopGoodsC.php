<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 饿百店铺商品信息
 *
 * @author Vincent
 */
class AdEBShopGoodsC extends CI_Controller {

    var $_s_view = 'EBShopGoods';
    var $_s_model = 'AdEBShopGoodsM';

    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdEBShopGoodsC';
        $this->load->helper('url');
        $this->load->view("admin/baseConfig/$this->_s_view", $data);
    }

    /**
     * 获得信息列表
     */
    function getList() {
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        if (!isset($_GET['oid'])) {
            exit();
        }
        $s_org_id = $_GET['oid'];
        $s_goods_name = isset($_GET['gn']) ? $_GET['gn'] : '';
        $s_barcode = isset($_GET['bc']) ? $_GET['bc'] : '';
        $s_filter_storage = isset($_GET['fs']) ? $_GET['fs'] : '';
        $s_filter_price = isset($_GET['fp']) ? $_GET['fp'] : '';
        $s_filter_up = isset($_GET['fu']) ? $_GET['fu'] : '';
        $this->load->model($this->_s_model);
        $o_result = $this->AdEBShopGoodsM->getList($i_page, $i_rows, $s_org_id, 
                $s_goods_name, $s_barcode, $s_filter_storage, $s_filter_price, $s_filter_up);
        echo json_encode($o_result);
    }
    
    function outputNewGoods(){
        $s_shop_id = isset($_GET['sid']) ? $_GET['sid'] : '';
        $this->load->model($this->_s_model);
        $this->AdEBShopGoodsM->outputNewGoods($s_shop_id);
    }
    
    function getNewGoods() {
        $s_shop_id = isset($_POST['sid']) ? $_POST['sid'] : '';        
        $this->load->model($this->_s_model);
        $o_result = $this->AdEBShopGoodsM->getNewGoods($s_shop_id);
        echo json_encode($o_result);
    }

    /**
     * 获得店铺商品
     */
    function getShopGoods() {
        $s_shop_id = isset($_GET['sid']) ? $_GET['sid'] : '';
        if ($s_shop_id !== '') {
            $this->load->model($this->_s_model);
            $o_result = $this->AdEBShopGoodsM->getShopGoods($s_shop_id);
            echo json_encode($o_result);
        } else {
            echo '';
        }
    }
    
    function keepUpdateTodayLog(){
        $this->load->model('AdEBSyncStorageM');
        $o_result = $this->AdEBSyncStorageM->keepUpdateTodayLog();
        echo $o_result;
    }
    
    function keepSKUTodayLog(){
        $this->load->model('AdEBSyncStorageM');
        $o_result = $this->AdEBSyncStorageM->keepSKUTodayLog();
        echo $o_result;
    }
    
    function keepPriceTodayLog(){
        $this->load->model('AdEBSyncStorageM');
        $o_result = $this->AdEBSyncStorageM->keepPriceTodayLog();
        echo $o_result;
    }
    
    function doFreezeStorage() {
        $s_bc = isset($_POST['bc']) ? $_POST['bc'] : '';
        $s_gname = isset($_POST['gn']) ? $_POST['gn'] : '';
        $this->load->model('AdEBSyncStorageM');
        $o_result = $this->AdEBSyncStorageM->doFreezeStorage($s_bc,$s_gname);
        echo json_encode($o_result);
    }
    
    function doUnfreezeStorage() {
        $s_bc = isset($_POST['bc']) ? $_POST['bc'] : '';
        $this->load->model('AdEBSyncStorageM');
        $o_result = $this->AdEBSyncStorageM->doUnfreezeStorage($s_bc);
        echo json_encode($o_result);
    }
    
    /**
     * 获得所有可以同步的门店
     */
    function getAllSyncShop() {
        $this->load->model('AdEBSyncStorageM');
        $o_result = $this->AdEBSyncStorageM->getAllSyncShop();
        echo json_encode($o_result);
    }
    
    /**
     * 获得所有有库存差异的门店
     */
    function getAllDiffStorageShop() {
        $this->load->model('AdEBSyncStorageM');
        $o_result = $this->AdEBSyncStorageM->getAllDiffStorageShop();
        echo json_encode($o_result);
    }
    
    function getAllDiffPriceShop() {
        $this->load->model('AdEBSyncStorageM');
        $o_result = $this->AdEBSyncStorageM->getAllDiffPriceShop();
        echo json_encode($o_result);
    }
    
    function getAllDiffShop(){
        $this->load->model('AdEBSyncStorageM');
        $o_result = $this->AdEBSyncStorageM->getAllDiffShop();
        echo json_encode($o_result);
    }
    
    function syncOnlineStorage() {
        $s_e_id = isset($_POST['eid']) ? $_POST['eid'] : '';
        $b_diff = isset($_POST['diff']) ? true : false;
        $this->load->model('AdEBSyncStorageM');
        $o_result = $this->AdEBSyncStorageM->syncOnlineStorage($s_e_id, $b_diff);
        echo json_encode($o_result);        
    }
    
    function syncPrice(){
        $s_e_id = isset($_POST['eid']) ? $_POST['eid'] : '';
        $b_diff = isset($_POST['diff']) ? true : false;
        $this->load->model('AdEBSyncStorageM');
        $o_result = $this->AdEBSyncStorageM->syncPrice($s_e_id, $b_diff);
        echo json_encode($o_result);
    }
    
    function syncSkuList(){
        $s_e_id = isset($_POST['eid']) ? $_POST['eid'] : '';        
        $this->load->model('AdEBSyncStorageM');
        $o_result = $this->AdEBSyncStorageM->syncSkuList($s_e_id);
        echo json_encode($o_result);;        
    }
        
    function getLogPriceList() {
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $this->load->model('AdEBSyncStorageM');
        $o_result = $this->AdEBSyncStorageM->getLogPriceList($i_page,$i_rows,$_GET);
        echo json_encode($o_result);    
    }
    
    function getLogStockList() {
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $this->load->model('AdEBSyncStorageM');
        $o_result = $this->AdEBSyncStorageM->getLogStockList($i_page,$i_rows,$_GET);
        echo json_encode($o_result);    
    }
    
    function getLogSKUList() {
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $this->load->model('AdEBSyncStorageM');
        $o_result = $this->AdEBSyncStorageM->getLogSKUList($i_page,$i_rows,$_GET);
        echo json_encode($o_result);    
    }
    
    function outputPlatformCSV() {
        $s_e_id = isset($_POST['oid']) ? $_POST['oid'] : '';
        $s_shop_name = isset($_POST['sn']) ? $_POST['sn'] : '';
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        if ($s_e_id != '' && $s_shop_name == '') {
            $o_result['state'] = false;
            $o_result['msg'] = '缺少参数';
            echo json_encode($o_result);
            exit();
        }
        $this->load->model($this->_s_model);
        $s_url = $this->AdEBShopGoodsM->outPfCSV($s_e_id, $s_shop_name);
        $o_result['state'] = true;
        $o_result['msg'] = $s_url;
        echo json_encode($o_result);
    }
        
    /**
     * 刷新库存
     */
    function refreshStorage() {        
        $s_e_id = isset($_POST['oid']) ? $_POST['oid'] : '';
        $this->load->model($this->_s_model);
        $o_result = $this->AdEBShopGoodsM->refreshStorage($s_e_id);     
        echo json_encode($o_result);
    }
    
    /**
     * 刷新零售价
     */
    function refreshPrice() {        
        $s_e_id = isset($_POST['oid']) ? $_POST['oid'] : '';
        $this->load->model($this->_s_model);
        $o_result = $this->AdEBShopGoodsM->refreshPrice($s_e_id);     
        echo json_encode($o_result);
    }
    
    /**
     * 上传CSV信息
     * 并预览
     */
    function uploadInfo() {
        $this->load->helper('url');
        //手动创建文件上传目录
        $config['upload_path'] = './uploads/'; //根目录下的uploads文件(即相对于入口文件)
        $config['allowed_types'] = 'csv|csv';
        $config['max_size'] = '10000'; //允许上传文件大小的最大值（以K为单位）。该参数为0则不限制。
        $config['file_name'] = uniqid();
        $this->load->library('upload', $config);
        $result = $this->upload->do_upload('file_csv');
        if (!$result) {
            $error = array(state => '0', 'error' => $this->upload->display_errors());
            echo json_encode($error);
        } else {
            $data = $this->upload->data();
            $bs_e_id = $this->input->post('e_shop_id');
            if ($bs_e_id == '') {
                $error = array(state => '0', 'error' => '没有选择店铺');
                echo json_encode($error);
                exit();
            }
            $o_result = $this->_loadCSV($data['full_path']);
            $this->load->model($this->_s_model);

            //获得临时表名
            $s_table_name_temp = $this->AdEBShopGoodsM->_getTempTableName();
            //导入临时表
            $i_rows = $this->AdEBShopGoodsM->inputCSV($s_table_name_temp, $o_result, $bs_e_id);
            $o_response = array('tbn' => $s_table_name_temp, 'rows' => $i_rows, 'state' => true);
            echo json_encode($o_response);
            /*
             * {"file_name":"5de4ebe704030.csv","file_type":"text\/plain","file_path":"D:\/xampp\/htdocs\/CVSManager\/uploads\/","full_path":"D:\/xampp\/htdocs\/CVSManager\/uploads\/5de4ebe704030.csv","raw_name":"5de4ebe704030","orig_name":"5de4ebe704030.csv","client_name":"\u6279\u91cf\u5efa\u5e97-\u5e10\u53f7\u4fe1\u606f\u6a21\u677f00000(1).csv","file_ext":".csv","file_size":6.45,"is_image":false,"image_width":null,"image_height":null,"image_type":"","image_size_str":""}}}Ok
             */
        }
    }

    /**
     * 加载解析CSV
     * @param type $_file_path
     * @return type
     */
    function _loadCSV($_file_path) {
        $this->load->library('CSVReader');
        return $this->csvreader->parse_file($_file_path);
    }

    /**
     * 加载预览数据
     */
    function loadPreview() {
        $s_tbn = $_GET['tbn'];
        $this->load->model($this->_s_model);
        $o_result = $this->AdEBShopGoodsM->_loadTempTable($s_tbn);
        echo json_encode($o_result);
    }

    /**
     * 覆盖导入数据
     */
    function coverData() {
        $s_tbn = $_POST['tbn'];
        $this->load->model($this->_s_model);
        $b_result = $this->AdEBShopGoodsM->doCoverSQL($s_tbn);
        $o_result = array('state' => $b_result);
        echo json_encode($o_result);
    }
    
    function deletePreviewData(){
        $s_tbn = $_POST['tbn'];
        $this->load->model($this->_s_model);
        $this->AdEBShopGoodsM->_dropTempTable($s_tbn);        
    }
}
