<?php defined('SYSPATH') or die('No direct access allowed.');

// Paths to media relative to DOCROOT

/**
* maximalni povolena velikost uploadovaneho souboru v bytech
*/
$config['default']['file_size_limit'] = '50MB';

/**
* povolene pripony souboru
*/
$config['default']['file_types'] = '*.*';

/**
* povolene pripony souboru
*/
$config['default']['file_types_desc'] = 'Vsechny soubory';

/**
* maximalni pocet souboru ve fronte
*/
$config['default']['file_queue_limit'] = 10;

/**
* maximum nahranych souboru
*/
$config['default']['file_upload_limit'] = 100;

/**
* resize hlavniho obrazku
*/
$config['default']['resize'] = TRUE;

$config['default']['width'] = 800;

$config['default']['height'] = 600;

$config['default']['watermark'] = FALSE;
$config['default']['watermark_image'] = 'admin/images/watermarks/watermark.png';
$config['default']['watermark_offset_x'] = NULL;
$config['default']['watermark_offset_y'] = NULL;
$config['default']['watermark_opacity'] = 50;



$config['default']['dimension'] = Image::AUTO;

/**
* ulozi take original, beze zmen
*/
$config['default']['orig'] = FALSE;

/**
* pouze jeden soubor
*/
$config['default']['one_file'] = FALSE;

/**
* ladici mod
*/
$config['default']['debug'] = FALSE;


$config['thumbs']['default'] = array (
  'cms' => array ('width' => 90, 'height' => 90),
  's'   => array ('width' => 100, 'height' => 100),
);

$config['file_types']['image'] = array('jpg','jpeg','png','gif','bmp');
$config['file_types']['movie'] = array('avi','flv','m4v','mpeg', 'mov', 'asf', 'wmv');
$config['file_types']['audio'] = array('wav','mp3','midi','aac', 'ogg');
$config['file_types']['document'] = array('doc','pdf','html','txt','rtf');
$config['file_types']['program'] = array('exe','com','bat');
$config['file_types']['archive'] = array('zip','rar','7z');

$config['images']['file_size_limit'] = '10MB';
$config['images']['file_types'] = '*.jpg;*.jpeg;*.png;*.gif;*.bmp';
$config['images']['file_types_desc'] = 'Obrázky';

$config['page_images']['file_size_limit'] = '10MB';
$config['page_images']['file_types'] = '*.jpg;*.jpeg;*.png;*.gif;*.bmp';
$config['page_images']['file_types_desc'] = 'Obrázky';

return $config;

