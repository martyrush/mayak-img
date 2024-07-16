<?php

    class SearchController {
      
        private $view;
        public function __construct($controller){
            if(file_exists(ROOT_PATH."/app/views/".$controller."View.php"))
            {   
                require_once(ROOT_PATH."/app/views/".$controller."View.php");
                $viewClass=$controller."View";
                $this->view=new $viewClass();
            }  else{

            }
        }
       
        public function execute(){
          $this->view->render();
        }
    }

?>