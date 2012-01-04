<?php defined('SYSPATH') or die('No direct script access.');

class URL extends Kohana_URL 
{
  public static function domain($index = FALSE, $strip = 'www.') 
  {
    $base_url = Kohana::$index_file.'/';
    
    if ($domain = parse_url($base_url, PHP_URL_HOST)) {
      $base_url = parse_url($base_url, PHP_URL_PATH);
    }
    else {
      $domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
    }
      
    return $domain;
  }
  
  public static function site($uri = '', $protocol = FALSE, $index = TRUE, $lang = TRUE)
  {
    if (Kohana::$config->load('lang.enabled')) {
      if ($lang === TRUE) {
        $uri = Request::$lang.'/'.ltrim($uri, '/');
      }
      elseif (is_string($lang)) {
        $uri = $lang.'/'.ltrim($uri, '/');
      }
    }

    return parent::site($uri, $protocol, $index);
  }
}