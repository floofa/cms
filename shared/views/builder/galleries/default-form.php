<div class="upload">
  <input id="file_upload_<?=$gallery_name?>" name="file_upload" type="file" />
</div>

<script type="text/javascript">
  $(document).ready(function() {
    
    $('#file_upload_<?=$gallery_name?>').uploadify({
      'swf'  : "<?=URL::site('media/admin/swf/uploadify.swf', TRUE, FALSE)?>",
      'uploader'    : CMS_URL + "galleries/upload",
      'cancelImage' : "<?=URL::site('media/admin/images/uploadify-cancel.png', TRUE, FALSE)?>",
      'buttonText' : 'Vyberte soubory',
      'checkExisting' : false,
      'fileSizeLimit' : "<?=$file_size_limit?>",
      'fileTypeExts' : "<?=$file_types?>",
      'fileTypeDesc' : "<?=$file_types_desc?>",
      'queueSizeLimit' : "<?=$file_queue_limit?>",
      'uploadLimit' : "<?=$file_upload_limit?>",
      "postData" : {
        model : "<?=$model?>",
        model_id : "<?=$model_id?>",
        name : "<?=$gallery_name?>",
        session_id : "<?=Session::instance()->id()?>"
      },
      'multi' : true,
      'auto'  : true,
      'debug' : "<?=$debug?>",
      'requeue_on_error' : false,
      'onQueueComplete' : function(event, data) {
        $.post(
          CMS_URL + 'galleries/list/<?=$model?>/<?=$model_id?>/<?=$gallery_name?>',
          function(payload) {
            $("#gallery-<?=$gallery_name?> .list").replaceWith(payload);
          },
          'html'
        );
      },
    });
  });
</script>
