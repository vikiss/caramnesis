<?php

class Text
{



/*function __construct() {

  $lang = Session::get('user_lang');
             
             //$database_user = $_SESSION['USER'];

        }*/


    private static $texts;
    
    public static function get($key)
    {
	    // if not $key
	    if (!$key) {
		    return null;
	    }

	    // load config file (this is only done once per application lifecycle)
        if (!self::$texts) {
        if (Session::get('user_lang')) $lang = Session::get('user_lang'); else $lang = Config::get('DEFAULT_LANGUAGE');
            self::$texts = require('../application/config/texts'.$lang.'.php');
        }

	    // check if array key exists
	    if (!array_key_exists($key, self::$texts)) {
		    return null;
	    }

        return self::$texts[$key];
    }

}
