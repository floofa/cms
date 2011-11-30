<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="robots" content="all, follow" />
  <meta name="title" content="<?=Head::get('head_title')?>" />
  <meta name="description" content="<?=Head::get('meta_description')?>" />
  <meta name="keywords" content="<?=Head::get('meta_keywords')?>" />
  <meta name="author" content="Ondrej Trojanek" />
  <title><?=Head::get('head_title')?></title>
  
  <script type="text/javascript">
  <!--
    var CMS_URL = "<?=URL::site('', TRUE)?>";  
    var BASE_URL = "<?=URL::site('', TRUE, FALSE)?>";  
  //-->
  </script>

  <?=Assets::render_css($assets)?>
  <?=Assets::render_js($assets)?>
</head>