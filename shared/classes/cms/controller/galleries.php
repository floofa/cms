<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cms_Controller_Galleries extends Controller
{
  public function before()
  {
    
  }
  
  public function action_upload()
  {
    $model = arr::get($_POST, 'model', FALSE);
    $model_id = arr::get($_POST, 'model_id', FALSE);
    $gallery_name = arr::get($_POST, 'name', 'default');
    $session_id = arr::get($_POST, 'session_id', NULL);
    
    $gallery = new Gallery(array ('model' => $model, 'model_id' => $model_id, 'gallery_name' => $gallery_name));
    $gallery->upload($session_id);
  }


  public function action_list()
  {
    $model = $this->request->param('model');
    $model_id = $this->request->param('model_id');
    $gallery_name = $this->request->param('gallery_name');
    
    $gallery = new Gallery(array ('model' => $model, 'model_id' => $model_id, 'gallery_name' => $gallery_name));
    
    $this->response->body($gallery->generate_list());
  }
  
  public function action_delete_item()
  {
    if ( ! $this->request->is_ajax())
      exit;
   
    $id = $this->request->param('id');
      
    $item = ORM::factory('gallery_item', $id);

    if ($item->loaded())
      $item->delete();
      
    $res['state'] = 'ok';
      
    $this->response->headers('Content-type','application/json; charset='.Kohana::$charset);
    $this->response->body(json_encode($res));
  }
  
  public function action_reorder()
  {
    if (isset($_POST['items'])) {
      ORM::factory('gallery')->reorder($_POST['items']);
    }
    
    $res['state'] = 'ok';
    $this->response->headers('Content-type','application/json; charset='.Kohana::$charset);
    $this->response->body(json_encode($res));
  }
}
