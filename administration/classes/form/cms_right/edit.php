<?php defined('SYSPATH') or die('No direct script access.');

class Form_Cms_Right_Edit extends Forms_List
{
  public function build()
  {
    $this->group('group1')
      ->col('col1')
      ->add('title')
      ->add('description');
      
      
    $this->col('col2')
      ->add('name')
      ->add('parent_id', 'select', array ('options' => array ('#null#' => '--- Vyberte ---') + ORM::factory('cms_right')->where('id', '!=', $this->_model_id)->find_all()->as_array('id', 'title')));
      
    $this->col('col')
      ->add('set_for_all_roles', 'bool');
  }
  
  public function set_rules()
  {
    $this->rules('title', array (
      array ('not_empty')
    ));
    
    $this->rules('name', array (
      array ('not_empty'),
      array (array ($this, 'is_unique'), array (':value', ':field'))
    ));
  }
  
  public function do_form($values = array (), $refresh = FALSE, $redirect = FALSE)
  {
    $set_to_all = $values['set_for_all_roles'];
    unset($values['set_for_all_roles']);
    
    parent::do_form($values, $refresh, $redirect);
    
    if ($set_to_all) {
      $this->_saved->set_for_all_roles();
    }
    
    Request::redirect_initial(Request::initial()->route()->uri(array ('controller' => Request::current()->controller(), 'action' => 'list', 'id' => FALSE) + Request::initial()->param()));
  }
}