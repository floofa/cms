<?php defined('SYSPATH') or die('No direct script access.');

class Cms_List_Table
{
  protected $_view = 'builder/list/default';
  
  protected $_name;
  
  protected $_heading = FALSE;
  
  protected $_fields = array ();
  
  protected $_items = array ();
  
  protected $_item_id_key = 'id';
  
  protected $_buttons = array ();
  
  protected $_pagination = FALSE;
  
  protected $_drag = FALSE;
  
  protected $_drag_url;
  
  protected $_drag_id = 'id';
  
  protected $_actions = array ();
  
  protected $_row_action = FALSE;
  
  protected $_actions_images = array ();
  
  protected $_filters = '';
  
  protected $_multi_actions = '';
  
  public function __construct($name, $config = array ())
  {
    $this->_name = $name;
    //cms::i('list_' . $name . '_fields');
    $this->_heading = ___('list_' . $name . '_heading');
    $this->_fields = ___('list_' . $name . '_fields');
    $this->_items = arr::get($config, 'items', array ());
    
    if (is_string($this->_fields))
      $this->_fields = array ();
      
    if (isset($config['item_id_key'])) {
      $this->_item_id_key = $config['item_id_key'];
    }
      
    // drag
    $this->_drag = arr::get($config, 'drag', FALSE);
    $this->_drag_url = arr::get($config, 'drag_url', Route::url(Route::name(Request::initial()->route()), array ('controller' => Request::current()->controller(), 'action' => 'sort') + Request::initial()->param()));
    
    // pridani akci nad radky
    foreach (arr::get($config, 'actions', array ()) as $action => $link) {
      $this->_actions[$action] = ($link === TRUE) ? Route::url(Route::name(Request::initial()->route()), array ('controller' => Request::current()->controller(), 'action' => $action, 'id' => '{id}') + Request::initial()->param()) : $link;
    }
    
    // zvoleni akce po kliku na radek
    $row_action = arr::get($config, 'row_action', 'edit');
    
    if ($row_action && isset($this->_actions[$row_action])) {
      $this->_row_action = $row_action;
    }
    
    // nacteni obrazku pro akce
    $this->_actions_images = Kohana::$config->load('administration.table_row_actions_icon_names', array ());
    
    // tlacitka pro novou polozku
    if ($new_button =  arr::get($config, 'new_button', FALSE)) {
      $link = ($new_button !== TRUE) ? $new_button : Route::url(Route::name(Request::initial()->route()), array ('controller' => Request::current()->controller(), 'action' => 'edit', 'id' => 0)+ Request::initial()->param());
      
      $this->_buttons['new'] = new Button_New(___('list_' . $name . '_new_button'), $link);
    }
    
    // nastaveni filtrovaciho formulare
    $this->_filters = arr::get($config, 'filters', '');
  }
  
  /*
  public function __construct($name, $items, $actions, $row_action = 'edit', $new_button = TRUE, $drag = FALSE)
  {
    $this->name = $name;
    
    $this->heading = ___('list_' . $name . '_heading');
    $this->fields = ___('list_' . $name . '_fields');
    $this->items = $items;
    
    if (is_string($this->fields))
      $this->fields = array ();
    
    // drag
    $this->drag = $drag;
    $this->drag_url = Linker::get(Request::initial()->directory(), Request::$initial_controller_name, 'sort');
    
    // pridani akci nad radky
    foreach ($actions as $action => $link) {
      $this->actions[$action] = ($link === TRUE) ? Linker::get(Request::initial()->route()->uri(array ('controller' => Request::$initial_controller_name, 'action' => $action, 'id' => '{id}'))) : $link;
    }
    
    // zvoleni akce po kliku na radek
    if (isset($this->actions[$row_action])) {
      $this->row_action = $row_action;
    }
    
    // nacteni obrazku pro akce
    $this->actions_images = Kohana::config('administration.table_row_actions_icon_names', array ());
    
    if ($new_button) {
      $this->buttons['new'] = new Button_New(___('list_' . $name . '_new_button'), Linker::get(Request::$initial->action('edit')->uri(array ('id' => '0'))));
    }
  }
  */
  
  public function render() 
  {
    $view = View::factory($this->_view)
      ->bind('name', $this->_name)
      ->bind('heading', $this->_heading)
      ->bind('fields', $this->_fields)
      ->bind('items', $this->_items)
      ->bind('item_id_key', $this->_item_id_key)
      ->bind('actions', $this->_actions)
      ->bind('row_action', $this->_row_action)
      ->bind('actions_images', $this->_actions_images)
      ->bind('pagination', $this->_pagination)
      ->bind('buttons', $this->_buttons)
      ->bind('drag', $this->_drag)
      ->bind('drag_url', $this->_drag_url)
      ->bind('filters', $this->_filters)
      ->bind('multi_actions', $this->_multi_actions);
    
    return $view->render();
  }
  
  public function __toString()
  {
    return $this->render();
  }
  
  public function set_pagination($pagination)
  {
    $this->_pagination = $pagination;
    
    return $this;
  }
  
  public function set_row_action($name) 
  {
    if (isset($this->_actions[$name])) {
      $this->_row_action = $name;
    }
    
    return $this;
  }
  
  /**
  * pridani akce
  */
  
  public function set_action($name, $link, $before = FALSE)
  {
    $actions = array ();
    
    if ($before !== FALSE && $before_key = isset($this->_actions[$before])) {
      foreach ($this->_actions as $key => $value) {
        if ($before_key == $key) {
          $actions[$name] = $link;
        }
        
        $actions[$key] = $value;
      }
      
      $this->_actions = $actions;
    }
    else {
      $this->_actions[$name] = $link;
    }
    
    return $this;
  }
  
  public function set_button($name, $button, $before = FALSE)
  {
    $this->_buttons[$name] = $button;
    //cms::i($this->buttons);
    return $this;
  }
  
  public function set_heading($value)
  {
    $this->_heading = $value;
    
    return $this;
  }
  
  public function set_multi_actions_form($form)
  {
    $this->_multi_actions = $form;
  }
}
