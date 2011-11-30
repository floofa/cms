<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_Controller_Builder_Template extends Controller 
{
  protected $_layout = 'default';
  
  protected $_view = NULL;
  
  protected $_auto_render = TRUE;
  
  public function before ()
  {
    parent::before();
    
    if ($this->request->is_initial()) {
      Head::load();
    }
    
    // view name
    $view_name = 'pages/' . trim($this->request->controller() . '-' . $this->request->action());
    
    // vypnuti layoutu pokud je zadost externi  
    if ( ! $this->request->is_initial()) {
      $this->_layout = FALSE;
      $view_name = 'pages/blocks/' . trim($this->request->controller() . '-' . $this->request->action());
    }
    
    // vypnuti layoutu a auto renderingu pokud se jedna o ajax 
    if ($this->request->is_ajax()) {
      $this->_layout = FALSE;
      $view_name = 'pages/ajax/' . trim($this->request->controller() . '-' . $this->request->action());
    }
    
    // nacteni view
    if ($file = Kohana::find_file('views', $view_name)) {
      $this->_view = View::factory($view_name);
    }
    else {
      $this->_view = View::factory('pages/blank');
    }
  }
  
  public function after()
  {
    parent::after();
    
    // auto layout
    if ($this->_layout !== FALSE) {
      $this->_view = View::factory('layouts/' . $this->_layout)->set('content', $this->_view);
    }
    
    // aotu render
    if ($this->_auto_render) {
      $this->response->body($this->_view->render());
    }
    
    // nastaveni hlavicek
    $this->response->headers('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0');
    $this->response->headers('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
    $this->response->headers('Last-Modified', gmdate("D, d M Y H:i:s").' GMT');
    $this->response->headers('Pragma', 'no-cache');
  }
}
