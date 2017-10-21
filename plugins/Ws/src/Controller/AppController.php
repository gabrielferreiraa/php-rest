<?php

namespace Ws\Controller;

use App\Controller\AppController as BaseController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\InvalidCsrfTokenException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\ORM\TableRegistry;
use Ws\Lib\Auth;

class AppController extends BaseController
{
    private $allowedControllers = ['Auth', 'Users'];

    public function initialize()
    {
        $this->loadComponent('RequestHandler');

        if (!$this->request->is(['json'])) {
            throw new MethodNotAllowedException('Not allowed');
        }

        $currentController = $this->request->controller;
        if (!in_array($currentController, $this->allowedControllers)) {
            $this->checkToken();
        }
    }

    /**
     * Verifica se o token é válido
     */
    private function checkToken()
    {
        $token = $this->getRequestToken();

        if (!$token) {
            throw new BadRequestException('Token não fornecido');
        }

        if (!$this->validateToken($token)) {
            throw new UnauthorizedException('Credenciais inválidas');
        }
    }

    /**
     * Get token
     * @return bool|null|string
     */
    private function getRequestToken()
    {
        if ($this->request->header('Authorization')) {
            return $this->request->header('Authorization');
        }

        return false;
    }

    /**
     * Validate decoded token
     * @param $token
     * @return bool
     */
    private function validateToken($token)
    {
        try {
            $decoded = Auth::decode($token);
        } catch (InvalidCsrfTokenException $e) {
            return false;
        }

        if (!$decoded) {
            return false;
        }

        $Users = TableRegistry::get('Users');

        $user = $Users->find('validate', $decoded);

        if (!$user) {
            return false;
        }

        return true;
    }
}
