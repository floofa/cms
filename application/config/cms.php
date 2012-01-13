<?php defined('SYSPATH') or die('No direct access allowed.');

return array (
  'default_email' => 'info@' . url::domain(),

  'dynamic_blocks' => array (
    'footer_menu' => array (
      'controller' => 'static_application',
      'action' => 'menu/footer_menu',
    )
  ),
);


