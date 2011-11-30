<?php defined('SYSPATH') or die('No direct access allowed.');

class Cms_Model_Page extends ORM_Classic 
{
  protected $_sorting = array ('name' => 'ASC');
  protected $_filter_as_like = array ('name');
  
  public function set_list_item_default(&$arr, $item) 
  {
    $arr['cms_status'] = ($item->cms_status) ? ___('basic_yes') : ___('basic_no');
  }
  
  public function filters()
  {
    $filters = parent::filters();
    
    $filters['sys_name'][ ] = array (array ('url', 'title'), array (':value', '_'));
    $filters['sys_name'][ ] = array (array ($this, 'set_unique_value'), array (':field', ':value', '_'));
    
    return $filters;
  }
  
  public function save(Validation $validation = NULL)
  {
    if ( ! strlen($this->sys_name)) {
      $this->sys_name = url::title($this->name, '_', TRUE);
    }
    
    if ( ! strlen($this->head_title)) {
      $this->head_title = $this->name;
    }
    
    return parent::save($validation);
  }
}