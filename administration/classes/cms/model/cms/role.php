<?php defined('SYSPATH') or die('No direct access allowed.');

class Cms_Model_Cms_Role extends Orm_Classic 
{
  protected $_sorting = array ('name' => 'ASC');
  
  protected $_has_many = array (
    'cms_users' => array('through' => 'cms_roles_cms_users'),
    'cms_rights' => array('through' => 'cms_rights_cms_roles'),
  );
  
  public function list_all($count = FALSE, $offset = FALSE)
  {
    $this->load_list_filters()->set_list_filters();
    
    if ($count !== FALSE)
      $this->limit($count);
      
    if ($offset !== FALSE) {
      $this->offset($offset);
    }
    
    return $this->set_list_default($this->find_all());
  }
}