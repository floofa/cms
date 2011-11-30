<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_Controller_Auth extends Controller_Builder_Template
{
  protected $_layout = 'login';
  
  public function action_login()
  {
    /*
    $form = Formo::form('login')->set('view_prefix', 'formo/basic')
      ->add('username', array ('label' => 'Jméno', 'attr' => array ('class' => 'text-input')))
      ->add('password', array ('label' => 'Password', 'attr' => array ('class' => 'text-input')));
    
    $form->rule('username', 'not_empty');
    
    if ($form->load()->validate()) {
      cms::i($form->as_array('value'));
    }
    
    $this->_view->form = $form;
    */
  }
  
  public function action_logout()
  {
    Auth::instance()->logout(TRUE);
    Request::redirect_initial(Route::get('auth-login')->uri());
  }
}