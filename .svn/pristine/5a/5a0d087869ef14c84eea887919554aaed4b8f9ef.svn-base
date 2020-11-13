<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 临时-易捷结算价目录信息
 *
 * @author Vincent
 */
class AdYJSPriceC extends CI_Controller {
    var $_s_view = 'YJSPrice';
    var $_s_model = 'AdYJSPriceM';
    
    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdYJSPriceC';
        $this->load->helper('url');
        $this->load->view("admin/tempUpdate/$this->_s_view", $data);
    }

    /**
     * 获得信息列表
     */
    function getList() {
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $s_goods_name = isset($_GET['gn']) ? $_GET['gn'] : '';
        $s_barcode = isset($_GET['bc']) ? $_GET['bc'] : '';
        $this->load->model($this->_s_model);
        $o_result = $this->AdYJSPriceM->_getList($i_page,$i_rows, $s_goods_name, $s_barcode);
        echo json_encode($o_result);
    }
    
    function getNewList() {
        $this->load->model($this->_s_model);
        $o_result = $this->AdYJSPriceM->getNewList();
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
            $o_result = $this->_loadCSV($data['full_path']);
            $this->load->model($this->_s_model);

            //获得临时表名
            $s_table_name_temp = $this->AdYJSPriceM->_getTempTableName();
            //导入临时表
            $i_rows = $this->AdYJSPriceM->inputCSV($s_table_name_temp, $o_result);
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
        $o_result = $this->AdYJSPriceM->_loadTempTable($s_tbn);
        echo json_encode($o_result);
    }
    
    /**
     * 覆盖导入数据
     */
    function doDiffUpdate() {
        $s_tbn = $_POST['tbn'];
        $this->load->model($this->_s_model);
        $o_result = $this->AdYJSPriceM->doDiffUpdate($s_tbn);
        echo json_encode($o_result);
    }
}
