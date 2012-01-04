<?php defined('SYSPATH') or die('No direct script access.');

class Route extends Kohana_Route 
{
  public function uri(array $params = NULL, $lang = FALSE)
  {
    $uri = parent::uri($params);
    
    if (Kohana::$config->load('lang.enabled') && ! $this->is_external()) {
      if ($lang === TRUE)
        return Request::$lang . '/' . ltrim($uri, '/');
      elseif (is_string($lang)) {
        return $lang . '/' . ltrim($uri, '/');
      }
    }
    
    return $uri;
  }
  
  public static function url($name, array $params = NULL, $protocol = NULL)
  {
    $route = Route::get($name);

    // Create a URI with the route and convert it to a URL
    if ($route->is_external())
      return Route::get($name)->uri($params);
    else
      return URL::site(Route::get($name)->uri($params), $protocol, TRUE, TRUE);
  }
}
