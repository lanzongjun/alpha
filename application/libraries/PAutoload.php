<?php

$GLOBALS['SPREAD_ROOT'] = __DIR__;
$GLOBALS['SPREAD_AUTOLOAD'] = array();
$GLOBALS['AUTOLOAD_HOOKS'] = array();

if (!function_exists('PAutoload')) {
  function PAutoload($class){
    global $SPREAD_AUTOLOAD;
    //$classl = strtolower($class);
	$classl = $class;
    if (isset($SPREAD_AUTOLOAD[$classl])) {
      include_once $GLOBALS['SPREAD_ROOT'].'/'.$SPREAD_AUTOLOAD[$classl];
    } elseif (!empty($GLOBALS['AUTOLOAD_HOOKS'])) {
      foreach ($GLOBALS['AUTOLOAD_HOOKS'] as $hook) {
        $hook($class);
      }
    } else {
	include_once $GLOBALS['SPREAD_ROOT']."/$class.php";
    }
  }
  spl_autoload_register("PAutoload");
}
