<?php defined('SYSPATH') or die('No direct script access.');

class Form_Cms_Role_Edit extends Forms_List
{
  public function build()
  {
    $this->group('group1')
      ->col('col1')
      ->add('name')
      ->add('description');
    
    $this->col('col2')
      ->add('cms_rights', 'select', array ('value' => ORM::factory('cms_role', $this->_model_id)->cms_rights->find_all()->as_array('id', 'id'), 'options' => ORM::factory('cms_right')->find_all()->as_array('id', 'name'), 'attr' => array ('multiple' => 'multiple')));
  }
  
  public function set_rules()
  {
    $this->rules('name', array (
      array ('not_empty'),
      array (array ($this, 'is_unique'), array (':value', ':field'))
    ));
  }
}