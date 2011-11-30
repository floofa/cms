<?php defined('SYSPATH') or die('No direct script access.');

class File extends Kohana_File 
{
  public static function extension($filename, $dot = FALSE)
  {
    $ext = preg_replace('/^.+\.(.+?)$/', '$1', $filename);
    return $dot ? '.' . $ext : ltrim($ext, '.');
  }
}