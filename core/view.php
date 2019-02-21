<?php if(!defined('ABSPATH')) exit();
/**
 * Heroku Microsite Web Framework
 * @version 1.0
 * @author Juan Caser
 * 
 * View class
*/

class View{
    public $path = null;
    private $variables = array();
    private $sections = array();

    public function __construct(){
        $this->path = (defined('VIEW_DIR') ? VIEW_DIR : ABSPATH.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'view');
    }

    /**
     *  Returns the generated views
     * @uses display()
    */
    public function render($name, array $arguments = null){
        ob_start();
        $this->display($name, $arguments);
        $html = ob_get_clean();            
        return $html;
    }

    /**
     * Displays the generated views
     * 
     * @param $name string
     * @param array $arguments
     * @return string
    */
    public function display($name, array $arguments = null){        
        $view_file = $this->load_view($name);
        if($view_file != false){                                    
            include($view_file);
        }
    }

    /**
     * Add section on any parts of the view template, usefull for adding section on header from main template
     * 
     * @param $name string
     * @param $callback callable
     * @param $priority int
    */
    public function add_section($name, callable $callback = null, $priority = 10){
        if(!is_null($callback)){
            $this->sections[$name][$priority][] = $callback;
        }
    }

    public function section($name){
        if(isset($this->sections[$name])){
            ksort($this->sections[$name]);
            foreach($this->sections[$name] as $sections){
                foreach($sections as $section){
                    call_user_func($section);
                }
            }
        }        
    }

    /**
     * Locate and load the view
     * 
     * @param string $name
     * @return string
     */    
    
    private function load_view($name){
        $view = explode('.',$name);
        $view = implode(DIRECTORY_SEPARATOR, $view);
        $view = $this->path.DIRECTORY_SEPARATOR.$view.'.php';
        if(file_exists($view)){
            return $view;
        }else{
            return false;
        }
    }


    /**
     * Magic methods getter and setter
    */
    public function __set($key, $value){
        $this->variables[$key] = $value;
    }

    public function __get($key){
        if(isset($this->variables[$key])) return $this->variables[$key];
        return false;
    }
}