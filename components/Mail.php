<?php
namespace components;
class Mail {
    public static $to = '';
    public static $from = '';
    public static $subject = '';
    public static $content = '';

    public function __construct() {
        return new self;
    }

    public static function send() : bool {
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'To: `'.self::$to.'`' . "\r\n";
        $headers .= 'From: `'.self::$from.'`' . "\r\n";

        return mail(self::$to, self::$subject, self::$content, $headers);
    }
}