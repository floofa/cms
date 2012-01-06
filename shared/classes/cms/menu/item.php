<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Menu_Item
{ 
  public $subitems = array ();
  
  public $label;
  
  public $link;
  
  public $classes = array ();
  
  protected $_links_for_active = array ();
  
  public $active = FALSE;
  
  public function __construct($label, $link = FALSE, $classes = array (), $links_for_active = array ())
  {
    if ($link === FALSE) {
      $link = Linker::get(url::title($label, '-', TRUE));
    }
    
    if ( ! is_array($classes)) {
      $classes = array ($classes);
    }
    
    $this->label = $label;
    $this->link = $link;
    $this->classes = $classes;
    $this->_links_for_active = $links_for_active;
  }
  
  public function add_subitem($item)
  {
    $this->subitems[ ] = $item;
  }
  
  public function set_actives($uri)
  {
    $uri = trim($uri, '/') . '/';
    
    if (count($this->_links_for_active)) {
      $links = $this->_links_for_active;
    }
    else {
      $links = array ($this->link);
    }
    
    foreach ($links as $link) {
      $link = trim($link, '/') . '/';
      
      if (stripos($uri, $link) !== FALSE) {
        $this->set_active();
        break;
      }
    }
      
    foreach ($this->subitems as $subitem) {
      if ($subitem->set_actives($uri)) {
        $this->set_active();
      }
    }
    
    return $this->active;
  }
  
  public function set_active()
  {
    $this->active = TRUE;
  }
  
  public function get_render_data($pos, $count)
  {
    $res = array 
    (
      'first' => ($pos == 1),
      'last' => ($pos == $count),
      'label' => $this->label,
      'link' => $this->link,
      'active' => $this->active,
      'classes' => implode(' ', $this->classes),
      'subitems' => array (),
    );
    
    $count_subitems = count($this->subitems);
    
    foreach ($this->subitems as $subitem_key => $subitem) {
      $res['subitems'][ ] = $subitem->get_render_data($subitem_key + 1, $count_subitems);
    }
    
    return $res;
  }
}
