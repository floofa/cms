<?php defined('SYSPATH') or die('No direct access allowed.');

class Cms_Model_Menu_ItemMPTT extends ORM_MPTT_Classic
{
  public $_table_name = 'menu_items';
  
  public $scope_column = 'menu_id';
  
  protected $_save_gallery = FALSE;

  public function set_list_item_default(&$arr, $item) 
  {
    $arr['link'] = $item->get_link();
  }
  
  public function get_link()
  {
    if ($this->page_id) {
      $link = URL::site(ORM::factory('page', $this->page_id)->rew_id, TRUE, FALSE);
    }
    elseif (stripos($this->url, 'http') === 0) {
      $link = $this->url;
    }
    else {
      $link = URL::site($this->url, TRUE, FALSE);
    }
    
    return $link;
  }
} 