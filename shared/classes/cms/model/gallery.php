<?php defined('SYSPATH') or die('No direct access allowed.');

class Cms_Model_Gallery extends Orm 
{
  protected $_has_many = array ('gallery_items' => array ());
  
  public function delete()
  {
    foreach ($this->gallery_items->find_all() as $item) {
      $item->delete();
    }
    
    return parent::delete();
  }
  
  public function reorder($items_ids)
  {
    $sequence = 1;
    
    foreach ($items_ids as $id) {
      $item = ORM::factory('gallery_item', $id);
      
      if ($item->loaded()) {
        $item->_auto_set_sequence = FALSE;
        $item->sequence = $sequence++;
        $item->save();
      }
    }
  }
} 