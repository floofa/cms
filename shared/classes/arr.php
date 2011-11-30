<?php defined('SYSPATH') or die('No direct script access.');

class Arr extends Kohana_Arr 
{
  /**
  * v puvodnim poli prepise jen ty polozky, ktere jsou v novem poli nastaveny
  * 
  * @param mixed $array1
  */
  public static function overwrite_nonempty($array1) 
  {
    foreach(array_slice(func_get_args(), 1) as $array2){
      foreach ($array2 as $key => $value){
        if (array_key_exists($key, $array1)){
          if (strlen($value))
            $array1[$key] = $value;
        }
      }
    }
    
    return $array1;
  }
  
  /**
  * odstrani z pole pozadovane polozky
  */
  public static function arr_without($keys = array(), &$arr = array()) {
    $keys = (array)$keys;
    foreach($keys as $key)
      if(array_key_exists($key, $arr))
        unset($arr[$key]);
    
    return $arr;
  }
}