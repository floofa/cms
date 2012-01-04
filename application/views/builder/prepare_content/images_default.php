<?if (count($items)):?>
  <?if (count($items) == 1):?>
    <?$item = current($items)?>
    <a href="<?=$item->get_link()?>" class="fancybox">
      <img src="<?=$item->get_link('s')?>" alt="" />
    </a>
  <?else:?>
    <div class="content-images-default">
      <?foreach ($items as $item):?>
        <a href="<?=$item->get_link()?>" class="fancybox">
          <img src="<?=$item->get_link('s')?>" alt="" />
        </a>
      <?endforeach;?>
    </div>
  <?endif;?>
<?endif;?>