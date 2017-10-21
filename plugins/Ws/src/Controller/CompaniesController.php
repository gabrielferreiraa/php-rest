<?php
namespace Ws\Controller;

use Cake\ORM\TableRegistry;
use Ws\Controller\AppController;

class CompaniesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->Companies = TableRegistry::get('Companies');
    }

    public function index()
    {
        $companies = $this->Companies->find('list');

        $this->set([
            'companies' => $companies
        ]);
    }

    public function add()
    {
        $user = $this->Companies->newEntity();

        $response = [
            'status' => 'error',
            'message' => 'Problema ao salvar empresa, por favor tente novamente'
        ];

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $user = $this->Companies->patchEntity($user, $data);

            if ($this->Companies->save($user)) {
                $response['status'] = 'success';
                $response['message'] = 'Empresa criada com sucesso';
            }
        }

        $this->set($response);
    }

    public function edit($id = null)
    {
        $user = $this->Companies->get($id, [
            'contain' => []
        ]);

        $response = [
            'status' => 'error',
            'message' => 'Problema ao editar empresa, por favor tente novamente'
        ];

        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Companies->patchEntity($user, $this->request->getData());

            if ($this->Companies->save($user)) {
                $response['status'] = 'success';
                $response['message'] = 'Empresa editada com sucesso';
            }
        }

        $this->set($response);
    }

    public function delete($id = null)
    {
        $user = $this->Companies->get($id);

        $response = [
            'status' => 'error',
            'message' => 'Problema ao deletar empresa, por favor tente novamente'
        ];

        if ($this->Companies->delete($user)) {
            $response['status'] = 'success';
            $response['message'] = 'Empresa deletada com sucesso';
        }

        $this->set($response);
    }
}
