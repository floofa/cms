<?php defined('SYSPATH') or die('No direct script access.');

class Form_Block_Edit extends Forms_List
{
  public function build()
  {
    $this->group('group1')
      ->col('col1')
      ->add('name')
      ->add('cms_status', 'bool', TRUE);
      
      
    $this->col('col2')
      ->add('sys_name')
      ->add('type', 'select', array ('options' => ___('static_block_types')));
      
      
    $this->col('col')
      ->add('content', 'textarea');
      
    $this->add_gallery('block_images', $this->_model, $this->_model_id);
  }
  
  public function set_rules()
  {
    $this->rules('name', array (
      array ('not_empty'),
      array ('max_length', array (':value', 50))
    ));
    
    $this->rules('sys_name', array (
      array ('max_length', array (':value', 50))
    ));
  }
}