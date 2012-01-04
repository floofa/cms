<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Email extends Kohana_Email 
{
  public $view = NULL;
  public $view_layout_name = 'email';
  
  public static function factory($subject = NULL, $message = NULL, $type = NULL)
  {
    $options = array ();
    
    if (is_array($subject)) {
      $options = $subject;
      
      $subject = arr::get($options, 'subject', NULL);
      $message = arr::get($options, 'message', NULL);
      $type = arr::get($type, 'message', 'text/html');
      
      if ( ! is_null($subject)) {
        $subject = ___('email.' . $subject . '_subject');
      }
    }
    
    $instance = parent::factory($subject, $message, $type);
    
    if ($options) {
      if ($view_name = arr::get($options, 'view_name', FALSE)) {
        $instance->view = View::factory('emails/' . $view_name);
      }
      
      if ($view_layout_name = arr::get($options, 'view_layout_name', FALSE)) {
        $instance->view_layout_name = $view_layout_name;
      }
      
      if ($from = arr::get($options, 'from', FALSE)) {
        $instance->from($from);
      }
      
      if ($to = arr::get($options, 'to', FALSE)) {
        $instance->to($to);
      }
      
      if ($cc = arr::get($options, 'cc', FALSE)) {
        $instance->cc($cc);
      }
      
      if ($bcc = arr::get($options, 'bcc', FALSE)) {
        $instance->bcc($bcc);
      }
    }
    
    return $instance;
  }
  
  public function send(array & $failed = NULL)
  {
    if ($this->view) {
      if ($this->view_layout_name !== FALSE) {
        $content = $this->view;
        
        $this->view = View::factory('layouts/' . $this->view_layout_name);
        $this->view->content = $content;
      }
      
      $this->message($this->view->render(), 'text/html');
    }
    
    return parent::send($failed);
  }
  
  public function render_view()
  {
    if ($this->view) {
      if ($this->view_layout_name !== FALSE) {
        $content = $this->view;
        
        $this->view = View::factory('layouts/' . $this->view_layout_name);
        $this->view->content = $content;
      }
      
      $this->message($this->view->render(), 'text/html');
    }
    
    echo $this->view;
    die;
  }
}
