<?php defined('SYSPATH') or die('No direct access allowed.');

class Cms_Model_Page extends ORM 
{
  public function __get($column)
  {
    switch ($column) {
      case 'content' :
        return cms::prepare_content($this, parent::__get($column));
    }
    
    return parent::__get($column);
  }
  
  public function get_ids()
  {
    return $this->find_all()->as_array('rew_id', 'id');
  }
}