<?php

/**
 * UserModel
 * Handles all the PUBLIC profile stuff. This is not for getting data of the logged in user, it's more for handling
 * data of all the other users. Useful for display profile information, creating user lists etc.
 */
class UserModel
{
    /**
     * Gets an array that contains all the users in the database. The array's keys are the user ids.
     * Each array element is an object, containing a specific user's data.
     * The avatar line is built using Ternary Operators, have a look here for more:
     * @see http://davidwalsh.name/php-shorthand-if-else-ternary-operators
     *
     * @return array The profiles of all users
     */
    public static function getPublicProfilesOfAllUsers()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT user_id, user_name, user_email, user_active, user_has_avatar, user_deleted FROM users";
        $query = $database->prepare($sql);
        $query->execute();

        $all_users_profiles = array();

        foreach ($query->fetchAll() as $user) {

            // all elements of array passed to Filter::XSSFilter for XSS sanitation, have a look into
            // application/core/Filter.php for more info on how to use. Removes (possibly bad) JavaScript etc from
            // the user's values
            array_walk_recursive($user, 'Filter::XSSFilter');

            $all_users_profiles[$user->user_id] = new stdClass();
            $all_users_profiles[$user->user_id]->user_id = $user->user_id;
            $all_users_profiles[$user->user_id]->user_name = $user->user_name;
            $all_users_profiles[$user->user_id]->user_email = $user->user_email;
            $all_users_profiles[$user->user_id]->user_active = $user->user_active;
            $all_users_profiles[$user->user_id]->user_deleted = $user->user_deleted;
            $all_users_profiles[$user->user_id]->user_avatar_link = (Config::get('USE_GRAVATAR') ? AvatarModel::getGravatarLinkByEmail($user->user_email) : AvatarModel::getPublicAvatarFilePathOfUser($user->user_has_avatar, $user->user_id));
        }

        return $all_users_profiles;
    }

    /**
     * Gets a user's profile data, according to the given $user_id
     * @param int $user_id The user's id
     * @return mixed The selected user's profile
     */
    public static function getPublicProfileOfUser($user_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT user_id, user_name, user_email, user_active, user_has_avatar, user_deleted
                FROM users WHERE user_id = :user_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':user_id' => $user_id));

        $user = $query->fetch();

        if ($query->rowCount() == 1) {
            if (Config::get('USE_GRAVATAR')) {
                $user->user_avatar_link = AvatarModel::getGravatarLinkByEmail($user->user_email);
            } else {
                $user->user_avatar_link = AvatarModel::getPublicAvatarFilePathOfUser($user->user_has_avatar, $user->user_id);
            }
        } else {
            Session::add('feedback_negative', _('FEEDBACK_USER_DOES_NOT_EXIST'));
        }

        // all elements of array passed to Filter::XSSFilter for XSS sanitation, have a look into
        // application/core/Filter.php for more info on how to use. Removes (possibly bad) JavaScript etc from
        // the user's values
        array_walk_recursive($user, 'Filter::XSSFilter');

        return $user;
    }

    /**
     * @param $user_name_or_email
     *
     * @return mixed
     */
    public static function getUserDataByUserNameOrEmail($user_name_or_email)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT user_id, user_name, user_email, user_uuid, user_lang  FROM users
                                     WHERE (user_name = :user_name_or_email OR user_email = :user_name_or_email)
                                           AND user_provider_type = :provider_type LIMIT 1");
        $query->execute(array(':user_name_or_email' => $user_name_or_email, ':provider_type' => 'DEFAULT'));

        return $query->fetch();
    } 

    /**
     * Checks if a username is already taken
     *
     * @param $user_name string username
     *
     * @return bool
     */
    public static function doesUsernameAlreadyExist($user_name)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT user_id FROM users WHERE user_name = :user_name LIMIT 1");
        $query->execute(array(':user_name' => $user_name));
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;
    }

    /**
     * Checks if a email is already used
     *
     * @param $user_email string email
     *
     * @return bool
     */
    public static function doesEmailAlreadyExist($user_email)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT user_id FROM users WHERE user_email = :user_email LIMIT 1");
        $query->execute(array(':user_email' => $user_email));
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;
    }

    /**
     * Writes new username to database
     *
     * @param $user_id int user id
     * @param $new_user_name string new username
     *
     * @return bool
     */
    public static function saveNewUserName($user_id, $new_user_name)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("UPDATE users SET user_name = :user_name WHERE user_id = :user_id LIMIT 1");
        $query->execute(array(':user_name' => $new_user_name, ':user_id' => $user_id));
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }

    /**
     * Writes new email address to database
     *
     * @param $user_id int user id
     * @param $new_user_email string new email address
     *
     * @return bool
     */
    public static function saveNewEmailAddress($user_id, $new_user_email)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("UPDATE users SET user_email = :user_email WHERE user_id = :user_id LIMIT 1");
        $query->execute(array(':user_email' => $new_user_email, ':user_id' => $user_id));
        $count = $query->rowCount();
        if ($count == 1) {
            return true;
        }
        return false;
    }

    /**
     * Edit the user's name, provided in the editing form
     *
     * @param $new_user_name string The new username
     *
     * @return bool success status
     */
    public static function editUserName($new_user_name)
    {
        // new username same as old one ?
        if ($new_user_name == Session::get('user_name')) {
            Session::add('feedback_negative', _('FEEDBACK_USERNAME_SAME_AS_OLD_ONE'));
            return false;
        }

        // username cannot be empty and must be azAZ09 and 2-64 characters
        if (!preg_match("/^[a-zA-Z0-9]{2,64}$/", $new_user_name)) {
            Session::add('feedback_negative', _('FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN'));
            return false;
        }

        // clean the input, strip usernames longer than 64 chars (maybe fix this ?)
        $new_user_name = substr(strip_tags($new_user_name), 0, 64);

        // check if new username already exists
        if (self::doesUsernameAlreadyExist($new_user_name)) {
            Session::add('feedback_negative', _('FEEDBACK_USERNAME_ALREADY_TAKEN'));
            return false;
        }

        $status_of_action = self::saveNewUserName(Session::get('user_id'), $new_user_name);
        if ($status_of_action) {
            Session::set('user_name', $new_user_name);
            Session::add('feedback_positive', _('FEEDBACK_USERNAME_CHANGE_SUCCESSFUL'));
            return true;
        } else {
            Session::add('feedback_negative', _('FEEDBACK_UNKNOWN_ERROR'));
            return false;
        }
    }

    /**
     * Edit the user's email
     *
     * @param $new_user_email
     *
     * @return bool success status
     */
    public static function editUserEmail($new_user_email)
    {
        // email provided ?
        if (empty($new_user_email)) {
            Session::add('feedback_negative', _('FEEDBACK_EMAIL_FIELD_EMPTY'));
            return false;
        }

        // check if new email is same like the old one
        if ($new_user_email == Session::get('user_email')) {
            Session::add('feedback_negative', _('FEEDBACK_EMAIL_SAME_AS_OLD_ONE'));
            return false;
        }

        // user's email must be in valid email format, also checks the length
        // @see http://stackoverflow.com/questions/21631366/php-filter-validate-email-max-length
        // @see http://stackoverflow.com/questions/386294/what-is-the-maximum-length-of-a-valid-email-address
        if (!filter_var($new_user_email, FILTER_VALIDATE_EMAIL)) {
            Session::add('feedback_negative', _('FEEDBACK_EMAIL_DOES_NOT_FIT_PATTERN'));
            return false;
        }

        // strip tags, just to be sure
        $new_user_email = substr(strip_tags($new_user_email), 0, 254);

        // check if user's email already exists
        if (self::doesEmailAlreadyExist($new_user_email)) {
            Session::add('feedback_negative', _('FEEDBACK_USER_EMAIL_ALREADY_TAKEN'));
            return false;
        }

        // write to database, if successful ...
        // ... then write new email to session, Gravatar too (as this relies to the user's email address)
        if (self::saveNewEmailAddress(Session::get('user_id'), $new_user_email)) {
            Session::set('user_email', $new_user_email);
            //Session::set('user_gravatar_image_url', AvatarModel::getGravatarLinkByEmail($new_user_email));
            Session::add('feedback_positive', _('FEEDBACK_EMAIL_CHANGE_SUCCESSFUL'));
            return true;
        }

        Session::add('feedback_negative', _('FEEDBACK_UNKNOWN_ERROR'));
        return false;
    }

    /**
     * Gets the user's id
     *
     * @param $user_name
     *
     * @return mixed
     */
    public static function getUserIdByUsername($user_name)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT user_id FROM users WHERE user_name = :user_name AND user_provider_type = :provider_type LIMIT 1";
        $query = $database->prepare($sql);

        // DEFAULT is the marker for "normal" accounts (that have a password etc.)
        // There are other types of accounts that don't have passwords etc. (FACEBOOK)
        $query->execute(array(':user_name' => $user_name, ':provider_type' => 'DEFAULT'));

        // return one row (we only have one result or nothing)
        return $query->fetch()->user_id;
    }

    /**
     * Gets the user's data
     *
     * @param $user_name string User's name
     *
     * @return mixed Returns false if user does not exist, returns object with user's data when user exists
     */
    public static function getUserDataByUsername($user_name)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT user_id, user_name, user_email, user_password_hash, user_active,user_deleted, user_suspension_timestamp, user_account_type,
                       user_failed_logins, user_last_failed_login, user_uuid, user_lang, user_currency, user_distance, user_cons, unread_messages
                  FROM users
                 WHERE (user_name = :user_name OR user_email = :user_name)
                       AND user_provider_type = :provider_type
                 LIMIT 1";
        $query = $database->prepare($sql);

        // DEFAULT is the marker for "normal" accounts (that have a password etc.)
        // There are other types of accounts that don't have passwords etc. (FACEBOOK)
        $query->execute(array(':user_name' => $user_name, ':provider_type' => 'DEFAULT'));

        // return one row (we only have one result or nothing)
        return $query->fetch();
    }

    /**
     * Gets the user's data by user's id and a token (used by login-via-cookie process)
     *
     * @param $user_id
     * @param $token
     *
     * @return mixed Returns false if user does not exist, returns object with user's data when user exists
     */
    public static function getUserDataByUserIdAndToken($user_id, $token)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        // get real token from database (and all other data)
        $query = $database->prepare("SELECT user_id, user_name, user_email, user_password_hash, user_active,
                                          user_account_type,  user_has_avatar, user_failed_logins, user_last_failed_login, user_uuid, user_lang, user_currency, user_distance, user_cons, unread_messages
                                     FROM users
                                     WHERE user_id = :user_id
                                       AND user_remember_me_token = :user_remember_me_token
                                       AND user_remember_me_token IS NOT NULL
                                       AND user_provider_type = :provider_type LIMIT 1");
        $query->execute(array(':user_id' => $user_id, ':user_remember_me_token' => $token, ':provider_type' => 'DEFAULT'));

        // return one row (we only have one result or nothing)
        return $query->fetch();
    }
    
    
    public static function getUserDataByUuid($uuid)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT *
                  FROM users
                 WHERE (user_uuid = :user_uuid)
                 LIMIT 1";
        $query = $database->prepare($sql);
                
        $query->execute(array(':user_uuid' => $uuid));

        
        return $query->fetch();
    }
    
       public static function setLanguage($lang, $uuid)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $available_locales = Config::get('AVAILABLE_LOCALES');
        if (array_key_exists(strtolower($lang), $available_locales)) {
        $query = $database->prepare("UPDATE users SET user_lang = :user_lang WHERE user_uuid = :user_uuid LIMIT 1");
        $query->execute(array(':user_lang' => strtoupper($lang), ':user_uuid' => $uuid));
        $count = $query->rowCount();
        if ($count == 1) {
            Session::set('user_lang', strtoupper($lang));
            Session::set('locale', '');
            Session::add('feedback_positive', _('FEEDBACK_LANGUAGE_CHANGE_SUCCESSFUL'));
            return true;
        }}
        Session::add('feedback_negative', _('FEEDBACK_LANGUAGE_CHANGE_FAILED'));
        return false;
    }
    
            public static function getUserLanguageByUUid($uuid) {
                
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT user_lang
                                     FROM users
                                     WHERE user_uuid = :user_uuid LIMIT 1");
        $query->execute(array(':user_uuid' => $uuid));
        // return one row (we only have one result or nothing)
        if ($result = $query->fetch()) {
            return $result->user_lang;
        } else {return false;}
                
            }
    
          public static function setCurrency($currency, $uuid)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $available_currencies = Config::get('CURRENCIES'); 
        if (in_array(strtoupper($currency), $available_currencies)) {
        $query = $database->prepare("UPDATE users SET user_currency = :user_currency WHERE user_uuid = :user_uuid LIMIT 1");
        $query->execute(array(':user_currency' => strtoupper($currency), ':user_uuid' => $uuid));
        $count = $query->rowCount();
        if ($count == 1) {
            Session::set('user_currency', strtoupper($currency));
            Session::add('feedback_positive', _('FEEDBACK_CURRENCY_CHANGE_SUCCESSFUL'));
            return true;
        }}
        Session::add('feedback_negative', _('FEEDBACK_CURRENCY_CHANGE_FAILED'));
        return false;
    }
    
    
    public static function setDistanceUnit($distance_unit, $uuid)//user_currency, user_distance, user_cons
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $preset_units = Config::get('DISTANCE_UNITS');
        if (in_array($distance_unit, $preset_units)) {
        $query = $database->prepare("UPDATE users SET user_distance = :user_distance WHERE user_uuid = :user_uuid LIMIT 1");
        $query->execute(array(':user_distance' => $distance_unit, ':user_uuid' => $uuid));
        $count = $query->rowCount();
        if ($count == 1) {
            Session::set('user_distance', $distance_unit);
            Session::add('feedback_positive', _('FEEDBACK_DISTANCE_UNIT_CHANGE_SUCCESSFUL'));
            return true;
        }}
        Session::add('feedback_negative', _('FEEDBACK_DISTANCE_UNIT_CHANGE_FAILED'));
        return false;
    }
    
    public static function setConsumptionUnit($cons_unit, $uuid)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $preset_units = Config::get('CONSUMPTION_UNITS');
        if (in_array($cons_unit, $preset_units)) {
        $query = $database->prepare("UPDATE users SET user_cons = :user_cons WHERE user_uuid = :user_uuid LIMIT 1");
        $query->execute(array(':user_cons' => $cons_unit, ':user_uuid' => $uuid));
        $count = $query->rowCount();
        if ($count == 1) {
            Session::set('user_cons', $cons_unit);
            Session::add('feedback_positive', _('FEEDBACK_CONSUMPTION_UNIT_CHANGE_SUCCESSFUL'));
            return true;
        }}
        Session::add('feedback_negative', _('FEEDBACK_CONSUMPTION_UNIT_CHANGE_FAILED'));
        return false;
    }
    
    public static function getCountryList() {
        
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT DISTINCT Country FROM worldcities;";
        $query = $database->prepare($sql);
        $query->execute();
        return $query->fetchAll();
        
    }
    
    public static function getRegionList($country_id) {
        
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT DISTINCT Region FROM worldcities WHERE Country = :country_id;");
        $query->execute(array(':country_id' => $country_id));
        $regions = $query->fetchAll();
        $result = array();
        foreach ($regions AS $region) {
            if (is_numeric($region->Region)) { 
                $result[$region->Region] = self::getRegionName($country_id, $region->Region);
            } else {
                $result[$region->Region] = $region->Region;
            }
        }
        asort($result);        
        return $result;
    }
    
    public static function getRegionName($country_id, $region_id) {  //find out largest city in te region to pass as region name
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT AccentCity FROM worldcities WHERE Country = :country_id AND Region = :region_id ORDER BY Population DESC LIMIT 1;");
        $query->execute(array(':country_id' => $country_id, ':region_id' => $region_id));               
        $city = $query->fetch(); $city = $city->AccentCity;
        return $city;
    }
    
      public static function getCityList($country_id, $region_id) {
        
        $database = DatabaseFactory::getFactory()->getConnection();
        if (strlen($region_id) > 1) {
            $query = $database->prepare("Select City, AccentCity FROM worldcities WHERE Country = :country_id AND Region = :region_id;");
            $query->execute(array(':country_id' => $country_id, ':region_id' => $region_id));    
        } else {
            $query = $database->prepare("SELECT City, AccentCity, Region FROM worldcities WHERE Country = :country_id;");
            $query->execute(array(':country_id' => $country_id));    
        }
        return $query->fetchAll();
        
    }
    
    public static function getCountry($uuid) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT country FROM users WHERE user_uuid = :user_uuid;");
        $query->execute(array(':user_uuid' => $uuid));
        $result = $query->fetch();
        return $result->country;
    }
    
     public static function setCountry($country_id, $uuid, $old_country='')
    {
        if ($country_id == $old_country) {return true;} //no change, do nothing
        $countries = self::getCountryList(''); $country_id_ok = false;
        foreach ($countries as $country) {
            if ($country->Country == $country_id) {$country_id_ok = true; break;}
        }
        
        if ($country_id_ok) { //we want to reset region and hometown as well if the user changes country
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("UPDATE users SET country = :country, state_id = '', state = '', hometown = '' WHERE user_uuid = :user_uuid LIMIT 1");
        $query->execute(array(':country' => $country_id, ':user_uuid' => $uuid));
        $count = $query->rowCount();
        if ($count == 1) {
            Session::add('feedback_positive', _('FEEDBACK_COUNTRY_CHANGE_SUCCESSFUL'));
            return true;
        }}
        Session::add('feedback_negative', _('FEEDBACK_COUNTRY_CHANGE_FAILED'));
        return false;
    }
    
      public static function setRegion($state_id, $uuid, $old_state_id = '')
    {
        if ($state_id == $old_state_id) {return true;} //no change, do nothing
        $country = self::getCountry($uuid);
        $regions = self::getRegionList($country); $region_id_ok = false; $thisregion = '';
        foreach ($regions as $region_key => $region) {
            if ($region_key == $state_id) {$region_id_ok = true; $thisregion = $region; break;}
        } 
        
        if ($region_id_ok) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("UPDATE users SET state_id = :state_id, state = :state, hometown = '' WHERE user_uuid = :user_uuid LIMIT 1");
        $query->execute(array(':state_id' => $state_id, ':state' => $thisregion, ':user_uuid' => $uuid));
        $count = $query->rowCount();
        if ($count == 1) {
            Session::add('feedback_positive', _('FEEDBACK_REGION_CHANGE_SUCCESSFUL'));
            return true;
        }}
        Session::add('feedback_negative', _('FEEDBACK_REGION_CHANGE_FAILED'));
        return false;
    }
    
      public static function resetRegion($uuid)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("UPDATE users SET state_id = '' WHERE user_uuid = :user_uuid LIMIT 1");
        $query->execute(array(':user_uuid' => $uuid));
        $count = $query->rowCount();
        if ($count == 1) {
            Session::add('feedback_positive', _('FEEDBACK_REGION_RESET_SUCCESSFUL'));
            return true;
        }
        Session::add('feedback_negative', _('FEEDBACK_REGION_RESET_FAILED'));
        return false;
    }
    
      public static function setGeoCoords($lat, $lng, $uuid)
    {
        if ((is_numeric($lat)) && (is_numeric($lng))) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("UPDATE users SET geolat = :lat, geolng = :lng WHERE user_uuid = :user_uuid LIMIT 1");
        $query->execute(array(':lat' => $lat, ':lng' => $lng, ':user_uuid' => $uuid));
        $count = $query->rowCount();
        if ($count == 1) {
            return true;
        }}
        return false;
    }
    
    public static function getGeoCoords($uuid) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT geolat, geolng FROM users WHERE user_uuid = :user_uuid LIMIT 1;");
        $query->execute(array(':user_uuid' => $uuid));
        $result = $query->fetch();
        if (($result->geolat > 0) && ($result->geolng > 0)) return $result; else return false;
    }
    
    public static function getCityCoords($city, $region, $uuid) {
        $country = self::getCountry($uuid);
        $database = DatabaseFactory::getFactory()->getConnection();
        if (strlen($region) > 1) {
        $query = $database->prepare("SELECT Latitude, Longitude, AccentCity FROM worldcities WHERE Country = :country AND City = :city AND Region = :region ORDER BY Population DESC LIMIT 1;");
        $query->execute(array(':country' => $country, ':city' => $city, ':region' => $region));
                                } else {
        $query = $database->prepare("SELECT Latitude, Longitude FROM worldcities WHERE Country = :country AND City = :city ORDER BY Population DESC LIMIT 1;");
        $query->execute(array(':country' => $country, ':city' => $city));                                    
                                }
        $result = $query->fetch();
        if (($result->Latitude > 0) && ($result->Longitude > 0)) return $result; else return false;
        
    }
    
    
    
    public static function setCity($city, $region, $uuid) {
        
        if ($cityCoords = self::getCityCoords($city, $region, $uuid)) {
            
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("UPDATE users SET geolat = :lat, geolng = :lng, hometown = :city WHERE user_uuid = :user_uuid LIMIT 1");
        $query->execute(array(':lat' => $cityCoords->Latitude, ':lng' => $cityCoords->Longitude, ':city' => $cityCoords->AccentCity ,':user_uuid' => $uuid));
        $count = $query->rowCount();
        if ($count == 1) {
            Session::add('feedback_positive', _('FEEDBACK_CITY_ADD_SUCCESSFUL'));
            return true;
        }}
        Session::add('feedback_negative', _('FEEDBACK_CITY_ADD_FAILED'));
        return false;
    }
    
    public static function getLocationByCoords($lat, $lng, $distance=25) { //distance in miles
        if ((is_numeric($lat)) && (is_numeric($lng))) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT Country, City, AccentCity, Region, Latitude, Longitude, SQRT(
                                    POW(69.1 * (Latitude - :lat), 2) +
                                    POW(69.1 * (:lng - Longitude) * COS(Latitude / 57.3), 2)) AS distance
                                    FROM worldcities HAVING distance < :distance ORDER BY distance LIMIT 1;
                                    ");
        $query->execute(array(':lat' => $lat, ':lng' => $lng, ':distance' => $distance));
        $result = $query->fetch();
        return $result;
        }
        else return false;
        
    }
    
    
       public static function setLocationByCoords($lat, $lng, $uuid) {
        if ((is_numeric($lat)) && (is_numeric($lng))) {
        if ($place = self::getLocationByCoords($lat, $lng)) {
        $state_name = self::getRegionName($place->Country, $place->Region);
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("UPDATE users SET country = :country, state = :state, state_id = :state_id, hometown = :city WHERE user_uuid = :user_uuid LIMIT 1");
        $query->execute(array(':country' => $place->Country, ':state' => $state_name, 'state_id' => $place->Region, ':city' => $place->AccentCity ,':user_uuid' => $uuid));
        $count = $query->rowCount();
        if ($count == 1) {
            return true;
        }}}
        Session::add('feedback_negative', _('FEEDBACK_UNKNOWN_ERROR'));
        return false;
    }
    
    public static function getCurrentLanguage() {
        return array_search (Session::get('locale'), Config::get('AVAILABLE_LOCALES'));
    }
    
    public static function getUserNameByUUid($uuid)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT user_name FROM users
                                     WHERE user_uuid = :uuid LIMIT 1");
        $query->execute(array(':uuid' => $uuid));
        $result = $query->fetch();
        if ($result) {
        return $result->user_name;
        } else {return false;}
    }
    
    
    public static function getEmailByUUid($uuid)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT user_email FROM users
                                     WHERE user_uuid = :uuid LIMIT 1");
        $query->execute(array(':uuid' => $uuid));
        $result = $query->fetch();
        if ($result) {
        return $result->user_email;
        } else {return false;}
    }
    
    public static function getUUidByUserName($user_name)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT user_uuid FROM users
                                     WHERE user_name = :name LIMIT 1");
        $query->execute(array(':name' => $user_name));
        $result = $query->fetch();
        if ($result) {
            return $result->user_uuid;
        } else {return false;}
    }
    
        public static function getUserUnits($uuid)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT user_currency, user_distance, user_cons FROM users WHERE user_uuid = :user_uuid LIMIT 1");
        $query->execute(array(':user_uuid' => $uuid));
        $count = $query->rowCount();
        if ($count == 1) {
            return $query->fetch();
        }
        Session::add('feedback_negative', _('CANNOT_GET_USER_UNITS'));
        return false;
    }
    
              public static function setLastSeen($uuid)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("UPDATE users SET last_seen = :last_seen WHERE user_uuid = :user_uuid LIMIT 1");
        $query->execute(array(':last_seen' => time(), ':user_uuid' => $uuid));
        $count = $query->rowCount();
        if ($count == 1) {
            return true;
        }
        Session::add('feedback_negative', _('FEEDBACK_CURRENCY_CHANGE_FAILED'));
        return false;
    }
    
    
     public static function getLastSeen($uuid)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT last_seen FROM users WHERE user_uuid = :user_uuid LIMIT 1");
                $query->execute(array(':user_uuid' => $uuid));
        $count = $query->rowCount();
        if ($count == 1) {
            $result = $query->fetch();
            return $result->last_seen;
        }
                return false;
    }

    
    
    
}
