<?php
namespace Ws\Controller;

use Cake\ORM\TableRegistry;
use Ws\Controller\AppController;

class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->Users = TableRegistry::get('Users');
    }

    public function index()
    {
        $this->Users->displayField('email');
        $users = $this->Users->find('list');

        $this->set([
            'users' => $users
        ]);
    }

    public function add()
    {
        $response = [
            'status' => 'error',
            'message' => 'Problema ao salvar usuário, por favor tente novamente'
        ];

        if ($this->request->is('post')) {
            $user = $this->Users->newEntity();
            $user = $this->Users->patchEntity($user, $this->request->getData());

            if ($this->Users->save($user)) {
                $response['status'] = 'success';
                $response['message'] = 'Usuário criado com sucesso';
            }
        }

        $this->set($response);
    }

    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);

        $response = [
            'status' => 'error',
            'message' => 'Problema ao editar usuário, por favor tente novamente'
        ];

        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());

            if ($this->Users->save($user)) {
                $response['status'] = 'success';
                $response['message'] = 'Usuário editado com sucesso';
            }
        }

        $this->set($response);
    }

    public function delete($id = null)
    {
        $user = $this->Users->get($id);

        $response = [
            'status' => 'error',
            'message' => 'Problema ao deletar usuário, por favor tente novamente'
        ];

        if ($this->Users->delete($user)) {
            $response['status'] = 'success';
            $response['message'] = 'Usuário deletado com sucesso';
        }

        $this->set($response);
    }
}
