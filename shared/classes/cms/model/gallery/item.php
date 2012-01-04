<?php defined('SYSPATH') or die('No direct access allowed.');

class Cms_Model_Gallery_Item extends Orm 
{
  protected $_belongs_to = array ('gallery' => array ());
  protected $_sorting = array ('sequence' => 'ASC');
  
  public function get_link($suffix = '')
  {
    if (strlen($suffix)) {
      $file_name = $this->id . '_' . $suffix . '.' . $this->ext;
    }
    else {
      $file_name = $this->id . '.' . $this->ext;
    }
    
    return URL::site('media/content-images/' . $this->gallery->model .'/' . $file_name, TRUE, FALSE, FALSE);
  }
  
  public function delete()
  {
    if ($this->loaded()) {
      $sequence = $this->sequence;
      $gallery = $this->gallery;
      
      try {
        $iterDir = new DirectoryIterator(URL::site('media/content-images/' . $gallery->model . '/'));
        $pattern ='~^('.$this->id.')(_(.{1,5}))?(\.)([a-zA-Z]*)$~';

        foreach($iterDir as $file )  {
          if(preg_match($pattern, $file->getFilename()))
            unlink($file->getPathname());
        }
      }
      catch (Exception $e) {}
    }
    
    parent::delete();
  }
  
  public function generate_thumbs($config, $thumbs)
  {
    $config = (object) $config;
    $file = $this->file(TRUE);
    $thumb_fce = 'no_thumbs';
    
    $types = Kohana::$config->load('gallery.file_types');
    foreach ($types as $key => $values) {
      if (in_array(mb_strtolower($this->ext), $values)) {
        $thumb_fce = $key.'_thumbs';
      }
    }

    $this->$thumb_fce($file, $config, $thumbs);
    
    //Log::instance()->add(Log::INFO, Debug::dump($thumbs));
  }
  
  public function image_thumbs($file, $config, $thumbs)
  {
    foreach ($thumbs as $key => $thumb) {
      $file_thumb = str_replace('.', '_' . $key . '.', $file);
      
      $image = Image::factory($file);
      $image->resize($thumb['width'], $thumb['height'], (isset($thumb['dimension'])) ? $thumb['dimension'] : NULL);
      
      if (isset($thumb['watermark']) && $thumb['watermark']) {
        $mark = Image::factory('media/' . $thumb['watermark_image']);
        $image->watermark($mark, (isset($thumb['watermark_offset_x'])) ? $thumb['watermark_offset_x'] : NULL, (isset($thumb['watermark_offset_y'])) ? $thumb['watermark_offset_y'] : NULL, (isset($thumb['watermark_opacity'])) ? $thumb['watermark_opacity'] : 50);
      }
      
      $image->save($file_thumb);
    }
    
    // ulozi original obrazku
    if ($config->orig) {
      $file_orig = str_replace('.', '_orig.', $file);
      
      $image = Image::factory($file);
      $image->save($file_orig);
    }
    
    // resizne hlavni obrazek
    if ($config->resize) {
      $image = Image::factory($file);
      $image->resize($config->width, $config->height, ($config->dimension) ? $config->dimension : NULL);
      $image->save();
    }
    
    // watermark pro hlavni obrazek
    if ($config->watermark) {
      $image = Image::factory($file);
      $mark = Image::factory('media/' . $config->watermark_image);
      
      $image->watermark($mark, $config->watermark_offset_x, $config->watermark_offset_y, $config->watermark_opacity);
      $image->save();
    }
    
  }
  
  public function file($rel = FALSE) 
  {
    $file = '';
    
    if ( ! $rel) {
      $file = URL::base(TRUE);
    }
    
    $file .= 'media/content-images/' . $this->gallery->model . '/' . $this->id . '.' . $this->ext;
    
    return $file;
  }
  
  public function get_src($suffix = '')
  {
    if (strlen($suffix)) {
      $file_name = $this->id . '_' . $suffix . '.' . $this->ext;
    }
    else {
      $file_name = $this->id . '.' . $this->ext;
    }
    
    return URL::site('media/content-images/' . $this->gallery->model . '/' . $file_name);
  }
} 
