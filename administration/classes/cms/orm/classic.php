<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Orm_Classic extends ORM
{
  protected $_rew_id_col_value = 'name';
  
  protected $_list_where = array ();
  
  protected $_filter_as_like = array ();
  
  protected $_filter_as_foreign = array ();

  protected $_filter_session_name = FALSE;
  
  protected $_form_date_fields = array ();
  
  protected $_form_date_time_fields = array ();
  
  protected $_form_numeric_fields = array ();
  
  
  public function formo()
  {
    return array ();
  }
  
  public function set_filter_session_name($name)
  {
    $this->_filter_session_name = $name;
    
    return $this;
  }
  
  public function load_list_filters()
  {
    $session_name = ($this->_filter_session_name !== FALSE) ? $this->_filter_session_name : 'filter_' . $this->_object_name;
    
    if ($filters = Session::instance()->get($session_name)) {
      foreach ($filters as $field => $value) {
        if (strlen($value)) {
          if (in_array($field, $this->_filter_as_like)) {
            $op = 'LIKE';
            $value = '%' . $value . '%';
          }
          elseif (array_key_exists($field, $this->_filter_as_foreign)) {
            $options = $this->_filter_as_foreign[$field];
            
            $options['op'] = 'LIKE';
            $value = '%' . $value . '%';
            
            $ids = ORM::factory($options['model'])->where($options['key'], $options['op'], $value)->find_all()->as_array('id', 'id');
            
            if ( ! count($ids)) {
              $ids = array (-1);
            }
            
            $field = $options['foreign_key'];
            $op = 'IN';
            $value = $ids;
          }
          else {
            $op = '=';
          }
          
          $this->list_where($field, $op, $value);
        }
      }
    }
    
    return $this;
  }
  
  public function set_list_filters()
  {
    foreach ($this->_list_where as $where) {
      $this->where($where[0], $where[1], $where[2]);
    }
    
    return $this;
  }

  public function list_count_all()
  {
    return $this->load_list_filters()->set_list_filters()->count_all();
  }
  
  /**
  * vypis polozek
  * 
  */
  public function list_all($count = FALSE, $offset = FALSE)
  {
    $this->load_list_filters()->set_list_filters();
    
    /*
    foreach ($this->_list_where as $where) {
      $this->where($where[0], $where[1], $where[2]);
    }
    */
    
    if ($count !== FALSE)
      $this->limit($count);
      
    if ($offset !== FALSE) {
      $this->offset($offset);
    }
    
    return $this->set_list_default($this->find_all());
  }
  
  /**
  * nastaveni polozek pro vypis
  * 
  * @param mixed $items
  * @return string
  */
  public function set_list_default($items) {
    $res = array ();
    
    foreach ($items as $item) {
      $arr = $item->as_array();
      
      $this->set_list_item_default($arr, $item);
      
      $res[ ] = $arr;
    }
    
    return $res;
  }
  
  /**
  * nastaveni polozky pro vypis
  * 
  * @param mixed $arr
  * @param mixed $item
  */
  public function set_list_item_default(&$arr, $item) 
  {
    
  }
  
  public function filters()
  {
    $filters = parent::filters();
    
    // prevod datumu na timestamp
    foreach ($this->_form_date_fields as $field) {
      $filters[$field] =  array (array ('strtotime'));
    }
    
    // prevod datumu a casu na timestamp
    foreach ($this->_form_date_time_fields as $field) {
      $filters[$field] =  array (array ('strtotime'));
    }
    
    return $filters;
  }
  
  public function save(Validation $validation = NULL)
  {
    parent::save($validation);
    
    return $this;
  }
  
  public function as_form_array()
  {
    $values = $this->as_array('value');

    foreach ($this->_form_date_fields as $key) {
      if ($values[$key]) {
        $values[$key] = date('d.m.Y', $values[$key]);
      }
    }
    
    foreach ($this->_form_date_time_fields as $key) {
      if ($values[$key]) {
        $values[$key] = date('d.m.Y H:i', $values[$key]);
      }
    }
    
    list($decimal) = array_values(localeconv());
    foreach ($this->_form_numeric_fields as $key) {
      $filters[$key] = str_replace('.', $decimal,  $values[$key]);
    }
    
    return $values;
  }
  
  public function list_where_arr($where)
  {
    $this->list_where($where[0], $where[1], $where[2]);
    
    return $this;
  }
  
  public function list_where($field, $op, $value)
  {
    $this->_list_where[ ] = array ($field, $op, $value);
    
    return $this;
  }
  
  /**
  * presun polozky na jinou stranu
  * 
  * @param mixed $ids
  * @param $this $from_sequence
  */
  public function move_to_page($ids, $from_sequence)
  {
    $max = ORM::factory($this->_object_name)->order_by('sequence', 'DESC')->find()->sequence;

    if($from_sequence)
      $from_sequence = $max - $from_sequence + 1;
    else
      $from_sequence = $max;
      
    if ($from_sequence < 1)
      $from_sequence = 1;

    foreach (ORM::factory($this->_object_name)->where($this->_primary_key, 'IN', $ids)->order_by('sequence', 'ASC')->find_all() AS $item){
      $item->sequence = $from_sequence;
      $item->save();
      
      $from_sequence++;
    }

    return $this;
  }
  
  public function get_navigation_val()
  {
    return $this->name;
  }
}
