<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 饿百账单信息
 *
 * @author Vincent
 */
class IFSpiderDataC extends CI_Controller {

    var $_s_view = 'SpiderMarket';
    var $_s_model = 'IFSpiderDataM';

    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'IFSpiderDataC';
        $this->load->helper('url');
        $this->load->view("admin/interface/$this->_s_view", $data);
    }

    function inputData() {
        $s_data = isset($_POST['data']) ? $_POST['data'] : '';
        $s_sname = isset($_POST['sname']) ? $_POST['sname'] : '';
        if ($s_data == '') {
            echo 'data is empty';
            exit();
        }
        $this->load->model($this->_s_model);
        $this->IFSpiderDataM->inputMarketData($s_data, $s_sname);
    }
    
    function outputData() {        
        $i_sui_id = isset($_GET['suid']) ? $_GET['suid'] : '';
        $this->load->model($this->_s_model);
        $o_result = $this->IFSpiderDataM->getList($i_page, $i_rows, $s_shopname, $s_gname, $i_sui_id);
        echo json_encode($o_result);
    }
    
    /**
     * 获得信息列表
     */
    function getList() {
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $s_shopname = isset($_GET['ss']) ? $_GET['ss'] : '';
        $s_gname = isset($_GET['gn']) ? $_GET['gn'] : '';
        $i_sui_id = isset($_GET['suid']) ? $_GET['suid'] : '';
        $this->load->model($this->_s_model);
        $o_result = $this->IFSpiderDataM->getList($i_page, $i_rows, $s_shopname, $s_gname, $i_sui_id);
        echo json_encode($o_result);
    }

    /**
     * 获得CSV文件列表
     */
    function getCSVList() {
        $i_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $i_rows = isset($_GET['rows']) ? $_GET['rows'] : 50;
        $this->load->model($this->_s_model);
        $o_result = $this->IFSpiderDataM->getCSVList($i_page, $i_rows);
        echo json_encode($o_result);
    }
    
    /**
     * 获得数据仓列表
     */
    function getOWList() {
        $this->load->model($this->_s_model);
        $o_result = $this->IFSpiderDataM->getOWList();
        echo json_encode($o_result);
    }

    /**
     * 导入CSV
     */
    function doInputCSV() {
        $s_filename = isset($_POST['s']) ? $_POST['s'] : '';
        $i_sui_id = isset($_POST['sid']) ? $_POST['sid'] : '';
        $i_ow_id = isset($_POST['owid']) ? $_POST['owid'] : '';
        if ($s_filename == '' || $i_sui_id == '' || $i_ow_id == '') {
            $o_result = array(
                'tbn' => '',
                'success' => 0,
                'fail' => 0,
                'repeat' => 0
            );
        } else {
            $this->load->model($this->_s_model);
            $o_result = $this->IFSpiderDataM->doInputCSV($s_filename, $i_sui_id, $i_ow_id);
        }
        echo json_encode($o_result);
    }

    function doRebuildCSV() {
        $o_result = array('state' => false, 'msg' => 'fail');
        $i_sui_id = isset($_POST['id']) ? $_POST['id'] : '';
        if ($i_sui_id == '') {
            $o_result = array(
                'state' => false,
                'msg' => 'fail'
            );
        } else {
            $this->load->model($this->_s_model);
            $o_result['state'] = $this->IFSpiderDataM->doRebuildCSV($i_sui_id);
            $o_result['msg'] = 'finish';
        }
        echo json_encode($o_result);
    }
    
    function doDeleteCSV() {
        $o_result = array('state' => false, 'msg' => 'fail');
        $i_sui_id = isset($_POST['id']) ? $_POST['id'] : '';
        if ($i_sui_id == '') {
            $o_result = array(
                'state' => false,
                'msg' => 'fail'
            );
        } else {
            $this->load->model($this->_s_model);
            $o_result['state'] = $this->IFSpiderDataM->doDeleteCSV($i_sui_id);
            $o_result['msg'] = 'finish';
        }
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
            $s_table_name_temp = $this->IFSpiderDataM->_getTempTableName();
            //导入临时表
            $i_rows = $this->IFSpiderDataM->inputCSV($s_table_name_temp, $o_result);
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
        $o_result = $this->IFSpiderDataM->_loadTempTable($s_tbn);
        echo json_encode($o_result);
    }

    /**
     * 追加导入数据
     */
    function appendData() {
        $s_tbn = isset($_POST['tbn']) ? $_POST['tbn'] : '';
        $i_id = isset($_POST['id']) ? $_POST['id'] : '';
        $i_sow_id = isset($_POST['owid']) ? $_POST['owid'] : '';
        if ($s_tbn == '' || $i_id == '' || $i_sow_id == '') {
            $o_result = array(
                'state' => false
            );
        } else {
            $this->load->model($this->_s_model);
            $b_result = $this->IFSpiderDataM->doAppendSQL($s_tbn, $i_id, $i_sow_id);
            $o_result = array('state' => $b_result);
        }
        echo json_encode($o_result);
    }
    
    function decodeData() {
        $s_tbn = isset($_POST['tbn']) ? $_POST['tbn'] : '';
        $i_id = isset($_POST['id']) ? $_POST['id'] : '';
        if ($s_tbn == '' || $i_id == '') {
            $o_result = array(
                'state' => false
            );
        } else {
            $this->load->model($this->_s_model);
            $this->IFSpiderDataM->doDecodeData($s_tbn, $i_id);
            $o_result = array('state' => true);
        }
        echo json_encode($o_result);
    }
}
