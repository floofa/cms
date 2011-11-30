<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_Controller_Builder_Template_Administration_Classic extends Controller_Builder_Template_Administration 
{ 
  protected $_model = TRUE;
  
  protected $_list_view = FALSE;
  
  protected $_list_name = FALSE;
  
  protected $_list_filter_name = FALSE;
  
  protected $_list_filter_session_name = FALSE;
  
  protected $_list_new = TRUE;
  
  protected $_list_drag = FALSE;

  protected $_list_actions = array ('edit' => TRUE, 'delete' => TRUE);
  
  protected $_list_row_action = 'edit';
  
  protected $_list_multi_actions = array ('delete' => array ('confirm' => TRUE));
  
  protected $_edit_view = 'pages/builder/edit';
  
  protected $_date_fields = array ();
  
  protected $_edit_bookmarks = array ();
  
  /**
  * formular pro editaci
  */
  protected $_form;
  
  /**
  * prefix pro nazev formulare
  */
  protected $_form_name = 'edit';
  
  /**
  * nazev sablony pro render formulare
  */
  protected $_form_view = 'builder/form';
  
  
  public function before()
  {
    if ($this->_model === TRUE) {
      $this->_model = Inflector::singular(Request::current()->controller());
    }
    
    parent::before();
    
    Navigation::add(___('navigation_base'), URL::base(TRUE, TRUE));
    Navigation::add(___('navigation_' . $this->request->controller()), Route::url('default', array ('controller' => $this->request->controller())));
  }
  
  public function action_index()
  {
    Request::redirect_initial($this->request->route()->uri(array ('controller' => $this->request->controller(), 'action' => 'list')));
  }
  
  public function action_list()
  {
    $s = 'navigation_' . $this->request->controller() . '_list';
    Navigation::add((___($s) != $s) ?  ___($s) : ___('navigation_list'), Request::initial_url());
    
    // filter
    $filters = Forms::get_exists(($this->_list_filter_name) ? $this->_list_filter_name : 'filter', $this->_model, $this->_model, FALSE, array ('session_name' => $this->_list_filter_session_name));
    
    // pagination
    $pagination = Pagination::factory(array ('total_items' => ORM::factory($this->_model)->set_filter_session_name($this->_list_filter_session_name)->list_count_all()));
    
    // $items
    $items = ORM::factory($this->_model)->set_filter_session_name($this->_list_filter_session_name)->list_all($pagination->items_per_page, $pagination->offset);
    
    // multi select
    $multi = Forms::get('multi', FALSE, $this->_model, '0', array ('list_name' => ($this->_list_name) ? $this->_list_name : $this->_model, 'actions' => $this->_list_multi_actions, 'items_per_page' => $pagination->items_per_page, 'total_pages' => $pagination->total_pages));
    
    // table
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
  
  public function action_edit()
  {
    $id = $this->request->param('id');
    $item = ORM::factory($this->_model, $id);
    
    // pokud bylo zadano id neexistujiciho zaznamu, presmeruje se na vytvoreni
    if ($id && ! $item->loaded())
      Request::redirect_initial($this->request->route()->uri(array ('controller' => $this->request->controller(), 'action' => $this->request->action(), 'id' => '0')));
    
    if ( ! $item->loaded()) {
      $s = 'navigation_' . $this->request->controller() . '_new';
      Navigation::add((___($s) != $s) ?  ___($s) : ___('navigation_new'), Request::initial_url());
    }
    else {
      Navigation::add($item->get_navigation_val(), Request::initial_url());
      $s = 'navigation_' . $this->request->controller() . '_edit';
      Navigation::add((___($s) != $s) ?  ___($s) : ___('navigation_edit'), Request::initial_url());
      
      $this->_add_bookmarks($id);
    }
    
    $args = func_get_args();
    
    // form
    $form_data = $this->set_form_data($args);
    $form = Forms_List::get($this->_form_name, $this->_model, $this->_model, $id, $form_data);
    
    $this->_view = View::factory($this->_edit_view);
    $this->_view->form = $form;
  }
  
  /**
  *  nastaveni dat predavanych do formulare
  * @param mixed $args
  * @return mixed
  */
  protected function set_form_data($args = array ()) {
    return array ();
  }
  
  public function action_delete()
  {
    $item = ORM::factory($this->_model, $this->request->param('id', '0'));

    if ($item->loaded())
      $item->delete();
    
    Request::redirect_initial($this->request->route()->uri(array ('controller' => $this->request->controller(), 'action' => 'list', 'id' => FALSE) +  $this->request->param()));
  }
  
  /**
  * drag na tabulce
  */
  public function action_sort()
  {
    ORM::factory($this->_model)->set_sequence($this->request->post('id'), $this->request->post('nearby_id'));
    die;
  }
  
  protected function _add_bookmarks($id)
  {
    foreach ($this->_edit_bookmarks as $bookmark_name => $options) {
      // link
      $link = $this->_get_bookmark_url($bookmark_name, $id);
      
      // nazev
      $name = ___('bookmarks_' . $options['name']);
      
      // pridani do zalozek
      Bookmarks::add_item($name, $link);
    }
    
    Bookmarks::set_active($this->request->url(TRUE));
  }
  
  public function _get_bookmark_url($name, $id)
  {
    // zpracovani parametru
    if (isset($this->_edit_bookmarks[$name]['params'])) {
      foreach ($this->_edit_bookmarks[$name]['params'] as $key => $value) {
        // pouzije aktualni controller
        if ($key == 'controller' && $value === TRUE) {
          $value = $this->request->controller();
        }
        
        // nahradi {id} za aktualni id
        $value = str_replace('{id}', $id, $value);
        
        // nastaveni zpet do pole
        $this->_edit_bookmarks[$name]['params'][$key] = $value;
      }
    }
    
    // sestaveni odkazu
    if (isset($this->_edit_bookmarks[$name]['route'])) {
      $link = Route::url($this->_edit_bookmarks[$name]['route'], $this->_edit_bookmarks[$name]['params'], TRUE);
    }
    else {
      $link = Request::initial_url();
    }
    
    return $link;
  }
  
  public function _get_main_bookmark_url($id)
  {
    $name = FALSE;
    
    if (count($this->_edit_bookmarks)) {
      foreach ($this->_edit_bookmarks as $bookmark_name => $bookmark) {
        if (isset($bookmark['type']) && $bookmark['type'] = 'main') {
          $name = $bookmark_name;
          break;
        }
      }
      
      if ($name == FALSE) {
        $name = key($this->_edit_bookmarks);
      }
    }
    
    if ($name !== FALSE)
      return $this->_get_bookmark_url($name, $id);
      
    return '';
  }
  
  
  
  public function action_list_bookmark()
  {
    
  }
  
  public function action_reset_filters()
  {
    Session::instance()->delete(($this->_list_filter_session_name) ? $this->_list_filter_session_name : 'filter_' . $this->_model);
    
    Request::redirect_initial($this->request->route()->uri(array ('controller' => $this->request->controller(), 'action' => 'list') +  $this->request->param()));
  }
}
