<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_Forms_List extends Forms
{
  protected $_view_name = 'default';
  
  protected $_formo_view_prefix = 'formo/cms';
  
  public function do_form($values = array (), $refresh = FALSE, $redirect = TRUE)
  {
    parent::do_form($values, $refresh, FALSE);

    if ($redirect === TRUE)
      Request::redirect_initial(Request::initial()->route()->uri(array ('controller' => Request::current()->controller(), 'action' => 'list', 'id' => FALSE) + Request::initial()->param()));
    elseif ($redirect !== FALSE)
      Request::redirect_initial($redirect);
  }
}