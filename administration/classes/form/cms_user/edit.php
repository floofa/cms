<?php defined('SYSPATH') or die('No direct script access.');

class Form_Cms_User_Edit extends Forms_List
{
  public function build()
  {
    $this->group('group1')
      ->col('col1')
      ->add('username')
      ->add('email', array ('attr' => array ('autocomplete' => 'off')));
      
    $this->col('col2')
      ->add('password', 'password', array ('attr' => array ('autocomplete' => 'off')));
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
  }
}