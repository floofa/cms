<?php defined('SYSPATH') or die('No direct script access.');

class Formo_Driver_Select extends Formo_Core_Driver_Select 
{
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
}