<?php
namespace components;

try{
    require __DIR__.'/../configs/db.php';
    spl_autoload_register(function ($class_name) {
        $class = __DIR__.'/../'.str_replace('\\', '/', $class_name).'.php';
        if (file_exists($class))
        {
            require_once $class;

            if(!class_exists($class_name) && !interface_exists($class_name)) {
                throw new ExceptionHandler("Не обнаружен класс $class_name");
            }

        }else throw new ExceptionHandler("Не обнаружен файл $class_name");
    });
}catch (ExceptionHandler $e) {}

