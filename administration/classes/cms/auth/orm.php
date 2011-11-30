<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * ORM Auth driver.
 *
 * @package    Kohana/Auth
 * @author     Kohana Team
 * @copyright  (c) 2007-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Cms_Auth_ORM extends Kohana_Auth_ORM 
{
  /**
   * Checks if a session is active.
   *
   * @param   mixed    $role Role name string, role ORM object, or array with role names
   * @return  boolean
   */
  public function logged_in($role = NULL)
  {
    // Get the user from the session
    $user = $this->get_user();

    if ( ! $user)
      return FALSE;

    if ($user instanceof Model_Cms_User AND $user->loaded())
    {
      // If we don't have a roll no further checking is needed
      if ( ! $role)
        return TRUE;

      if (is_array($role))
      {
        // Get all the roles
        $roles = ORM::factory('cms_role')
              ->where('name', 'IN', $role)
              ->find_all()
              ->as_array(NULL, 'id');

        // Make sure all the roles are valid ones
        if (count($roles) !== count($role))
          return FALSE;
      }
      else
      {
        if ( ! is_object($role))
        {
          // Load the role
          $roles = ORM::factory('cms_role', array('name' => $role));

          if ( ! $roles->loaded())
            return FALSE;
        }
      }

      return $user->has('cms_roles', $roles);
    }
  }
  
  /**
   * Logs a user in.
   *
   * @param   string   username
   * @param   string   password
   * @param   boolean  enable autologin
   * @return  boolean
   */
  protected function _login($user, $password, $remember)
  {
    if ( ! is_object($user))
    {
      $username = $user;

      // Load the user
      $user = ORM::factory('cms_user');
      $user->where($user->unique_key($username), '=', $username)->find();
    }

    if (is_string($password))
    {
      // Create a hashed password
      $password = $this->hash($password);
    }

    // If the passwords match, perform a login
    if ($user->has('cms_roles', ORM::factory('cms_role', array('name' => 'login'))) AND $user->password === $password)
    {
      if ($remember === TRUE)
      {
        // Token data
        $data = array(
          'user_id'    => $user->id,
          'expires'    => time() + $this->_config['lifetime'],
          'user_agent' => sha1(Request::$user_agent),
        );

        // Create a new autologin token
        $token = ORM::factory('cms_user_token')
              ->values($data)
              ->create();

        // Set the autologin cookie
        Cookie::set('authautologin', $token->token, $this->_config['lifetime']);
      }

      // Finish the login
      $this->complete_login($user);

      return TRUE;
    }

    // Login failed
    return FALSE;
  }
  
  /**
   * Forces a user to be logged in, without specifying a password.
   *
   * @param   mixed    username string, or user ORM object
   * @param   boolean  mark the session as forced
   * @return  boolean
   */
  public function force_login($user, $mark_session_as_forced = FALSE)
  {
    if ( ! is_object($user))
    {
      $username = $user;

      // Load the user
      $user = ORM::factory('cms_user');
      $user->where($user->unique_key($username), '=', $username)->find();
    }

    if ($mark_session_as_forced === TRUE)
    {
      // Mark the session as forced, to prevent users from changing account information
      $this->_session->set('auth_forced', TRUE);
    }

    // Run the standard completion
    $this->complete_login($user);
  }
  
  /**
   * Logs a user in, based on the authautologin cookie.
   *
   * @return  mixed
   */
  public function auto_login()
  {
    if ($token = Cookie::get('authautologin'))
    {
      // Load the token and user
      $token = ORM::factory('cms_user_token', array('token' => $token));

      if ($token->loaded() AND $token->user->loaded())
      {
        if ($token->user_agent === sha1(Request::$user_agent))
        {
          // Save the token to create a new unique token
          $token->save();

          // Set the new token
          Cookie::set('authautologin', $token->token, $token->expires - time());

          // Complete the login with the found data
          $this->complete_login($token->cms_user);

          // Automatic login was successful
          return $token->cms_user;
        }

        // Token is invalid
        $token->delete();
      }
    }

    return FALSE;
  }
  
  /**
   * Log a user out and remove any autologin cookies.
   *
   * @param   boolean  completely destroy the session
   * @param  boolean  remove all tokens for user
   * @return  boolean
   */
  public function logout($destroy = FALSE, $logout_all = FALSE)
  {
    // Set by force_login()
    $this->_session->delete('auth_forced');

    if ($token = Cookie::get('authautologin'))
    {
      // Delete the autologin cookie to prevent re-login
      Cookie::delete('authautologin');

      // Clear the autologin token from the database
      $token = ORM::factory('cms_user_token', array('token' => $token));

      if ($token->loaded() AND $logout_all)
      {
        ORM::factory('cms_user_token')->where('cms_user_id', '=', $token->cms_user_id)->delete_all();
      }
      elseif ($token->loaded())
      {
        $token->delete();
      }
    }

    return parent::logout($destroy);
  }
  
  /**
   * Get the stored password for a username.
   *
   * @param   mixed   username string, or user ORM object
   * @return  string
   */
  public function password($user)
  {
    if ( ! is_object($user))
    {
      $username = $user;

      // Load the user
      $user = ORM::factory('cms_user');
      $user->where($user->unique_key($username), '=', $username)->find();
    }

    return $user->password;
  }
}