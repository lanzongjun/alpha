<?php

/**
 * Description of AdMTSyncOrderM
 *
 * @author Vincent
 */
class AdMTSyncOrderM extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    /**
     * 商家确认订单
     * @param type $l_order_id
     * @return string
     */
    function doConfirmOrder($l_order_id) {
        $this->load->library('ThriftMt');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $o_res = $this->thriftmt->doConfirmOrder($l_order_id);
            $o_result['state'] = $o_res->errorNo == 0;
            $o_result['msg'] = $o_res->message == "success" ? $o_res->message : $o_res->message . ':' . $o_res->error;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '美团接口 doConfirmOrder\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "doConfirmOrder-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }
    
    /**
     * 商家取消订单
     * @param type $l_order_id
     * @param type $s_reason
     * @param type $s_reason_code
     * @return string
     */
    function doCancelOrder($l_order_id, $s_reason='客服取消，用户测试', $s_reason_code='1202') {
        $this->load->library('ThriftMt');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $o_res = $this->thriftmt->doCancelOrder($l_order_id,$s_reason,$s_reason_code);
            $o_result['state'] = $o_res->errorNo == 0;
            $o_result['msg'] = $o_res->message == "success" ? $o_res->message : $o_res->message . ':' . $o_res->error;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '美团接口 doCancelOrder\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "doCancelOrder-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }
    
    /**
     * 订单确认退款请求
     * @param type $l_order_id
     * @param type $s_reason
     * @return string
     */
    function doRefundAgree($l_order_id,$s_reason='同意退款') {
        $this->load->library('ThriftMt');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $o_res = $this->thriftmt->doOrderRefundAgree($l_order_id,$s_reason);
            $o_result['state'] = $o_res->errorNo == 0;
            $o_result['msg'] = $o_res->message == "success" ? $o_res->message : $o_res->message . ':' . $o_res->error;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '美团接口 doRefundAgree\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "doRefundAgree-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }
    
    /**
     * 驳回订单退款申请
     * @param type $l_order_id
     * @param type $s_reason
     * @return string
     */
    function doRefundReject($l_order_id,$s_reason='不同意退款') {
        $this->load->library('ThriftMt');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $o_res = $this->thriftmt->doOrderRefundReject($l_order_id,$s_reason);
            $o_result['state'] = $o_res->errorNo == 0;
            $o_result['msg'] = $o_res->message == "success" ? $o_res->message : $o_res->message . ':' . $o_res->error;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '美团接口 doOrderRefundReject\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "doOrderRefundReject-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }
    
    /**
     * 拉取用户真实手机号
     * @param type $s_api_id APP方门店id
     * @return string
     */
    function doPullPhone($s_api_id='') {
        $this->load->library('ThriftMt');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $o_res = $this->thriftmt->doBatchPullPhoneNumber($s_api_id);
            $o_result['state'] = $o_res->errorNo == 0;
            $o_result['msg'] = $o_res->message == "success" ? $o_res->message : $o_res->message . ':' . $o_res->error;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '美团接口 doPullPhone\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "doPullPhone-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }
    
    /**
     * 是否自动接单
     * @return object
     */ 
    function isOrderAutoConfirm() {
        $this->load->library('ThriftMt');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $b_res = $this->thriftmt->isOrderAutoConfirm();
            $o_result['msg'] = $b_res;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '美团接口 isOrderAutoConfirm\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "isOrderAutoConfirm-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }

    /**
     * 开始自动接单
     * @return object
     */
    function startOrderConfirm() {
        $this->load->library('ThriftMt');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $o_res = $this->thriftmt->startOrderConfirm();
            $o_result['state'] = $o_res->errorNo == 0;
            $o_result['msg'] = $o_res->message == "success" ? $o_res->message : $o_res->message . ':' . $o_res->error;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '美团接口 startOrderConfirm\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "startOrderConfirm-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }

    /**
     * 停止自动接单
     * @return object
     */
    function stopOrderConfirm() {
        $this->load->library('ThriftMt');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $o_res = $this->thriftmt->stopOrderConfirm();
            $o_result['state'] = $o_res->errorNo == 0;
            $o_result['msg'] = $o_res->message == "success" ? $o_res->message : $o_res->message . ':' . $o_res->error;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '美团接口 stopOrderConfirm\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "stopOrderConfirm-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }
    
    function getOrderInfo($s_order_id){
        $this->load->library('ThriftMt');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $o_res = $this->thriftmt->getOrderInfo($s_order_id);
            $o_result['state'] = $o_res->errorNo == 0;
            $o_result['msg'] = $o_res->message == "success" ? $o_res->message : $o_res->message . ':' . $o_res->error;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '美团接口 getOrderInfo\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "getOrderInfo-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }

}
