<div class="content-box">
  <div class="content-box-header">
    <h3><?=___($form->name() . '_heading')?></h3>
    
    <div class="clear"></div>
  </div>
  
  <div class="content-box-content">
    <?if ($errors = $form->errors()):?>
      <div class="notification error png_bg">
        <div>
          <?foreach ($errors as $error):?>
            <?=$error?>.<br />
          <?endforeach;?>
        </div>
      </div>
    <?endif;?>
    
    <?=$form->open()?>
    
    <?foreach ($groups as $group):?>
      <fieldset>
        <?if (strlen($group['name'])):?>
          <h3><?=$group['name']?></h3>
        <?endif;?>
        
        <?foreach ($group['columns'] as $col => $fields):?>
          <div class="<?=$col?>">
            <?foreach ($fields as $field):?>
              <?=$field?>
            <?endforeach;?>
          </div>
        <?endforeach;?>
      </fieldset>
    <?endforeach;?>
    
    <?foreach ($galleries as $gallery):?>
      <fieldset>
        <?=$gallery->generate()?>
      </fieldset>
    <?endforeach;?>
    
    <?/*
    <?foreach ($groups as $group_name => $cols):?>
      <fieldset>
        <?if (strlen($groups_names[$group_name])):?>
          <h3><?=$groups_names[$group_name]?></h3>
        <?endif;?>
        
        <?foreach ($cols as $col => $fields):?>
          <div class="<?=$col?>">
            <?foreach ($fields as $field => $args):?>
              <p<?if (isset($errors[$field])):?> class="field-error"<?endif;?>>
                <label><?=$form->$field->label()?>:</label>
                <?$class = 'text-input' . (($form->$field->driver() instanceof Formo_Driver_Bool) ? '' : ' medium-input');?>
                
                <?=$form->$field->add_class($class)->render()?>
              </p>
            <?endforeach;?>
          </div>
        <?endforeach;?>
      </fieldset>
    <?endforeach;?>

    <?foreach ($form->get_galleries() as $gallery):?>
      <fieldset>
        <?=$gallery->generate()?>
      </fieldset>
    <?endforeach;?>
    */?>

    
    <br />
    <br />
    
    <p>
      <input type="submit" value="Uložit" class="button">
      nebo   
      <a href="<?=$link_back?>">zrušit</a>
    </p>
    
    </form>
    <?//=$form->close()?>
  </div>
</div>