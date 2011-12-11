<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_Controller_Error_Handler extends Controller_Builder_Template
{
  protected $_layout = 'error';
  protected $_page = FALSE;
  protected $_message = FALSE;
  protected $_status = 400;
  
  protected $_actions = array (
    404 => array (400, 401, 402, 403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417),
    500 => array (500, 501, 502, 503, 504, 505),
  );
  
  public function before()
  {
    $this->_page = URL::site(rawurldecode(Request::$initial->uri()), TRUE);
    $this->_status = $this->request->action();
    
    // Internal request only!
    if (Request::$initial !== Request::$current)
    {
      // nastaveni akce
      foreach ($this->_actions as $action => $values) {
        if (in_array($this->request->action(), $values))
          $this->request->action($action);
      }
      
      // chybova hlaska
      if ($message = rawurldecode($this->request->param('message'))) {
        $this->_message = $message;
      }
    }
    else
    {
        $this->request->action(404);
    }
    
    if ($file = Kohana::find_file('views', 'kohana/error/report_' . $this->request->action())) {
      $this->_view = View::factory('kohana/error/report_' . $this->request->action());
    }
    else {
      $this->_view = View::factory('kohana/error/report_' . 404);
    }
  }
  
  public function action_404()
  {

  }
  
  public function action_500()
  {

  }
  
  public function after()
  {
    parent::after();
    
    $this->response->status($this->_status);
  }
}