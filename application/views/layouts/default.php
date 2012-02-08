<html>
  <?=Head::render()?>

<body>
  <?=Request::factory('static_application/menu/main_menu')->execute()?>

  <?=Navigation::render()?>
  <?=$content?>
  
  <?=Blocks::get('footer')?>
</body>
</html>