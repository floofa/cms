<?php defined('SYSPATH') or die('No direct access allowed.');

$config['sorting_sections'] = array ();
$config['sorting_items'] = array ();

$config['items']['administration_module']['children']['pages']['children'] = array ();
$config['items']['administration_module']['children']['blocks']['children'] = array ();
$config['items']['administration_module']['children']['menus']['children'] = array ();

$config['items']['settings_module']['children']['cms_users']['children'] = array ();

/**
* vypnuti polozek v menu
* 
* $config['items_disabled']['administration_module']['menus'] = TRUE;
*/
$config['items_disabled'] = array ();

return isset($config) ? $config : array ();

