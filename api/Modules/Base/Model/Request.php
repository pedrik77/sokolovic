<?php

namespace Tulic\aPiE\Base;

/**
 * Description of Request
 *
 * @author Peter Tulic
 */
class Request
{
     private $urlParams = [];
     private $count = 0;

     private $method = '';

     private $input = null;
     private $files = null;
     private $queryString = [];

     private $contentType = '';
     private $authorization = '';

     public function __construct()
     {
          $headers = apache_request_headers();
          $urlParams = $this->parseUrl($_SERVER['REQUEST_URI']);

          //api or localhost
          array_shift($urlParams);
          //api or localhost

          $this->urlParams = $urlParams;
          $this->contentType = (isset($headers['Content-Type']) ? $headers['Content-Type'] : '');
          $this->authorization = (isset($headers['Authorization']) ? trim(str_replace('Bearer', '', $headers['Authorization'])) : '');
          $this->queryString = $_GET;
          $this->method = $_SERVER["REQUEST_METHOD"];

          if ($this->method == 'POST' || $this->method == 'PUT') {
               if (strpos($this->contentType, 'form-data') !== false) {
                    $input = $_POST;
                    $this->files = $_FILES;
                    $input = $input;
               } else {
                    $input = json_decode(file_get_contents('php://input'), true);
               }
               $this->input = $input;
          }
     }

     private function parseUrl($url)
     {
          $parsedUrl = parse_url($url);
          $parsedUrl = trim($parsedUrl['path'], '/');
          $parsedUrl = trim($parsedUrl);

          $explodedUrl = explode('/', $parsedUrl);

          return $explodedUrl;
     }

     public function getUrlParams()
     {
          return $this->urlParams;
     }

     public function getNextParam($holdPosition = false)
     {
          $next = isset($this->urlParams[$this->count]) ? $this->urlParams[$this->count] : null;
          if (!$holdPosition) {
               $this->count++;
          }
          return $next;
     }

     public function getMethod()
     {
          return $this->method;
     }

     public function getInput()
     {
          return $this->input;
     }

     public function getFiles()
     {
          return $this->files;
     }

     public function getContentType()
     {
          return $this->contentType;
     }

     public function getQueryString()
     {
          return $this->queryString;
     }

     public function getAuthorization()
     {
          return $this->authorization;
     }

     public function reArrayFiles($file_post) {

          $file_ary = array();
          $file_count = count($file_post['name']);
          $file_keys = array_keys($file_post);
      
          for ($i=0; $i<$file_count; $i++) {
              foreach ($file_keys as $key) {
                  $file_ary[$i][$key] = $file_post[$key][$i];
              }
          }
      
          return $file_ary;
      }
}
