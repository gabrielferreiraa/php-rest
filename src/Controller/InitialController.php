<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;

class InitialController extends AppController
{
    public function index()
    {
        $baseUrl = Router::url('/', true) . 'ws/';

        $this->set(compact('baseUrl'));
    }
}
