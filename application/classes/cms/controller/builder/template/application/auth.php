<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_Controller_Builder_Template_Application_Auth extends Controller_Builder_Template_Application
{ 
  protected $_user;
  
  protected $_disabled_auth = array ();
  
  public function before()
  {
    $this->_user = Authlite::instance('authlite_user')->get_user();
    
    if ( ! $this->_user && ! in_array($this->request->action(), $this->_disabled_auth))
      $this->not_logged_in_redirect();
    
    parent::before();
  }
  
  public function not_logged_in_redirect()
  {
    Request::redirect_initial('');
  }
}