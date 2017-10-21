<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class TokenComponent extends Component
{
    protected $_defaultConfig = [];

    public function secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1)
            return $min;
        $log = ceil(log($range, 2));
        $bytes = (int)($log / 8) + 1;
        $bits = (int)$log + 1;
        $filter = (int)(1 << $bits) - 1;
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter;
        } while ($rnd >= $range);
        return $min + $rnd;
    }

    public function generate($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->secure(0, $max)];
        }
        return $token;
    }
}
