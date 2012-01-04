<?php defined('SYSPATH') or die('No direct access allowed.');

class Cms_Model_Menu_Item extends ORM 
{
  protected $_belongs_to = array ('menu' => array (), 'page' => array ());
  
  protected $_multilang_fields = array ('name');
  
  public function get_link()
  {
    if ($this->page_id) {
      $link = URL::site(ORM::factory('page', $this->page_id)->rew_id, TRUE);
    }
    elseif (stripos($this->url, 'http') === 0) {
      $link = $this->url;
    }
    else {
      $link = URL::site($this->url, TRUE);
    }
    
    return $link;
  }
  
  public function get_children()
  {
    $item_mptt = ORM::factory('menu_itemmptt', $this->id);
    $children = $item_mptt->get_children();
    
    $ids = $children->as_array('id', 'id');
    
    if ( ! count($ids))
      $ids = array (-1);
    
    return ORM::factory('menu_item')->where('id', 'in', $ids)->order_by(array (NULL, DB::expr('FIELD(id,'.implode(',', $ids).')')))->find_all();
  }
}  
