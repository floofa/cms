<?php defined('SYSPATH') or die('No direct access allowed.');

class Cms_Model_Menu extends ORM
{
  protected $_has_many = array ('menu_items' => array ());
  
  protected $_sorting = array ('sequence' => 'DESC');
  
  public function get_top_items()
  {
    $children = ORM::factory('menu_itemmptt')->get_root($this->id)->get_children();
    
    $ids = $children->as_array('id', 'id');
    
    if ( ! count($ids))
      $ids = array (-1);
    
    return ORM::factory('menu_item')->where('id', 'in', $ids)->order_by(array (NULL, DB::expr('FIELD(id,'.implode(',', $ids).')')))->find_all();
  }
}