<?php
require_once APPPATH . 'libraries\PAutoload.php';

require_once APPPATH . 'libraries\ZipStream\ZipStream.php';

use ZipStream\ZipStream;
use ZipStream\Option\Archive;

/**
 * Description of LibZipStream
 *
 * @author Vincent
 */
class LibZipStream {
    var $__i_rand = 0;
    
    public function outputHTTP($a_file_name,$a_file_path,$s_output_name='ZipPackage'){
        $options = new Archive();
        $options->setSendHttpHeaders(true);
        
        $zip = new ZipStream("$s_output_name.zip", $options);
        for ($i=0; $i<count($a_file_path); $i++) {
            $s_file_name = $a_file_name[$i];
            $s_file_path = $a_file_path[$i];
            $zip->addFileFromPath("$s_file_name", "$s_file_path");
        }
        $zip->finish();
    }
    
    private function getRand(){
        $this->__i_rand = $this->__i_rand + 1;
        return $this->__i_rand;
    }
    
    public function outputLocal($a_file_name,$a_file_path){
        $options = new Archive();
        $s_rand = $this->getRand();
        if (file_exists("./output/打包下载($s_rand).zip")) {
            $s_rand = $this->getRand();
        }
        $s_output_name = "打包下载($s_rand).zip";
        $s_output_path = "./output/$s_output_name";
        $o_stream = @fopen($s_output_path,'wb');
        if (!$o_stream) {
            return '';
        }
        $options->setOutputStream($o_stream);
        
        $zip = new ZipStream($s_output_name, $options);
        for ($i=0; $i<count($a_file_path); $i++) {
            $s_file_name = $a_file_name[$i];
            $s_file_path = $a_file_path[$i];
            $zip->addFileFromPath("$s_file_name", "$s_file_path");
        }
        $zip->finish();
        return $s_output_path;
    }
}
