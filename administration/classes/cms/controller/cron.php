<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Controller_Cron extends Controller
{
  public function action_index()
  {
    
  }
  
  public function action_run()
  {
    $event_type = $this->request->param('event_type');
    $events = Kohana::$config->load('cron.events');
    
    if ( ! array_key_exists($event_type, $events))
      throw new Kohana_HTTP_Exception_404('Requested event not found.');
    
    foreach ($events[$event_type] as $name => $event) {
      Request::factory(Route::get($event['route'])->uri(isset($event['params']) ? $event['params'] : array ()))->execute();
      echo "$name - done.\n";
    }
    
    echo "Cron $event_type done.\n";
  }
}