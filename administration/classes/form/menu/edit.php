<?php defined('SYSPATH') or die('No direct script access.');

class Form_Menu_Edit extends Forms_List
{
  public function build()
  {
    $this->group('group1')
      ->col('col1')
      ->add('name');
      
    $this->col('col2')
      ->add('sys_name');
  }
  
  public function set_rules()
  {
    $this->rule('name', 'not_empty');
    $this->rule('name', 'max_length', array (':value', 50));
    
    //$this->rule('sys_name', 'not_empty');
    $this->rule('sys_name', 'max_length', array (':value', 50));
    $this->rule('sys_name', array ($this, 'is_unique'), array (':value', ':field'));
  }
}
