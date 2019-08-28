<?php

namespace Tulic\aPiE\Base;

use Tulic\aPiE\Base\RouterInterface;
use Tulic\aPiE\Base\ErrorHandlerInterface;

class Finalizer
{

     const MODE_JSON = 1;
     const MODE_HTML = 2;

     /**
      * @var RouterInterface $router
      */
     private $router = null;

     /**
      * @var ErrorHandlerInterface $errorHandler
      */
     private $errorHandler = null;

     private $finalResult = '';

     public function __construct(RouterInterface $router, $directRun = false)
     {
          $this->router = $router;
          if ($directRun) {
               $this->startApp($directRun);
          }
     }

     public function __toString()
     {
          return is_string($this->finalResult) ? $this->finalResult : '';
     }

     public function startApp($mode = self::MODE_JSON)
     {
          if (!$this->router) {
               return false;
          }
          $this->router->run();
          $res = $this->router->getResult();
          if ($mode == self::MODE_JSON) {
               $this->finalResult = json_encode($res);
          }
          if ($res) {
               return true;
          } else {
               return false;
          }
     }

     public function setErrorHandler(ErrorHandlerInterface $errorHandler)
     {
          $this->errorHandler = $errorHandler;
     }
}
