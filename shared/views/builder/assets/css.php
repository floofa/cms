<?foreach ($css as $item):?>
  <link rel="stylesheet" type="text/css" href="<?=$item['href']?>"<?if ($item['media']):?> media="<?=$item['media']?>"<?endif;?> />
<?endforeach;?>

