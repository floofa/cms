<?php defined('SYSPATH') or die('No direct script access.');

/*** MPTT ***/

// mptt - razeni
Route::set('mptt-drag', '<controller>/move_tree_node')
  ->defaults(
    array (
    'action' => 'move_tree_node',
  ));

// mptt - smazani polozky stromu
Route::set('mptt-delete_item', '<controller>/delete_tree_item/<id>')
  ->defaults(
    array (
    'action' => 'delete_tree_item',
  ));

// mptt - editace
Route::set('mptt-edit', '<controller>/edit_tree_item/<parent_id>/<id>')
  ->defaults(
    array (
    'action' => 'edit_tree_item',
  ));

// mptt - stromovy vypis
Route::set('mptt-list', '<controller>/list_tree/<id>')
  ->defaults(
    array (
    'action' => 'list_tree',
  ));


/*** AUTH ***/

/* prihlaseni */
Route::set('auth-login', 'login')
  ->defaults(array(
    'controller'  => 'auth',
    'action'      => 'login',
  ));
  
/* odhlaseni */
Route::set('auth-logout', 'logout')
  ->defaults(array(
    'controller'  => 'auth',
    'action'      => 'logout',
  ));



/*
// static
Route::set('static-administration', 'static/administration/<action>(/<args>)', array ('args' => '.+'))
  ->defaults(array (
    'controller' => 'static_administration',
));

// default - edit mptt
Route::set('default-edit_tree', '<controller>/edit_tree/<id>(/<parent_id>)')
  ->defaults(
    array (
    'action' => 'edit_tree',
  ));

Route::set('default', '(<controller>(/<action>(/<id>)))')
  ->defaults(array(
    'controller' => 'dashboard',
    'action'     => 'index',
));*/

