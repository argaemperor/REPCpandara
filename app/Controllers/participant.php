<?php

namespace App\Controllers;

use App\Models\ParticipantModel;
use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;

class participant extends BaseController
{
    public function index()
    {
        return view('participant/index', ['title' => 'participant']);
    }
    public function ajaxList()
    {
        $request = service('request');
        $db = db_connect();

        $draw = $request->getGet('draw');
        $start = $request->getGet('start');
        $length = $request->getGet('length');
        $searchValue = $request->getGet('search')['value'];

        // JOIN masterevent dan filter eventActive
        $builder = $db->table('master_regnow')
            ->join('masterevent', 'masterevent.eventId = master_regnow.eventID')
            ->where('masterevent.eventActive', 1);

        if ($searchValue) {
            $builder->groupStart()
                ->like('firstname', $searchValue)
                ->orLike('lastname', $searchValue)
                ->orLike('bib', $searchValue)
                ->orLike('idNumber', $searchValue)
                ->groupEnd();
        }

        $recordsFiltered = $builder->countAllResults(false);
        $totalRecords = $recordsFiltered;

        $builder->limit($length, $start);
        $query = $builder->get();

        $data = [];
        $no = $start + 1;

        foreach ($query->getResultArray() as $row) {
            $data[] = [
                'no' => $no++,
                'fullname' => esc($row['firstname'] . ' ' . $row['lastname']),
                'firstname' => esc($row['firstname']),
                'lastname' => esc($row['lastname']),
                'race_category' => esc($row['race_category']),
                'bib' => esc($row['bib']),
                'phonenumber' => esc($row['phonenumber']),
                'jerseySize' => esc($row['jerseySize']),
                'idNumber' => esc($row['idNumber']),
            ];
        }

        return $this->response->setJSON([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }




    public function update()
    {
        $id = $this->request->getPost('id');
        $model = new ParticipantModel();

        $data = [
            'firstname'    => $this->request->getPost('firstname'),
            'lastname'     => $this->request->getPost('lastname'),
            'race_category' => $this->request->getPost('race_category'),
            'phonenumber'  => $this->request->getPost('phonenumber'),
            'jerseySize'   => $this->request->getPost('jerseySize'),
            'idNumber'     => $this->request->getPost('idnumber'),

        ];

        $model->update($id, $data);
        return redirect()->to(base_url('participants'))->with('success', 'Data updated successfully');
    }

    public function getJerseyOptions()
    {
        $db = db_connect();
        $query = $db->query("SELECT DISTINCT jerseySize FROM master_regnow WHERE jerseySize IS NOT NULL AND jerseySize != '' ORDER BY jerseySize ASC");
        $result = $query->getResultArray();

        return $this->response->setJSON($result);
    }
}
