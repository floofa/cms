<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Request extends Kohana_Request
{
  /**
   * Redirects as the request response. If the URL does not include a
   * protocol, it will be converted into a complete URL.
   *
   *     $request->redirect($url);
   *
   * [!!] No further processing can be done after this method is called!
   * 
   * [update] - $url = URL::site($url, TRUE, ! empty(Kohana::$index_file));
   *
   * @param   string   $url   Redirect location
   * @param   integer  $code  Status code: 301, 302, etc
   * @return  void
   * @uses    URL::site
   * @uses    Request::send_headers
   */
  public function redirect($url = '', $code = 302)
  {
    $referrer = $this->uri();

    if (strpos($referrer, '://') === FALSE)
    {
      $referrer = URL::site($referrer, TRUE, ! empty(Kohana::$index_file));
    }

    if (strpos($url, '://') === FALSE)
    {
      // Make the URI into a URL
      $url = URL::site($url, TRUE, ! empty(Kohana::$index_file));
    }

    if (($response = $this->response()) === NULL)
    {
      $response = $this->create_response();
    }

    echo $response->status($code)
      ->headers('Location', $url)
      ->headers('Referer', $referrer)
      ->send_headers()
      ->body();

    // Stop execution
    exit;
  }
  
  /**
  * vrati aktualni url
  */
  public static function initial_url($index = TRUE)
  {
    return URL::site(self::initial()->uri(), TRUE, $index);
  }
  
  /**
  * presmerovani na pozadovanou stranku
  */
  public static function redirect_initial($url = '', $code = 302)
  {
    self::initial()->redirect($url, $code);
  }
  
  /**
  * refreshuje stranku
  */
  public static function refresh_page()
  {
    self::redirect_initial(self::$initial->uri());
  }
}
