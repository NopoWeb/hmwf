<?php
/**
 * Heroku Microsite Web Framework
 * @version 1.0
 * @author Juan Caser
*/
// Define absolute path
if(!defined('ABSPATH')) define('ABSPATH', dirname(__DIR__));
define('DS', DIRECTORY_SEPARATOR);
define('VIEW_DIR', ABSPATH.DS.'app'.DS.'view');

/**
 * Initialize PSR-4
 */
spl_autoload_register(function($class){    
    $cls = strtolower($class);
    $cls = str_replace('\\',DS,$cls);
    $cls = trim($cls, DS);

    if(file_exists(ABSPATH.DS.$cls.'.php')){
        include_once(ABSPATH.DS.$cls.'.php');
    }elseif(file_exists(ABSPATH.DS.'core'.DS.$cls.'.php')){
        include_once(ABSPATH.DS.'core'.DS.$cls.'.php');
    }elseif(file_exists(ABSPATH.DS.'app'.DS.$cls.'.php')){
        include_once(ABSPATH.DS.'app'.DS.$cls.'.php');
    }
});


// Parse routes to controller
$raw_uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),'/');

// Extend some exception class
class PageNotFound extends Exception{}

try {
    if(empty($raw_uri)){
        $class = 'Home';
        $method = 'index';
        $args = null;
    }else{
        $fragments = explode('/',$raw_uri);
        $class = array_shift($fragments);
        $class = ucwords(str_replace(array('_','-'),' ',$class));
        $class = str_replace(' ','',$class);

        $method = (is_array($fragments) && count($fragments) > 0 ? array_shift($fragments) : 'index');
        $args = (is_array($fragments) && count($fragments) > 0 ? $fragments : null);
    }
    
    $class = 'App\\Controller\\'.$class;

    // Use ReflectionClass as much as possible
    if(class_exists('ReflectionClass')){   
        try {
            $ref = new ReflectionClass($class);
            $obj = $ref->newInstance();
        } catch (ReflectionException $exception) {
            throw new PageNotFound($exception->getMessage());            
        }     
    }else{       
        $obj = new $class;
    }
    if(!method_exists($obj,$method)) $method = 'any';
    if(method_exists($obj,$method)){
        if($method == 'any'){
            $args = explode('/',$raw_uri);
            array_shift($args);
        }
        header($_SERVER['SERVER_PROTOCOL']." 200 Ok");
        if(is_null($args)){
            echo call_user_func(array($obj,$method));
        }else{
            echo call_user_func_array(array($obj,$method), $args);
        }
    }else{
        throw new PageNotFound($method.' not found!');
    }
} catch (PageNotFound $exception) {
    header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
    $view = new View;    
    echo $view->display('error.404', array(
        'error_message' => $exception->getMessage()
    ));
    die();
}

