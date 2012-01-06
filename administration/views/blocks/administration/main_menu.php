<ul id="main-nav">  <!-- Accordion Menu -->
  <?foreach ($menu->get_items() as $item_lvl1):?>
    <li>
      <a class="nav-top-item<?if ($item_lvl1['active']):?> current<?endif;?><?if ( ! ($item_lvl1['subitems'])):?> no-submenu<?endif;?>" title="<?=$item_lvl1['label']?>" href="<?=$item_lvl1['link']?>"><?=$item_lvl1['label']?></a>
      <?if ($item_lvl1['subitems']):?>
        <ul>
          <li></li>
          <?foreach ($item_lvl1['subitems'] as $item_lvl2):?>
            <li><a <?if ($item_lvl2['active']):?>class="current"<?endif;?> href="<?=$item_lvl2['link']?>"><?=$item_lvl2['label']?></a></li>
          <?endforeach;?>
        </ul>
      <?endif;?>
    </li>
  <?endforeach;?>
</ul>