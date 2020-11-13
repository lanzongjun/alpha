<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 易捷-资金池
 *
 * @author Vincent
 */
class AdYJCashPoolC extends CI_Controller {
    var $_s_view = 'YJCashPool';
    var $_s_model = 'AdYJCashPoolM';
    
    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdYJCashPoolC';
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
        $o_result = $this->AdYJCashPoolM->_getList($i_page,$i_rows);
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
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size'] = '10000'; //允许上传文件大小的最大值（以K为单位）。该参数为0则不限制。
        $config['file_name'] = uniqid();
        $this->load->library('upload', $config);
        $result = $this->upload->do_upload('file_xls');
        if (!$result) {
            $error = array(state => '0', 'error' => $this->upload->display_errors());
            echo json_encode($error);
        } else {
            $data = $this->upload->data();
            $s_file_path = $data['full_path'];            
            $this->load->model($this->_s_model);
            //获得临时表名
            $s_table_name_temp = $this->AdYJCashPoolM->_getTempTableName();
            
            //导入临时表
            $i_rows = $this->AdYJCashPoolM->inputXls($s_table_name_temp, $s_file_path);
            $o_response = array('tbn' => $s_table_name_temp, 'rows' => $i_rows, 'state' => true);
            echo json_encode($o_response);
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
        $o_result = $this->AdYJCashPoolM->_loadTempTable($s_tbn);
        echo json_encode($o_result);
    }
    
    /**
     * 追加导入数据
     */
    function appendData() {        
        $s_tbn = $_POST['tbn'];
        $this->load->model($this->_s_model);
        $b_result = $this->AdYJCashPoolM->doAppendSQL($s_tbn);
        $o_result = array('state'=>$b_result);
        echo json_encode($o_result);
    }
        
    function deletePreviewData(){
        $s_tbn = $_POST['tbn'];
        $this->load->model($this->_s_model);
        $this->AdYJCashPoolM->_dropTempTable($s_tbn);        
    }
}
