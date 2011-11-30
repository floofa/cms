<html>
  <?=Head::render('default', 'login')?>
</html>

<body id="login">
  <div id="login-wrapper" class="png_bg">
    <div id="login-top">
      <h1>Simpla Admin</h1>
      <img id="logo" src="<?=URL::site('media/admin/images/logo.png', TRUE, FALSE)?>" alt="Simpla Admin logo" />        
    </div> <!-- End #logn-top -->

    <div id="login-content">
      <?=$content?>
    </div>
  </div>
</body>