<?php defined('SYSPATH') or die('No direct script access.');

class Cms_ORM extends Kohana_ORM 
{
  protected $_primary_val = 'name';
  
  /**
  * automaticke nastaveni timestampu
  */
  protected $_auto_set_timestamp = TRUE;
  
  /**
  * automaticke nastaveni sequence
  */
  protected $_auto_set_sequence = TRUE;
  
  /**
  * auto set rew_id
  * obsahuje nazev pole pouzivaneho pro rew_id
  * pokud je FALSE, nebude se nastavovat
  */
  protected $_auto_set_rew_id = 'rew_id';
  
  /**
  * save gallery
  */
  protected $_save_gallery = TRUE;
  
  /**
  * many_to_many items
  */
  protected $_habtm = array ();
  
  protected $_multilang_fields = array ();
  
  public $multilang_fields_enabled = TRUE;
  
  public function __get($column)
  {
    // multilang columns
    if (Kohana::$config->load('lang.enabled') && $this->multilang_fields_enabled) {
      if (in_array($column, $this->_multilang_fields)) {
        if (Kohana::$config->load('lang.default_lang') != Request::$lang) {
          $column = $column . '_' . Request::$lang;
        }
      }
    }
    
    return parent::__get($column);
  }
  
  public function set($column, $value)
  {
    if ($value === "#null#") {
      $value = NULL;
    }
    
    switch ($column) {
      case 'sequence' :
        if ( ! $this->id || ! $this->_auto_set_sequence)
          return parent::set($column, $value);
        
        if ( ! ($value = (int) $value) || $value == $this->sequence)
          return;
        
        if ($value < $this->sequence)
          Database::instance()->query(Database::UPDATE, 'UPDATE `' . $this->_table_name . '` SET sequence = sequence + 1 WHERE sequence >= ' . $value . ' AND sequence < ' . $this->sequence);
        
        if ($value > $this->sequence)
          Database::instance()->query(Database::UPDATE, 'UPDATE `' . $this->_table_name . '` SET sequence = sequence - 1 WHERE sequence <= ' . $value . ' AND sequence > ' . $this->sequence);
        
        break;
    }
    
    return parent::set($column, $value);
  }
  
  public function filters()
  {
    $filters = parent::filters();
    
    $filters['rew_id'][ ] = array (array ('url', 'title'), array (':value', '-', TRUE));
    $filters['rew_id'][ ] = array (array ($this, 'set_unique_value'), array (':field', ':value'));
    
    return $filters;
  }
  
  
  /**
  * save
  */
  public function save(Validation $validation = NULL)
  {
    // automaticke nastaveni rew_id
    if ($this->_auto_set_rew_id !== FALSE && array_key_exists($this->_auto_set_rew_id, $this->_object) && (is_null($this->_object[$this->_auto_set_rew_id]) || ! strlen($this->_object[$this->_auto_set_rew_id]))) {
      //$this->{$this->_auto_set_rew_id} = url::title($this->_object[$this->_primary_val], '-', TRUE);
      $this->{$this->_auto_set_rew_id} = $this->_object[$this->_primary_val];
    }
    
    // automaticke nastaveni timestampu
    if ($this->_auto_set_timestamp && array_key_exists('timestamp', $this->_object) && (is_null($this->_object['timestamp']) || ! $this->_object['timestamp'])) {
      $this->timestamp = time();
    }
    
    // automaticke nastaveni sequence
    if ($this->_auto_set_sequence && array_key_exists('sequence', $this->_object) && (is_null($this->_object['sequence']) || ! $this->_object['sequence'])) {
      $this->sequence = ORM::factory($this->_object_name)->order_by('sequence', 'DESC')->find()->sequence + 1;
    }
    
    parent::save($validation);
    
    $this->_sync_habtm();
    
    // save gallery
    if ($this->_save_gallery) {
      $galleries = ORM::factory('gallery')
        ->where('model', '=', $this->_object_name)
        ->where('session_id', '=', Session::instance()->id())
        ->where('model_id', 'IS', NULL)
        ->find_all();
      
      foreach ($galleries as $gallery) {
        $gallery->model_id = $this->id;
        $gallery->session_id = NULL;
        $gallery->save();
      }
    }
    
    return $this;
  }
  
  public function values(array $values, array $expected = NULL)
  {
    parent::values($values);
    
    // set habtm items
    foreach($this->_has_many as $key => $value) {
      if (isset($values[$key])) {
        $this->_habtm[$key] = $values[$key];
        continue;
      }
    }
    
    return $this; 
  }
  
  /**
  * set many_to_many items
  */
  protected function _sync_habtm() 
  {
    foreach($this->_habtm as $alias => $values) {
      DB::delete($this->_has_many[$alias]['through'])
        ->where($this->_has_many[$alias]['foreign_key'], '=', $this->pk())
        ->execute($this->_db);

      
      foreach ($values as $value)
        $this->add($alias, ORM::factory($this->_has_many[$alias]['model'], $value)); 
    }
    
    return $this;
  }
  
  public function delete()
  {
    // delete galleries
    if ($this->loaded()) {
      $galleries = ORM::factory('gallery')
        ->where('model', '=', $this->_object_name)
        ->where('model_id', '=', $this->id)
        ->find_all();

      foreach ($galleries as $gallery) {
        $gallery->delete();
      }
    }
    
    // pri odstraneni zaznamu se sequenci se musi nasledujici sequnce snizit
    if ($this->_auto_set_sequence && array_key_exists('sequence', $this->_object)) {
      Database::instance()->query(Database::UPDATE, 'UPDATE ' . $this->_table_name . ' SET sequence = sequence - 1 WHERE sequence > ' . $this->sequence);
    }
    
    return parent::delete();
  }
  
  /**
  * nastaveni sequence
  */
  public function set_sequence($id, $nearby_id) 
  {
    $item = ORM::factory($this->_object_name, $id);
    $nearby_item = ORM::factory($this->_object_name, $nearby_id);
    
    if ($item->loaded() && $nearby_item->loaded()) {
      $item->sequence = $nearby_item->sequence;
      $item->save();
    }
  }
  
  /**
  * nastaveni unikatni hodnotu pro polozku
  * 
  * @param string $value
  * @return string
  */
  public function set_unique_value($field, $value, $sep = '-')
  {
    if (strlen($value)) {
      $tvalue = $value;
        
      for ($x = 1; ORM::factory($this->_object_name)->where($field, '=', $value)->where('id', '!=', $this->id)->find()->id; $x++)
        $value = $tvalue . $sep . $x;
    }
    
    return $value;
  }
  
  public function select_list($key = 'id', $value = 'name')
  {
    $res = array ();
    
    foreach ($this->find_all() as $item) {
      $res[$item->$key] = $item->$value;
    }
    
    return $res;
  }
  
  public function get_gallery_items($gallery_name = FALSE, $count = FALSE)
  {
    if ($gallery_name === FALSE) {
      $gallery_name = $this->_object_name . '_images';
    }
    
    $gallery = ORM::factory('gallery')
      ->where('model', '=', $this->_object_name)
      ->where('model_id', '=', $this->id)
      ->where('name', '=', $gallery_name)
      ->find();
      
    if ($gallery->loaded()) {
      $items = $gallery->gallery_items;
      
      if ($count) {
        $items->limit(abs($count));
        
        if ($count < 0)
          $items->order_by('sequence', 'DESC');
      }
      
      return $items->find_all()->as_array();
    }
    
    return array ();
  }
  
  public function get_main_img($gallery_name = FALSE)
  {
    if ($gallery_name === FALSE) {
      $gallery_name = $this->_object_name . '_images';
    }
    
    $items = $this->get_gallery_items($gallery_name, 1);
    $gallery_item = current($items);
    
    if ( ! $gallery_item)
      return FALSE;
      
    return $gallery_item;
  }
  
  /**
  * vrati odkaz na hlavni obrazek z galerie
  * 
  * @param mixed $suffix
  * @param mixed $gallery_name
  * @return string
  */
  public function get_main_img_src($suffix = '', $gallery_name = FALSE)
  {
    $gallery_item = $this->get_main_img($gallery_name);
    
    if ( ! $gallery_item)
      return '';
    
    return $gallery_item->get_link($suffix);
  }
  
  /**
  * zjisti, jestli hodnota pro dane pole neni jiz vlozena v databazi
  * 
  * @param mixed $value
  * @param mixed $field
  * @return mixed
  */
  public function is_unique($value, $field)
  {
    return ! (bool) DB::select(array(DB::expr('COUNT(*)'), 'total'))
      ->from($this->_table_name)
      ->where($field, '=', $value)
      ->where('id', '!=', $this->id)
      ->execute()
      ->get('total');
  }
  
  public function exists($value, $field)
  {
    return $this->where($field, '=', $value)->find()->loaded();
  }
  
  public function as_form_array()
  {
    return $this->as_array('value');
  }
  
  
}
