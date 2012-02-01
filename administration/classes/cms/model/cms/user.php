<?php defined('SYSPATH') or die('No direct access allowed.');

class Cms_Model_Cms_User extends ORM_Classic {

	protected $_has_many = array(
		'user_tokens' => array ('model' => 'user_token'),
		'cms_roles' => array ('model' => 'cms_role', 'through' => 'cms_roles_cms_users'),
	);
  
  protected $_roles = array ();
  
  protected $_rights = array ();
  
  public function set($column, $value)
  {
    switch ($column) {
      case 'password':
        if ( ! strlen($value))
          return;
    }
  
    return parent::set($column, $value);
  }
  
	/**
	 * Filters to run when data is set in this model. The password filter
	 * automatically hashes the password when it's set in the model.
	 *
	 * @return array Filters
	 */
	public function filters()
	{
		return array(
			'password' => array(
				array(array(Auth::instance(), 'hash'))
			)
		);
	}
  
  public function save(Validation $validation = NULL)
  {
    $return = parent::save($validation);
  }

	/**
	 * Complete the login for a user by incrementing the logins and saving login timestamp
	 *
	 * @return void
	 */
	public function complete_login()
	{
		if ($this->_loaded)
		{
			// Update the number of logins
			$this->logins = new Database_Expression('logins + 1');

			// Set the last login date
			$this->last_login = time();

			// Save the user
			$this->update();
		}
	}
  
  /**
   * Allows a model use both email and username as unique identifiers for login
   *
   * @param   string  unique value
   * @return  string  field name
   */
  public function unique_key($value)
  {
    return Valid::email($value) ? 'email' : 'username';
  }

	/**
	 * Password validation for plain passwords.
	 *
	 * @param array $values
	 * @return Validation
	 */
	public static function get_password_validation($values)
	{
		return Validation::factory($values)
			->rule('password', 'min_length', array(':value', 8))
			->rule('password_confirm', 'matches', array(':validation', ':field', 'password'));
	}

	/**
	 * Create a new user
	 *
	 * Example usage:
	 * ~~~
	 * $user = ORM::factory('user')->create_user($_POST, array(
	 *	'username',
	 *	'password',
	 *	'email',
	 * );
	 * ~~~
	 *
	 * @param array $values
	 * @param array $expected
	 * @throws ORM_Validation_Exception
	 */
	public function create_user($values, $expected)
	{
		// Validation for passwords
		$extra_validation = Model_User::get_password_validation($values)
			->rule('password', 'not_empty');

		return $this->values($values, $expected)->create($extra_validation);
	}

	/**
	 * Update an existing user
	 *
	 * [!!] We make the assumption that if a user does not supply a password, that they do not wish to update their password.
	 *
	 * Example usage:
	 * ~~~
	 * $user = ORM::factory('user')
	 *	->where('username', '=', 'kiall')
	 *	->find()
	 *	->update_user($_POST, array(
	 *		'username',
	 *		'password',
	 *		'email',
	 *	);
	 * ~~~
	 *
	 * @param array $values
	 * @param array $expected
	 * @throws ORM_Validation_Exception
	 */
	public function update_user($values, $expected = NULL)
	{
		if (empty($values['password']))
		{
			unset($values['password'], $values['password_confirm']);
		}

		// Validation for passwords
		$extra_validation = Model_User::get_password_validation($values);

		return $this->values($values, $expected)->update($extra_validation);
	}
  
  public function get_navigation_val()
  {
    return $this->username;
  }
  
  public function load_roles_and_rights()
  {
    // nacteni roli
    foreach (ORM::factory('cms_role')->find_all() as $role) {
      $this->_roles[$role->name] = FALSE;
    }
    
    // nacteni prav
    foreach (ORM::factory('cms_right')->find_all() as $right) {
      $this->_rights[$right->name] = FALSE;
    }
    
    // nastaveni roli a opravneni
    foreach ($this->cms_roles->find_all() as $role) {
      $this->_roles[$role->name] = TRUE;

      $rights = $role->cms_rights->find_all()->as_array('id', 'id');
      
      foreach ($role->cms_rights->find_all() as $right) {
        $this->_rights[$right->name] = TRUE;
      }
    }
  }

  public function has_role($role)
  {
    if (is_array($role)) {
      foreach ($role as $_role) {
        if ( ! Arr::get($this->_roles, $_role, TRUE))
          return FALSE;
      }
    }
    else {
      return Arr::get($this->_roles, $role, TRUE);
    }
    
    return TRUE;
  }
  
  public function has_right($right)
  {
    if (is_array($right)) {
      foreach ($right as $_right) {
        if ( ! Arr::get($this->_rights, $right, TRUE))
          return FALSE;
      }
    }
    else {
      return Arr::get($this->_rights, $right, TRUE);
    }
    
    return TRUE;
  }
}
