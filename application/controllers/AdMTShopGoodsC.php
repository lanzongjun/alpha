<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 美团店铺商品信息
 *
 * @author Vincent
 */
class AdMTShopGoodsC extends CI_Controller {

    var $_s_view = 'MTShopGoods';
    var $_s_model = 'AdMTShopGoodsM';

    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdMTShopGoodsC';
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
        $s_filter_up = isset($_GET['fu']) ? $_GET['fu'] : '';
        $this->load->model($this->_s_model);
        $o_result = $this->AdMTShopGoodsM->getList($i_page, $i_rows, $s_org_id, 
                $s_goods_name, $s_barcode, $s_filter_storage, $s_filter_up);
        echo json_encode($o_result);
    }
    
    function outputNewGoods(){
        $s_shop_id = isset($_GET['sid']) ? $_GET['sid'] : '';
        $this->load->model($this->_s_model);
        $this->AdMTShopGoodsM->outputNewGoods($s_shop_id);
    }
    
    function getNewGoods() {
        $s_shop_id = isset($_POST['sid']) ? $_POST['sid'] : '';
        $this->load->model($this->_s_model);
        $o_result = $this->AdMTShopGoodsM->getNewGoods($s_shop_id);
        echo json_encode($o_result);
    }

    /**
     * 获得店铺商品
     */
    function getShopGoods() {
        $s_shop_id = isset($_GET['sid']) ? $_GET['sid'] : '';
        if ($s_shop_id !== '') {
            $this->load->model($this->_s_model);
            $o_result = $this->AdMTShopGoodsM->getShopGoods($s_shop_id);
            echo json_encode($o_result);
        } else {
            echo '';
        }
    }
    
    function keepStockTodayLog(){
        $this->load->model('AdMTSyncStorageM');
        $o_result = $this->AdMTSyncStorageM->keepStockTodayLog();
        echo $o_result;
    }
    
    function keepSKUTodayLog(){
        $this->load->model('AdMTSyncStorageM');
        $o_result = $this->AdMTSyncStorageM->keepSKUTodayLog();
        echo $o_result;
    }
    
    function doFreezeStorage() {
        $s_bc = isset($_POST['bc']) ? $_POST['bc'] : '';
        $s_gname = isset($_POST['gn']) ? $_POST['gn'] : '';
        $this->load->model('AdMTSyncStorageM');
        $o_result = $this->AdMTSyncStorageM->doFreezeStorage($s_bc,$s_gname);
        echo json_encode($o_result);
    }
    
    function doUnfreezeStorage() {
        $s_bc = isset($_POST['bc']) ? $_POST['bc'] : '';
        $this->load->model('AdMTSyncStorageM');
        $o_result = $this->AdMTSyncStorageM->doUnfreezeStorage($s_bc);
        echo json_encode($o_result);
    }
    
    function getAllSyncShop() {
        $this->load->model('AdMTSyncStorageM');
        $o_result = $this->AdMTSyncStorageM->getAllSyncShop();
        echo json_encode($o_result);
    }
    
    function syncOnlineStorage() {
        $s_m_id = isset($_POST['mid']) ? $_POST['mid'] : '';
        $b_diff = isset($_POST['diff']) ? true : false;
        $this->load->model('AdMTSyncStorageM');
        $o_result = $this->AdMTSyncStorageM->syncOnlineStorage($s_m_id, $b_diff);
        echo json_encode($o_result);;        
    }
    
    function syncSkuList(){
        $s_m_id = isset($_POST['mid']) ? $_POST['mid'] : '';        
        $this->load->model('AdMTSyncStorageM');
        $o_result = $this->AdMTSyncStorageM->syncSkuList($s_m_id);
        echo json_encode($o_result);;        
    }
    
    function getLogStockList() {
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $this->load->model('AdMTSyncStorageM');
        $o_result = $this->AdMTSyncStorageM->getLogStockList($i_page,$i_rows,$_GET);
        echo json_encode($o_result);    
    }
    
    function getLogSKUList() {
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $this->load->model('AdMTSyncStorageM');
        $o_result = $this->AdMTSyncStorageM->getLogSKUList($i_page,$i_rows,$_GET);
        echo json_encode($o_result);    
    }

    /**
     * 刷新库存
     */
    function refreshStorage() {        
        $s_m_id = isset($_POST['oid']) ? $_POST['oid'] : '';
        $this->load->model($this->_s_model);
        $o_result = $this->AdMTShopGoodsM->refreshStorage($s_m_id);     
        echo json_encode($o_result);
    }

    /**
     * 刷新零售价
     */
    function refreshPrice() {
        $s_m_id = isset($_POST['oid']) ? $_POST['oid'] : '';
        $this->load->model($this->_s_model);
        $o_result = $this->AdMTShopGoodsM->refreshPrice($s_m_id);
        echo json_encode($o_result);
    }
    
    /**
     * 刷新库存
     */
    function updateStorage() {        
        $s_m_id = isset($_POST['oid']) ? $_POST['oid'] : '';
        $this->load->model($this->_s_model);
        $o_result = $this->AdMTShopGoodsM->updateStorage($s_m_id);     
        echo json_encode($o_result);
    }

    function getAllDiffShop()
    {
        $this->load->model('AdMTSyncStorageM');
        $o_result = $this->AdMTSyncStorageM->getAllDiffShop();
        echo json_encode($o_result);
    }

}
