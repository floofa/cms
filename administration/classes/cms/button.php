<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Button
{
  protected $view = 'builder/buttons/base';
  
  public function __construct($label, $link, $classes = array ())
  {
    $this->label = $label;
    $this->link = $link;
    $this->classes = $classes;
  }
  
  public function render() 
  {
    $class = implode(' ', $this->classes);
    
    $view = View::factory($this->view)
      ->bind('label', $this->label)
      ->bind('link', $this->link)
      ->bind('class', $class);
    
    return $view->render();
  }
  
  public function __toString()
  {
    return $this->render();
  }
}