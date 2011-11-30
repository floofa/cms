<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_List_Tree
{
  protected $_view = 'builder/list/tree';
  
  protected $_name;
  
  protected $_heading = FALSE;
  
  protected $_root;
  
  protected $_items = array ();
  
  protected $_items_id_key = 'id';
  
  protected $_items_parent_id_key = 'parent_id';
  
  protected $_main_fields = array ();
  
  protected $_main_fields_delimiter = ' - ';
  
  protected $_secondary_fields = array ();
  
  protected $_secondary_fields_delimiter = ' | ';
  
  protected $_drag;
  
  protected $_drag_url;
  
  protected $_actions = array ();
  
  protected $_actions_images = array ();
  
  protected $_buttons = array ();
  
  public function __construct($name, $config)
  {
    $this->_name = $name;
    $this->_heading = ___('list_tree_' . $name . '_heading');
    
    $this->_root = arr::get($config, 'root', FALSE);
    $this->_items = arr::get($config, 'items', array ());
    $this->_items_id_key = arr::get($config, 'items_id_key', 'id');
    $this->_items_parent_id_key = arr::get($config, 'items_id_key', 'parent_id');
    
    $this->_main_fields = ___('list_tree_' . $name . '_fields');
    $this->_secondary_fields = ___('list_tree_' . $name . '_secondary_fields');
        
    if ( ! is_array($this->_main_fields)) {
      $this->_main_fields = array ();
    }
    
    if ( ! is_array($this->_secondary_fields)) {
      $this->_secondary_fields = array ();
    }
    
    // drag
    $this->_drag = arr::get($config, 'drag', FALSE);
    $this->_drag_url = arr::get($config, 'drag_url', Route::url('mptt-drag', array ('controller' => Request::initial()->controller()), TRUE));
    
    // pridani akci nad radky
    foreach (arr::get($config, 'actions', array ()) as $action => $link) {
      if ($link) {
        if ($link === TRUE) {
          switch ($action) {
            case 'add_item':
              $link = Route::url('mptt-edit', array ('controller' => Request::$initial->controller(), 'id' => 0, 'parent_id' => '{id}'));
              break;
            case 'edit_item':
              $link = Route::url('mptt-edit', array ('controller' => Request::$initial->controller(), 'id' => '{id}', 'parent_id' => '{parent_id}'));
              break;
            case 'delete_item':
              $link = Route::url('mptt-delete_item', array ('controller' => Request::$initial->controller(), 'id' => '{id}'));
              break;
          }
        }
        
        $this->_actions[$action] = $link;
      }
    }
    
    // nacteni obrazku pro akce
    $this->_actions_images = Kohana::$config->load('administration.table_row_actions_icon_names', array ());
    
    // tlacitka pro novou polozku
    if ($new_button =  arr::get($config, 'new_button', FALSE)) {
      $link = ($new_button !== TRUE) ? $new_button : Route::url('mptt-edit', array ('controller' => Request::$initial->controller(), 'id' => 0, 'parent_id' => ($this->_root && $this->_root->loaded()) ? $this->_root->id : FALSE));
      $this->_buttons['new'] = new Button_New(___('list_tree_' . $name . '_new_button'), $link);
    }
    
    Assets::add_js('jquery.cookie');
    Assets::add_js('jquery.jstree');
  }
  
  protected function set_links()
  {
    foreach ($this->_items as $item) {
      foreach ($this->_actions as $key => $link) {
        $this->_actions[$key] = str_replace('{parent_id}', $item->{$this->_items_parent_id_key}, str_replace('{id}', $item->{$this->_items_id_key}, $link));
      }
    }
  }
  
  public function render() 
  {
    //$this->set_links();
    
    $view = View::factory($this->_view)
      ->bind('name', $this->_name)
      ->bind('heading', $this->_heading)
      ->bind('main_fields', $this->_main_fields)
      ->bind('main_fields_delimiter', $this->_main_fields_delimiter)
      ->bind('secondary_fields', $this->_secondary_fields)
      ->bind('secondary_fields_delimiter', $this->_secondary_fields_delimiter)
      ->bind('items', $this->_items)
      ->bind('items_id_key', $this->_items_id_key)
      ->bind('items_parent_id_key', $this->_items_parent_id_key)
      ->bind('actions', $this->_actions)
      ->bind('actions_images', $this->_actions_images)
      ->bind('buttons', $this->_buttons)
      ->bind('drag', $this->_drag)
      ->bind('drag_url', $this->_drag_url);
    
    return $view->render();
  }
  
  public function __toString()
  {
    return $this->render();
  }
}