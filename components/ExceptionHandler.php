<?php
namespace components;
use Exception;
class ExceptionHandler extends Exception {

    public function __construct($message = "", $code = 0)
    {
        die("<h2 style='color: red;position:absolute;top:50%;left:50%;display:block;width:500px;height:200px;margin-left:-250px;margin-top:-100px;'>$message</h2>");
    }
}
