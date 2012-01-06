<?php defined('SYSPATH') or die('No direct script access.');

/**
* bloky editovatelne pres cms
*/
Route::set('cms-blocks', 'cms_blocks/<sys_name>(/<params>)')
  ->defaults(array (
    'controller' => 'blocks',
    'action' => 'get',
  ));

/**
* static pages routes
*/
Route::set('static-pages', array ('Controller_Pages', 'route'));

