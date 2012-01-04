<div id="lang">
  <?foreach ($langs as $key => $name):?>
    <a href="<?=URL::site('', FALSE, TRUE, $key)?>"<?if ($key == $active_lang):?> class="active"<?endif;?>><?=___('lang_' . $key)?></a>
  <?endforeach;?>
</div>