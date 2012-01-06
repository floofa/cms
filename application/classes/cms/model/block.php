<?php defined('SYSPATH') or die('No direct access allowed.');

class Cms_Model_Block extends ORM
{
  public function __get($column)
  {
    switch ($column) {
      case 'content' :
        return cms::prepare_content(parent::__get($column));
    }
    
    return parent::__get($column);
  }
}