<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MasterEventModel;
use App\Models\UserEventModel;
use CodeIgniter\Controller;


class EventManager extends BaseController
{


    public function Dashboard()
    {
        return view('EventMgr/dashboard', ['title' => 'Dashboard']);
    }
    public function participantmgr()
    {
        return view('EventMgr/list_participan', ['title' => 'participant']);
    }

    public function participantmgrCheckout()
    {
        return view('EventMgr/list_participanCheckOut', ['title' => 'checkout']);
    }
    public function Event()
    {

        return view('EventMgr/EventMasterMgr', ['title' => 'EventMaster']);
    }

    public function MasterUserMgr()
    {

        return view('EventMgr/ListOprator', ['title' => 'UserMaster']);
    }

    public function ajaxMasterUserListmgr()
    {
        $request = service('request');
        $db = db_connect();
        $builder = $db->table('users')
            ->where('Level', 3)
            ->where('deleted_at', null);

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

    public function ajaxEventListManager()
    {
        try {
            $model = new MasterEventModel();
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




    public function toggleEventStatusmgr($id)
    {
        $model = new MasterEventModel();
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
        $model = new MasterEventModel();
        $data = [
            'eventName'    => $this->request->getPost('eventName'),
            'eventYears'   => $this->request->getPost('eventYears'),
            'eventActive'  => $this->request->getPost('eventActive'),
        ];
        $model->insert($data);
        return redirect()->to('EventMgr/Event')->with('success', 'Event berhasil ditambahkan.');
    }

    public function updateEventmgr()
    {
        $model = new MasterEventModel();
        $id = $this->request->getPost('eventId');

        $data = [
            'eventName' => $this->request->getPost('eventName'),
            'eventYears' => $this->request->getPost('eventYears'),
            'eventActive' => $this->request->getPost('eventActive')
        ];

        $model->update($id, $data);

        return $this->response->setJSON(['status' => true, 'message' => 'Event berhasil diperbarui.']);
    }

    public function deleteMgr($id)
    {
        $model = new MasterEventModel();
        $model->delete($id);

        return $this->response->setJSON(['status' => true, 'message' => 'Event berhasil dihapus.']);
    }

    public function ajaxListparticipantmgrCheckOut()
    {
        $request = service('request');
        $db = db_connect();

        $draw = $request->getGet('draw');
        $start = $request->getGet('start');
        $length = $request->getGet('length');
        $search = $request->getGet('search');

        $searchValue = isset($search['value']) ? $search['value'] : '';

        // === 1. Hitung Total Tanpa Filter (recordsTotal)
        $builderTotal = $db->table('master_regnow')
            ->join('masterevent', 'masterevent.eventId = master_regnow.eventID')
            ->where('masterevent.eventActive', 1)
            ->whereIn('master_regnow.status_repc', ['Pending', 'Proses']);

        $totalRecords = $builderTotal->countAllResults();

        // === 2. Query Data + Filter (recordsFiltered)
        $builder = $db->table('master_regnow')
            ->select('master_regnow.*, masterevent.eventName AS event_name')
            ->join('masterevent', 'masterevent.eventId = master_regnow.eventID')
            ->where('masterevent.eventActive', 1)
            ->whereIn('master_regnow.status_repc', ['Pending', 'Proses']);

        if ($searchValue) {
            $builder->groupStart()
                ->like('firstname', $searchValue)
                ->orLike('lastname', $searchValue)
                ->orLike('bib', $searchValue)
                ->orLike('invoice', $searchValue)
                ->orLike('idNumber', $searchValue)
                ->groupEnd();
        }


        $recordsFiltered = $builder->countAllResults(false);
        // Ambil parameter order
        $order = $request->getGet('order');
        $columns = $request->getGet('columns');

        if (!empty($order) && !empty($columns)) {
            $orderColumnIndex = $order[0]['column'];
            $orderColumnName = $columns[$orderColumnIndex]['data'];
            $orderDir = $order[0]['dir'];

            // Cek nama kolom agar hanya sorting kolom valid
            $allowedColumns = ['invoice', 'fullname', 'race_category', 'bib', 'phonenumber', 'jerseySize', 'idNumber', 'event_name', 'status_repc'];

            // Jika fullname atau event_name (alias), harus di-handle manual
            if ($orderColumnName == 'fullname') {
                $builder->orderBy('firstname', $orderDir);
                $builder->orderBy('lastname', $orderDir);
            } elseif ($orderColumnName == 'event_name') {
                $builder->orderBy('masterevent.eventName', $orderDir);
            } elseif (in_array($orderColumnName, $allowedColumns)) {
                $builder->orderBy($orderColumnName, $orderDir);
            }
        }

        // Ambil parameter order
        $order = $request->getGet('order');
        $columns = $request->getGet('columns');

        if (!empty($order) && !empty($columns)) {
            $orderColumnIndex = $order[0]['column'];
            $orderColumnName = $columns[$orderColumnIndex]['data'];
            $orderDir = $order[0]['dir'];

            // Cek nama kolom agar hanya sorting kolom valid
            $allowedColumns = ['invoice', 'fullname', 'race_category', 'bib', 'phonenumber', 'jerseySize', 'idNumber', 'event_name', 'status_repc'];

            // Jika fullname atau event_name (alias), harus di-handle manual
            if ($orderColumnName == 'fullname') {
                $builder->orderBy('firstname', $orderDir);
                $builder->orderBy('lastname', $orderDir);
            } elseif ($orderColumnName == 'event_name') {
                $builder->orderBy('masterevent.eventName', $orderDir);
            } elseif (in_array($orderColumnName, $allowedColumns)) {
                $builder->orderBy($orderColumnName, $orderDir);
            }
        }

        // === 3. Apply limit & get data
        $builder->limit($length, $start);
        $query = $builder->get();

        $data = [];


        foreach ($query->getResultArray() as $row) {
            $data[] = [

                'fullname' => esc($row['firstname'] . ' ' . $row['lastname']),
                'firstname' => esc($row['firstname']),
                'lastname' => esc($row['lastname']),
                'race_category' => esc($row['race_category']),
                'bib' => esc($row['bib'] . ' ' . $row['chipscode']),
                'phonenumber' => esc($row['phonenumber']),
                'jerseySize' => esc($row['jerseySize']),
                'idNumber' => esc($row['idNumber']),
                'event_name' => esc($row['event_name']),
                'status_repc' => esc($row['status_repc']),
                'invoice' => esc($row['invoice']),
                'id' => esc($row['id']),
            ];
        }



        return $this->response->setJSON([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    public function ajaxListparticipantmgr()
    {
        $request = service('request');
        $db = db_connect();

        $draw = $request->getGet('draw');
        $start = $request->getGet('start');
        $length = $request->getGet('length');
        $search = $request->getGet('search');

        $searchValue = isset($search['value']) ? $search['value'] : '';

        // === 1. Hitung Total Tanpa Filter (recordsTotal)
        $builderTotal = $db->table('master_regnow')
            ->join('masterevent', 'masterevent.eventId = master_regnow.eventID')
            ->where('masterevent.eventActive', 1);

        $totalRecords = $builderTotal->countAllResults();

        // === 2. Query Data + Filter (recordsFiltered)
        $builder = $db->table('master_regnow')
            ->select('master_regnow.*, masterevent.eventName AS event_name')
            ->join('masterevent', 'masterevent.eventId = master_regnow.eventID')
            ->where('masterevent.eventActive', 1);

        if ($searchValue) {
            $builder->groupStart()
                ->like('firstname', $searchValue)
                ->orLike('lastname', $searchValue)
                ->orLike('bib', $searchValue)
                ->orLike('invoice', $searchValue)
                ->orLike('idNumber', $searchValue)
                ->groupEnd();
        }


        $recordsFiltered = $builder->countAllResults(false);
        // Ambil parameter order
        $order = $request->getGet('order');
        $columns = $request->getGet('columns');

        if (!empty($order) && !empty($columns)) {
            $orderColumnIndex = $order[0]['column'];
            $orderColumnName = $columns[$orderColumnIndex]['data'];
            $orderDir = $order[0]['dir'];

            // Cek nama kolom agar hanya sorting kolom valid
            $allowedColumns = ['invoice', 'fullname', 'race_category', 'bib', 'phonenumber', 'jerseySize', 'idNumber', 'event_name', 'status_repc'];

            // Jika fullname atau event_name (alias), harus di-handle manual
            if ($orderColumnName == 'fullname') {
                $builder->orderBy('firstname', $orderDir);
                $builder->orderBy('lastname', $orderDir);
            } elseif ($orderColumnName == 'event_name') {
                $builder->orderBy('masterevent.eventName', $orderDir);
            } elseif (in_array($orderColumnName, $allowedColumns)) {
                $builder->orderBy($orderColumnName, $orderDir);
            }
        }

        // Ambil parameter order
        $order = $request->getGet('order');
        $columns = $request->getGet('columns');

        if (!empty($order) && !empty($columns)) {
            $orderColumnIndex = $order[0]['column'];
            $orderColumnName = $columns[$orderColumnIndex]['data'];
            $orderDir = $order[0]['dir'];

            // Cek nama kolom agar hanya sorting kolom valid
            $allowedColumns = ['invoice', 'fullname', 'race_category', 'bib', 'phonenumber', 'jerseySize', 'idNumber', 'event_name', 'status_repc'];

            // Jika fullname atau event_name (alias), harus di-handle manual
            if ($orderColumnName == 'fullname') {
                $builder->orderBy('firstname', $orderDir);
                $builder->orderBy('lastname', $orderDir);
            } elseif ($orderColumnName == 'event_name') {
                $builder->orderBy('masterevent.eventName', $orderDir);
            } elseif (in_array($orderColumnName, $allowedColumns)) {
                $builder->orderBy($orderColumnName, $orderDir);
            }
        }

        // === 3. Apply limit & get data
        $builder->limit($length, $start);
        $query = $builder->get();

        $data = [];


        foreach ($query->getResultArray() as $row) {
            $data[] = [

                'fullname' => esc($row['firstname'] . ' ' . $row['lastname']),
                'firstname' => esc($row['firstname']),
                'lastname' => esc($row['lastname']),
                'race_category' => esc($row['race_category']),
                'bib' => esc($row['bib'] . ' ' . $row['chipscode']),
                'phonenumber' => esc($row['phonenumber']),
                'jerseySize' => esc($row['jerseySize']),
                'idNumber' => esc($row['idNumber']),
                'event_name' => esc($row['event_name']),
                'status_repc' => esc($row['status_repc']),
                'invoice' => esc($row['invoice']),
                'id' => esc($row['id']),
            ];
        }



        return $this->response->setJSON([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }



    public function checkoutMultiple()
    {
        $ids = $this->request->getPost('ids'); // array of idNumber
        $userID = session()->get('user_id');

        if (!$ids || !is_array($ids)) {
            return $this->response->setJSON(['message' => 'Data tidak valid.']);
        }

        $db = db_connect();
        $builder = $db->table('master_regnow');
        $builder->whereIn('id', $ids)
            ->update([
                'processed_by' => $userID,
                'processed_at' => date('Y-m-d H:i:s'),
                'status_repc' => 'Proses'
            ]);

        return $this->response->setJSON(['message' => 'Checkout berhasil diproses.']);
    }

    public function checkoutScan()
    {
        $ids = $this->request->getPost('ids');
        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'Tidak ada peserta yang dipilih.');
        }

        $participantModel = new \App\Models\ParticipantModel();

        // Update status_repc, processed_at, processed_by
        $updateData = [
            'status_repc'    => 'Proses',
            'processed_at'   => date('Y-m-d H:i:s'),
            'processed_by'   => session('user_id'),
        ];
        $participantModel->whereIn('id', $ids)->set($updateData)->update();

        // Ambil data peserta setelah update
        $participants = $participantModel->whereIn('id', $ids)->findAll();

        return view('EventMgr/checkout_scan', [
            'title' => 'Scan & Validasi BIB',
            'participants' => $participants
        ]);
    }


    public function processCheckoutScan()
    {
        $validated = $this->request->getPost('validated');
        $scanned_bib = $this->request->getPost('scanned_bib');

        if (!$validated || !is_array($validated)) {
            return redirect()->back()->with('error', 'Tidak ada data validasi.');
        }

        $participantModel = new \App\Models\ParticipantModel();

        foreach ($validated as $id => $status) {
            if (isset($scanned_bib[$id])) {
                $participantModel->update($id, [
                    'status_repc'    => 'Done',
                    'check_bib'      => $scanned_bib[$id],
                    'processed_End'  => date('Y-m-d H:i:s'),
                    'processed_by'   => session('user_id'),
                    'status_wakil'   => 'collective',
                ]);
            }
        }

        return redirect()->to('EventMgr/participantCO')->with('message', 'Checkout berhasil dan data disimpan.');
    }
}
