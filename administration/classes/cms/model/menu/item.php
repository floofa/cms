<?php defined('SYSPATH') or die('No direct access allowed.');

class Cms_Model_Menu_Item extends ORM_Classic 
{
  protected $_belongs_to = array ('menu' => array ());
  
  protected $has_one = array ('page' => array ());
  
  public function rules()
  {
    return array (
      'name' => array(
        array('not_empty'),
        array('max_length', array(':value', 50)),
      ),
      'url' => array(
        array('max_length', array(':value', 100)),
      ),
    );
  }
}