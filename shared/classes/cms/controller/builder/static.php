<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_Controller_Builder_Static extends Controller
{
  protected $_folder = 'static';
  
  protected $_view = NULL;
  
  protected $_auto_render = TRUE;
  
  public function before()
  {
    parent::before();
    
    // nastaveni view
    $view_name = $this->_folder . '/' . $this->request->action();
    
    if ($file = Kohana::find_file('views', $view_name)) {
      $this->_view = View::factory($view_name);
    }
    else {
      $this->_view = View::factory('pages/blank');
    }
  }
  
  public function after()
  {
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