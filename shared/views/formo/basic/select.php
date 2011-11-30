<?php echo $this->open(); ?>
	<?php foreach ($this->_field->get('options') as $key => $value): ?>
	<?php if (is_array($value)): ?>
		<optgroup label="<?php echo $key?>">
		<?php foreach ($value as $_key => $_value): ?>
			<option<?php echo HTML::attributes($this->get_option_attr('select', $_key)); ?>><?php echo $this->option_label($_value); ?></option>
		<?php endforeach; ?>
		</optgroup>
	<?php else: ?>
		<option<?php echo HTML::attributes($this->get_option_attr('select', $key)); ?>><?php echo $this->option_label($value); ?></option>
	<?php endif; ?>
	<?php endforeach; ?>
<?php echo $this->close(); ?>
			