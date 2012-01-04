<?php defined('SYSPATH') or die('No direct access allowed.');

class Cms_Model_Menu extends ORM
{
  protected $_has_many = array ('menu_items' => array ());
  
  protected $_sorting = array ('sequence' => 'DESC');
}