<?php defined('SYSPATH') or die('No direct script access.');

class Form_Cms_User_Edit extends Forms_List
{
  public function build()
  {
    $this->group('group1')
      ->col('col1')
      ->add('username')
      ->add('password', 'password', array ('attr' => array ('autocomplete' => 'off')))
      ->add('email', array ('attr' => array ('autocomplete' => 'off')));
      
    $user = Auth::instance()->get_user();
      
    if ($user->has_right('right_edit_all_users')) {
      $this->col('col2')
        ->add('cms_roles', 'select', array ('value' => ORM::factory('cms_user', $this->_model_id)->cms_roles->find_all()->as_array('id', 'id'), 'options' => ORM::factory('cms_role')->find_all()->as_array('id', 'name'), 'attr' => array ('multiple' => 'multiple')));
    }
  }
  
  public function set_rules()
  {
    $this->rule('username', 'not_empty');
    $this->rule('username', 'min_length', array (':value', 4));
    $this->rule('username', 'max_length', array (':value', 32));
    $this->rule('username', 'regex', array(':value', '/^[-\pL\pN_.]++$/uD'));
    $this->rule('username', array ($this, 'is_unique'), array (':value', ':field'));
    
    $this->rule('email', 'not_empty');
    $this->rule('email', 'min_length', array (':value', 4));
    $this->rule('email', 'max_length', array (':value', 127));
    $this->rule('email', 'email');
    $this->rule('email', array ($this, 'is_unique'), array (':value', ':field'));
    
    // heslo musi byt zadano pouze u novych zaznamu
    if ( ! $this->_model_id)
      $this->rule('password', 'not_empty');
    
    $this->rule('email', 'min_length', array (':value', 6));
    
    if ($this->_formo->find('cms_roles')) {
      $this->rules('cms_roles', array (
        array ('not_empty'),
      ));
    }
  }
}