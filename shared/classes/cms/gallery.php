<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Gallery 
{ 
  protected $_data = array ('model' => '', 'model_id' => '0', 'gallery_name' => 'default', 'heading' => '', 'views' => array ('gallery' => 'builder/galleries/default-gallery', 'form' => 'builder/galleries/default-form', 'list' => 'builder/galleries/default-list'));
  
  public function __construct($data)
  {
    $default_config = Kohana::$config->load('gallery.default');
    $config = Kohana::$config->load('gallery.' . $data['gallery_name']);
    
    if (is_array($config)) {
      $config += $default_config;
    }
    else {
      $config = $default_config;
    }
    
    $this->_data = $data + $config + $this->_data;
  }
  
  public function generate()
  {
    return View::factory($this->_data['views']['gallery'])
      ->set($this->_data)
      ->set('form', $this->generate_form())
      ->set('list', $this->generate_list());
  }
  
  public function generate_form()
  {
    return View::factory($this->_data['views']['form'])
      ->set($this->_data);
  }
  
  public function generate_list()
  {
    $gallery = ORM::factory('gallery')->where('model', '=', $this->_data['model'])->where('name', '=', $this->_data['gallery_name']);
    
    if ($this->_data['model_id']) {
      $gallery->where('model_id', '=', $this->_data['model_id']);
    }
    else {
      $gallery->where('session_id', '=', Session::instance()->id());
    }
    
    $gallery = $gallery->find();
    
    $gallery_items = $gallery->gallery_items->find_all();
    
    //$gallery_items->current()->generate_thumbs(Kohana::config('gallery.default'), Kohana::config('gallery.thumbs.default'));
   
    return View::factory($this->_data['views']['list'])
      ->set('gallery', $gallery)
      ->set('gallery_items', $gallery_items)
      ->set($this->_data);
  }
  
  public function upload($session_id)
  {
    if( ! isset($_FILES["Filedata"]) || ! is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0)
      exit;
    
    $model = $this->_data['model'];
    $model_id = $this->_data['model_id'];
    $gallery_name = $this->_data['gallery_name'];
     
    $gallery = ORM::factory('gallery')->where('model', '=', $model)->and_where('name', '=', $gallery_name);
    
    if ($model_id) {
      $gallery->where('model_id', '=', $model_id);
    }
    else {
      $gallery->where('session_id', '=', $session_id);
    }
    
    $gallery->find();
    
    if ( ! $gallery->loaded()) {
      $gallery->model = $model;
      $gallery->model_id = ($model_id) ? $model_id : NULL;
      $gallery->name = $gallery_name;
      $gallery->session_id = ($model_id) ? NULL : $session_id;
      $gallery->save();
    }
    elseif ($this->_data['one_file']) {
      foreach ($gallery->gallery_items->find_all() as $item) {
        $item->delete();
      }
    }
    
    $item = ORM::factory('gallery_item');
    $item->gallery_id = $gallery->id;
    $item->ext = file::extension($_FILES['Filedata']['name']);
    $item->name = $_FILES['Filedata']['name'];
    $item->description = $_FILES['Filedata']['name'];
    $item->save();
    
    move_uploaded_file($_FILES['Filedata']['tmp_name'], DOCROOT . 'media/content-images/' . $model . '/' . $item->id . '.' . $item->ext);
    
    $item->generate_thumbs($this->_data, $this->get_thumbs());
  }
  
  public function get_thumbs()
  {
    $config = Kohana::$config->load('gallery.thumbs');
    
    $thumbs = $config['default'];
    
    if (isset($config[$this->_data['gallery_name']])) {
      $thumbs = $config[$this->_data['gallery_name']] + $thumbs;
    }
    
    return $thumbs;
  }
}
