<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_Controller_Pages extends Controller_Builder_Template_Application
{ 
  protected $_page;
  
  /**
  * presmerovani 
  * 
  * @var mixed
  */
  protected $_redirect_homepage = TRUE;
  
  /**
  * route callback - nastavi routy pro stat. stranky
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
  
  public function action_show($uri = NULL)
  {
    $_uri = $uri;
    
    if (is_null($uri)) {
      $uri = trim($this->request->param('uri', $this->request->uri()), '/');
    }
    
    $this->_page = ORM::factory('page')->where('rew_id', '=', $uri)->find();
    
    if ( ! $this->_page->loaded())
      throw new HTTP_Exception_404('Unable to find a route to match the URI: :uri', array (':uri' => $uri));
      
    if ($this->_page->sys_name == 'homepage' && is_null($_uri)) {
      Request::redirect_initial('', 301);
    }
    
    // nastaveni typu stranky
    $this->page_sys_name = $this->_page->sys_name;
    
    // nastaveni layoutu stranky, pokud existuje
    if (Kohana::find_file('views/layouts', $this->_page->page_layout)) {
      $this->_layout = 'layouts/' . $this->_page->page_layout;
    }
    
    $layout_content = FALSE;
    
    // nastaveni layout obsahu, pokud existuje
    if (Kohana::find_file('views/layouts/content', 'page-' . $this->_page->page_layout)) {
      $layout_content = 'layouts/content/' . 'page-' . $this->_page->page_layout;
    }
    
    // view
    $view_name = 'pages/pages-show';
    
    // nastaveni view podle sys_name, pokud existuje
    if (Kohana::find_file('views/pages/static', 'page-' . $this->_page->sys_name)) {
      $view_name = 'pages/static/' . 'page-' . $this->_page->sys_name;
    }
    
    // vytvoreni view
    if ($layout_content) {
      $this->_view = View::factory($layout_content);
      $this->_view->content = View::factory($view_name)->set('content', $this->_page->content);
    }
    else {
      $this->_view = View::factory($view_name)->set('content', $this->_page->content);
    }
    
    Head::set_arr($this->_page->as_array());
    Navigation::add($this->_page->name, $this->request->url());
  }
  
  public function action_index() 
  {
    $this->action_show(ORM::factory('page')->get_page_by_type('homepage')->rew_id);
  }
}