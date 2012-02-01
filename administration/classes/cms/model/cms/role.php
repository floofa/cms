<?php defined('SYSPATH') or die('No direct access allowed.');

class Cms_Model_Cms_Role extends Orm_Classic 
{
  protected $_sorting = array ('name' => 'ASC');
  
  protected $_has_many = array (
    'cms_users' => array('through' => 'cms_roles_cms_users'),
    'cms_rights' => array('through' => 'cms_rights_cms_roles'),
  );
}