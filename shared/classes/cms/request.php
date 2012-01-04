<?php defined('SYSPATH') or die('No direct script access.');

class Cms_Request extends Kohana_Request
{
  public static $lang;
  
  public static function factory($uri = TRUE, HTTP_Cache $cache = NULL, $injected_routes = array())
  {
    $lang_config = Kohana::$config->load('lang');
    
    if ($lang_config['enabled']) {
      if (is_null(Request::initial())) {
        // current URI
        if ($uri === TRUE) {
          $uri = Request::detect_uri();
        }
        
        $uri = ltrim($uri, '/');
        
        if ( ! preg_match('~^(?:'.implode('|', array_keys($lang_config['languages'])).')(?=/|$)~i', $uri, $matches)) {
          // Find the best default language
          $lang = Lang::find_default();

            // Use the default server protocol
            $protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';

            // Redirect to the same URI, but with language prepended
            header($protocol.' 302 Found');
            header('Location: '.URL::base(TRUE, TRUE).$lang.'/'.$uri);

            // Stop execution
            exit;
        }
        
        // Language found in the URI
        Request::$lang = strtolower($matches[0]);

        // Store target language in I18n
        I18n::$lang = $lang_config['languages'][Request::$lang]['i18n'];
        
        // Set locale
        setlocale(LC_ALL, $lang_config['languages'][Request::$lang]['locale']);
        
        // Update language cookie if needed
        if (Cookie::get($lang_config['cookie']) !== Request::$lang)
          Cookie::set($lang_config['cookie'], Request::$lang);

        // Remove language from URI
        $uri = (string) substr($uri, strlen(Request::$lang));
      }
      else {
        $uri = ltrim($uri, '/');
        
        if (preg_match('~^(?:'.implode('|', array_keys($lang_config['languages'])).')(?=/|$)~i', $uri, $matches)) {
          $uri = (string) substr($uri, strlen($matches[0]));
        }
      }
    }
    
    return parent::factory($uri, $cache, $injected_routes);
  }
  
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
  public static function refresh_page($lang = FALSE)
  {
    self::redirect_initial(self::$initial->uri());
  }
}
