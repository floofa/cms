<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_Controller_Builder_Template_Application extends Controller_Builder_Template
{ 
  public $page_type = FALSE;
  
  public function after()
  {
    View::set_global('page_type', $this->page_type);
    
    parent::after();
  }
}