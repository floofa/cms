<form id="<?=$form->name?>" method="post" action="">
  <?if ($form->errors()):?>
    <div class="notification attention png_bg">
      <?foreach ($form->errors() as $error):?>
          <div><?=$error?></div>
          <?break;?>
      <?endforeach;?>
    </div>
  <?endif;?>

  <p>
    <label for="<?=$form->username->alias()?>"><?=$form->username->get('label')?>:</label>
    <?=$form->username->view()->add_class('text-input')->field()->render()?>
  </p>
  <div class="clear"></div>
  <p>
    <label for="<?=$form->password->get('alias')?>"><?=$form->password->get('label')?>:</label>
    <?=$form->password->view()->add_class('text-input')->field()->render()?>
  </p>
  
  <div class="clear"></div>
  
  <p>
    <input class="button" type="submit" value="Přihlásit se" />
  </p>
</form>