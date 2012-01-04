<?php echo $open; ?>
  <?if (isset($this->field()->lang)):?>
    <span class="lang lang_<?=$this->field()->lang?>">
  <?endif;?>
	
  <label<?if ($id = $this->attr('id')) echo ' for="'.$id.'"'; ?>><?=$label?>:</label>
  
  <?$this->add_class('text-input medium-input')?>
  
  <?
    if ( ! $this->editable()) {
      $this->add_class('readonly')->attr('readonly', 'readonly');
    }
  ?>
  
	<?=$this->html()?>
  
  <?if (isset($this->field()->lang)):?>
    </span>
  <?endif;?>
<?php echo $close; ?>