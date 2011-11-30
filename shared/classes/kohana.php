<?php defined('SYSPATH') or die('No direct script access.');

class Kohana extends Kohana_Core 
{
  /**
   * Provides auto-loading support of classes that follow Kohana's [class
   * naming conventions](kohana/conventions#class-names-and-file-location).
   * See [Loading Classes](kohana/autoloading) for more information.
   *
   * Class names are converted to file names by making the class name
   * lowercase and converting underscores to slashes:
   *
   *     // Loads classes/my/class/name.php
   *     Kohana::auto_load('My_Class_Name');
   *
   * You should never have to call this function, as simply calling a class
   * will cause it to be called.
   *
   * This function must be enabled as an autoloader in the bootstrap:
   *
   *     spl_autoload_register(array('Kohana', 'auto_load'));
   *
   * @param   string   class name
   * @return  boolean
   */
  public static function auto_load($class)
  {
    try
    {
      // Transform the class name into a path
      $file = str_replace('_', '/', strtolower($class));

      if ($path = Kohana::find_file('classes', $file))
      {
        // Load the class file
        require $path;

        // Class has been found
        return TRUE;
      }

      // Class is not in the filesystem
      return FALSE;
    }
    catch (Exception $e)
    {
      Kohana_Exception::handler($e);
      die;
    }
  }
}
