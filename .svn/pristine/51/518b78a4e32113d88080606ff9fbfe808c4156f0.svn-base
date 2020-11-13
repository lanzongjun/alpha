<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdEBShop
 *
 * @author Vincent
 */
class AdYJSyncStorageM extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
        $this->load->dbforge();
    }

    /**
     * SKU是否自动更新
     * @return object
     */
    function isSKUAutoUpdate() {
        $this->load->library('ThriftSH');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $b_res = $this->thriftsh->isSKUAutoUpdate();
            $o_result['msg'] = $b_res;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '石化接口 isSKUAutoUpdate\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "isSKUAutoUpdate-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }

    /**
     * 库存是否自动更新
     * @return object
     */
    function isStorageAutoUpdate() {
        $this->load->library('ThriftSH');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $b_res = $this->thriftsh->isStorageAutoUpdate();
            $o_result['msg'] = $b_res;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '石化接口 isStorageAutoUpdate\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "isStorageAutoUpdate-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }

    /**
     * 获得库存自动更新状态
     * @return object
     */
    function getStorageUpdateState() {
        $this->load->library('ThriftSH');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $o_res = $this->thriftsh->getStorageUpdateState();
            $o_result['msg'] = $o_res;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '石化接口 getStorageUpdateState\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "getStorageUpdateState-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }

    /**
     * 获得SKU自动更新状态
     * @return object
     */
    function getSKUUpdateState() {
        $this->load->library('ThriftSH');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $o_res = $this->thriftsh->getSKUUpdateState();
            $o_result['msg'] = $o_res;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '石化接口 getSKUUpdateState\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "getSKUUpdateState-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }

    /**
     * 开始SKU自动更新
     * @return object
     */
    function startSKUAutoUpdate() {
        $this->load->library('ThriftSH');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $o_res = $this->thriftsh->startSKUAutoUpdate();
            $o_result['state'] = $o_res->errorNo == 0;
            $o_result['msg'] = $o_res->message == "success" ? $o_res->message : $o_res->message . ':' . $o_res->error;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '石化接口 startSKUAutoUpdate\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "startSKUAutoUpdate-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }

    /**
     * 停止SKU自动更新
     * @return object
     */
    function stopSKUAutoUpdate() {
        $this->load->library('ThriftSH');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $o_res = $this->thriftsh->stopSKUAutoUpdate();
            $o_result['state'] = $o_res->errorNo == 0;
            $o_result['msg'] = $o_res->message == "success" ? $o_res->message : $o_res->message . ':' . $o_res->error;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '石化接口 stopSKUAutoUpdate\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "stopSKUAutoUpdate-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }

    /**
     * 开始库存自动更新
     * @return object
     */
    function startStorageAutoUpdate() {
        $this->load->library('ThriftSH');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $o_res = $this->thriftsh->startStorageAutoUpdate();
            $o_result['state'] = $o_res->errorNo == 0;
            $o_result['msg'] = $o_res->message == "success" ? $o_res->message : $o_res->message . ':' . $o_res->error;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '石化接口 startStorageAutoUpdate\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "startStorageAutoUpdate-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }

    /**
     * 停止库存自动更新
     * @return object
     */
    function stopStorageAutoUpdate() {
        $this->load->library('ThriftSH');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $o_res = $this->thriftsh->stopStorageAutoUpdate();
            $o_result['state'] = $o_res->errorNo == 0;
            $o_result['msg'] = $o_res->message == "success" ? $o_res->message : $o_res->message . ':' . $o_res->error;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '石化接口 stopStorageAutoUpdate\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "stopStorageAutoUpdate-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }

    function doSyncTest() {
        $this->load->library('ThriftSH');
        try {

            $o_res = $this->thriftsh->getShopStocks('', '');
            return $o_res;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '石化接口 getShopStocks\r\n' . $e->getMessage());
//            $o_result['state'] = false;
//            $o_result['msg'] = "新增结算记录-异常中断！\r\n" . $e->getMessage();
//            return $o_result;
            return null;
        }
    }

    function doSyncSKUTest($s_org_code) {
        $this->load->library('ThriftSH');
        try {
            $o_res = $this->thriftsh->getShopPluList($s_org_code);
            return $o_res;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '石化接口 getShopStocks\r\n' . $e->getMessage());
//            $o_result['state'] = false;
//            $o_result['msg'] = "新增结算记录-异常中断！\r\n" . $e->getMessage();
//            return $o_result;
            return null;
        }
    }

}
