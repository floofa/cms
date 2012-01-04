<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Cms
{ 
  public static function i($var, $clean = TRUE) 
  {
    if ($clean)
      ob_clean();
      
    echo '<pre>' . debug::dump($var) . '</pre>';
    exit();
  }
  
  public static function m($path, $file = FALSE, $default = '#path#')
  {
    if ( ! $file)
      $file = basename(APPPATH);
      
    if ($default == '#path#') {
      $default = $file.'.'.$path;
    }

    return Kohana::message($file, $path, $default);
  }
  
  public static function prepare_content($content, $config = array ())
  {
    // orm object
    $orm_object = arr::get($config, 'orm_object', FALSE);

    // site url
    $content = preg_replace('~{site_url}~', URL::site('', TRUE), $content);
    
    // images url
    $content = preg_replace('~{img_url}~', URL::site('media/images', TRUE, FALSE, FALSE), $content);
    
    // content images url
    $content = preg_replace('~{cimg_url}~', URL::site('media/content-images', TRUE, FALSE, FALSE), $content);
    
    // obrazky z galerie
    if ($orm_object && preg_match_all('~{img:([0-9,]*)(:([a-zA-Z0-9,-]*))?}~', $content, $matches)) {
      $gallery_name = arr::get($config, 'gallery_name', FALSE);
      $view_name = arr::get($config, 'view_cimg', 'builder/prepare_content/images_default');
      
      $gallery_items = $orm_object->get_gallery_items($gallery_name);
      
      $_gallery_items = array ();
      foreach ($gallery_items as $gallery_item) {
        $_gallery_items[$gallery_item->id] = $gallery_item;
      }
      
      $gallery_items = $_gallery_items;
      
      $ci_view = View::factory($view_name);
      
      foreach($matches[0] as $key => $reg) {
        $items_ids = explode(',', $matches[1][$key]);
        $items = array();
        
        foreach ($items_ids as $id) {
          if (isset($gallery_items[$id])) {
            $items[ ] = $gallery_items[$id];
          }
        }
        
        $ci_view->set('items', $items);
        $content = preg_replace('~'.$reg.'~', $ci_view->render(), $content);
      }
    }
    
    // TODO: vlozeni bloku
    // TODO: vlozeni formularu
    // TODO: vlozeni fotogalerii
    
    return $content;
  }
  
  /*
  public static function prepare_content($orm_object, $content, $config = array ())
  {
    
    //$config = Kohana::$config->load('application.prepare_content');
    
    // site url
    $content = preg_replace('~{site_url}~', URL::site('', TRUE), $content);
    
    // images url
    $content = preg_replace('~{img_url}~', URL::site('media/images', TRUE, FALSE, FALSE), $content);
    
    // content images url
    $content = preg_replace('~{cimg_url}~', URL::site('media/content-images', TRUE, FALSE, FALSE), $content);
    
    // vlozeni obrazku
    if (preg_match_all('~{img:([0-9,]*)(:([a-zA-Z0-9,-]*))?}~', $content, $matches)) {
      $gallery_items = $orm_object->get_gallery_items();
      
      $_gallery_items = array ();
      foreach ($gallery_items as $gallery_item) {
        $_gallery_items[$gallery_item->id] = $gallery_item;
      }
      
      $gallery_items = $_gallery_items;
      
      //$gallery_items = $orm_object->get_gallery_items($config['content_images']['gallery_name'])->as_array('id');
      
      $ci_view = View::factory('builder/prepare_content/images_default');
      
      foreach($matches[0] as $key => $reg) {
        $items_ids = explode(',', $matches[1][$key]);
        $items = array();
        
        foreach ($items_ids as $id) {
          if (isset($gallery_items[$id])) {
            $items[ ] = $gallery_items[$id];
          }
        }
        
        $ci_view->set('items', $items);
        $content = preg_replace('~'.$reg.'~', $ci_view->render(), $content);
      }
    }
    
    // TODO: vlozeni bloku
    // TODO: vlozeni formularu
    // TODO: vlozeni fotogalerii
    
    return $content;
  }
  */
}