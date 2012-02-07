<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Controller_Cms_Users extends Controller_Builder_Template_Administration_Classic
{
  protected function _check_access_rights()
  {
    $res = parent::_check_access_rights();
    
    if ( ! $res)
      return $res;
    
    // overeni pri editaci nebo mazani uzivatele
    if ($this->request->action() == 'edit' || $this->request->action() == 'delete') {
      // uzivatel nema opravneni pro editaci vsech uzivatelu a pokousi se editovat jineho uzivatele nez sam sebe
      if ( ! $this->_user->has_right('right_edit_all_users') && $this->_user->id !== $this->request->param('id'))
        return FALSE;
    }
    
    return TRUE;
  }
}

