<?php echo $open; ?>
	<label<?if ($id = $this->attr('id')) echo ' for="'.$id.'"'; ?>><?=$label?>:</label>
	<?=$this->add_class('text-input medium-input')->add_class(( ! $this->editable()) ? 'readonly' : array ())->html()?>
<?php echo $close; ?>