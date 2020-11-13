<?php
require_once APPPATH . 'libraries\Tautoload.php';
require_once APPPATH . 'libraries\Thrift\ClassLoader\ThriftClassLoader.php';
require_once APPPATH . 'libraries\NiftyMt\IfaceMtServer.php';
require_once APPPATH . 'libraries\NiftyMt\Types.php';

use Thrift\ClassLoader\ThriftClassLoader;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\TSocketPool;
use Thrift\Transport\TFramedTransport;
use Thrift\Transport\TBufferedTransport;

class ThriftMt {
    public $config;
    
    private $CI;

    public function __construct(array $config=array()) {
        $config_default = array(
            'service_ip' => 'localhost',
            'service_port' => 8318,
            'time_out' => 3600000
        );
        $this->config = array_merge($config_default, $config);
        $this->CI = &get_instance();
        
        $thrift_lib = APPPATH . 'libraries/Thrift/';
        $thrift_gen = APPPATH . 'libraries/NiftyMt';
        $loader = new ThriftClassLoader();
        $loader->registerNamespace('Thrift',$thrift_lib);
        $loader->registerDefinition('ThriftMt', $thrift_gen);
        $loader->register();
    }
    
    public function doConfirmOrder($l_order_id){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftyMt\IfaceMtServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $b_state = $client->doConfirmOrder($l_order_id);
        $transport->close();        
        return $b_state;
    }
    
    public function isOrderAutoConfirm(){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftyMt\IfaceMtServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $b_state = $client->isOrderAutoConfirm();
        $transport->close();        
        return $b_state;
    }
    
    public function startOrderConfirm(){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftyMt\IfaceMtServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $o_xception = $client->startOrderConfirm();
        $transport->close();        
        return $o_xception;
    }
    
    public function stopOrderConfirm(){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftyMt\IfaceMtServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $o_xception = $client->stopOrderConfirm();
        $transport->close();        
        return $o_xception;
    }
    
    public function doCancelOrder($l_order_id, $s_reason, $s_reason_code){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftyMt\IfaceMtServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $o_xception = $client->doCancelOrder($l_order_id, $s_reason, $s_reason_code);
        $transport->close();        
        return $o_xception;
    }
    
    
    public function doOrderRefundAgree($l_order_id,$s_reason){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftyMt\IfaceMtServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $o_xception = $client->doOrderRefundAgree($l_order_id,$s_reason);
        $transport->close();        
        return $o_xception;
    }
    
    public function doOrderRefundReject($l_order_id,$s_reason){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftyMt\IfaceMtServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $o_xception = $client->doOrderRefundReject($l_order_id,$s_reason);
        $transport->close();        
        return $o_xception;
    }
    
    public function doBatchPullPhoneNumber($s_app_poi_code){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftyMt\IfaceMtServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $o_res = $client->doBatchPullPhoneNumber($s_app_poi_code);
        $transport->close();        
        return $o_res;
    }
    
    public function getShopSkuList($s_app_poi_code){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftyMt\IfaceMtServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $o_res = $client->getShopSkuList($s_app_poi_code);
        $transport->close();        
        return $o_res;
    }
    
    public function SkuStockStatusUpdate($s_shop_id, $a_stock_info) {        
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftyMt\IfaceMtServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        
        $o_update = new \NiftyMt\TMtUpdateStockList();
        $o_update->shop_id = $s_shop_id;
        $o_update->sku_list = array();
        
        foreach($a_stock_info as $o_stock_info) {
            $o_stock = new \NiftyMt\TMtUpdateStock();
            $o_stock->app_food_code = $o_stock_info->sgm_cid;
            $o_stock->sku_id = $o_stock_info->sgm_gid;
            $o_stock->stock = $o_stock_info->sgm_count-0;
            $o_stock->is_sold_out = $o_stock_info->sgm_online-0;
            array_push($o_update->sku_list,$o_stock);
        }
        
        $o_res = $client->SkuStockStatusUpdate($o_update);
        $transport->close();
        return $o_res;
    }
    
    public function getOrderInfo($s_order_id){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftyMt\IfaceMtServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $o_xception = $client->getOrderInfo($s_order_id);
        $transport->close();        
        return $o_xception;
    }
}
