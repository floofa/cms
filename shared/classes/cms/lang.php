<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Lang
{ 
  public static function find_default()
  {
    // konfigurace
    $config = (array) Kohana::$config->load('lang');

    // nejdrive hledame v cookie
    if ($lang = Cookie::get($config['cookie'])) {
      // pokud je v cookie nalezen validni jazyk, tak jej vratime
      if (isset($config['languages'][$lang]))
        return $lang;

      // pokud ne, tak nevalidni jazyk odstranime
      Cookie::delete($config['cookie']);
    }

    // zkusime nacist z hlavicky
    foreach (Request::accept_lang() as $lang => $quality) {
      // Return the first language found (the language with the highest quality)
      if (isset($langs[$lang]))
        return $lang;
    }

    // vratime defaultni jazyk
    return $config['default_lang'];
  }
}