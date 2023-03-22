<?php
namespace helpers;

use helpers\interfaces\Letter;

enum SignatureLetter implements Letter {
    case Red;
    case Green;

    /**
     * @return string
     */
    function SignColor(): string {
        return match($this) {
            SignatureLetter::Red => 'red',
            SignatureLetter::Green => 'green',
        };
    }
}