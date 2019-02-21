<?php namespace App\Controller;

class Home extends \Controller{
    public function __construct(){
        parent::__construct();
        $this->view->name = 'Mister Juan';
    }
    function index(){    
        $this->view->title = 'Jan';
        return $this->view->display('home', array(
            'name' => 'Juan Caser'
        ));
    }
}