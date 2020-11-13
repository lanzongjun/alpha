<?php
require_once APPPATH . 'libraries\Tautoload.php';
require_once APPPATH . 'libraries\Thrift\ClassLoader\ThriftClassLoader.php';
require_once APPPATH . 'libraries\NiftyEle\IfaceEleServer.php';
require_once APPPATH . 'libraries\NiftyEle\Types.php';

use Thrift\ClassLoader\ThriftClassLoader;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\TSocketPool;
use Thrift\Transport\TFramedTransport;
use Thrift\Transport\TBufferedTransport;

class ThriftEle {
    public $config;
    
    /** @var CI_Controller $CI */
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
        $thrift_gen = APPPATH . 'libraries/NiftyEle';
        $loader = new ThriftClassLoader();
        $loader->registerNamespace('Thrift',$thrift_lib);
        $loader->registerDefinition('ThriftEle', $thrift_gen);
        $loader->register();
    }
    
    public function doConfirmOrder($s_order_id){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftyEle\IfaceEleServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $b_state = $client->doConfirmOrder($s_order_id);
        $transport->close();        
        return $b_state;
    }
    
    public function isOrderAutoConfirm(){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftyEle\IfaceEleServerClient($protocol);
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
        $client = new \NiftyEle\IfaceEleServerClient($protocol);
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
        $client = new \NiftyEle\IfaceEleServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $o_xception = $client->stopOrderConfirm();
        $transport->close();        
        return $o_xception;
    }
    
    public function doOrderRefundAgree($s_order_id,$s_refund_order_id){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftyEle\IfaceEleServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $o_xception = $client->doOrderRefundAgree($s_order_id,$s_refund_order_id);
        $transport->close();        
        return $o_xception;
    }
    
    public function doOrderRefundReject($s_order_id,$s_refund_order_id,$s_reason){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftyEle\IfaceEleServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $o_xception = $client->doOrderRefundReject($s_order_id,$s_refund_order_id,$s_reason);
        $transport->close();        
        return $o_xception;
    }
    
    public function getShopSkuList($s_shop_id) {
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftyEle\IfaceEleServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $o_shop_sku_list = $client->getShopSkuList($s_shop_id);
        $transport->close();        
        return $o_shop_sku_list;
    }
    
    public function SkuStockUpdateBatch($s_shop_id, $a_upc_count) {        
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftyEle\IfaceEleServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        
        $o_stock_update = new \NiftyEle\StockUpdateList();
        $o_stock_update->shop_id = $s_shop_id;
        $a_update_upc = array();
        foreach ($a_upc_count as $o_upc_count) {
            $o_stock_update_upc = new \NiftyEle\StockUpdateUpc();
            $o_stock_update_upc->barcode = $o_upc_count['upc'];
            $o_stock_update_upc->stocks = $o_upc_count['stock'];            
            array_push($a_update_upc,$o_stock_update_upc);
        }
        
        $o_stock_update->update_upc = $a_update_upc;
        $o_res = $client->SkuStockUpdateBatch($o_stock_update);
        $transport->close();
        return $o_res;
    }
    
    public function SkuPriceUpdateBatch($s_shop_id, $a_upc_price) {        
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftyEle\IfaceEleServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        
        $o_price_update = new \NiftyEle\PriceUpdateList();
        $o_price_update->shop_id = $s_shop_id;
        $a_update_upc = array();
        foreach ($a_upc_price as $o_upc_price) {
            $o_price_update_upc = new \NiftyEle\PriceUpdateUpc();
            $o_price_update_upc->barcode = $o_upc_price['upc'];
            $o_price_update_upc->price = $o_upc_price['price'];            
            array_push($a_update_upc,$o_price_update_upc);
        }
        
        $o_price_update->update_upc = $a_update_upc;
        $o_res = $client->SkuPriceUpdateBatch($o_price_update);
        $transport->close();
        return $o_res;
    }
    
    public function getOrderInfo($s_order_id){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftyEle\IfaceEleServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $o_xception = $client->getOrderInfo($s_order_id);
        $transport->close();        
        return $o_xception;
    }
}
