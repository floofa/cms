<?php defined('SYSPATH') or die('No direct access allowed.');

class Cms_Model_Cms_Role extends Orm_Classic 
{
  // Relationships
  protected $_has_many = array ('cms_users' => array('through' => 'cms_roles_cms_users'));

  public function rules()
  {
    return array(
      'name' => array(
        array('not_empty'),
        array('min_length', array(':value', 4)),
        array('max_length', array(':value', 32)),
      ),
      'description' => array(
        array('max_length', array(':value', 255)),
      )
    );
  }

} // End Role Model