<?php

class DatabitModel
{

    public static function loadStructure($serialized, $errshow = false)
    {
        $errormsg = '';
	    libxml_use_internal_errors(true);
        $doc = simplexml_load_string($serialized);
        
        if ($doc === false) {
           $errors = libxml_get_errors();
            
            if($errshow) {
                $xml = explode("\n", $serialized);
               foreach ($errors as $error) {
                   $errormsg.= self::display_xml_error($error, $xml);
                }
                    Session::add('feedback_negative', $errormsg);                
            }

    libxml_clear_errors();
    return false;
        } else {
            return $doc;
        }
        
    }
    
    public static function display_xml_error($error, $xml)
{
    $return  = $xml[$error->line - 1] . "\n";
    $return .= str_repeat('-', $error->column) . "^\n";

    switch ($error->level) {
        case LIBXML_ERR_WARNING:
            $return .= "Warning $error->code: ";
            break;
         case LIBXML_ERR_ERROR:
            $return .= "Error $error->code: ";
            break;
        case LIBXML_ERR_FATAL:
            $return .= "Fatal Error $error->code: ";
            break;
    }

    $return .= trim($error->message) .
               "\n  Line: $error->line" .
               "\n  Column: $error->column";

    if ($error->file) {
        $return .= "\n  File: $error->file";
    }

    return "$return\n\n--------------------------------------------\n\n";
}

    

}

?>