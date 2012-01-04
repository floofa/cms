<?php defined('SYSPATH') or die('No direct access allowed.');

// Paths to media relative to DOCROOT
return array
(
  // zapnuto / vypnuto
  'enabled' => FALSE,
  
  // nazev pro cookie
  'cookie' => 'lang',
  
  // vychozi jazyk
  'default_lang' => 'cs',
  
  // zapnuto / vypnuto v CMS
  'cms_enabled' => FALSE,
  
  // nazev pro cookie v CMS
  'cms_cookie' => 'cms_lang',
  
  // vychozi jazyk v CMS
  'cms_default_lang' => 'cs',

  // dostupne jazyky
  'languages' => array (
    'cs' => array (
      'i18n' => 'cs-cz',
      'locale' => array ('czech'),
      'label' => 'czech',
    ),
    'en' => array (
      'i18n' => 'en-us',
      'locale' => array ('en_US.utf-8'),
      'label' => 'english',
    ),
  ),
);
