<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_Controller_Application extends Cms_Controller_Builder_Template_Application
{ 
  public function action_footer()
  {
    
  }
  
  public function action_generate_menu()
  {
    $sys_name = $this->request->param('id');
    
    $menu_model = ORM::factory('menu')->where('sys_name', '=', $sys_name)->find();
    
    $menu = new Menu($sys_name);

    foreach ($menu_model->menu_items->where('lvl', '=', 2)->find_all() as $item) {
      $menu_item = $menu->add($item->name, $item->get_link());
      
      if ($children = $item->get_children())
        $this->_generate_submenu($menu, $menu_item, $children);
    }
    
    if (Kohana::find_file('views', 'pages/blocks/' . $this->request->controller() . '-' . $sys_name)) {
      $this->_view = View::factory('pages/blocks/' . $this->request->controller() . '-' . $sys_name);
    }
    
    $this->_view->menu = $menu;
    
    if (strlen(trim($this->request->uri(), '/')))
      $menu->set_actives($this->request->url(TRUE));
    else
      $menu->set_actives(URL::site('uvod'));
  }
  
  public function action_main_menu()
  {
    $sys_name = 'main_menu';
    
    $menu_model = ORM::factory('menu')->where('sys_name', '=', $sys_name)->find();
    
    $menu = new Menu($sys_name);
    
    foreach ($menu_model->menu_items->where('lvl', '=', 2)->find_all() as $item) {
      $menu_item = $menu->add($item->name, $item->get_link());
      
      if ($item->page->page_type == 'products') {
        foreach (ORM::factory('product_category')->where('cms_status', '=', 1)->find_all() as $category) {
          $menu->add_sub($menu_item, $category->name, $category->get_url());
        }
      }
      elseif ($children = $item->get_children())
        $this->_generate_submenu($menu, $menu_item, $children);
      
    }
    
    $this->_view->menu = $menu;
    
    if (strlen(trim($this->request->uri(), '/')))
      $menu->set_actives($this->request->url(TRUE));
    else
      $menu->set_actives(URL::site('uvod'));
  }
  
  public function _generate_submenu($menu, $menu_item, $items)
  {
    foreach ($items as $item) {
      $subitem = $menu->add_sub($menu_item, $item->name, $item->get_link());
      
      if ($children = $item->get_children())
        $this->_generate_submenu($menu, $subitem, $children);
    }
  }
  
  public function action_lang_switcher()
  {
    $this->_view->active_lang = Request::$lang;
    $this->_view->langs = Kohana::$config->load('lang.languages');
  }
}
