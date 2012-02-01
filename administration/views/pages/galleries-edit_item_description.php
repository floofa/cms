<div style="width:500px;height:200px">
  <h2>Editace popisu obrazku</h2>
  <input type="text" id="galleryImageDescription" value="<?=$item->description?>" style="width: 100%;" /><br />
  <input type="button" value="Uložit" id="saveGalleryImageDescription"/>
</div>

<script type="text/javascript">
<!--
  $("#saveGalleryImageDescription").click(function() {
    $.post(
      "<?=Route::url('default', array ('controller' => 'galleries', 'action' => 'set_item_description', 'id' => $item->id), TRUE)?>",
      {description : $("#galleryImageDescription").val()}
    );
    
    $.fancybox.close();
  });
//-->
</script>

