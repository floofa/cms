<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Email_View
{
  protected $_email;
  protected $_config;
  
  protected $_view;
  
  public static function factory($group)
  {
    return new Email_View($group);
  }
  
  public function __construct($group, $options = array ())
  {
    $config = Kohana::$config->load('emails.groups.' . $group);
    
    $this->_config = $options + (($config) ? $config : array ()) + array ('view_name' => $group);
    $this->_email = Email::factory(___('emails.' . $group . '.subject'), NULL, 'text/html');
    $this->_view = View::factory('emails/' . $this->_config['view_name']);
    
    if (isset($config['to'])) {
      $this->_email->to($config['to']);
    }
  }
  
  public function send(array & $failed = NULL)
  {
    // nastavi view jako obsah emailu
    $this->_email->subject($this->_view->render());
    
    // odesle email
    return $this->_email->send();
  }
  
  public function view_set_param($name, $value)
  {
    $this->_view->set($name, $value);
    
    return $this;
  }
  
  public function __call($method, $args)
  {
    $method = new ReflectionMethod($this->_email, $method);
    return $method->invokeArgs($this->_email, $args);
  }
}