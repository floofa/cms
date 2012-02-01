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
Route::set('mptt-edit', '<controller>/edit_tree_item/<id>(/<parent_id>)')
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
  
// galerie - informace o obrazku
Route::set('galleries-item_info', 'galleries/item_info/<id>')
  ->defaults(
    array (
    'controller' => 'galleries',
    'action' => 'item_info',
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

// cron
Route::set('cron', 'cron/<event_type>')
  ->defaults(array (
    'controller' => 'cron',
    'action' => 'run',
  ));


