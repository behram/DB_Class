<?php

ini_set('display_errors', 1);
define('PATH', realpath('.').'/');

function __autoload($className)
{
    $classPathReplace = str_replace('\\', '/', rtrim(ltrim($className, '\\'), '\\'));
    require_once PATH.$classPathReplace.'.php';
    
}