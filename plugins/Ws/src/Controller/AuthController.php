<?php
namespace Ws\Controller;

use Cake\Network\Exception\UnauthorizedException;
use Cake\ORM\TableRegistry;
use Ws\Controller\AppController;
use Ws\Lib\Auth;


class AuthController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->Users = TableRegistry::get('Users');
    }

    public function index()
    {
        if ($this->request->is('post')) {
            $post = $this->request->getData();

            $user = $this->Users->find('login', $post);

            if (!$user) {
                throw new UnauthorizedException('Credenciais inválidas');
            }

            $user->token = Auth::encode($user->email, $user->password, 'RS256');
            debuG(strlen($user->token));exit;
            $this->Users->save($user);

            $this->set([
                'status' => 'success',
                'data' => [
                    'token' => $user->token
                ]
            ]);
        }
    }
}
