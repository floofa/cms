<?=$open;?>
	<label<?php if ($id = $this->attr('id')) echo ' for="'.$id.'"'; ?>><?=$label; ?></label>
	<?=$this->add_class('text-input medium-input')->open(); ?>
    <?foreach ($this->_field->get('options') as $key => $value): ?>
    <?//cms::i($this->_field->get('options'))?>
      <?php if (is_array($value)): ?>
        <optgroup label="<?=$key?>">
        <?foreach ($value as $_key => $_value):?>
          <option<?=HTML::attributes($this->get_option_attr('select', $_key));?>><?=$this->option_label($_value);?></option>
        <?endforeach;?>
        </optgroup>
      <?else:?>
        <option<?=HTML::attributes($this->get_option_attr('select', $key));?>><?=$this->option_label($value); ?></option>
      <?endif;?>
    <?endforeach;?>
  <?=$this->close(); ?>
<?=$close;?>    
  
    
		