<?php echo $open; ?>
	<label<?if ($id = $this->attr('id')) echo ' for="'.$id.'"'; ?>><?=$label?>:</label>
	<?if ($this->editable()):?>
    <?=$this->add_class('text-input medium-input datepicker')->html()?>
  <?else:?>
    <?=$this->add_class('text-input medium-input readonly')->attr('readonly', 'readonly')->html()?>
  <?endif;?>
<?php echo $close; ?>