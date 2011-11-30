<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Controller_Menus extends Controller_Builder_Template_Administration_Classic_Tree
{
  protected $_model = 'menu';
  protected $_model_mptt = 'menu_itemmptt';
  protected $_only_tree = FALSE;
  
  protected $_list_actions = array ('list_tree' => TRUE, 'edit' => TRUE, 'delete' => TRUE);
  protected $_list_row_action = 'list_tree';
  protected $_list_drag = TRUE;
}