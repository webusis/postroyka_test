<?php
namespace helpers;

class BaseMethods{

    /**
     * @param $date string
     * @return int
     */
    public static function calcYersAgo($date = '1970-01-01') : int {
        $date = explode('-', date('Y-m-d',strtotime($date)));
        return floor((time()-mktime(0, 0, 0, $date[1], $date[2], $date[0]))/(60*60*24*365));
    }
}