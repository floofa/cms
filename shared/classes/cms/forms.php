<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Forms
{ 
  protected static $_forms = array ();
  
  protected static $_submitted_form = NULL;
  
  protected $_name;
  
  protected $_folder;
  
  protected $_model;
  
  protected $_model_id;
  
  protected $_saved = NULL;
  
  protected $_formo;
  
  protected $_formo_view_prefix = 'formo/basic';
  
  protected $_view_name = FALSE;
  
  protected $_current_group = 'group1';
  
  protected $_current_column = 'col';
  
  protected $_galleries = array ();
  
  public static function get($name, $folder = FALSE, $model = FALSE, $model_id = FALSE, $data = array ())
  {
    $file = 'form/' . (($folder !== FALSE) ? $folder . '/' : '') . $name;
    
    if ($path = Kohana::find_file('classes', $file)) {
      require_once $path;
    }
    else
      throw new Kohana_Exception('Your requested form :file is not found.', array (':file' => $file));
      
    $class = 'form_' . (($folder !== FALSE) ? $folder . '_' : '') . $name;
    
    // vytvoreni instance formulare
    self::$_forms[$class] = $form = new $class($name, $folder, $model, $model_id, $data);
    
    $form->build();
    $form->set_values();
    $form->set_rules();
    
    if ($form->validate()) {
      $form->do_form($form->prepare_values());
    }
    
    return $form->render();
  }
  
  public static function get_exists ($name, $folder = FALSE, $model = FALSE, $model_id = FALSE, $data = array ())
  {
    $file = 'form/' . (($folder !== FALSE) ? $folder . '/' : '') . $name;
    
    if ( ! Kohana::find_file('classes', $file))
      return '';
      
    return self::get($name, $folder, $model, $model_id, $data);
  }
  
  public function __construct($name, $folder = FALSE, $model = FALSE, $model_id = FALSE, $data = array ())
  {
    $this->_name = $name;
    $this->_folder = $folder;
    $this->_model = $model;
    $this->_model_id = $model_id;
    $this->_data = $data;
    
    $this->_formo = Formo::form('form_' . (($this->_folder !== FALSE) ? $this->_folder . '_' : '') . $this->_name);
    $this->_formo->view()->attr('id', $this->_formo->alias());
    
    if ($this->_formo_view_prefix !== FALSE)
      $this->_formo->set('view_prefix', $this->_formo_view_prefix);
  }
  
  public function build()
  {
    
  }
  
  /**
  * nacteni hodnot do formulare
  * 
  */
  public function set_values()
  {
    $orm_object = $this->_load_orm_object();
    
    if ( ! is_null($orm_object) && $orm_object->loaded()) {
      foreach ($orm_object->as_form_array() as $key => $value) {
        if ($this->_formo->find($key)) {
          $this->_formo->$key->val($value);
        }
      }
    }
    
    return $this;
  }
  
  /**
  * nacteni pravidel do formulare
  */
  public function set_rules()
  {

  }
  
  /**
  * validace formulare
  */
  public function validate($values = NULL) 
  {
    return $this->_formo->load($values)->validate(TRUE);
  }
  
  /**
  * uprava hodnot pred ulozenim
  */
  public function prepare_values()
  {
    return $this->as_array();
  }
  
  /**
  * zpracovani formulare
  */
  public function do_form($values = array (), $refresh = TRUE, $redirect = FALSE)
  {
    $orm_object = $this->_load_orm_object();
    
    if ( ! is_null($orm_object)) {
      $this->_saved = $orm_object->values($values)->save();
    }
    
    // ulozi do session informaci o zpracovani formulare
    Session::instance()->set('cms.submitted_form', $this->get_form_name());
      
    if ($refresh)
      Request::refresh_page();
      
    if ($redirect !== FALSE)
      Reuqest::redirect_initial($redirect);
  }
  
  protected function _load_orm_object()
  {
    if ($this->_model !== FALSE)
      return ORM::factory($this->_model, $this->_model_id);
      
    return NULL;
  }
  
  public function render($view = FALSE)
  {
    if ($view === FALSE) {
      if ($this->_view_name === FALSE) {
        $view = 'form/' . (($this->_folder !== FALSE) ? $this->_folder . '/' : '') . $this->_name;
      }
      else {
        $view = 'form/' . $this->_view_name;
      }
    }
    return View::factory($view)
      ->bind('form', $this->_formo)
      ->set('groups', $this->get_groups())
      ->set('data', $this->_data)
      ->set('galleries', $this->_galleries)
      ->set('link_back', $this->get_back_link());
  }
  
  /**
  * link pro navraceni z formulare beze zmen
  */
  public function get_back_link()
  {
    return Route::url(Route::name(Request::initial()->route()), array ('controller' => Request::current()->controller(), 'action' => 'list', 'id' => FALSE) + Request::initial()->param());
  }
  
  /**
  * ziskani hodnot z formulare
  */
  public function as_array()
  {
    return $this->_formo->as_array('value');
  }
  
  public function get_form_name()
  {
    return $this->_formo->name();
  }
  
  /**
  * pridani pole do formulare
  */
  public function add($alias, $driver = NULL, $value = NULL, array $options = NULL)
  {
    $alias = (is_array($alias)) ? $alias['alias'] : $alias;
    
    $this->_formo->add($alias, $driver, $value, $options);

    // set group, col, label
    $this->_formo->$alias->set(array (
      'group' => $this->_current_group, 
      'column' => $this->_current_column, 
      'label' => ___($this->_formo->alias() . '_field_' . $alias)
    ));
    
    return $this;
  }
  
  /**
  * vlozeni galerie do formulare
  * 
  * @param mixed $gallery_name
  * @param mixed $model
  * @param mixed $model_id
  * @param string $data
  */
  public function add_gallery($gallery_name = 'default', $model = FALSE, $model_id = FALSE, $data = array ())
  {
    $data['model'] = $model;
    $data['model_id'] = (is_null($model_id)) ? 0 : $model_id;
    $data['gallery_name'] = $gallery_name;
    $data['heading'] = ___($this->_formo->alias() . '_group_' . $gallery_name);
    
    $this->_galleries[ ] = new Gallery($data);
    
    Assets::add_css('uploadify');
    Assets::add_js('jquery.uploadify');
  }
  
  public function group($name)
  {
    $this->_current_group = $name;
    
    return $this;
  }
  
  public function col($name)
  {
    $this->_current_column = $name;
    
    return $this;
  }
  
  public function get_groups()
  {
    $groups = array ();
    
    foreach ($this->_formo->fields() as $field) {
      $name = ($this->_formo->alias() . '_group_' . $field->group != ___($this->_formo->alias() . '_group_' . $field->group)) ? ___($this->_formo->alias() . '_group_' . $field->group) : '';
      
      $groups[$field->group]['name'] = $name;
      $groups[$field->group]['columns'][$field->column][ ] = $field;
    }
    
    return $groups;
  }
  
  public function rule($field, $rule, array $params = NULL)
  {
    return $this->_formo->rule($field, $rule, $params);
  }
  
  public function rules($field, array $rules)
  {
    return $this->_formo->rules($field, $rules);
  }
  
  public function is_unique($value, $field)
  {
    return ORM::factory($this->_model, $this->_model_id)->is_unique($value, $field);
  }
  
  public function exists($value, $field, $model)
  {
    return ORM::factory($model)->exists($value, $field);
  }
  
  public function sent($values = NULL) 
  {
    return $this->_formo->sent($values);
  }

  // zjisti, jestli byl formular odeslan
  public static function is_sent($name, $folder, $values = NULL)
  {
    $form_name = 'form_' . (($folder !== FALSE) ? $folder . '_' : '') . $name;
    
    if (isset(self::$_forms[$form_name]))
      return self::$_forms[$form_name]->sent();
    
    return FALSE;
  }
  
  /**
  * zjisti, jestli byl formular USPESNE odeslan, z hodnoty v session nastavenou pri zpracovani formulare
  * 
  * @param mixed $form_name
  */
  public static function is_submitted($form_name)
  {
    if (is_null(self::$_submitted_form)) {
      self::$_submitted_form = Session::instance()->get_once('cms.submitted_form', FALSE);
    }
    
    return (self::$_submitted_form !== FALSE && self::$_submitted_form == $form_name);
  }
}