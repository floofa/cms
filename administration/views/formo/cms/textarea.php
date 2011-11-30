<?php echo $open; ?>
	<label<?if ($id = $this->attr('id')) echo ' for="'.$id.'"'; ?>><?=$label; ?></label>
	<?php echo $this->attr('rows', ($rows = $this->attr('rows')) ? $rows : 10)->html(); ?>
<?php echo $close; ?>