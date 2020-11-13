<?php

/**
 * Description of AdEBSyncOrderM
 *
 * @author Vincent
 */
class AdEBSyncOrderM extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    /**
     * 商家确认订单
     * @param type $s_order_id
     * @return string
     */
    function doConfirmOrder($s_order_id) {
        $this->load->library('ThriftEle');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $o_res = $this->thriftele->doConfirmOrder($s_order_id);
            $o_result['state'] = $o_res->errorNo == 0;
            $o_result['msg'] = $o_res->message == "success" ? $o_res->message : $o_res->message . ':' . $o_res->error;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '饿了么接口 doConfirmOrder\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "doConfirmOrder-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }
    
    /**
     * 订单确认退款请求
     * @param type $s_order_id
     * @param type $s_refund_order_id
     * @return string
     */
    function doRefundAgree($s_order_id,$s_refund_order_id) {
        $this->load->library('ThriftEle');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $o_res = $this->thriftele->doOrderRefundAgree($s_order_id,$s_refund_order_id);
            $o_result['state'] = $o_res->errorNo == 0;
            $o_result['msg'] = $o_res->message == "success" ? $o_res->message : $o_res->message . ':' . $o_res->error;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '饿了么接口 doRefundAgree\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "doRefundAgree-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }
    
    /**
     * 驳回订单退款申请
     * @param type $s_order_id
     * @param type $s_refund_order_id
     * @param type $s_reason
     * @return string
     */
    function doRefundReject($s_order_id,$s_refund_order_id,$s_reason='refuse') {
        $this->load->library('ThriftEle');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $o_res = $this->thriftele->doOrderRefundReject($s_order_id,$s_refund_order_id,$s_reason);
            $o_result['state'] = $o_res->errorNo == 0;
            $o_result['msg'] = $o_res->message == "success" ? $o_res->message : $o_res->message . ':' . $o_res->error;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '饿了么接口 doOrderRefundReject\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "doOrderRefundReject-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }
    
    /**
     * 是否自动接单
     * @return object
     */ 
    function isOrderAutoConfirm() {
        $this->load->library('ThriftEle');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $b_res = $this->thriftele->isOrderAutoConfirm();
            $o_result['msg'] = $b_res;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '饿了么接口 isOrderAutoConfirm\r\n' . $e->getMessage());
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
        $this->load->library('ThriftEle');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $o_res = $this->thriftele->startOrderConfirm();
            $o_result['state'] = $o_res->errorNo == 0;
            $o_result['msg'] = $o_res->message == "success" ? $o_res->message : $o_res->message . ':' . $o_res->error;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '饿了么接口 startOrderConfirm\r\n' . $e->getMessage());
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
        $this->load->library('ThriftEle');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $o_res = $this->thriftele->stopOrderConfirm();
            $o_result['state'] = $o_res->errorNo == 0;
            $o_result['msg'] = $o_res->message == "success" ? $o_res->message : $o_res->message . ':' . $o_res->error;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '饿了么接口 stopOrderConfirm\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "stopOrderConfirm-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }
    
    function getOrderInfo($s_order_id){
        $this->load->library('ThriftEle');
        $o_result['state'] = true;
        $o_result['msg'] = "";
        try {
            $o_res = $this->thriftele->getOrderInfo($s_order_id);
            $o_result['state'] = $o_res->errorNo == 0;
            $o_result['msg'] = $o_res->message == "success" ? $o_res->message : $o_res->message . ':' . $o_res->error;
            return $o_result;
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '饿了么接口 getOrderInfo\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "getOrderInfo-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }
    }
}
