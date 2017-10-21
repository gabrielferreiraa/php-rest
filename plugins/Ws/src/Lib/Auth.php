<?php

namespace Ws\Lib;

use Aura\Intl\Exception;
use Cake\Core\Configure;
use Firebase\JWT\JWT;

class Auth
{
    static function decode($token)
    {
        $config = Configure::read('JWT');
        $key = base64_decode($config['key']);

        try {
            $decoded = JWT::decode($token, $key, ['HS256']);

            if ($decoded) {
                return [
                    'email' => $decoded->email,
                    'token' => $decoded->token
                ];
            }
        } catch (Exception $e) {
            return false;
        }

        return false;
    }

    static function encode($email, $password)
    {
        $config = Configure::read('JWT');

        $values = [
            'email' => $email,
            'password' => $password
        ];
        $key = base64_decode($config['key']);

        return JWT::encode($values, $key);
    }
}
