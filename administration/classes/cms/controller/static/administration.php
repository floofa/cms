<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Controller_Static_Administration extends Cms_Controller_Static
{
  /**
  * main menu
  */
  public function action_main_menu()
  {
    $config = Kohana::$config->load('cms_menu')->as_array();
    
    // odebere polozky, ktere se nemaji zobrazovat
    foreach ($config['items_disabled'] as $section => $items) {
      if ($items === TRUE)
        unset($config['items'][$section]);
        
      elseif (is_array($items)) {
        foreach ($items as $item => $disable) {
          if ($disable === TRUE)
            unset($config['items'][$section]['children'][$item]);
        }
      }
    }
    
    $menu = new Menu;
    
    $sections = array ();
    
    // sort sections
    foreach ($config['sorting_sections'] as $section_name) {
      if (isset($config['items'][$section_name])) {
        $sections[$section_name] = array ('children' => array ());
      }
    }
    
    foreach ($config['items'] as $section_name => $items) {
      if ( ! isset($sections[$section_name])) {
        $sections[$section_name] = array ('children' => array ());
      }
    }
    
    // sort items
    foreach ($config['items'] as $section_name => $items) {
      if (isset($config['sorting_items'][$section_name])) {
        foreach ($config['sorting_items'][$section_name] as $item_name) {
          if (isset($config['items'][$section_name]['children'][$item_name])) {
            $sections[$section_name]['children'][$item_name] = $config['items'][$section_name]['children'][$item_name];
          }
        }
      }
      
      foreach ($config['items'][$section_name]['children'] as $item_name => $value) {
        if ( ! isset($sections[$section_name]['children'][$item_name])) {
          $sections[$section_name]['children'][$item_name] = $value;
        }
      }
    }
    
    foreach ($sections as $section => $items) {
      $menu_item = $menu->add(___('cms_menu_' . $section), URL::site($section));
      
      foreach ($items['children'] as $item => $subitems) {
        $menu->add_sub($menu_item, ___('cms_menu_' . $section . '_' . $item), URL::site($item));
      }
    }
    
    $uri = trim(Request::$initial->uri(), '/');
    
    $menu->set_actives(URL::site($uri));
    
    $this->_view->menu = $menu;
  }
}