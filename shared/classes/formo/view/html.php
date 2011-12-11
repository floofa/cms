<?php defined('SYSPATH') or die('No direct script access.');

class Formo_View_HTML extends Formo_Core_View_HTML 
{
  public function _get_select_option_attr($option_value)
  {
    $array = array('value' => $option_value);

    $val = $this->val();
    
    // pokud je multi select, vybrane hodnoty jsou v poli
    if ((is_array($val) && array_key_exists($option_value, $val)) || (string) $option_value == (string) $val) {
      $array += array('selected' => 'selected');
    }

    return $array;
  }
}