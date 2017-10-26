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
        $this->loadComponent('Token');
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

            $user->token = $this->Token->generate(64);
            $this->Users->save($user);

            $firstName = explode(' ', $user->name)[0];

            $response = [
                'message' => $firstName . ', bem vindo',
                'token' => Auth::encode($user->email, $user->token),
                'user' => $user
            ];

            $this->set(compact('response'));
            $this->set('_serialize', ['response']);
        }
    }
}
