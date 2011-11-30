<div class="content-box">
  <div class="content-box-header">
    <h3><?=$heading?></h3>

    <div class="buttons">
      <?foreach ($buttons as $button):?>
        <?=$button?>
      <?endforeach;?>
    </div>
    
    <div class="clear"></div>
  </div>    
  
  <div class="content-box-content">
    
    <?=$filters?>
    
    <?$columns = 1;?>
    
    <?if (count($items)):?>
      <table class="datalist-browse" id="datalist-<?=$name?>">
        <thead>
          <tr class="nodrop nodrag">
            <?if ($item_id_key):?>
              <th class="th-check"><input type="checkbox" class="check-all"></th>
              <?$columns++?>
            <?endif;?>
            <?foreach ($fields as $key => $value):?>
              <th class="th-<?=$key?>"><?php echo $value?></th>
              <?$columns++?>
            <?endforeach;?>
            
            <?if ($drag):?>
              <th class="th-drag">Drag</th>
              <?$columns++?>
            <?endif;?>
            
            <?if (count($actions)):?>
            <th class="th-actions">Akce</th>
            <?endif?>
          </tr>
        </thead>
        
        <tbody>
          <?foreach ($items as $key => $row):?>
            <tr <?if($drag):?>id="row-<?=$row['id']?>"<?endif;?> rel="<?=$key + 1?>">
              <?if ($item_id_key):?>
                <td class="check"><input type="checkbox" rel="<?=$row[$item_id_key]?>" class="datalist-<?=$name?>-checkboxes"></td>
              <?endif;?>
            
              <?foreach ($fields as $key => $value):?>
                <td class="td-<?=$key?> redirect"><?php echo $row[$key]?></td>
              <?endforeach;?>
              
              <?if ($drag):?>
                <?if ($drag):?>
                  <td class="td-drag_handler"></td>
                <?endif;?>
              <?endif;?>
              
              <?if (count($actions)):?>
                <td class="td-actions">
                  <?foreach ($actions as $key => $action):?>
                    <?$action = str_replace('{id}', $row[$item_id_key], $action);?>
                    <a href="<?=$action?>" class="action-<?=$key?>"><img src="<?=Url::site('media/admin/images/icons/' . $actions_images[$key], TRUE, FALSE)?>" /></a>
                  <?endforeach;?>
                </td>
              <?endif;?>
            </tr>
          <?endforeach;?>
        </tbody>
        <tfoot>
          <tr class="nodrop nodrag">
            <td colspan="<?=$columns?>">
              <?=$multi_actions?>
            
              <?=$pagination?>
            </td>
          </tr>
        </tfoot>
        
      </table>
    <?else:?>
      <p>Žádné položky nebyly nalezeny.</p>
    <?endif;?>
  </div>
</div>

<script type="text/javascript">
<!--
  $("#datalist-<?=$name?> td.td-actions a.action-delete").click(function() {
    if ( ! confirm("Opravdu chcete odstranit vybranou položku?"))
      return false;
  });
  
  <?if (strlen($row_action)):?>
    $("#datalist-<?=$name?> td.td-actions a.action-<?=$row_action?>").each(function(){
      var href = $(this).attr("href");

      $(this).parent().parent().find("td").each(function(){
        if ($(this).hasClass('redirect'))
          $(this).click(function() {
            $.cms.redirect(href);
          });
      });
    });
  <?endif;?>
  
  // drag
  <?if ($drag):?>
    $("#datalist-<?php echo $name?> tr").hover(function() {
      $(this).children(".td-drag_handler").addClass('showDragHandle');
    }, function() {
      $(this).children(".td-drag_handler").removeClass('showDragHandle');
    });

    $("#datalist-<?php echo $name?>").tableDnD({
      onDrop: function(table, row) {
        $("#datalist-<?php echo $name?> tr").removeClass('alt-row');
        $("#datalist-<?php echo $name?> tr:odd").addClass('alt-row');
        
        var rows = table.tBodies[0].rows;

        var prev = false;
        var use_next = false;
        var nearby_id = false;
        
        for (var i = 0; i < rows.length; i++) {
          if (use_next) {
            // kontrola, jestli sem neskoncil na stejnem radku, na kterem jsem zacal
            if ((parseInt($("#" + rows[i].id).attr("rel")) - 1) == parseInt($("#" + row.id).attr("rel")))
              return false;
            
            nearby_id = rows[i].id;
            break;
          }
          
          if (row.id == rows[i].id) {
            // kontrola, jestli sem neskoncil na stejnem radku, na kterem jsem zacal
            if (prev && (parseInt($("#" + prev.id).attr("rel")) + 1) == parseInt($("#" + row.id).attr("rel")))
              return false;
            
            // posun smerem nahoru (pouzije se sequence od radku pod)
            if ( ! prev || parseInt($("#" + row.id).attr("rel")) > parseInt($("#" + prev.id).attr("rel"))) {
              use_next = true;
              continue;
            }
            
            // posun smerem dolu (pouzije se sequence od radku nad)
            else {
              nearby_id = prev.id;
              break;
            }
          }
          
          prev = rows[i];
        }
        
        var data = 'id=' + row.id.substr(4) + '&nearby_id=' + nearby_id.substr(4);
        
        $.post(
          "<?php echo $drag_url?>",
          data,
          function () {
          }
        );
        
        for (var i = 0; i < rows.length; i++) {
          $("#" + rows[i].id).attr("rel", i+1);
        }
        
        return false;
        /*
        var rows = table.tBodies[0].rows;
        var first = false;
        var prev = false;
        var sequence = false;
        
        for (var i = 0; i < rows.length; i++) {
          if (row.id == rows[i].id && i == 0) {
            first = true;
            continue;
          }
          
          if (i == 1 && first) {
            sequence = rows[i].id.substr(4, rows[i].id.indexOf('.') - 4);
          }
          
          if (row.id == rows[i].id) {
            sequence = prev.substr(4, prev.indexOf('.') - 4);;
          }
          
          prev = rows[i].id;
        }
        
        data = 'sequence=' + sequence + '&id=' + row.id.substr(row.id.indexOf('.') + 1);
        
        $.post(
          "<?php echo $drag_url?>",
          data,
          function () {
          }
        );

        return false;
        
        /*
        $("#datalist-<?php echo $name?> tr").removeClass('alt-row');
        $("#datalist-<?php echo $name?> tr:odd").addClass('alt-row');
        
        var rows = table.tBodies[0].rows;
        var data = '';
        
        
        
        for (var i=0; i<rows.length; i++) {
          data += 'items[]=' + rows[i].id + '&';
        }

        $.post(
          "<?php echo $drag_url?>",
          data,
          function () {
          }
        );
        
        */

        return false;
        
      },
      dragHandle: "td-drag_handler"
    });
  <?endif;?>
//-->
</script>