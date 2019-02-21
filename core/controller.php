<?php if(!defined('ABSPATH')) exit();

/**
 * Heroku Microsite Web Framework
 * @version 1.0
 * @author Juan Caser
 * 
 * Base controller
*/

abstract class Controller{   
    
    public function __construct(){
        $this->view = new View;
    }

    abstract function index();
}