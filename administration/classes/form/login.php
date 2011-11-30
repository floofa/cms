<?php defined('SYSPATH') or die('No direct script access.');

class Form_Login extends Forms
{
  public function build()
  {
    $this->add('username');
    $this->add('password', 'password');
  }
  
  public function set_rules()
  {
    $this->rule('username', 'not_empty');
    $this->rule('password', 'not_empty');
    
    $this->rule($this->_formo->alias(), array ($this, 'check_user'));
  }
  
  public function do_form($values = array (), $refresh = TRUE, $redirect = FALSE)
  {
    Request::redirect_initial(Route::get('default')->uri());
  }
  
  public function check_user($field)
  {
    return Auth::instance()->login($this->_formo->username->val(), $this->_formo->password->val());
  }
}