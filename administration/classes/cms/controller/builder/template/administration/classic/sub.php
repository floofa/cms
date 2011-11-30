<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_Controller_Builder_Template_Administration_Classic_Sub extends Controller_Builder_Template_Administration_Classic 
{
  protected $_bookmark_name;
  
  protected $_parent_model;
  
  protected $_parent_item;
  
  public function before()
  {
    parent::before();
    
    $this->_parent_item = ORM::factory($this->_parent_model, $this->request->param('parent_id'));
    
    if ( ! $this->_parent_item->loaded())
      throw new Kohana_Exception('The requested id (:id) for model :model not found.', array (':id' => $parent_id, ':model' => $this->_parent_model));
    
    Navigation::remove_last();
    Navigation::add(___('navigation_' . $this->request->param('parent_controller')), Route::url('default', array ('controller' => $this->request->param('parent_controller'))));
    Navigation::add($this->_parent_item->get_navigation_val(), $this->_get_main_bookmark_url($this->_parent_item->id));
    Navigation::add(___('navigation_' . $this->request->controller()), URL::site() . $this->request->route()->uri(array ('parent_id' => $this->_parent_item->id)));
  }
  
  public function action_index()
  {
    Request::redirect_initial($this->request->route()->uri(array ('controller' => $this->request->controller(), 'action' => 'list') + $this->request->param()));
  }
  
  public function action_list()
  {
    Navigation::add(___('navigation_list'), Request::initial_url());
    
    $this->_add_bookmarks($this->_parent_item->id);
    
    // filter
    $filters = Forms::get_exists(($this->_list_filter_name) ? $this->_list_filter_name : 'filter', $this->_model, $this->_model, FALSE, array ('session_name' => $this->_list_filter_session_name));
    
    $items_count = ORM::factory($this->_model)->set_filter_session_name($this->_list_filter_session_name);
    $this->_set_list_where($items_count);
    
    $items = clone $items_count;
    
    $pagination = Pagination::factory(array ('total_items' => $items_count->list_count_all()));
    $items = $items->list_all($pagination->items_per_page, $pagination->offset);
    
    // multi select
    $multi = Forms::get('multi', FALSE, $this->_model, '0', array ('list_name' => ($this->_list_name) ? $this->_list_name : $this->_model, 'actions' => $this->_list_multi_actions, 'items_per_page' => $pagination->items_per_page, 'total_pages' => $pagination->total_pages));
    
    $table_config = array (
      'items' => $items,
      'actions' => $this->_list_actions, 
      'row_action' => $this->_list_row_action,
      'new_button' => $this->_list_new,
      'drag' => $this->_list_drag,
      'filters' => $filters
    );
    
    $table = new List_Table(($this->_list_name) ? $this->_list_name : $this->_model, $table_config);
    $table->set_pagination($pagination);
    $table->set_multi_actions_form($multi);
    
    // view
    if ($this->_list_view !== FALSE) {
      $this->_view = View::factory($this->_list_view);
    }
    
    $this->_view->render = $table->render();
  }
  
  protected function _set_list_where($orm_object)
  {
    $orm_object->where($this->_parent_model . '_id', '=', $this->_parent_item->id);
  }
  
  public function action_edit()
  {
    $id = $this->request->param('id');
    $item = ORM::factory($this->_model, $id);
    
    // pokud bylo zadano id neexistujiciho zaznamu, presmeruje se na vytvoreni
    if ($id && ! $item->loaded())
      Request::redirect_initial($this->request->route()->uri(array ('controller' => $this->request->controller(), 'action' => $this->request->action(), 'id' => '0') + $this->request->param()));
    
    if ( ! $item->loaded()) {
      $s = 'navigation_' . $this->request->controller() . '_new';
      Navigation::add((___($s) != $s) ?  ___($s) : ___('navigation_new'), Request::initial_url());
    }
    else {
      Navigation::add($item->get_navigation_val(), Request::initial_url());
      $s = 'navigation_' . $this->request->controller() . '_edit';
      Navigation::add((___($s) != $s) ?  ___($s) : ___('navigation_edit'), Request::initial_url());
    }
    
    $this->_add_bookmarks($this->_parent_item->id);
    
    $args = func_get_args();
    
    // form
    $form_data = $this->set_form_data($args);
    $form = Forms_List::get($this->_form_name, $this->_model, $this->_model, $id, $form_data);
    
    $this->_view = View::factory($this->_edit_view);
    $this->_view->form = $form;
  }
}