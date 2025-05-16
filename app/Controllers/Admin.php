<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MasterEventModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Admin extends BaseController
{
    public function Dashboard()
    {

        return view('admin/dashboard', ['title' => 'Dashboard']);
    }
    public function Event()
    {

        return view('admin/EventMaster', ['title' => 'EventMaster']);
    }

    public function ajaxEventList()
    {
        try {
            $model = new \App\Models\MasterEventModel();
            $events = $model->orderBy('eventId', 'DESC')->findAll();

            $data = [];
            $no = 1;
            foreach ($events as $event) {
                $data[] = [
                    'no'          => $no++,
                    'eventId'     => $event['eventId'],
                    'eventName'   => $event['eventName'],
                    'eventYears'  => $event['eventYears'],
                    'eventActive' => $event['eventActive'],
                    'created_at'  => date('d-m-Y', strtotime($event['created_at']))
                ];
            }

            return $this->response->setJSON(['data' => $data]);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function toggleEventStatus($id)
    {
        $model = new \App\Models\MasterEventModel();
        $event = $model->find($id);

        if ($event) {
            $newStatus = $event['eventActive'] == 1 ? 0 : 1;
            $model->update($id, ['eventActive' => $newStatus]);
            return $this->response->setJSON(['status' => true, 'message' => 'Status berhasil diubah.']);
        }

        return $this->response->setJSON(['status' => false, 'message' => 'Event tidak ditemukan.']);
    }

    public function saveEvent()
    {
        $model = new \App\Models\MasterEventModel();
        $data = [
            'eventName'    => $this->request->getPost('eventName'),
            'eventYears'   => $this->request->getPost('eventYears'),
            'eventActive'  => $this->request->getPost('eventActive'),
        ];
        $model->insert($data);
        return redirect()->to('Admin/Event')->with('success', 'Event berhasil ditambahkan.');
    }

    public function update()
    {
        $model = new \App\Models\MasterEventModel();
        $id = $this->request->getPost('eventId');

        $data = [
            'eventName' => $this->request->getPost('eventName'),
            'eventYears' => $this->request->getPost('eventYears'),
            'eventActive' => $this->request->getPost('eventActive')
        ];

        $model->update($id, $data);

        return $this->response->setJSON(['status' => true, 'message' => 'Event berhasil diperbarui.']);
    }

    public function delete($id)
    {
        $model = new \App\Models\MasterEventModel();
        $model->delete($id);

        return $this->response->setJSON(['status' => true, 'message' => 'Event berhasil dihapus.']);
    }

    public function MasterUser()
    {

        return view('admin/UserMaster', ['title' => 'UserMaster']);
    }

    public function MasterUsersave()
    {
        $model = new UserModel();

        $data = [
            'Username'   => $this->request->getPost('username'),
            'Password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'name'       => $this->request->getPost('name'),
            'email'      => $this->request->getPost('email'),
            'phone'      => $this->request->getPost('phone'),
            'address'    => $this->request->getPost('address'),
            'Level'      => $this->request->getPost('level')
        ];

        $model->insert($data);
        return redirect()->to('/users')->with('message', 'User berhasil ditambahkan.');
    }
    public function ajaxMasterUserList()
    {
        $request = service('request');
        $db = db_connect();
        $builder = $db->table('users')->where('deleted_at', null);

        $draw = $request->getGet('draw');
        $start = $request->getGet('start');
        $length = $request->getGet('length');
        $searchValue = $request->getGet('search')['value'];

        if ($searchValue) {
            $builder->groupStart()
                ->like('name', $searchValue)
                ->orLike('email', $searchValue)
                ->orLike('Level', $searchValue)
                ->groupEnd();
        }

        $totalRecords = $builder->countAllResults(false);
        $builder->limit($length, $start);
        $query = $builder->get();

        $data = [];
        $no = $start + 1;
        foreach ($query->getResultArray() as $row) {
            $data[] = [
                'no' => $no++,
                'id' => $row['id'],
                'name' => esc($row['name']),
                'email' => esc($row['email']),
                'phone' => esc($row['phone']),
                'address' => esc($row['address']),
                'Level' => esc($row['Level']),
                'created_at' => esc($row['created_at']),
                'updated_at' => esc($row['updated_at'])
            ];
        }

        return $this->response->setJSON([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    public function deleteUser($id)
    {
        $userModel = new UserModel();
        $currentUserId = session('user_id');

        // Tandai siapa yang menghapus
        $userModel->update($id, [
            'Username'   => null,
            'deleted_by' => $currentUserId
        ]);


        $userModel->delete($id);

        return $this->response->setJSON(['status' => true, 'message' => 'User berhasil dihapus.']);
    }

    public function updateUser()
    {
        $id = $this->request->getPost('id');
        $model = new \App\Models\UserModel();

        $data = [
            'name'        => $this->request->getPost('name'),
            'email'       => $this->request->getPost('email'),
            'phone'       => $this->request->getPost('phone'),
            'address'     => $this->request->getPost('address'),
            'Level'       => $this->request->getPost('level'),
            'updated_at'  => date('Y-m-d H:i:s'),
            'updated_by'  => session('user_id'), // hanya kalau ada kolom updated_by
        ];

        $model->update($id, $data);

        return redirect()->to('/users')->with('message', 'User berhasil diperbarui.');
    }
    public function selectEvent()
    {
        if (session('level') != 1) {
            return redirect()->to('/')->with('error', 'Akses ditolak.');
        }

        $eventModel = new \App\Models\MasterEventModel();
        $data['events'] = $eventModel->where('eventActive', 1)->findAll();

        return view('admin/select_event', $data);
    }

    public function saveEventSelection()
    {
        if (session('level') != 1) {
            return redirect()->to('/')->with('error', 'Akses ditolak.');
        }

        $eventId = $this->request->getPost('eventID');

        if ($eventId) {
            session()->set('eventID', $eventId);
            return redirect()->to('/Admin/Dashboard')->with('success', 'Event berhasil dipilih.');
        }

        return redirect()->back()->with('error', 'Silakan pilih event.');
    }
}
