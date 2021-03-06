<?php defined('SYSPATH') or die('No direct script access.');

return array (
  'basic_yes' => 'Ano',
  'basic_no' => 'Ne',
  
  'static_page_page_types' => array ('static' => 'Statická', 'homepage' => 'Homepage', 'news' => 'Novinky', 'articles' => 'Články', 'photogalleries' => 'Fotogalerie'),
  'static_page_page_layouts' => array ('static' => 'Výchozí', 'homepage' => 'Homepage', 'fancy' => 'Fancy'),
  'static_block_types' => array ('static' => 'Statický', 'dynamic' => 'Dynamický'),

  // hlavni menu
  'cms_menu_administration_module' => 'Administrace',
  'cms_menu_administration_module_pages' => 'Statické stránky',
  'cms_menu_administration_module_menus' => 'Menu',
  'cms_menu_settings_module' => 'Nastavení',
  'cms_menu_settings_module_cms_users' => 'Uživatelé',
  'cms_menu_settings_module_cms_roles' => 'Uživatelské role',
  'cms_menu_settings_module_cms_rights' => 'Uživatelská oprávnění',
  
  // drobeckova navigace
  'navigation_base' => 'Cms',
  'navigation_list' => 'Výpis',
  'navigation_new' => 'Vytvořit',
  'navigation_edit' => 'Editace',
  'navigation_access_denied' => 'Nepovolený přístup',
  
  // bookmarks
  'bookmarks_edit' => 'Editace',
  
  //-------- LISTS ------------ //
  
  // uzivatele cms
  'navigation_cms_users' => 'Uživatelé',
  'list_cms_user_heading' => 'Výpis uživatelů',
  'list_cms_user_new_button' => 'Nový uživatel',
  'list_cms_user_fields' => array ('username' => 'Uživ. jméno', 'email' => 'E-mail'),
  
  // role uzivatelu cms
  'navigation_cms_roles' => 'Uživatelské role',
  'list_cms_role_heading' => 'Výpis uživatelských rolí',
  'list_cms_role_new_button' => 'Nová role',
  'list_cms_role_fields' => array ('name' => 'Název', 'description' => 'Popis'),
  
  // opravneni uzivatelu cms
  'navigation_cms_rights' => 'Uživatelská oprávnění',
  'list_cms_right_heading' => 'Výpis uživatelských oprávnění',
  'list_cms_right_new_button' => 'Nové oprávnění',
  'list_cms_right_fields' => array ('title' => 'Název', 'description' => 'Popis', 'name' => 'Systémový název', 'parent' => 'Spadá pod'),
  
  // staticke stranky
  'navigation_pages' => 'Statické stránky',
  'list_page_heading' => 'Výpis statických stránek',
  'list_page_new_button' => 'Nová stránka',
  'list_page_fields' => array ('name' => 'Název', 'cms_status' => 'Zobrazit'),
  
  // menu - výpis
  'navigation_menus' => 'Menu',
  'list_menu_heading' => 'Výpis menu',
  'list_menu_new_button' => 'Nové menu',
  'list_menu_fields' => array ('name' => 'Název', 'sys_name' => 'Systémový název'),
  
  // menu - mptt
  'list_tree_menu_heading' => 'Položky menu',
  'list_tree_menu_new_button' => 'Nová položka',
  'list_tree_menu_fields' => array ('name' => 'Název'),
  'list_tree_menu_secondary_fields' => array ('link' => ''),
  
  // staticke a dynamicke bloky
  'cms_menu_administration_module_blocks' => 'Bloky',
  'navigation_blocks' => 'Bloky',
  'list_block_heading' => 'Bloky',
  'list_block_new_button' => 'Nový blok',
  'list_block_fields' => array ('name' => 'Název', 'sys_name' => 'Systémový název', 'type' => 'Typ', 'cms_status' => 'Zobrazit'),
  
  
  //-------- FORMS ------------ //
  
  // prihlaseni
  'form_login_field_username' => 'Jméno',
  'form_login_field_password' => 'Heslo',
  
  // hromadne akce
  'form_multi_field_action_option_choose' => 'Vyberte akci ...',
  'form_multi_field_action_option_move_to_page' => 'Přesunout na stránku:',
  'form_multi_field_action_option_delete' => 'Odstranit',
  
  'form_multi_confirm_action_delete' => 'Opravdu chcete odstranit vybrané položky?',
  
  // uzivatel cms
  'form_cms_user_edit_heading' => 'Editace uživatele',
  'form_cms_user_edit_field_username' => 'Login',
  'form_cms_user_edit_field_email' => 'E-mail',
  'form_cms_user_edit_field_password' => 'Heslo',
  'form_cms_user_edit_field_cms_roles' => 'Role',
  
  // role uzivatelu cms
  'form_cms_role_edit_heading' => 'Editace uživatelského oprávnění',
  'form_cms_role_edit_field_name' => 'Název',
  'form_cms_role_edit_field_description' => 'Popis',
  
  // opravneni uzivatelu cms
  'form_cms_right_edit_heading' => 'Editace uživatelského oprávnění',
  'form_cms_right_edit_field_title' => 'Název',
  'form_cms_right_edit_field_name' => 'Systémový název',
  'form_cms_right_edit_field_description' => 'Popis',
  'form_cms_right_edit_field_parent_id' => 'Nadřazené oprávnění',
  'form_cms_right_edit_field_set_for_all_roles' => 'Nastavit pro všechny role',
  'form_cms_role_edit_field_cms_rights' => 'Oprávnění',
  
  // staticke stranky
  'form_page_edit_heading' => 'Editace statické stránky',
  'form_page_edit_group_page_images' => 'Obrázky',
  'form_page_edit_field_name' => 'Název',
  'form_page_edit_field_name_en' => 'Název (EN)',
  'form_page_edit_field_head_title' => 'Titulek',
  'form_page_edit_field_head_title_en' => 'Titulek (EN)',
  'form_page_edit_field_rew_id' => 'Url',
  'form_page_edit_field_sys_name' => 'Systémový název',
  'form_page_edit_field_meta_keywords' => 'Meta keywords',
  'form_page_edit_field_meta_keywords_en' => 'Meta keywords (EN)',
  'form_page_edit_field_meta_description' => 'Meta description',
  'form_page_edit_field_meta_description_en' => 'Meta description (EN)',
  'form_page_edit_field_cms_status' => 'Zobrazit',
  'form_page_edit_field_page_type' => 'Typ stránky',
  'form_page_edit_field_page_layout' => 'Layout stránky',
  'form_page_edit_field_content' => 'Obsah',
  'form_page_edit_field_content_en' => 'Obsah (EN)',
  
  'form_page_filter_field_name' => 'Název',
  
  // menu
  'form_menu_edit_heading' => 'Editace menu',
  'form_menu_edit_field_name' => 'Název',
  'form_menu_edit_field_sys_name' => 'Systémový název',
  
  // menu - polozka
  'form_menu_item_edit_heading' => 'Editace položky menu',
  'form_menu_item_edit_field_name' => 'Název',
  'form_menu_item_edit_field_name_en' => 'Název (EN)',
  'form_menu_item_edit_field_page_id' => 'Statická stránka',
  'form_menu_item_edit_field_links_for_active' => 'URL pro aktivni odkaz',
  'form_menu_item_edit_field_url' => 'Url',
  
  // staticke a dynamicke bloky
  'form_block_edit_heading' => 'Editace bloku',
  'form_block_edit_group_block_images' => 'Obrázky',
  'form_block_edit_field_name' => 'Název',
  'form_block_edit_field_sys_name' => 'Systémový název',
  'form_block_edit_field_cms_status' => 'Zobrazit',
  'form_block_edit_field_type' => 'Typ',
  'form_block_edit_field_content' => 'Obsah',
  
  
  
  //-------- VALIDATIONS ------------ //
  'valid' => array (
    'alpha'      => 'Pole `:field` může obsahovat pouze písmena',
    'alpha_dash'  => 'Pole `:field` může obsahovat pouze písmena, číslice, pomlčku a potržítko',
    'alpha_numeric'  => 'Pole `:field` může obsahovat pouze písmena a číslice',
    'color'      => 'Do pole `:field` musíte zadat kód barvy',
    'credit_card'  => 'Do pole `:field` musíte zadat platné číslo platební karty',
    'date'      => 'Do pole `:field` musíte zadat platné datum',
    'decimal' => array(
      'one'    => 'Do pole `:field` musíte zadat číslo s jedním desetinným místem',
      'other'    => 'Do pole `:field` musíte zadat číslo s :param2 desetinnými místy',
    ),
    'digit'      => 'Do pole `:field` musíte zadat celé číslo',
    'email'      => 'Do pole `:field` musíte zadat emailovou adresu',
    'email_domain'  => 'Do pole `:field` musíte zadat platnou emailovou doménu',
    'equals'    => 'Pole `:field` se musí rovnat :param2',
    'exact_length' => array(
      'one'    => 'Pole `:field` musí být dlouhé přesně 1 znak',
      'few'    => 'Pole `:field` musí být přesně :param2 znaky dlouhé',
      'other'    => 'Pole `:field` musí být přesně :param2 znaků dlouhé',
    ),
    'in_array'    => 'Do pole `:field` musíte vložit pouze jednu z dovolených možností',
    'ip'      => 'Do pole `:field` musíte zadat platnou ip adresu',
    'match'      => 'Pole `:field` se musí shodovat s polem :param2',
    'max_length' => array(
      'few'    => 'Pole `:field` musí být nanejvýš :param2 znaky dlouhé',
      'other'    => 'Pole `:field` musí být nanejvýš :param2 znaků dlouhé',
    ),
    'min_length' => array(
      'one'    => 'Pole `:field` musí být alespoň jeden znak dlouhé',
      'few'    => 'Pole `:field` musí být alespoň :param2 znaky dlouhé',
      'other'    => 'Pole `:field` musí být alespoň :param2 znaků dlouhé',
    ),
    'not_empty'    => 'Pole `:field` nesmí být prázdné',
    'numeric'       => 'Hodnota pole `:field` musí mít číselnou hodnotu',
    'phone'      => 'Pole `:field` musí být platné telefonní číslo',
    'range'      => 'Hodnota pole `:field` musí ležet v intervalu od :param2 do :param3',
    'regex'      => 'Pole `:field` musí splňovat požadovaný formát',
    'url'      => 'Do pole `:field` musíte zadat platnou adresu URL',
  ),
  
  
  'validate' => array (
    'email' => array (
      'is_unique' => 'Hodnota v poli `:field` musí být unikátní. Vámi zadaná hodnota (:value) již existuje',
    ),
    'form_login' => array (
      'check_user' => 'Zadané jméno nebo heslo není správné.',
    ),
    'sys_name' => array (
      'is_unique' => 'Hodnota v poli `:field` musí být unikátní. Vámi zadaná hodnota (:value) již existuje',
    ),
    'username' => array (
      'is_unique' => 'Hodnota v poli `:field` musí být unikátní. Vámi zadaná hodnota (:value) již existuje',
    ),
  ),
);
