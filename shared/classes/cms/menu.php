<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Menu
{ 
  protected $items = array ();
  
  protected $label;
  
  protected $classes = array ();
  
  public function __construct($label = 'main', $classes = array ())
  {
    if ( ! is_array($classes)) {
      $classes = array ($classes);
    }
    
    $this->label = $label;
    $this->classes = $classes;
  }
  
  public function add($label, $link = FALSE, $classes = array ()) 
  {
    $item = new Menu_Item($label, $link, $classes);
    
    $this->items[ ] = $item;
    
    return $item;
  }
  
  public function add_sub($item, $label, $link, $classes = array ())
  {
    $subitem = new Menu_Item($label, $link, $classes);
    
    $item->add_subitem($subitem);
    
    return $subitem;
  }
  
  public function set_actives($uri)
  {
    //cms::i($uri);
    foreach ($this->items as $item) {
      $item->set_actives($uri);
    }
  }
  
  public function get_items()
  {
    $items = array ();
    $count_items = count($this->items);
    
    foreach ($this->items as $key => $item) {
      $items[ ] = $item->get_render_data($key + 1, $count_items);
    }
    
    return $items;
  }
}