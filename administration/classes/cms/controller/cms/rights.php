<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Controller_Cms_Rights extends Controller_Builder_Template_Administration_Classic
{
  protected $_list_drag = TRUE;
  
  public function before()
  {
    parent::before();
  }
  
  protected function _check_access_rights()
  {
    return FALSE;
  }
}

