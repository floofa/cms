<?php echo $open; ?>
	<label<?if ($id = $this->attr('id')) echo ' for="'.$id.'"'; ?>><?=$label?>:</label>
  
  <?$this->add_class('text-input medium-input')?>
  
  <?
    if ( ! $this->editable()) {
      $this->add_class('readonly')->attr('readonly', 'readonly');
    }
  ?>
  
	<?=$this->html()?>
<?php echo $close; ?>