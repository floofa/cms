<div id="navigation">
  <ul class="topnav">
    <?foreach ($menu->get_items() as $item_lvl1):?>
      <li>
        <a class="level1" href="<?=$item_lvl1['link']?>"><?=$item_lvl1['label']?></a>

        <?if ($items_lvl2 = $item_lvl1['subitems']):?>
          <ul class="subnav">
            <?foreach ($items_lvl2 as $item_lvl2):?>
              <li><a href="<?=$item_lvl2['link']?>"><?=$item_lvl2['label']?></a></li>
            <?endforeach;?>
          </ul>
        <?endif;?>
      </li>
    <?endforeach;?>
  </ul>
</div>