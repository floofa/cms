<?php echo $open; ?>
	<label<?if ($id = $this->attr('id')) echo ' for="'.$id.'"'; ?>><?=$label?>:</label>
  <?=$this->add_class('text-input middle-input datepicker')->html()?>
<?php echo $close; ?>