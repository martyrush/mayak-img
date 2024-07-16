<?php 
  require 'vendor/autoload.php'; 

  use GuzzleHttp\Client;
  use GuzzleHttp\Pool;
  use GuzzleHttp\Psr7\Request;
  use GuzzleHttp\Promise\Utils; 
  use GuzzleHttp\Exception\RequestException;
class ApiModel
    {
       private $client;
        public function __construct()
        {
            $this->client = new Client();
        }
        //add url to matches
        public function getImages($url)
        {
            $html= file_get_contents($url);
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($html);
            libxml_clear_errors();
            $domain=$this->getBaseUrl($url);

            $images = @$dom->getElementsByTagName('img');
            $imgSrcList = [];
            foreach ($images as $img) {
                $src = $img->getAttribute('src');
                if (!empty($src)) {
                    
                    if(!filter_var($src, FILTER_VALIDATE_URL))
                    {
                        $src=$this->checkSlash($src);
                        $imgSrcList[] = $domain.$src;
                    }
                    else{
                        $imgSrcList[] = $src;
                    }
                }
            }
            $cssSrcList = [];
            preg_match_all('/background(-image)?\s*:\s*url\((["\']?)(.*?)\2\)/', $html, $matches);
            if (!empty($matches[3])) {
                foreach($matches[3] as $match)
                {
                    if(!filter_var($match, FILTER_VALIDATE_URL))
                    {
                        $urlImg=$this->checkSlash($match);
                        $s=$domain.$urlImg;
                        if(!filter_var($s, FILTER_VALIDATE_URL))
                        {
                            if($this->tryToLoadImg($s))
                            $cssSrcList[]=$s;
                        }
                    }else{
                        $cssSrcList[] = $match;
                    }
                }
                
              
            }
            preg_match_all('/background(-image)?\s*:\s*(url\(("|\')(.*?)("|\')\)|("|\')(.*?)("|\'))/', $html, $matches2);
            if (!empty($matches2[3])) {
                foreach ($matches2[3] as $match) {
                   
                    if(!filter_var($match, FILTER_VALIDATE_URL))
                    {
                        $urlImg=$this->checkSlash($match);
                        $s=$domain.$urlImg;
                        if($this->tryToLoadImg($s))
                        {
                            $cssSrcList[]=$s;
                        }
                    }else{
                         $cssSrcList[] =$match;
                    }
                }
            }
            try {
            $srcList = array_merge($imgSrcList, $cssSrcList);
            }catch(Exception $e){
                header('Content-Type: application/json');
                echo json_encode("Error! image array");
                exit;
            }
            header('Content-Type: application/json');
            echo json_encode($srcList);
        }
       
        public function getImageSize($images)
        {
            
            header('Content-Type: application/json');
            $size =$this->calcImgSize($images);
            $format = $this->formatBytes($size);
            echo json_encode($format);
        }
        private function calcImgSize($images)
        {
            $promises = [];
           foreach($images as $image) {
                
                $promises[] = $this->getImageSizeFromUrlAsync($this->client, $image);
           }
           $results = Utils::settle($promises)->wait();
           $totalSize = 0;

            foreach ($results as $result) {
                if ($result['state'] === 'fulfilled') {
                    $totalSize += intval($result['value']);
                }
            }
            return $totalSize;
        }
        private function getImageSizeFromUrlAsync($client, $imageUrl) {
            return $client->headAsync($imageUrl)->then(function($response) {
                return $response->getHeader('Content-Length')[0] ?? 0;
            });
        }
      
        private function formatBytes($bytes, $precision = 2) {
            $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);
        
            $bytes /= pow(1024, $pow);
        
            return round($bytes, $precision) . ' ' . $units[$pow];
        }
        public function isValidImageUrl($url) {
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return false;
            }
           return true;
        }
        public function tryToLoadImg($url)
        {
            try {
                $response = $this->client->request('HEAD', $url);
                $contentType = $response->getHeaderLine('Content-Type');
                return strpos($contentType, 'image/') === 0;
            } catch (RequestException $e) {

                return false;
            }

        }
        public function getDomainFromUrl($url) {
            $parsedUrl = parse_url($url);
            return isset($parsedUrl['host']) ? $parsedUrl['host'] : null;
        }
        public function getBaseUrl($url) {
            $parsedUrl = parse_url($url);
            if (isset($parsedUrl['scheme']) && isset($parsedUrl['host'])) {
                $baseUrl = sprintf('%s://%s', $parsedUrl['scheme'], $parsedUrl['host']);
                return $baseUrl;
            }
        
            return null;
        }
        public function process_url($url) {
            if (preg_match('/\/[^\/]+\.[^\/]+$/', $url)) {
                return preg_replace('/\/[^\/]+\.[^\/]+$/', '', $url);
            } else {
                return rtrim($url, '/');
            }
        }
        private function checkSlash($src){
            $new_url = preg_replace("/(\.\.\/)+/", "", $src);
        if (substr($new_url, 0, 1) != '/') {  
            $new_url = "/" . $new_url;
            }
            return $new_url;
        }
        
    }
?>