<?php defined('SYSPATH') or die('No direct access allowed.');

class Cms_Model_Menu_ItemMPTT extends ORM_MPTT
{
  public $_table_name = 'menu_items';
  
  public $scope_column = 'menu_id';
  
  protected $_multilang_fields = array ('name');
} 