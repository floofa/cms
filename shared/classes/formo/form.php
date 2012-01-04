<?php defined('SYSPATH') or die('No direct script access.');

class Formo_Form extends Formo_Core_Form 
{
  /**
   * Load data, works automatcially with with post
   *
   * @access public
   * @param mixed array $input. (default: NULL)
   * @return void
   */
  public function load(array $input = NULL)
  {
    // Set input to $_POST if it's not explicitly definied
    ($input === NULL AND $input = $_POST);

    if ($this->sent($input) === FALSE)
      // Stop if input doesn't match the form's fields
      return $this;

    foreach ($this->fields() as $field)
    {
      if ($field->get('editable') === FALSE)
        // Don't ever adjust values for not editable fields
        continue;

      // post keys never have spaces
      $input_key = str_replace(' ', '_', $field->alias());
      
      // pokud neni hodnota obsazena v $_POST (napr. pri multiselectu, kdy neni vybrana zadna polozka), nastavi se vychozi hodnota
      if ( ! isset($input[$field->parent()->alias()][$input_key])) {
        if ($field->driver() instanceof Formo_Driver_Select && isset($field->attr['multiple']) && $field->attr['multiple'] == 'multiple') {
          $input[$field->parent()->alias()][$input_key] = array ();
        }
        else {
          $input[$field->parent()->alias()][$input_key] = '';
        }
      }
       
      if ($field instanceof Formo_Form)
      {
        // Recursively load values
        $field->load($input);
        continue;
      }

      // Fetch the namespace for this form
      $namespaced_input = (Formo::config($this, 'namespaces') === TRUE)
        ? Arr::get($input, $this->alias(), array())
        : $input;

      if (isset($namespaced_input[$input_key]))
      {
        // Set the value
        $field->driver()->load($namespaced_input[$input_key]);
      }
      elseif ($field->driver()->file === TRUE AND isset($_FILES[$input_key]))
      {
        // Load the $_FILES params as the value
        $field->driver()->load($_FILES[$input_key]);
      }
      elseif ($field->driver()->empty_input === TRUE)
      {
        // If the an empty input is allowed, pass an empty value
        $field->driver()->load(array());
      }
    }

    $this->set('input', $input);

    return $this;
  }
}