<?php

namespace App\Controllers;

use App\Models\TblAdminsDataModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    protected $adminModel;
    protected $session;

    public function __construct() {
        $this->adminModel = new TblAdminsDataModel();
        $this->session = session();
    }

    public function login()
    {
        // $password = 'admin';
        // echo password_hash($password, PASSWORD_ARGON2ID);
        return view('auth/login');
    }

    public function doLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $admin = $this->adminModel->where('username', $username)->first();

        if ($admin && password_verify($password, $admin->password)) {
            $this->session->set([
                'admin_id'      => $admin->id,
                'username'      => $admin->username,
                'type'          => $admin->type,
                'allow'         => $admin->allow,
                'allow_photo'   => $admin->allow_photo,
                'allow_paper'   => $admin->allow_paper,
                'sec_id'        => $admin->sec,
                'sub_sec_id'    => $admin->sub_sec,
                'isLoggedIn'    => true
            ]);
            return redirect()->to('employees');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials.');
        }
    }

    public function logout()
    {
        // $this->session()->destroy();
        $this->session->destroy();
        return redirect()->to('/login');
    }
}
