<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Formo_Driver_Text_Core class.
 * 
 * @package   Formo
 * @category  Drivers
 */
class Formo_Driver_SelectChosen extends Formo_Driver {

  protected $_view_file = 'selectchosen';
  
  public function name()
  {
    if ( ! $this->_field->attr['multiple'])
      return parent::name();
    
    if ( ! Formo::config($this->_field, 'namespaces'))
      return $this->_field->alias() . '[]';

    if ( ! $parent = $this->_field->parent())
      // If there isn't a parent, don't namespace the name
      return $this->_field->alias() . '[]';

    return $parent->alias().'['.$this->_field->alias().']' . '[]';
  }
  
  public function html()
  {
    $this->_view
      ->set_var('tag', 'select')
      ->attr('name', $this->name())
      ->add_class('chzn');
  }
}