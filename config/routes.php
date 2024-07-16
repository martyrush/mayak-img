<?php
class Router {
    private $routes=[];
    private $appPath="";
    private $controllerPath="";
    private $modelPath="";

    public function __construct(){
        $this->appPath="/app/";
        $this->controllerPath=ROOT_PATH.$this->appPath."controllers/";
        $this->modelPath=ROOT_PATH.$this->appPath."models/";
    }

    public function addRoute($url,$controller)
    {
        $this->routes[$url]=$controller;
    }

    public function dispatch($url){
        
        if(array_key_exists($url,$this->routes)){
            $routesPart=[];
            if (strpos($this->routes[$url], '@') !== false) {
                $routesPart=explode('@',$this->routes[$url]);
            }else{
                $routesPart[0]=$this->routes[$url];
            }
            if(isset($routesPart[0]))
            {
                $controller=$routesPart[0]."Controller";
            }
            else{
                http_response_code(404);
                echo json_encode(['error' => 'Error url']);;
                exit;
            }
          
            if($this->loadController($controller)){
               
                $controllerObject=new $controller($this->routes[$url]);
                if(isset($routesPart[1])&& $routesPart[1]!=null)
                {
                    $method=$routesPart[1];
                    $controllerObject->$method();
                }else{
                $controllerObject->execute();
                }

            }
           else{
            
             http_response_code(404);
            echo json_encode(['error' => 'Controller not find']);;
            exit;
           
           }
        }
        else{
            http_response_code(404);
            echo json_encode(['error' => 'URL not find']);;
           exit;
        }
    }
    public function setAppPath($appPath)
    {
        $this->appPath=$appPath;
        $this->controllerPath=$this->appPath."controllers/";
    }
   private function loadController($contollerName)
   {
       
        if(file_exists($this->controllerPath.$contollerName.".php")){
            require_once($this->controllerPath.$contollerName.".php");
            return true;
        }
        return false;
   }
   private function loadModel($modelName)
   {
         if(file_exists($this->modelPath.$modelName.".php")){
        require_once($this->modelPath.$modelName.".php");
        return true;
        return false;
      }
    }
}
?>