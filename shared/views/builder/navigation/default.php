<div id="breadcrumbs">
  <?foreach($items as $key => $item): ?>
    <?if (($key + 1) < $count_items): ?>
      <a href="<?=$item['link']?>"><?=$item['name']?></a>&nbsp;&gt;&nbsp;
    <?else:?>
      <?=$item['name']?>
    <?endif; ?>
  <?endforeach; ?>
</div>