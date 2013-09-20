<?php

ini_set('display_errors', 1);
define('PATH', realpath('.').'/');

function __autoload($className)
{
    $classPath = str_replace('\\', '/', rtrim(ltrim($className, '\\'), '\\'));
    require_once PATH.$classPath.'.php';
    
}
