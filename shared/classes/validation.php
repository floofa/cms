<?php defined('SYSPATH') or die('No direct script access.');

class Validation extends I18n_Validation 
{
  // Rules that are executed even when the value is empty
  protected $_empty_rules = array('not_empty', 'matches', 'checked');
}
