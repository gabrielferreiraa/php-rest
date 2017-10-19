<?php

namespace Ws\Controller;

use App\Controller\AppController as BaseController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\UnauthorizedException;
use Firebase\JWT\JWT;

class AppController extends BaseController
{
    public function initialize() {
        $this->loadComponent('RequestHandler');

        if (!$this->request->is(['json'])) {
            throw new MethodNotAllowedException('Not allowed');
        }

        $this->checkToken();
    }

    /**
     * Verifica se o token é valido
     */
    private function checkToken() {
        $token = $this->getRequestToken();

        if (!$token) {
            throw new BadRequestException('Token not provided');
        }

        if (!$this->validateToken($token)) {
            throw new UnauthorizedException('Invalid credentials');
        }
    }

    /**
     * Get token
     * @return bool|null|string
     */
    private function getRequestToken() {
        if ($this->request->header('Authorization')) {
            return $this->request->header('Authorization');
        }

        return false;
    }

    /**
     * @param $token
     * @return bool
     */
    private function validateToken($token) {
        try {

        } catch (\Exception $e) {
            return false;
        }

        return false;
    }
}
