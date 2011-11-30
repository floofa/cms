<?php defined('SYSPATH') or die('No direct script access.');

class Form_Page_Filter extends Forms_Filter
{
  public function build()
  {
    $this->group('group1')
      ->add('name');
  }
}
