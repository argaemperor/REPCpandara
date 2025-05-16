<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MasterEventModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function doLogin()
    {
        $username = $this->request->getPost('username'); // bisa username/email tergantung level
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            return redirect()->back()->with('error', 'Username/Email dan Password wajib diisi.');
        }

        $userModel = new \App\Models\UserModel();

        // Coba cari user berdasarkan Username atau Email
        $user = $userModel
            ->groupStart()
            ->where('Username', $username)
            ->orWhere('email', $username)
            ->groupEnd()
            ->first();

        if ($user) {
            // 🔐 Tambahan: Jika Level 2 atau 3, login hanya boleh via email
            if (in_array($user['Level'], [2, 3, 4]) && !filter_var($username, FILTER_VALIDATE_EMAIL)) {
                return redirect()->back()->with('error', 'Login harus menggunakan email.');
            }

            if (password_verify($password, $user['Password'])) {

                // Rehash jika perlu
                if (password_needs_rehash($user['Password'], PASSWORD_BCRYPT)) {
                    $userModel->update($user['id'], [
                        'Password' => password_hash($password, PASSWORD_BCRYPT)
                    ]);
                }

                // Set session
                session()->set([
                    'user_id'   => $user['id'],
                    'fullname'  => $user['name'],
                    'level'     => $user['Level'],
                    'eventID'   => $user['EventId'],
                    'logged_in' => true
                ]);

                // Redirect berdasarkan level
                switch ($user['Level']) {
                    case 1:
                        return redirect()->to('Admin/Dashboard');
                    case 2:
                        return redirect()->to('EventMgr/Dashboard');
                    case 3:
                        return redirect()->to('Operator/Dashboard');
                    default:
                        return redirect()->to('/login');
                }
            } else {
                return redirect()->back()->with('error', 'Password salah.');
            }
        } else {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }
    }



    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function showRegister()
    {
        $eventModel = new MasterEventModel();
        $events = $eventModel->where('eventActive', 1)->findAll(); // hanya event aktif

        return view('auth/register', ['events' => $events]);
    }

    public function register()
    {
        $validation = \Config\Services::validation();

        // Validasi input
        $rules = [
            'id_number'         => 'required',
            'name'              => 'required',
            // 'username'          => 'required|is_unique[users.username]',
            'email'             => 'required|valid_email|is_unique[users.email]',
            'EventId'           => 'required|is_natural_no_zero',
            'password'          => 'required|min_length[6]',
            'password_confirm'  => 'matches[password]',
            'usia'              => 'required|greater_than_equal_to[10]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(', ', $validation->getErrors()));
        }

        $model = new UserModel();

        $model->save([
            'id_number' => $this->request->getPost('id_number'),
            'name'      => $this->request->getPost('name'),
            // 'Username'  => $this->request->getPost('username'),
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone'),
            'address'   => $this->request->getPost('address'),
            'usia'      => $this->request->getPost('usia'),
            'EventId' => $this->request->getPost('EventId'),
            'Password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'Level'     => 3, // default user level

        ]);

        return redirect()->to(base_url('login'))->with('success', 'Registrasi berhasil! Silakan login.');
    }
}
