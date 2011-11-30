<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_Forms_Filter extends Forms
{
  protected $_formo_view_prefix = 'formo/filter';
  protected $_view_name = 'filter_default';
  protected $_session_name = FALSE;
  
  public function __construct($name, $folder = FALSE, $model = FALSE, $model_id = FALSE, $data = array ())
  {
    parent::__construct($name, $folder, $model, $model_id, $data);
    
    if (isset($this->_data['session_name'])) {
      $this->_session_name = $this->_data['session_name'];
    }
    
    if ($this->_session_name === FALSE) {
      $this->_session_name = 'filter_' . $this->_model;
    }
    
    $this->set_reset_url();
  }
  
  public function set_values()
  {
    $values = Session::instance()->get($this->_session_name, array ());
    
    foreach ($values as $key => $value) {
      if ($this->_formo->find($key)) {
        $this->_formo->$key->val($value);
      }
    }
  }
  
  public function set_rules()
  {
    
  }
  
  public function do_form($values = array (), $refresh = FALSE, $redirect = TRUE)
  {
    Session::instance()->set($this->_session_name, $values);
    
    Request::refresh_page();
  }
  
  public function set_reset_url()
  {
    if ( ! isset($this->_data['reset_url'])) {
      $this->_data['reset_url'] = URL::site() . Request::initial()->route()->uri(array ('controller' => Request::initial()->controller(), 'action' => 'reset_filters') +  Request::initial()->param());
    }
  }
}
