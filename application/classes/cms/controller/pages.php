<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_Controller_Pages extends Cms_Controller_Builder_Template_Application
{ 
  protected $_page;
  
  /**
  * route callback
  * 
  * @param mixed $uri
  * @return mixed
  */
  public static function route($uri)
  {
    $pages = ORM::factory('page')->get_ids();
    
    if (isset($pages[$uri])) {
      return array (
        'controller' => 'pages',
        'action' => 'show',
        'uri' => $uri,
      );
    }
  }
  
  public function action_show($uri = FALSE)
  {
    if ($uri === FALSE)
      $uri = trim($this->request->param('uri', $this->request->uri()), '/');
    
    $this->_page = ORM::factory('page')->where('rew_id', '=', $uri)->find();
    
    if ( ! $this->_page->loaded())
      throw new HTTP_Exception_404('Unable to find a route to match the URI: :uri', array (':uri' => $uri));
    
    // nastaveni typu stranky
    $this->page_type = $this->_page->page_type;
    
    // layout
    if (Kohana::find_file('views/layouts', $this->_page->page_layout)) {
      $this->_layout = 'layouts/' . $this->_page->page_layout;
    }
    
    $view_type_name = FALSE;
    
    // type layout
    if (Kohana::find_file('views/layouts/pages', 'page-' . $this->_page->page_type)) {
      $view_type_name = 'layouts/pages/' . 'page-' . $this->_page->page_type;
    }
    
    // view
    $view_name = 'pages/pages-show';
    
    if (Kohana::find_file('views/pages/static', 'page-' . $this->_page->sys_name)) {
      $view_name = 'pages/static/' . 'page-' . $this->_page->sys_name;
    }
    
    if ($view_type_name !== FALSE) {
      $view_type = View::factory($view_type_name);
    }

    $view = View::factory($view_name);
    $view->content = $this->_page->content;
    
    if ($view_type_name !== FALSE) {
      $view_type = View::factory($view_type_name);
      $view_type->content = $view;
      
      $this->_view = $view_type;
    }
    else {
      $this->_view = $view;
    }
    
    Head::set_arr($this->_page->as_array());
    Navigation::add($this->_page->name, $this->request->url());
  }
  
  public function action_index() 
  {
    $this->action_show(ORM::factory('page')->where('page_type', '=', 'homepage')->find()->rew_id);
  }
}