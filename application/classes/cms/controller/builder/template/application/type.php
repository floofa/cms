<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_Controller_Builder_Template_Application_Type extends Controller_Builder_Template_Application
{ 
  protected $_model = FALSE;
  
  protected $_model_category = FALSE;
  
  protected $_page_type;
  
  protected $_page = NULL;
  
  public function before()
  {
    parent::before();
    
    $this->_page = ORM::factory('page')->where('sys_name', '=', $this->_page_type)->find();  
    
    // pokud stranka nema povoleno zobrazeni, ukoncime
    if ($this->_page->loaded() && ! $this->_page->cms_status)
      throw new HTTP_Exception_404('Unable to find page type :type.', array (':type' => $this->_page_type));
    
    if ($this->_page->loaded()) {
      Head::set_arr($this->_page->as_array('value'));
      Navigation::add($this->_page->name, Route::url($this->_page_type));
    }
  }
  
  /**
  * vypis polozek
  */
  public function action_list()
  {
    if ($this->_model) {
      $pagination = Pagination::factory(array ('total_items' => ORM::factory($this->_model)->list_count_all()));
      $items = ORM::factory($this->_model)->list_all($pagination->items_per_page, $pagination->offset);
      
      $this->_view
        ->set('items', $items)
        ->set('pagination', $pagination)
        ->set('page', $this->_page);
        
      if ($this->_model_category !== FALSE) {
        $this->_view->set('categories', ORM::factory($this->_model_category)->find_all());
      }
    }
  }
  
  /**
  * vypis polozek z kategorie
  */
  public function action_list_category()
  {
    if ($this->_model && $this->_model_category) {
      $category = ORM::factory($this->_model_category)->where('rew_id', '=', $this->request->param('category_rew_id'))->find();
      
      if ( ! $category->cms_status)
        throw new HTTP_Exception_404('Unable to find category: :category', array (':category' => $this->request->param('category_rew_id')));
      
      Navigation::add($category->name, $category->get_url());
      
      $pagination = Pagination::factory(array ('total_items' => ORM::factory($this->_model)->where($this->_model_category . '_id', '=', $category->id)->list_count_all()));
      $items = ORM::factory($this->_model)->where($this->_model_category . '_id', '=', $category->id)->list_all($pagination->items_per_page, $pagination->offset);
      
      $this->_view
        ->set('category', $category)
        ->set('items', $items)
        ->set('pagination', $pagination)
        ->set('page', $this->_page);
    }
  }
  
  /**
  * detail
  */
  public function action_get()
  {
    if ($this->_model) {
      if ($this->_model_category) {
        $category = ORM::factory($this->_model_category)->where('rew_id', '=', $this->request->param('category_rew_id'))->find();
        
        if ( ! $category->cms_status)
          throw new HTTP_Exception_404('Unable to find category: :category', array (':category' => $this->request->param('category_rew_id')));
      }
      
      $item = ORM::factory($this->_model)->where('rew_id', '=', $this->request->param('rew_id'))->find();

      if ( ! $item->cms_status || ($this->_model_category && $item->{$this->_model_category . '_id'} != $category->id))
        throw new HTTP_Exception_404('Unable to find item :item', array (':item' => $this->request->param('rew_id')));
      
      Head::set_arr($item->as_array('values'));
      Head::set('head_title', $item->name);
      
      if ($this->_model_category)
        Navigation::add($item->{$this->_model_category}->name, $item->{$this->_model_category}->get_url());
      
      Navigation::add($item->name, $item->get_url());
      
      $this->_view
        ->set('item', $item)
        ->set('page', $this->_page);
    }
  }
}