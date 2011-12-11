<?php defined('SYSPATH') or die('No direct script access.');

class Form_Menu_Item_Edit extends Forms_List
{
  public function build()
  {
    $this->group('group1')
      ->col('col1')
      ->add('name');
      
    $this->col('col2')
      ->add('page_id', 'select', array ('options' => array ('#null#' => 'Vybrat') + ORM::factory('page')->find_all()->as_array('id', 'name')))
      ->add('url');
  }
  
  public function set_rules()
  {
    $this->rule('name', 'not_empty');
    $this->rule('name', 'max_length', array (':value', 50));
    
    $this->rule('url', 'max_length', array (':value', 50));
  }
  
  public function do_form($values = array (), $refresh = FALSE, $redirect = TRUE)
  {
    $orm_object = ORM::factory($this->_model, $this->_model_id);
    
    if ( ! $orm_object->loaded()) {
      $mptt_object = ORM::factory('menu_itemmptt');
      $parent = ($this->_data['parent_id']) ? $this->_data['parent_id'] : ORM::factory('menu_itemmptt')->root($this->_data['scope']);
      $mptt_object->values($values)->insert_as_last_child($parent);
      
      $orm_object = ORM::factory($this->_model, $mptt_object->id);
    }
    else {
      $orm_object->values($values)->save();
    }
      
    Request::redirect_initial(Route::get('mptt-list')->uri(array ('controller' => Request::initial()->controller(), 'id' => $orm_object->menu_id)));
  }
}
