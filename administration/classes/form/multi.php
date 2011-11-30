<?php defined('SYSPATH') or die('No direct script access.');

class Form_Multi extends Forms 
{
  public function build()
  {
    $options[''] = ___($this->_formo->alias() . '_field_action_option_choose');
    
    $count = 0;
    if (isset($this->_data['actions'])) {
      foreach ($this->_data['actions'] as $name => $params) {
        $options[$name] = ___($this->_formo->alias() . '_field_action_option_' . $name);
        
        // nastaveni confirm textu
        $this->_data['actions'][$name]['confirm'] = (isset($params['confirm'])) ? ___($this->_formo->alias() . '_confirm_action_' . $name) : FALSE;
        
        $count++;
      }
    }
    
    $this->_data['count_actions'] = $count;
    $this->_data['list_name'] = isset($this->_data['list_name']) ? $this->_data['list_name'] : $this->_model;
    
    $this->add('action', 'select', array ('options' => $options));
    $this->add('page', 'input', 1);
    $this->add('ids', 'hidden');
  }
  
  public function do_form($values = array (), $refresh = TRUE, $redirect = FALSE)
  {
    $ids = explode(',', $values['ids']);
    
    if (count($ids)) {
      switch($values['action']) {
        case 'delete' :
          foreach (ORM::factory($this->_model)->where('id', 'IN', $ids)->find_all() as $orm_object)
            $orm_object->delete();
          break;
        case 'move_to_page' :
          $from_sequence = $values['page'] * $this->_data['items_per_page'];
          ORM::factory($this->_model)->move_to_page(array_reverse($ids), $from_sequence);
          Request::initial()->redirect(URL::site(Request::$initial->query('page', $values['page'])->uri(), TRUE));
      }
    }

    Request::initial()->redirect(URL::site(Request::$initial->uri(), TRUE));
  }
}