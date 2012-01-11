<div class="content-box">
  <div class="content-box-header">
    <?if (isset($form->active_lang)):?>
      <?$langs = Kohana::$config->load('lang.languages'); $keys = array_keys($langs); $last_key = end($keys);?>

      <span id="<?=$form->name()?>_lang_switcher" class="form_heading_lang_switcher">
        <?foreach (Kohana::$config->load('lang.languages') as $key => $lang):?>
          <a rel="<?=$key?>"<?if($key == $form->active_lang):?> class="active"<?endif;?>><?=$key?></a> <?if ($key !== $last_key):?>|<?endif;?>
        <?endforeach;?>
      </span>
    <?endif;?>
    
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
    
    <?if (isset($data['_render_after'])):?>
      <?=$data['_render_after']?>
    <?endif;?>
    
  </div>
</div>

<script type="text/javascript">
<!--
  $(function(){
    <?if (isset($form->active_lang)):?>
      $("#<?=$form->name()?> p.lang").hide();
      $("#<?=$form->name()?> p.lang_<?=$form->active_lang?>").show();
      //$("#<?=$form->name()?> p.lang.field-error").show();
      
      $("#<?=$form->name()?>_lang_switcher a").click(function(){
        var lang = $(this).attr("rel");
        
        // aktivni odkaz
        $("#<?=$form->name()?>_lang_switcher a").removeClass("active");
        $(this).addClass("active");
        
        // zobrazeni spravnych inputu
        $("#<?=$form->name()?> p.lang").hide();
        $("#<?=$form->name()?> p.lang_" + lang).show();
      });
    <?endif;?>
  });
  
  
//-->
</script>