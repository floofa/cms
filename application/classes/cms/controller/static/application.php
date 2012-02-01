<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_Controller_Static_Application extends Controller_Builder_Static
{ 
  protected $_folder = 'application';
  
  /**
  * paticka
  */
  public function action_footer() {}
  
  /**
  * generovani menu z databaze
  */
  public function action_menu()
  {
    $sys_name = $this->request->param('id');
    
    $menu_model = ORM::factory('menu')->where('sys_name', '=', $sys_name)->find();
    
    $menu = new Menu($sys_name);

    foreach ($menu_model->get_top_items() as $item) {
      $menu_item = $menu->add($item->name, $item->get_link(), array (), $item->get_links_for_active());
      
      if ($children = $item->get_children())
        $this->_generate_submenu($menu, $menu_item, $children);
    }
    
    if (strlen(trim(Request::initial()->uri(), '/')))
      $menu->set_actives(Request::initial()->url(TRUE));
    else {
      $homepage = ORM::factory('page')->get_page_by_type('homepage');
      $menu->set_actives(URL::site($homepage->rew_id, TRUE));
    }
      
    if (Kohana::find_file('views', 'blocks/' . $this->_folder . '/' . $sys_name)) {
      $this->_view = View::factory('blocks/' . $this->_folder . '/' .  $sys_name);
    }
    
    $this->_view->menu = $menu;
  }
  
  /**
  * rekurzivni generovani submenu
  * 
  * @param mixed $menu
  * @param mixed $menu_item
  * @param mixed $items
  */
  public function _generate_submenu($menu, $menu_item, $items)
  {
    foreach ($items as $item) {
      $subitem = $menu->add_sub($menu_item, $item->name, $item->get_link());
      
      if ($children = $item->get_children())
        $this->_generate_submenu($menu, $subitem, $children);
    }
  }
  
  /**
  * prepinac jazyku
  */
  public function action_lang_switcher()
  {
    $this->_view->active_lang = Request::$lang;
    $this->_view->langs = Kohana::$config->load('lang.languages');
  }
}
