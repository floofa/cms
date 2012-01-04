<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_Forms_List extends Forms
{
  protected $_view_name = 'default';
  
  protected $_formo_view_prefix = 'formo/cms';
  
  protected $_multilang_fields = array ();
  
  public function do_form($values = array (), $refresh = FALSE, $redirect = TRUE)
  {
    parent::do_form($values, $refresh, FALSE);

    if ($redirect === TRUE)
      Request::redirect_initial(Request::initial()->route()->uri(array ('controller' => Request::current()->controller(), 'action' => 'list', 'id' => FALSE) + Request::initial()->param()));
    elseif ($redirect !== FALSE)
      Request::redirect_initial($redirect);
  }
  
  public function add($alias, $driver = NULL, $value = NULL, array $options = NULL) 
  {
    $alias = (is_array($alias)) ? $alias['alias'] : $alias;
    
    if ( ! Kohana::$config->load('lang.cms_enabled') || ! in_array($alias, $this->_multilang_fields)) {
      parent::add($alias, $driver, $value, $options);
    }
    else {
      $_alias = $alias;
      
      $this->_formo->active_lang = $default_lang = Kohana::$config->load('lang.cms_default_lang');
      
      foreach (Kohana::$config->load('lang.languages') as $key => $name) {
        if ($key !== $default_lang) {
          $_alias = $alias . '_' . $key;
        }
        
        parent::add($_alias, $driver, $value, $options);
        
        $this->_formo->$_alias->set('lang', $key);
      }
    }
    
    return $this;
  }
  
  public function rule($field, $rule, array $params = NULL)
  {
    if ( ! Kohana::$config->load('lang.cms_enabled') || ! in_array($field, $this->_multilang_fields)) {
      parent::rule($field, $rule, $params);
    }
    else {
      $_field = $field;
      
      $default_lang = Kohana::$config->load('lang.cms_default_lang');
      
      foreach (Kohana::$config->load('lang.languages') as $key => $name) {
        if ($key !== $default_lang) {
          $_field = $field . '_' . $key;
        }
        
        parent::rule($_field, $rule, $params);
      }
    }
    
    return $this;
  }
  
  public function rules($field, array $rules)
  {
    if ( ! Kohana::$config->load('lang.cms_enabled') || ! in_array($field, $this->_multilang_fields)) {
      parent::rules($field, $rules);
    }
    else {
      $_field = $field;
      
      $default_lang = Kohana::$config->load('lang.cms_default_lang');
      
      foreach (Kohana::$config->load('lang.languages') as $key => $name) {
        if ($key !== $default_lang) {
          $_field = $field . '_' . $key;
        }
        
        parent::rules($_field, $rules);
      }
    }
    
    return $this;
  }
  
  /*
  public function add_multi($alias, $driver = NULL, $value = NULL, array $options = NULL)
  {
    if ( ! Kohana::$config->load('lang.cms_enabled')) {
      parent::add($alias, $driver, $value, $options);
    }
    else {
      $alias = $_alias = (is_array($alias)) ? $alias['alias'] : $alias;
      
      $this->_formo->active_lang = $default_lang = Kohana::$config->load('lang.cms_default_lang');
      
      foreach (Kohana::$config->load('lang.languages') as $key => $name) {
        if ($key !== $default_lang) {
          $_alias = $alias . '_' . $key;
        }
        
        parent::add($_alias, $driver, $value, $options);
        
        $this->_formo->$_alias->set('lang', $key);
      }
    }
    
    return $this;
  }
  */
  
  /*
  public function rules_multi($field, array $rules)
  {
    if ( ! Kohana::$config->load('lang.cms_enabled')) {
      parent::rules($field, $rules);
    }
    else {
      $_field = $field;
      
      $default_lang = Kohana::$config->load('lang.cms_default_lang');
      
      foreach (Kohana::$config->load('lang.languages') as $key => $name) {
        if ($key !== $default_lang) {
          $_field = $field . '_' . $key;
        }
        
        parent::rules($_field, $rules);
      }
    }
    
    return $this;
  }
  */
  
  public function render($view = FALSE) 
  {
    $langs_errors = array ();
    
    foreach ($this->_formo->errors() as $key => $error) {
      if (isset($this->_formo->$key->lang)) {
        $langs_errors[ ] = $this->_formo->$key->lang;
      }
    }
    
    $view = parent::render($view);
    
    if (count($langs_errors)) {
      $default_lang = Kohana::$config->load('lang.cms_default_lang');
      
      if (in_array($default_lang, $langs_errors)) {
        $this->_formo->active_lang = $default_lang;
      }
      else {
        $this->_formo->active_lang = current($langs_errors);
      }
    }
    
    return $view;
  }
}