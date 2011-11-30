<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Head 
{ 
  protected static $_data = array ();
  protected static $_converted = FALSE;
  protected static $_view_folder = 'builder/head';
  
  public static function load()
  {
    //self::$_data = array ('head_title' => ___('application.layout_title'), 'meta_description' => ___('application.meta_description'), 'meta_keywords' => ___('application.meta_keywords'), 'raw_code' => '');
    self::$_data = array ('head_title' => '', 'meta_description' => '', 'meta_keywords' => '', 'raw_code' => '');
  }
  
  public static function render($view = 'default', $assets = 'default')
  {
    return View::factory(self::$_view_folder . '/' . $view)->set('assets', $assets)->render();
  }
  
  public static function set($name, $value)
  {
    self::$_data[$name] = $value;
  }
  
  public static function set_arr($arr) 
  {
    self::$_data = arr::overwrite_nonempty(self::$_data, (array)$arr);
  }
  
  public static function convert() 
  {
    if(Request::$initial->controller() == 'pages' && Request::$initial->method() == 'index')
      self::$_data['head_title'] = /*___('application.layout_title_index');*/ '';
    else
      self::$_data['head_title'] .= /*___('application.layout_title_join');*/ '';
      
    self::$_converted = TRUE;
  }
  
  public static function get($index = FALSE) 
  {
    if(self::$_converted === FALSE)
      self::convert();
    
    if(isset(self::$_data[$index]))
      return self::$_data[$index];

    return self::$_data;
  }
}