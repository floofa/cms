<div class="list sortable" id="list-gallery-<?=$gallery->name?>">
  <?foreach ($gallery_items as $item):?>
    <div class="item" id="gallery_item-<?=$item->id?>">
      <div class="number"><?=$item->id?></div>
    
      <div class="img">
        <a href="<?=$item->get_link()?>">
          <img src="<?=$item->get_link('cms')?>" />
        </a>
      </div>
      <div class="actions">
        <a href="" class="sort align-left">
          <img src="<?=URL::site('media/admin/images/icons/updown2.gif', TRUE, FALSE)?>" title="Přesunout" alt="Přesunout" />
        </a>
        
        <a href="<?=Route::url('galleries-delete_item', array ('id' => $item->id))?>" class="delete align-right">
          <img src="<?=URL::site('media/admin/images/icons/cross.png', TRUE, FALSE)?>" title="Odstranit" alt="Odstranit" />
        </a>
        
        <a href="<?=Route::url('default', array ('controller' => 'galleries', 'action' => 'edit_item_description', 'id' => $item->id), TRUE)?>" class="align-right fancybox">
          <img src="<?=URL::site('media/admin/images/icons/pencil.png', TRUE, FALSE)?>" title="Editace" alt="Odstranit" />
        </a>
        
        <a href="<?=Route::url('galleries-item_info', array ('id' => $item->id), TRUE)?>" class="align-right fancybox">
          <img src="<?=URL::site('media/admin/images/icons/bullet_black.png', TRUE, FALSE)?>" title="Info" alt="Info" />
        </a>
        
      </div>
    </div>
  <?endforeach;?>
  
  <?if ( ! count($gallery_items)):?>
    <p>Nebyly nahrány žádné soubory.</p>
  <?endif;?>
  
  <div class="clear"></div>
</div>

<script type="text/javascript">
<!--
  $("#list-gallery-<?=$gallery->name?> .actions .delete").click(function() {
    if (confirm("Opravdu chcete odstranit obrazek?")) {
      $item = $(this).parent().parent();
      
      $.post(
        $(this).attr("href"),
        function (payload) {
          if (payload.state == 'ok') {
            $item.remove();
          }
        }
      )
    }
    
    return false;
  });
  
  $(function(){
    $("#list-gallery-<?=$gallery->name?>.list.sortable").sortable({
      items : "div.item",
      handle : 'a.sort',
      containment : 'parent',
      stop : function(event, ui) {
        data = "";
        
        $parent = ui.item.parent();
        
        $parent.children('div.item').each(function(){
          if (data != '')
            data += '&';
            
          id = $(this).attr('id');
          data += 'items[]=' + id.substr(id.indexOf("-") + 1);
        });

        $.post(
          "<?=Route::url('galleries-reorder')?>",
          data
        );
      }
    });
  });
//-->
</script>