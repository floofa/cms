<?php
  function render_tree($items, $data) {
    echo '<ul>';
    
    $count = count($items);
    
    foreach ($items as $key => $item) {
      render_tree_item($item, $key, $count, $data);
    }
    
    echo '</ul>';
  }

  function render_tree_item($item, $key, $count, $data) 
  {
    echo '<li id="node_'.$item['id'].'">';
    
    // hlavni text
    echo '<a href="">'; 
    
    $count = 0;
    foreach ($data['main_fields'] as $field => $label) {
      if (++$count > 1) {
        echo $data['main_fields_delimiter'];
      }
      
      echo $item[$field];
    }
    
    echo '</a>';
    
    // akce
    if (count($data['actions'])) {
      echo '<span class="actions">';
      
      foreach ($data['actions'] as $key => $action) {
        $action = str_replace('{parent_id}', $item[$data['items_parent_id_key']], str_replace('{id}', $item[$data['items_id_key']], $action));
        
        echo '<img class="action-' . $key . '>" src="'.URL::site('media/admin/images/icons/' . $data['actions_images'][$key], TRUE, FALSE) . '" onClick="'.$key.'(\''.$action.'\')" />';
      }
      
      echo '</span>';
    }
    
    echo '<br />';
    
    // doplnujici text
    echo '<span class="link">';
    
    $count = 0;
    foreach ($data['secondary_fields'] as $field => $label) {
      if (++$count > 1) {
        echo $data['secondary_fields_delimiter'];
      }
      
      if (strlen($label)) {
        echo $label . ': ';
      }
      
      echo $item[$field];
    }
    
    echo '</span>';
    
    
    // potomci
    echo '<div class="border-bottom'. ((count($item['_children'])) ? ' submenu' : '') . '"></div>';
    
    if (count($item['_children'])) {
      echo '<ul>';
    
      foreach ($item['_children'] as $key_child => $child) {
        render_tree_item($child, $key_child, count($item['_children']), $data);
      }
      
      echo '</ul>';
    }
    
    echo '</li>';
  }
?>

<div class="content-box">
  <div class="content-box-header">
    <h3 style="cursor: s-resize;"><?=$heading?></h3>

    <div class="buttons">
      <?foreach ($buttons as $button):?>
        <?=$button?>
      <?endforeach;?>
    </div>
    <div class="clear"></div>
  </div>
  <div class="content-box-content">
    <?if (count($items)):?>
      <div id="menu-tree">
        <?php render_tree($items, array (
          'actions' => $actions,
          'actions_images' => $actions_images,
          'items_id_key' => $items_id_key,
          'items_parent_id_key' => $items_parent_id_key,
          'main_fields' => $main_fields,
          'main_fields_delimiter' => $main_fields_delimiter,
          'secondary_fields' => $secondary_fields,
          'secondary_fields_delimiter' => $secondary_fields_delimiter));
        ?>
      </div>
    <?else:?>
      <p>Žádné položky nebyly nalezeny.</p>
    <?endif;?>
    
  </div>
</div>


<script type="text/javascript">
<!--

$(function() {
  $("#menu-tree").jstree({
    "plugins" : [ "themes", "html_data", "crrm", "ui"<?if($drag):?>, "dnd"<?endif;?>, "cookies" ],
    
    "themes" : {
      "theme" : "apple",
      "url" : BASE_URL + "media/admin/css/jstree.css"
    },
    
  })
  .bind("move_node.jstree", function (e, data) {
    data.rslt.o.each(function (i) {
      var prev = 0;
      
      if ($(this).prev().length != 0) {
        prev = $(this).prev().attr("id").replace("node_", "");
      }
      
      $.post(
        '<?=$drag_url?>',
        {
          'id' : $(this).attr("id").replace("node_", ""),
          'prev' : prev,
          'parent' : data.rslt.np.attr("id").replace("node_","")
        },
        function (payload) {
          if (payload.state !== 'ok') {
            $.jstree.rollback(data.rlbk);
          }
        }
      );
    });
  });
  
  $("#menu-tree .actions").mouseover(function(){
    $(this).prev().addClass("jstree-hovered");
  }).mouseout(function(){
    $(this).prev().removeClass("jstree-hovered");
  });
});

function add_item(url) {
  $.cms.redirect(url);
}

function edit_item(url) {
  $.cms.redirect(url);
}

function delete_item(url) {
  if (confirm("Opravdu chcete odstranit polozku?"))
    $.cms.redirect(url);
}

</script>
