<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Button_New extends Button
{
  protected $view = 'builder/buttons/base';
  
  public function __construct($label, $link, $classes = array ('new-button'))
  {
    parent::__construct($label, $link, $classes);
  }
}