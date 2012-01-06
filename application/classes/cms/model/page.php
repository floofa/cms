<?php defined('SYSPATH') or die('No direct access allowed.');

class Cms_Model_Page extends ORM 
{
  protected $_multilang_fields = array ('head_title', 'meta_keywords', 'meta_description', 'content');
  
  public function __get($column)
  {
    switch ($column) {
      case 'content' :
        return cms::prepare_content(parent::__get($column), array ('orm_object' => $this));
    }
    
    return parent::__get($column);
  }
  
  public function get_ids()
  {
    return $this->find_all()->as_array('rew_id', 'id');
  }
  
  public function get_page_by_type($type)
  {
    return ORM::factory('page')->where('page_type', '=', $type)->find();
  }
}