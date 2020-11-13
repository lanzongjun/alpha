<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 站点库存信息
 *
 * @author Vincent
 */
class AdYJShopStorageC extends CI_Controller {
    var $_s_view = 'YJShopStorage';
    var $_s_model = 'AdYJShopStorageM';

    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdYJShopStorageC';
        $this->load->helper('url');
        $this->load->view("admin/baseConfig/$this->_s_view", $data);
    }

    /**
     * 获得信息列表
     */
    function getList() {
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $this->load->model($this->_s_model);
        $o_result = $this->AdYJShopStorageM->getList($i_page, $i_rows, $_GET);
        echo json_encode($o_result);
    }

    function isSKUAutoUpdate() {
        $this->load->model('AdYJSyncStorageM');
        $o_result = $this->AdYJSyncStorageM->isSKUAutoUpdate();
        echo json_encode($o_result);
    }

    function isStorageAutoUpdate() {
        $this->load->model('AdYJSyncStorageM');
        $o_result = $this->AdYJSyncStorageM->isStorageAutoUpdate();
        echo json_encode($o_result);
    }

    function getStorageUpdateState() {
        $this->load->model('AdYJSyncStorageM');
        $o_result = $this->AdYJSyncStorageM->getStorageUpdateState();
        echo json_encode($o_result);
    }

    function getSKUUpdateState() {
        $this->load->model('AdYJSyncStorageM');
        $o_result = $this->AdYJSyncStorageM->getSKUUpdateState();
        echo json_encode($o_result);
    }

    function startSKUAutoUpdate() {
        $this->load->model('AdYJSyncStorageM');
        $o_result = $this->AdYJSyncStorageM->startSKUAutoUpdate();
        echo json_encode($o_result);
    }

    function stopSKUAutoUpdate() {
        $this->load->model('AdYJSyncStorageM');
        $o_result = $this->AdYJSyncStorageM->stopSKUAutoUpdate();
        echo json_encode($o_result);
    }

    function startStorageAutoUpdate() {
        $this->load->model('AdYJSyncStorageM');
        $o_result = $this->AdYJSyncStorageM->startStorageAutoUpdate();
        echo json_encode($o_result);
    }

    function stopStorageAutoUpdate() {
        $this->load->model('AdYJSyncStorageM');
        $o_result = $this->AdYJSyncStorageM->stopStorageAutoUpdate();
        echo json_encode($o_result);
    }

    function doSyncTest() {
        $this->load->model('AdYJSyncStorageM');
        $o_result = $this->AdYJSyncStorageM->doSyncTest();
        print_r($o_result);
    }

    function doSyncSKUTest() {
        $s_org_code = isset($_POST['eid']) ? $_POST['eid'] : '';
        if ($s_org_code != '') {
            $this->load->model('AdYJSyncStorageM');
            $o_result = $this->AdYJSyncStorageM->doSyncSKUTest($s_org_code);
            print_r($o_result);
        } else {
            echo 'eid is null';
        }
    }

    function deletePreviewData(){
        $s_tbn = $_POST['tbn'];
        $this->load->model($this->_s_model);
        $this->AdYJShopStorageM->_dropTempTable($s_tbn);        
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
        $config['max_size'] = '20000'; //允许上传文件大小的最大值（以K为单位）。该参数为0则不限制。
        $config['file_name'] = uniqid();
        $this->load->library('upload', $config);
        $result = $this->upload->do_upload('file_csv');
        if (!$result) {
            $error = array(state => '0', 'error' => $this->upload->display_errors());
            echo json_encode($error);
        } else {
            $data = $this->upload->data();
            $o_result = $this->_loadCSV($data['full_path']);
            $this->load->model($this->_s_model);

            //获得临时表名
            $s_table_name_temp = $this->AdYJShopStorageM->_getTempTableName();
            //导入临时表
            $i_rows = $this->AdYJShopStorageM->inputCSV($s_table_name_temp, $o_result);
            $o_response = array('tbn' => $s_table_name_temp, 'rows' => $i_rows, 'state' => true);
            echo json_encode($o_response);
            /*
             * {"file_name":"5de4ebe704030.csv","file_type":"text\/plain","file_path":"D:\/xampp\/htdocs\/CVSManager\/uploads\/","full_path":"D:\/xampp\/htdocs\/CVSManager\/uploads\/5de4ebe704030.csv","raw_name":"5de4ebe704030","orig_name":"5de4ebe704030.csv","client_name":"\u6279\u91cf\u5efa\u5e97-\u5e10\u53f7\u4fe1\u606f\u6a21\u677f00000(1).csv","file_ext":".csv","file_size":6.45,"is_image":false,"image_width":null,"image_height":null,"image_type":"","image_size_str":""}}}Ok
             */
        }
    }

    function updateCacheGoodsEle(){
        $o_return = array('state' => true,'msg'=>"SUCCESS");
        $this->load->model('AdCacheGoodsM');
        $b_return = $this->AdCacheGoodsM->updateGoodsEle();
        $o_return['state'] = $b_return;
        $o_return['msg'] = $b_return ? "SUCCESS" : "FAULT";
        echo json_encode($o_return);
    }
    
    function updateCacheGoodsMT(){
        $o_return = array('state' => true,'msg'=>"SUCCESS");
        $this->load->model('AdCacheGoodsM');
        $b_return = $this->AdCacheGoodsM->updateGoodsMT();
        $o_return['state'] = $b_return;
        $o_return['msg'] = $b_return ? "SUCCESS" : "FAULT";
        echo json_encode($o_return);
    }
    
    /**
     * 更新商品缓存
     */
    function updateCacheGoods() {
        $o_return = array('state' => true,'msg'=>"SUCCESS");
        $this->load->model('AdCacheGoodsM');
        $b_return1 = $this->AdCacheGoodsM->updateSaleOnlineGoods();
        $b_return2 = $this->AdCacheGoodsM->updateGoodsBalancePrice();
        $b_return = $b_return1 && $b_return2;
        $o_return['state'] = $b_return;
        $o_return['msg'] = $b_return ? "SUCCESS" : "FAULT";
        echo json_encode($o_return);
    }
    
    /**
     * 根据当前库存数据，更新总商品库
     */
    function updateBaseGoods() {
        $this->load->model($this->_s_model);
        $o_result = $this->AdYJShopStorageM->updateBaseGoods();
        echo json_encode($o_result);
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
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $s_tbn = $_GET['tbn'];
        $this->load->model($this->_s_model);
        $o_result = $this->AdYJShopStorageM->_loadTempTable($s_tbn, $i_page, $i_rows);
        echo json_encode($o_result);
    }

    /**
     * 覆盖导入数据
     */
    function coverData() {
        $s_tbn = $_POST['tbn'];
        $this->load->model($this->_s_model);
        $b_result = $this->AdYJShopStorageM->doCoverSQL($s_tbn);
        $o_result = array('state' => $b_result);
        echo json_encode($o_result);
    }

}
