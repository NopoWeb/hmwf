<?php namespace App\Controller;

class AmbotLang extends \Controller{
    function index(){
        exit(__CLASS__);
    }
    function nimo($id){
        var_dump($id);
        exit(__CLASS__.'@'.__FUNCTION__);
    }
}