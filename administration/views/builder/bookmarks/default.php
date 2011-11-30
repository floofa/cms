<ul class="bookmarks-buttons-set">
  <?foreach ($items as $item):?>
    <li>
      <a class="bookmarks-button<?if ($item['active']):?> active<?endif;?>" href="<?=$item['link']?>">
        <span><?=$item['name']?></span>
      </a>
    </li>
  <?endforeach;?>
</ul>
      
<div class="clear"></div>