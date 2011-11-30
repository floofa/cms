<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Formo_Driver_Text_Core class.
 * 
 * @package   Formo
 * @category  Drivers
 */
class Formo_Driver_Date extends Formo_Driver {

  protected $_view_file = 'date';
  
  protected function get_type()
  {
    return ($type = $this->_field->get('type'))
      ? $type
      : 'text';
  }
  
  public function html()
  {
    $this->_view
      ->set_var('tag', 'input')
      ->attr('type', $this->get_type())
      ->attr('name', $this->name())
      ->attr('value', $this->_field->val());
  }
}