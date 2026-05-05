<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Live Map',
            'name'  => session()->get('name'),
            'role'  => session()->get('role'),
        ];
        return view('dashboard/index', $data);
    }
}