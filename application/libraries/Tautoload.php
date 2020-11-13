<?php

$GLOBALS['THRIFT_ROOT'] = __DIR__;
$GLOBALS['THRIFT_AUTOLOAD'] = array();
$GLOBALS['AUTOLOAD_HOOKS'] = array();

if (!function_exists('Tautoload')) {
  function Tautoload($class){
    global $THRIFT_AUTOLOAD;
    //$classl = strtolower($class);
	$classl = $class;
    if (isset($THRIFT_AUTOLOAD[$classl])) {
      include_once $GLOBALS['THRIFT_ROOT'].'/'.$THRIFT_AUTOLOAD[$classl];
    } elseif (!empty($GLOBALS['AUTOLOAD_HOOKS'])) {
      foreach ($GLOBALS['AUTOLOAD_HOOKS'] as $hook) {
        $hook($class);
      }
    } else {
	include_once $GLOBALS['THRIFT_ROOT']."/$class.php";
    }
  }
  spl_autoload_register("Tautoload");
}
