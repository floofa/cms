<?php echo $open; ?>
	<label<?if ($id = $this->attr('id')) echo ' for="'.$id.'"'; ?>><?=$label?>:</label>
	<?=$this->add_class('text-input middle-input')->html()?>
    <?/*
		<span class="field">
			<?php if ($this->editable() === TRUE): ?>
				<?php echo $this->add_class('input')->html(); ?>
			<?php else: ?>
				<span><?php echo $this->val(); ?></span>
			<?php endif; ?>
		</span>
    */?>
<?php echo $close; ?>