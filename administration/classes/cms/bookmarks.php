<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_Bookmarks 
{ 
  protected static $_items = array ();
  
  public static $show = TRUE;
  
  public static function add_item($name, $link, $active = FALSE)
  {
    self::$_items[ ] = array (
      'name' => $name,
      'link' => $link,
      'active' => $active,
    );
  }
  
  public static function render($view = 'builder/bookmarks/default')
  {
    if ( ! self::$show || ! count(self::$_items))
      return '';
      
    return View::factory($view)
      ->set('items', self::$_items)
      ->set('count_items', count(self::$_items))
      ->render();
  }
  
  public static function set_active($uri)
  {
    foreach (self::$_items as &$item) {
      if (stripos($uri, $item['link']) !== FALSE) {
        $item['active'] = TRUE;
      }
    }
  }
}
