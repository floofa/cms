<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_Controller_Builder_Template_Application extends Controller_Builder_Template
{ 
  public $page_sys_name = FALSE;
  
  public function after()
  {
    View::set_global('page_sys_name', $this->page_sys_name);
    
    parent::after();
  }
}