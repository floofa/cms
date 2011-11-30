<?php defined('SYSPATH') or die('No direct script access.');

class Form_Page_Edit extends Forms_List
{
  public function build()
  {
    $this->group('group1')
      ->col('col1')
      ->add('name')
      ->add('head_title');
      
    $this->col('col2')
      ->add('rew_id')
      ->add('sys_name');
      
    $this->col('col')
      ->add('meta_description')
      ->add('meta_keywords');
      
      
    $this->group('group2');
    $this->col('col1')
      ->add('page_type', 'select', array ('options' => ___('static_page_page_types')));
    
    $this->col('col2')
      ->add('page_layout', 'select', array ('options' => ___('static_page_page_layouts')));
      
    
    $this->col('col')
      ->add('content', 'textarea', array ('attr' => array ('rows' => 20)))
      ->add('cms_status', 'bool', array ('value' => TRUE));
      
    $this->add_gallery('page_images', $this->_model, $this->_model_id);
  }
  
  public function set_rules()
  {
    $this->rules('name', array (
      array ('not_empty'),
      array ('max_length', array (':value', 50))
    ));
    
    $this->rules('head_title', array (
      array ('max_length', array (':value', 50))
    ));
    
    $this->rules('sys_name', array (
      array ('max_length', array (':value', 50))
    ));
    
    $this->rules('rew_id', array (
      array ('max_length', array (':value', 50))
    ));
    
    $this->rules('meta_keywords', array (
      array ('max_length', array (':value', 255))
    ));
    
    $this->rules('meta_description', array (
      array ('max_length', array (':value', 255))
    ));
  }
}