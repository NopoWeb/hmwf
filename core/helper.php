<?php if(!defined('ABSPATH')) exit();
/**
 * Heroku Microsite Web Framework
 * @version 1.0
 * @author Juan Caser
*/

/**
 * Load php file
 * 
 * @param $name string
 * @param $return bool
 * @return any
 */
function load_php($name, $return = false){
    $file = str_replace(array('.'), DIRECTORY_SEPARATOR, $name);
    $file = ABSPATH.DIRECTORY_SEPARATOR.$file.'.php';
    if(file_exists($file)){
        if($return){
            return require $file;
        }else{
            include($file);
        }
    }
}