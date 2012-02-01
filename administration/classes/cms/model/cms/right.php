<?php defined('SYSPATH') or die('No direct access allowed.');

class Cms_Model_Cms_Right extends Orm_Classic 
{
  protected $_sorting = array ('sequence' => 'DESC');
  
  protected $_belongs_to = array ('cms_right' => array ('foreign_key' => 'parent_id'));
  protected $_has_many = array ('cms_roles' => array('through' => 'cms_rights_cms_roles'));
  
  public function set_list_item_default(&$arr, $item) 
  {
    $arr['parent'] = $item->cms_right->title;
  }
  
  public function set_for_all_roles()
  {
    foreach (ORM::factory('cms_role')->find_all() as $role) {
      if ( ! $role->has('cms_rights', $this->id)) {
        $role->add('cms_rights', $this->id);
        $role->save();
      }
    }
    
    return $this;
  }
  
  public function get_navigation_val()
  {
    return $this->title;
  }
  
  public function get_parents_ids()
  {
    $res = array ();
    
    if ($this->parent_id) {
      $res = $this->cms_right->get_parents_ids() + array ($this->parent_id => $this->parent_id);
    }
    
    return $res;
  }
}
