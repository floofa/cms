<?php defined('SYSPATH') or die('No direct script access.');
  
// galerie - upload
Route::set('galleries-upload', 'galleries/upload')
->defaults(array(
  'controller' => 'galleries',
  'action' => 'upload'
));

// galerie - vypis
Route::set('galleries-list', 'galleries/list/<model>/<model_id>(/<gallery_name>)')
->defaults(array(
  'controller' => 'galleries',
  'action' => 'list',
  'gallery_name' => 'default',
));

// galerie - serazeni
Route::set('galleries-reorder', 'galleries/reorder')
->defaults(array(
  'controller' => 'galleries',
  'action' => 'reorder'
));

// galerie - smazani polozky
Route::set('galleries-delete_item', 'galleries/delete_item/<id>')
->defaults(array(
  'controller' => 'galleries',
  'action' => 'delete_item'
));
  
Route::set('galleries', 'gallery(/<action>(/<param_1>(/<param_2>(/<param_3>))))')
  ->defaults(array(
    'controller' => 'gallery',
  ));
  
Route::set('error', 'error/<action>(/<message>)', array('action' => '[0-9]++', 'message' => '.+'))
  ->defaults(array (
    'controller' => 'error_handler'
  ));
  
// staticke bloky
Route::set('static', '<controller>/<action>(/<args>)', array (
  'controller' => 'static_.*',
  'args' => '.+',
));

// error handler
Route::set('error', 'error/<action>(/<message>)', array('action' => '[0-9]++', 'message' => '.+'))
  ->defaults(array(
    'controller' => 'error_handler'
));
