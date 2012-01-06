<div id="main-menu">
  <ul>
    <?foreach ($menu->get_items() as $item_lvl1):?>
      <li>
        <a class="lvl1<?if($item_lvl1['active']):?> active<?endif;?>" href="<?=$item_lvl1['link']?>"><?=$item_lvl1['label']?></a>

        <?if ($items_lvl2 = $item_lvl1['subitems']):?>
          <ul>
            <?foreach ($items_lvl2 as $item_lvl2):?>
              <li><a class="lvl2<?if($item_lvl2['active']):?> active<?endif;?>" href="<?=$item_lvl2['link']?>"><?=$item_lvl2['label']?></a></li>
            <?endforeach;?>
          </ul>
        <?endif;?>
      </li>
    <?endforeach;?>
  </ul>
</div>