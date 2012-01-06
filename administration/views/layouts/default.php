<html>
  <?=Head::render()?>
</html>

<body>
  <div id="body-wrapper">
    <div id="sidebar">
      <div id="sidebar-wrapper">
        <a href="<?=URL::site()?>"><img alt="Simpla Admin logo" src="<?=URL::site('media/admin/images/logo.png', FALSE, FALSE)?>" id="logo"></a>
        
        <div id="profile-links">
          <?$user = Auth::instance()->get_user()?>
          Přihlášen jako: <span class="name"><?=$user->username?></span><br />
          <a target="_blank" href="<?=URL::site('', TRUE, FALSE)?>" title="Zobrazit web">Zobrazit web</a> | <a href="<?=Route::url('auth-logout')?>" title="Odhlásit se">Odhlásit se</a>
        </div>
        
        <?=Request::factory('static_administration/main_menu')->execute()?>
      </div>
    </div>
    
    <div id="main-content">
      <?=Navigation::render()?>
      
      <?=Bookmarks::render()?>
      
      <?=$content?>
    </div>
  </div>
  
  <div class="clear"></div>
</body>