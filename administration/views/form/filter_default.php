<div class="list-filters png_bg">
  <?=$form->open()?>
    <div class="buttons-filters">
      <input type="submit" value="Filtrovat" class="button" /> nebo <a href="<?=$data['reset_url']?>">resetovat</a>
    </div>
  
    <?foreach ($groups as $group):?>
      <div class="params">
        <?foreach ($group['columns'] as $col => $fields):?>
            <?foreach ($fields as $field):?>
              <?=$field?>
            <?endforeach;?>
        <?endforeach;?>
      </div>
    
    <?/*
      <div class="params">
        <?foreach ($cols as $col => $fields):?>
          <?foreach ($fields as $field => $args):?>
            <div class="param">
              <label for="<?=$form->$field->alias()?>"><?=$form->$field->label()?>:</label>
              
              <?$class = 'text-input' . (($form->$field->driver() instanceof Formo_Driver_Bool) ? '' : ' middle-input');?>
              <?=$form->$field->add_class($class)->render()?>
            </div>
          <?endforeach;?>  
        <?endforeach;?>
      </div>
      */?>
    <?endforeach;?>
    
    <div class="clear"></div>
  </form>
</div>