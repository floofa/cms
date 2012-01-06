<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Blocks
{
  public static function get($sys_name)
  {
    $block = ORM::factory('block')->where('sys_name', '=', $sys_name)->find();
    
    $res = FALSE;
    
    if ($block->loaded() && $block->cms_status) {
      if ($block->type == 'dynamic') {
        if ($params = arr::get(Kohana::$config->load('cms.dynamic_blocks'), $block->sys_name)) {
          $res = Request::factory($params['controller'] . '/' . $params['action'])->execute();
        }
      }
      else {
        $res = $block->content;
      }
    }
    
    return ($res !== FALSE) ? $res : '';
  }
}