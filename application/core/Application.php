<?php

/**
 * Class Application
 * The heart of the application
 */
class Application
{
    /** @var mixed Instance of the controller */
    private $controller;

    /** @var array URL parameters, will be passed to used controller-method */
    private $parameters = array();

    /** @var string Just the name of the controller, useful for checks inside the view ("where am I ?") */
    private $controller_name;

    /** @var string Just the name of the controller's method, useful for checks inside the view ("where am I ?") */
    private $action_name;

    /**
     * Start the application, analyze URL elements, call according controller/method or relocate to fallback location
     */
    public function __construct()
    {
          // create array with URL parts in $url
        $this->splitUrl();

	    // creates controller and action names (from URL input)
	    $this->createControllerAndActionNames();

        // does such a controller exist ?
        if (file_exists(Config::get('PATH_CONTROLLER') . $this->controller_name . '.php')) {

            // load this file and create this controller
            // example: if controller would be "car", then this line would translate into: $this->car = new car();
            require Config::get('PATH_CONTROLLER') . $this->controller_name . '.php';
            $this->controller = new $this->controller_name();
            
			/* gettext */
            
    $available_locales = Config::get('AVAILABLE_LOCALES');            
    
    if ((Request::get('lang')) && (array_key_exists(strtolower(Request::get('lang')), $available_locales))) //change locale if passed by GET request  
    {
        $locale = $available_locales[strtolower(Request::get('lang'))];
    }
	elseif (
			(!Session::get('locale')) && (Session::get('user_lang')) && (array_key_exists(strtolower(Session::get('user_lang')), $available_locales))
		)  //set locale on logged in user session lang variable
    {
           $locale = $available_locales[strtolower(Session::get('user_lang'))];
    }
    elseif ((Session::get('locale')) && (in_array(Session::get('locale'), $available_locales)))  //set locale if stored in session
    {
        $locale = Session::get('locale');
    } 
    elseif (($_SERVER['HTTP_ACCEPT_LANGUAGE']) && (array_key_exists(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2), $available_locales)))  //set locale after users http request settings
    {
        $locale = $available_locales[substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)]; 
    }
    else
    {
        $locale = 'en_US.UTF-8'; //if all else fails, leave it in plain English
    }
   
Session::set('locale', $locale); 
putenv("LANGUAGE=".$locale);
putenv("LANG=".$locale);
putenv("LC_ALL=".$locale);
setlocale(LC_ALL, $locale);
$domain="messages";
bindtextdomain($domain, '/var/www/html/application/Locale');
bind_textdomain_codeset($domain, 'UTF-8');
textdomain($domain);  
            /* gettext */
			

            // check for method: does such a method exist in the controller ?
            if (method_exists($this->controller, $this->action_name)) {
                if (!empty($this->parameters)) {
                    // call the method and pass arguments to it
                    call_user_func_array(array($this->controller, $this->action_name), $this->parameters);
                } else {
                    // if no parameters are given, just call the method without parameters, like $this->index->index();
                    $this->controller->{$this->action_name}();
                }
            } else {
                // load 404 error page
				
                require Config::get('PATH_CONTROLLER') . 'ErrorController.php';
                $this->controller = new ErrorController;
                $this->controller->error404();
            }
        } else {
			
            // load 404 error page
			
			/*gettext again - oh no*/
$locale = 'en_US.UTF-8';			
Session::set('locale', $locale); 
putenv("LANGUAGE=".$locale);
putenv("LANG=".$locale);
putenv("LC_ALL=".$locale);
setlocale(LC_ALL, $locale);
$domain="messages";
bindtextdomain($domain, '/var/www/html/application/Locale');
bind_textdomain_codeset($domain, 'UTF-8');
textdomain($domain);  
			/*gettext again - oh no*/			
			
			
            require Config::get('PATH_CONTROLLER') . 'ErrorController.php';
            $this->controller = new ErrorController;
            $this->controller->error404();
        }
    }

    /**
     * Get and split the URL
     */
    private function splitUrl()
    {
        if (Request::get('url')) {

            // split URL
            $url = trim(Request::get('url'), '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);

            // put URL parts into according properties
            $this->controller_name = isset($url[0]) ? $url[0] : null;
            $this->action_name = isset($url[1]) ? $url[1] : null;

            // remove controller name and action name from the split URL
            unset($url[0], $url[1]);

            // rebase array keys and store the URL parameters
            $this->parameters = array_values($url);
        }
    }

	/**
	 * Checks if controller and action names are given. If not, default values are put into the properties.
	 * Also renames controller to usable name.
	 */
	private function createControllerAndActionNames()
	{
		// check for controller: no controller given ? then make controller = default controller (from config)
		if (!$this->controller_name) {
			$this->controller_name = Config::get('DEFAULT_CONTROLLER');
		}

		// check for action: no action given ? then make action = default action (from config)
		if (!$this->action_name OR (strlen($this->action_name) == 0)) {
			$this->action_name = Config::get('DEFAULT_ACTION');
		}

		// rename controller name to real controller class/file name ("index" to "IndexController")
		$this->controller_name = ucwords($this->controller_name) . 'Controller';
	}
}
