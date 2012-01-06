<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Assets 
{ 
  public static $config = NULL;
  
  public static $library = NULL;
  
  public static $loaded_css = array ();
  
  public static $loaded_js = array ();
  
  public static $added_css = array ();
  
  public static $added_js = array ();
  
  public static $options = array ();
  
  /**
  * vrati konfiguraci pro danou skupinu
  */
  public static function get_config($group)
  {
    if (is_null(self::$config)) {
      $assets = Kohana::$config->load('assets');
      
      self::$config = $assets['groups'][$group];
      
      self::$options = array (
        'default_folder' => $assets['default_folder'],
      );
    }
    
    return self::$config;
  }
  
  public static function get_library($type = NULL)
  {
    if (is_null(self::$library)) {
      self::$library = Kohana::$config->load('assets.library');
    }
    
    if ( ! is_null($type))
      return self::$library[$type];
    
    return self::$library;
  }
  
  /**
  * prida css
  */
  public static function add_css($css)
  {
    $library = self::get_library('css');
    
    if (isset($library[$css])) {
      self::$added_css[$css] = $library[$css];
    }
    else {
      self::$added_css[$css] = array ('file' => $css);
    }
  }
  
  /**
  * prida js
  */
  public static function add_js($js)
  {
    $library = self::get_library('js');
    
    if (isset($library[$js])) {
      self::$added_js[$js] = $library[$js];
    }
    else {
      self::$added_js[$js] = array ('file' => $js);
    }
  }
  
  /**
  * nacte css z conf souboru pro danou skupinu
  */
  public static function load_css($group = 'default')
  {
    $config = self::get_config($group);
    $library = self::get_library('css');
    
    $names = explode(',', $config['css']);
    
    foreach ($names as $name) {
      $name = trim($name);
      
      if (isset($library[$name]) && isset($library[$name]['file']) && $library[$name]['file'] !== FALSE) {
        self::$loaded_css[$name] = $library[$name];
      }
    }
    
    return self::$loaded_css;
  }
  
  /**
  * nacte js z conf souboru pro danou skupinu a dany typ
  */
  public static function load_js($group = 'default', $type = 'js')
  {
    $config = self::get_config($group);
    $library = self::get_library('js');

    $names = explode(',', $config['js']);
    
    foreach ($names as $name) {
      $name = trim($name);
      
      if (isset($library[$name]) && isset($library[$name]['file']) && $library[$name]['file'] !== FALSE) {
        self::$loaded_js[$name] = $library[$name];
      }
    }
    
    return self::$loaded_js;
  }
  
  protected static function prepare_css($items, $group = 'default')
  {
    $res = array ();
    
    $config = self::get_config($group);
    $default_folder = arr::get($config, 'default_folder', self::$options['default_folder']);

    foreach ($items as $item) {
      // nastaveni cesty
      if (stripos($item['file'], 'http') === 0) {
        $href = $item['file'];
      }
      else {
        $path = 'media/';
        
        if (isset($item['default_folder'])) {
          if (strlen($item['default_folder'])) {
            $path .= $item['default_folder'] . '/';
          }
        }
        elseif (strlen($default_folder)) {
          $path .= $default_folder . '/';
        }
        
        $path .= 'css/';
        
        if (isset($item['prefix_folder']) && strlen($item['prefix_folder'])) {
          $path .= $item['prefix_folder'] . '/';
        }
        
        $href = URL::base(TRUE) . $path . $item['file'];
      }
      
      $attr_str = '';
      
      foreach (arr::get($item, 'attr', array ()) as $key => $value) {
        $attr_str .= " $key=\"$value\"";
      }
      
      $res[ ] = array (
        'href' => $href, 
        'attr' => $attr_str,
      );
      
      
    }

    return $res;
  }
  
  protected static function prepare_js($items, $group = 'default')
  {
    $res = array ();
    
    $config = self::get_config($group);
    $default_folder = self::$options['default_folder'];
    
    foreach ($items as $item) {
      // nastaveni cesty
      if (stripos($item['file'], 'http') === 0) {
        $src = $item['file'];
      }
      else {
        $path = 'media/';
        
        if (isset($item['folder'])) {
          if (strlen($item['folder'])) {
            $path .= $item['folder']. '/';
          }
        }
        elseif (strlen($default_folder)) {
          $path .= $default_folder . '/';
        }
        
        $path .= 'js/';
        
        if (isset($item['prefix']) && strlen($item['prefix'])) {
          $path .= $item['prefix'] . '/';
        }
        
        $src = URL::base(TRUE) . $path . $item['file'];
      }
      
      $res[ ] = array (
        'src' => $src,
      );
    }
    
    return $res;
  }
  
  public static function render_css($group = 'default', $view = 'css')
  {
    $css = array_merge(self::load_css($group), self::$added_css);
    
    return View::factory('builder/assets/' . $view)
      ->set('css', self::prepare_css($css))
      ->render();
  }
  
  public static function render_js($group = 'default', $view = 'js')
  {
    $js = array_merge(self::load_js($group), self::$added_js, self::load_js($group, 'js_end'));
    
    return View::factory('builder/assets/' . $view)
      ->set('js', self::prepare_js($js))
      ->render();
  }
}