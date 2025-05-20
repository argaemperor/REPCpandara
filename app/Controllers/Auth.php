<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MasterEventModel;
use App\Models\UserEventModel;


class Auth extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function doLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            return redirect()->back()->with('error', 'Username/Email dan Password wajib diisi.');
        }

        $userModel = new UserModel();
        $eventModel = new MasterEventModel();
        $userEventModel = new UserEventModel();

        // Cari user
        $user = $userModel
            ->groupStart()
            ->where('Username', $username)
            ->orWhere('email', $username)
            ->groupEnd()
            ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        if (in_array($user['Level'], [2, 3, 4]) && !filter_var($username, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'Login harus menggunakan email.');
        }

        if (!password_verify($password, $user['Password'])) {
            return redirect()->back()->with('error', 'Password salah.');
        }

        // Rehash password jika perlu
        if (password_needs_rehash($user['Password'], PASSWORD_BCRYPT)) {
            $userModel->update($user['id'], [
                'Password' => password_hash($password, PASSWORD_BCRYPT)
            ]);
        }

        // Siapkan variabel untuk session
        $eventList = [];
        $currentLevel = null;
        $currentEventId = null;

        if (in_array($user['Level'], [1, 2])) {
            // Admin dan Event Manager => semua event
            $events = $eventModel->where('delete_at', null)->findAll();
            $eventList = array_column($events, 'eventId');

            $currentLevel = $user['Level'];
            $currentEventId = $eventList[0] ?? null;
        } else {
            // Operator dan Visitor => hanya event yg dimiliki
            $userEvents = $userEventModel
                ->where('user_id', $user['id'])
                ->where('deleted_at', null)
                ->findAll();

            if (empty($userEvents)) {
                return redirect()->back()->with('error', 'User belum terdaftar di event manapun.');
            }

            $eventList = array_column($userEvents, 'event_id');
            $currentLevel = $userEvents[0]['level'];
            $currentEventId = $userEvents[0]['event_id'];
        }

        // Set session
        session()->set([
            'user_id'    => $user['id'],
            'fullname'   => $user['name'],
            'level'      => $currentLevel,
            'eventID'    => $currentEventId,
            'event_list' => $eventList,
            'logged_in'  => true
        ]);

        // Arahkan sesuai level
        switch ($currentLevel) {
            case 1:
                return redirect()->to('Admin/Dashboard');
            case 2:
                return redirect()->to('EventMgr/Dashboard');
            case 3:
                return redirect()->to('Operator/Dashboard');
            default:
                return redirect()->to('/login');
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

        $rules = [
            'id_number'         => 'required|min_length[15]',
            'name'              => 'required',
            'email'             => 'required|valid_email',
            'EventId'           => 'required|is_natural_no_zero',
            'password'          => 'required|min_length[6]',
            'password_confirm'  => 'matches[password]',
            'usia'              => 'required|greater_than_equal_to[10]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(', ', $validation->getErrors()));
        }

        $userModel = new UserModel();
        $userEventModel = new UserEventModel();

        $idNumber = $this->request->getPost('id_number');
        $eventId = $this->request->getPost('EventId');

        // Cek apakah user sudah ada berdasarkan id_number
        $existingUser = $userModel->where('id_number', $idNumber)->first();

        $userData = [
            'id_number' => $idNumber,
            'name'      => $this->request->getPost('name'),
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone'),
            'address'   => $this->request->getPost('address'),
            'usia'      => $this->request->getPost('usia'),
            'Password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'Level'     => 3
        ];

        if ($existingUser) {
            $userId = $existingUser['id'];

            // ✅ Update user lama dengan data terbaru
            $userModel->update($userId, $userData);

            // Cek apakah user sudah terdaftar di event tersebut
            $existingUserEvent = $userEventModel
                ->where('user_id', $userId)
                ->where('event_id', $eventId)
                ->where('deleted_at', null)
                ->first();

            if ($existingUserEvent) {
                return redirect()->back()->withInput()->with('error', 'User sudah terdaftar di event ini.');
            }
        } else {
            // ✅ Insert user baru
            $userId = $userModel->insert($userData);
        }

        // Hitung jumlah operator di event untuk pemberian identifier
        $countOperator = $userEventModel
            ->where('event_id', $eventId)
            ->where('level', 3)
            ->where('deleted_at', null)
            ->countAllResults();

        $identifier = 'Operator ' . ($countOperator + 1);

        // ✅ Tambahkan ke user_event
        $userEventModel->insert([
            'user_id'    => $userId,
            'event_id'   => $eventId,
            'level'      => 3,
            'identifier' => $identifier
        ]);

        return redirect()->to(base_url('login'))->with('success', 'Registrasi berhasil! Silakan login.');
    }


    public function checkID()
    {
        $idNumber = $this->request->getPost('id_number');
        $user = (new UserModel())
            ->where('id_number', $idNumber)
            ->first();

        if ($user) {
            return $this->response->setJSON([
                'status' => 'found',
                'data' => [
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'address' => $user['address'],
                    'usia' => $user['usia']
                ]
            ]);
        } else {
            return $this->response->setJSON(['status' => 'not_found']);
        }
    }
}
