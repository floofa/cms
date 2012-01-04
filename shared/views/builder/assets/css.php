<?foreach ($css as $item):?>
  <link rel="stylesheet" type="text/css" href="<?=$item['href']?>"<?if ($item['attr']):?><?=$item['attr']?><?endif;?> />
<?endforeach;?>

