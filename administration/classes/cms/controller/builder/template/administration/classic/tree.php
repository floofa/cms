<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_Controller_Builder_Template_Administration_Classic_Tree extends Controller_Builder_Template_Administration_Classic
{ 
  protected $_model_mptt;
  
  protected $_list_tree_new_button = TRUE;
  
  protected $_list_tree_row_actions = array ('add_item' => TRUE, 'edit_item' => TRUE, 'delete_item' => TRUE);
  
  protected $_list_tree_root_lvl = 1;
  
  protected $_list_tree_view = FALSE;
  
  protected $_list_tree_default_scope = '1';
  
  protected $_list_tree_drag = TRUE;
  
  protected $_only_tree = TRUE;
  
  protected $_mptt_form_name = 'edit';
  
  public function action_index()
  {
    if ($this->_only_tree)
      Request::redirect_initial(Route::get('mptt-list')->uri(array ('controller' => $this->request->controller(), 'id' => $this->_list_tree_default_scope)));
    else
      parent::action_index();
  }
  
  public function action_list_tree()
  {
    $scope = $this->request->param('id', $this->_list_tree_default_scope);
    
    $root = ORM::factory($this->_model_mptt)->get_root($scope);
    
    if ( ! $root->loaded() || $root->lvl != $this->_list_tree_root_lvl) {
      $root = ORM::factory($this->_model_mptt)->root($this->_list_tree_default_scope);
    }
    
    // navigation
    if ( ! $this->_only_tree) {
      Navigation::add(ORM::factory($this->_model, $root->{$root->scope_column})->name, Request::initial_url());
    }
    
    $s = 'navigation_' . $this->request->controller() . '_list_tree';
    Navigation::add((___($s) != $s) ?  ___($s) : ___('navigation_list') , Request::initial_url());
    
    // table
    $list_config = array (
      'root' => $root,
      'items' => $root->list_all(),//children,
      'actions' => $this->_list_tree_row_actions, 
      'new_button' => $this->_list_tree_new_button,
      'drag' => $this->_list_tree_drag
    );
    
    $list = new List_Tree(($this->_list_name) ? $this->_list_name : $this->_model, $list_config);
    
    // view
    if ($this->_list_view !== FALSE) {
      $this->_view = View::factory($this->_list_view);
    }
    
    $this->_view->render = $list->render();
  }
  
  public function action_move_tree_node()
  {
    if ( ! $this->request->is_ajax())
      exit;
    
    $state = 'error';
    $node = ORM::factory($this->_model_mptt, $this->request->post('id'));
    
    // pouze pokud to danou polozku nacetlo
    if ($node->loaded() && $node->lvl > 1) {
      // pokud ma jeste nejakeho predchoziho souseda, zaradime za nej
      if ($prev_id = $this->request->post('prev')) {
        $node->move_to_next_sibling($prev_id);
      }
      // jinak nastavim jako prvniho
      else {
        $parent_id = $this->request->post('parent');
        
        if (Valid::digit($parent_id))
          $node->move_to_first_child($parent_id);
        else {
          $node->move_to_first_child($node->get_root()->id);
        }
      }
      
      $state = 'ok';
    }
    
    $res['state'] = $state;
    echo json_encode($res);
  }
  
  public function action_edit_tree_item()
  {
    $section = $this->request->param('section');
    $id = $this->request->param('id');
    $parent_id = $this->request->param('parent_id');
    $scope = FALSE;
    
    $parent = ORM::factory($this->_model_mptt, $parent_id);
    
    if ($parent->loaded()) {
      $scope = $parent->{$parent->scope_column};
    }
    
    // navigation
    if ( ! $id) {
      $s = ___('navigation_' . $this->request->controller() . '_new_tree');
      Navigation::add((___($s) != $s) ?  ___($s) : ___('navigation_new'), Request::initial_url());
    }
    else {
      $s = ___('navigation_' . $this->request->controller() . '_edit_tree');
      Navigation::add((___($s) != $s) ?  ___($s) : ___('navigation_edit'), Request::initial_url());
      
      $this->_add_bookmarks($id);
    }
    
    
    $model = inflector::singular(ORM::factory($this->_model_mptt)->table_name());
    
    $form = Forms_List::get($this->_mptt_form_name, $model, $model, $id, array ('scope' => $scope, 'parent_id' => $parent_id));
    $form->set('link_back', Route::url('default', array ('controller' => $this->request->controller(), 'action' => 'list_tree', 'id' => $scope)));
    
    $this->_view = View::factory($this->_edit_view);
    $this->_view->form = $form;
  }
  
  /**
  * smazani polozky ze stromu
  */
  public function action_delete_tree_item()
  {
    $id = $this->request->param('id');
    
    $item_mptt = ORM::factory($this->_model_mptt, $id);

    if ($item_mptt->loaded() && ! $item_mptt->is_root()) {
      $scope = $item_mptt->{$item_mptt->scope_column};
      
      foreach (ORM::factory('gallery')->where('model', '=', $this->_model)->where('model_id', '=', $item_mptt->id)->find_all() as $gallery) {
        $gallery->delete();
      }
      
      $item_mptt->delete();
      
      $this->request->redirect(Route::get('mptt-list')->uri(array ('controller' => $this->request->controller(), 'id' => $scope)));
    }
    
    $this->request->redirect(Route::get('mptt-list')->uri(array ('controller' => $this->request->controller(), 'id' => FALSE)));
  }
}