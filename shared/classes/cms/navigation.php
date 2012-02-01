<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Navigation 
{ 
  protected static $_items = array ();
  
  public static $show = TRUE;
  
  public static function add($name, $link)
  {
    self::$_items[ ] = array ('name' => $name, 'link' => $link);
  }
  
  /**
  * prida na zacatek
  */
  public static function add_first($name, $link)
  {
    array_unshift(self::$_items, array ('name' => $name, 'link' => $link));
  }
  
  /**
  * prida na pozadovanou pozici
  */
  public static function add_pos($name, $link, $pos)
  {
    if ($pos <= count(self::$_items)) {
      $current = 1;
      
      foreach (self::$_items as $key => $value) {
        if ($current == $pos) {
          self::$_items[$key] = array ('name' => $name, 'link' => $link);
        }
        
        $current++;
      }
    }
    else {
      self::$_items[ ] = array ('name' => $name, 'link' => $link);
    }
  }
  
  public static function add_before_last($name, $link)
  {
    if (count(self::$_items) > 1) {
      $last = array_pop(self::$_items);
      self::$_items[ ] = array ('name' => $name, 'link' => $link);
      self::$_items[ ] = $last;
    }
    else {
      self::$_items[ ] = array ('name' => $name, 'link' => $link);
    }
  }
  
  public static function render($view = 'builder/navigation/default')
  {
    if ( ! self::$show)
      return '';
      
    return View::factory($view)
      ->set('items', self::$_items)
      ->set('count_items', count(self::$_items))
      ->render();
  }
  
  public static function remove_last()
  {
    array_pop(self::$_items);
  }
  
  public static function remove_all()
  {
    self::$_items = array ();
  }
}