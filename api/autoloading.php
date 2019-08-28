<?php

function autoloading($className)
{
     $className = explode("\\", trim($className, "\\"));
     $vendor = array_shift($className);
     $productName = '';
     if ($vendor == VENDOR) {
          $productName = $vendor . "\\" . array_shift($className);
     } else {
          $productName = $vendor;
     }
     $classNameShort = array_pop($className);
     $require = '';
     if ($productName == PRODUCT_NAME) {
          $require = 'Modules/' . implode('/', $className) . '/';
          if (preg_match('/Controller$/', $classNameShort)) {
               $require .= 'Controller';
          } else if (preg_match('/Interface$/', $classNameShort)) {
               $require .= 'Interface';
          } else {
               $require .= 'Model';
          }
          $require .= '/' . $classNameShort;
     } else {
          $require = 'Asset/' . $vendor . ($className ? '.' . implode('.', $className) : '') . '/' . $classNameShort;
     }
     if ($require = $require . '.php') {
          file_exists($require) && require_once $require;
     } else {
          trigger_error('Cannot load ' . $require);
     }
}
