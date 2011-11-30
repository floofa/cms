<?php defined('SYSPATH') OR die('No direct access allowed.');

class Cms_ORM_MPTT_Classic extends ORM_MPTT 
{
  public function list_all()
  {
    return $this->set_list_default($this->children);
  }
  
  public function set_list_default($items)
  {
    $res = array ();
    
    foreach ($items as $item) {
      $arr = $item->as_array();
      $arr['_children'] = $item->set_list_default($item->children);
      
      $this->set_list_item_default($arr, $item);
      
      $res[ ] = $arr;
    }
    
    return $res;
  }
  
  public function set_list_item_default(&$arr, $item) 
  {}
}