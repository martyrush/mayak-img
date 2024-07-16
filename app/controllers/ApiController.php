<?php
    class ApiController{
        
        private $model;

        public function __construct()
        {
            require_once(ROOT_PATH.'/app/models/ApiModel.php');
            $this->model = new ApiModel();
        }
        public function parseImg(){
         $url="";
          header('Content-Type: application/json');

          $input = file_get_contents('php://input');
          $data = json_decode($input, true);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $data) {
            if(isset($data['url'])){
                $url=$data['url'];
            }
        }
            if(!filter_var($url, FILTER_VALIDATE_URL))
            {
               
                $response = [
                'status' => 404,
                'error' => 'Not Found url ',
                 'message' => 'Broken url '
                ];
                header('Content-Type: application/json');
                http_response_code(404);
                echo json_encode($response);
                 exit;
            }
            header('Content-Type: application/json');
            echo $this->model->getImages($url);
        }  
        public function execute(){
           
             $this->parseImg();
        }
        public function getSizeImages()
        {
            header('Content-Type: application/json');

          $input = file_get_contents('php://input');
          $data = json_decode($input, true);
            $img=[];
           if ($_SERVER['REQUEST_METHOD'] === 'POST' && $data) {
                if(isset($data['img'])){
                    $img=$data['img'];
                }
            
            }
          
            if ($img === null && json_last_error() !== JSON_ERROR_NONE) {
                header('Content-Type: application/json');
                echo json_encode("Error get size img");
            } else {
                
               echo $this->model->getImageSize($img);
            }

        }
        

    }
?>