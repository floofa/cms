<?if ($data['count_actions'] > 0):?>
  <div class="bulk-actions align-left">
    <?=$form->open()?>
      <?=$form->ids?>
      <?=$form->action?>
      <?=$form->page->view()->add_class('text-input')->css('width', '40px')->field()->render()?>
      <a href="" class="button">Provést</a>
    </form>
  </div>
  
  <script type="text/javascript">
  <!--
    var actions = $.parseJSON('<?=json_encode($data['actions'])?>');
  
    $(function() {
      $("input[name='form_multi[page]']").hide();
      
      $("#<?=$form->alias()?> a.button").click(function(){
        $.cms.submit("<?=$form->alias()?>");
        return false;
      });
      
      $("select[name='form_multi[action]']").change(function(){
        var val = $(this).val();
        
        if (val == 'move_to_page') {
          $("input[name='form_multi[page]']").show();
        }
        else {
          $("input[name='form_multi[page]']").hide();
        }
      });
      
      $("#<?=$form->alias()?>").submit(function(){
        // vybrane polozky
        var items = '';
        $("input[type='checkbox']:checked.datalist-<?=$data['list_name']?>-checkboxes").each(function(){
          items += ((items.length > 0) ? "," : "") + $(this).attr('rel');
        });
        
        // kontrola vybranych polozek
        if ( ! items.length) {
          alert("Nejsou vybrány žádné záznamy.");
          return false;
        }
        
        // nastaveni vybranych polozek
        $("input[name='form_multi[ids]']").val(items);
        
        var action = $("select[name='form_multi[action]']").val();
        
        if ( ! action.length) {
          alert("Není vybrána žádná akce.");
          return false;
        }
        
        // confirm
        if (actions[action]["confirm"]) {
          if ( ! confirm(actions[action]["confirm"]))
            return false;
        }
      });
    });
  //-->
  </script>
<?endif;?>