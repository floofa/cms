<?php defined('SYSPATH') or die('No direct access allowed.');

class Cms_Model_Menu extends ORM_Classic
{
  protected $_has_many = array ('menu_items' => array ());
  
  protected $_sorting = array ('sequence' => 'DESC');
  
  public function __get($column)
  {
    switch ($column) {
      case 'items_mptt' :
        return $this->get_items_mptt();
    }
    
    return parent::__get($column);
  }
  
  public function filters()
  {
    $filters = parent::filters();
    
    $filters['sys_name'][ ] = array (array ('url', 'title'), array (':value', '_', TRUE));
    $filters['sys_name'][ ] = array (array ($this, 'set_unique_value'), array (':field', ':value', '_'));
    
    return $filters;
  }
  
  public function get_items_mptt()
  {
    return ORM::factory('menu_itemMPTT')->root($this->id)->children;
  }
  
  public function save(Validation $validation = NULL)
  {
    $new_menu = ! ($this->id);
   
    if ( ! strlen($this->sys_name)) {
      $this->sys_name = $this->name;
    }
    
    parent::save($validation);
    
    // pokud bylo vytvoreno nove menu, musime vytvorit take novy scope pro polozky
    if ($new_menu) {
      $this->create_root_item();
    }
  }
  
  public function create_root_item()
  {
    $item = ORM::factory('menu_itemmptt');
    $item->name = 'ROOT - ' . $this->name;
    $item->make_root($this->id);
  }
  
  public function delete() 
  {
    ORM::factory('menu_itemmptt')->root($this->id)->delete();
    
    return parent::delete();
  }
}