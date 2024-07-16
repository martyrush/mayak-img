<?php 

 class ParserController{

    private $view;

    public function __construct($controller){
        
        
       if(file_exists(ROOT_PATH."/app/views/".$controller."View.php"))
        {  
    
        require_once(ROOT_PATH."/app/views/".$controller."View.php");
        $viewClass=$controller."View";
        $this->view = new $viewClass();
        }
        else{
            die("Model or View not found");
       }
        
    }
   
      
    public function execute(){
    
         $this->view->render();   

    }
 
 }
?>