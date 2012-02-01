<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Galleries extends Cms_Controller_Galleries
{
  public function action_edit_item_description()
  {
    $item = ORM::factory('gallery_item', $this->request->param('id'));
    
    $view = View::factory('pages/galleries-edit_item_description');
    $view->item = $item;
    
    $this->response->body($view->render());
  }
  
  public function action_set_item_description()
  {
    $item = ORM::factory('gallery_item', $this->request->param('id'));
    
    if ( ! $item->loaded())
      return;
      
    $item->description = $this->request->post('description');
    $item->save();
  }
  
  public function action_item_info()
  {
    $item = ORM::factory('gallery_item', $this->request->param('id'));
    
    $view = View::factory('pages/galleries-item_info');
    $view->item = $item;
    
    $this->response->body($view->render());
  }
}