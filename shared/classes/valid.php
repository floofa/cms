<?php defined('SYSPATH') or die('No direct script access.');

class Valid extends Kohana_Valid 
{
  /**
  * kontrola na cislo (cela kladna i zaporna cisla)
  * 
  * @param mixed $str
  */
  public static function int($str)
  {
    if (is_int($str) === TRUE) 
      return TRUE;
      
    if (is_string($str) === TRUE && is_numeric($str) === TRUE)
      return (strpos($str, '.') === FALSE && strpos($str, ',') === FALSE);
      
    return FALSE;
  }
  
  public static function checked($value)
  {
    return (bool) $value;
  }
  
  public static function time($value)
  {
    if (stripos($value, ':') !== FALSE) {
      list($h, $m) = explode(':', $value);
      return (valid::range($h, 0, 23) && valid::range($m, 0, 59));
    }
      
    return FALSE;
  }
}
