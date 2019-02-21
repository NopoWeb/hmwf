<?php namespace App\Controller;

class Blog extends \Controller{
    public function __construct(){
        parent::__construct();
        $this->view->name = 'Mister Juan';
    }
    function index(){
    }

    function any($slug){
        $data_path = ABSPATH.DS.'app'.DS.'data'.DS;
        $metadata_file = $data_path.$slug.'.meta.json';
        // Check if meta data exists
        if(file_exists($metadata_file)){
            $meta = json_decode(file_get_contents($metadata_file));
            if(json_last_error() == JSON_ERROR_NONE){
                if($meta->content->type == 'file'){
                    $this->view->title = $meta->title;
                    if(isset($meta->description)) $this->view->description = $meta->description;
                    if(isset($meta->images)) $this->view->images = $meta->images;
                    if(file_exists($data_path.$meta->content->file)){                        
                        $this->view->content = file_get_contents($data_path.$meta->content->file);
                    }
                }
                return $this->view->render('blog.single');
            }else{
                exit('Invalid JSON format');    
            }
        }else{
            exit('Data doesnt exists!');
        }        
    }
}