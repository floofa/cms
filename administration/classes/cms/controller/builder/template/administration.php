<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_Controller_Builder_Template_Administration extends Controller_Builder_Template
{ 
  protected $_user;
  
  public function before()
  {
    if ( ! Auth::instance()->logged_in())
      Request::redirect_initial(Route::get('auth-login')->uri());
      
    $this->_user = Auth::instance()->get_user();
    
    parent::before();
  }
  
  public function action_index()
  {
    
  }
}