<?php
require_once APPPATH . 'libraries\Tautoload.php';
require_once APPPATH . 'libraries\Thrift\ClassLoader\ThriftClassLoader.php';
require_once APPPATH . 'libraries\NiftySH\IfaceSHServer.php';
require_once APPPATH . 'libraries\NiftySH\Types.php';

use Thrift\ClassLoader\ThriftClassLoader;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\TSocketPool;
use Thrift\Transport\TFramedTransport;
use Thrift\Transport\TBufferedTransport;

class ThriftSH {
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
        $thrift_gen = APPPATH . 'libraries/NiftySH';
        $loader = new ThriftClassLoader();
        $loader->registerNamespace('Thrift',$thrift_lib);
        $loader->registerDefinition('ThriftSH', $thrift_gen);
        $loader->register();
    }
    
    public function isSKUAutoUpdate(){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftySH\IfaceSHServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $b_state = $client->isSKUAutoUpdate();
        $transport->close();        
        return $b_state;
    }
    
    public function isStorageAutoUpdate(){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftySH\IfaceSHServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $b_state = $client->isStorageAutoUpdate();
        $transport->close();        
        return $b_state;
    }
    
    public function getStorageUpdateState(){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftySH\IfaceSHServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $o_TJobState = $client->getStorageUpdateState();
        $transport->close();        
        return $o_TJobState;
    }
    
    public function getSKUUpdateState(){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftySH\IfaceSHServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $o_TJobState = $client->getSKUUpdateState();
        $transport->close();        
        return $o_TJobState;
    }
    
    public function startSKUAutoUpdate(){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftySH\IfaceSHServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $o_xception = $client->startSKUAutoUpdate();
        $transport->close();        
        return $o_xception;
    }
    
    public function stopSKUAutoUpdate(){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftySH\IfaceSHServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $o_xception = $client->stopSKUAutoUpdate();
        $transport->close();        
        return $o_xception;
    }
    
    public function startStorageAutoUpdate(){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftySH\IfaceSHServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $o_xception = $client->startStorageAutoUpdate();
        $transport->close();        
        return $o_xception;
    }
    
    public function stopStorageAutoUpdate(){
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftySH\IfaceSHServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $o_xception = $client->stopStorageAutoUpdate();
        $transport->close();        
        return $o_xception;
    }
    
    public function getShopPluList($s_org_code) {
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftySH\IfaceSHServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $o_shop_plu_list = $client->getShopPluList($s_org_code);
        $transport->close();        
        return $o_shop_plu_list;
    }
    
    public function getShopStocks($s_org_code, $s_plu_id) {
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftySH\IfaceSHServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        $i_stocks = $client->getShopStocks($s_org_code, $s_plu_id);
        $transport->close();        
        return $i_stocks;
    }
    
    public function getShopStocksBat($a_org2plu) {        
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftySH\IfaceSHServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        
        $a_stocks_bat = array();
        foreach ($a_org2plu as $o_org2plu) {
            $o_stocks_bat = new \NiftySH\GetStocksBatBean();
            $o_stocks_bat->plucode = $o_org2plu['plucode'];
            $o_stocks_bat->orgcode = $o_org2plu['orgcode'];
            array_push($a_stocks_bat,$o_stocks_bat);
        }
        $o_res = $client->SkuStockUpdateBatch($a_stocks_bat);
        $transport->close();
        return $o_res;
    }
    
    public function updateShopList($a_orgcode){
        $a_shopinfo = array();
        foreach ($a_orgcode as $o_orgcode) {
            $o_shopinfo = new \NiftySH\ShopOrgCodeInfo();
            $o_shopinfo->orgcode = $o_orgcode->bs_org_sn;
            $o_shopinfo->shopname = $o_orgcode->bs_shop_sn;
            array_push($a_shopinfo,$o_shopinfo);
        }
        
        $socket = new TSocket($this->config['service_ip'],$this->config['service_port']);  
        $socket->setSendTimeout($this->config['time_out']);
        $socket->setRecvTimeout($this->config['time_out']);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);
        $client = new \NiftySH\IfaceSHServerClient($protocol);
        $transport->open();  
        $socket->setDebug(TRUE);
        
        $b_res = $client->updateShopList($a_shopinfo);
        $transport->close();
        return $b_res;
    }
    
    
}
