<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ParticipantModel;
use App\Models\MasterEventModel;
use CodeIgniter\Controller;

class Operator extends BaseController
{


    public function Dashboard()
    {
        $eventID = session('eventID');

        $participantModel = new ParticipantModel();
        $eventModel = new MasterEventModel();

        $totalParticipant = $participantModel
            ->where('eventID', $eventID)
            ->where("firstname <>", '')
            ->countAllResults();

        $doneParticipant = $participantModel
            ->where('eventID', $eventID)
            ->groupStart()
            ->where('status_repc', 'Done')
            ->groupEnd()
            ->countAllResults();

        $processingParticipant = $participantModel
            ->where('eventID', $eventID)
            ->where('processed_at IS NOT NULL', null, false)
            ->where('processed_End IS NULL', null, false)
            ->countAllResults();


        $notYetParticipant = $participantModel
            ->where('eventID', $eventID)
            ->groupStart()
            ->where("firstname <>", '')
            //->where('status_repc IS NULL')
            ->Where('status_repc', 'Pending')
            ->groupEnd()
            ->countAllResults();

        // Hitung jumlah jersey per ukuran
        $jerseyCounts = $participantModel
            ->select('jerseySize, COUNT(*) as total')
            ->where('eventID', $eventID)
            ->where('jerseySize !=', '')
            ->groupBy('jerseySize')
            ->orderBy('jerseySize', 'ASC')
            ->findAll();

        $jerseyTaken =  $participantModel
            ->select('jerseySize, COUNT(*) as total')
            ->where('eventID', $eventID)
            ->where('status_repc', 'Done') // hanya yang sudah checkout
            ->groupBy('jerseySize')
            ->get()
            ->getResultArray();

        $event = $eventModel->where('eventId', $eventID)->first();

        return view('operator/dashboard', [
            'title' => 'Dashboard',
            'totalParticipant' => $totalParticipant,
            'doneParticipant' => $doneParticipant,
            'processingParticipant' => $processingParticipant,
            'notYetParticipant' => $notYetParticipant,
            'jerseyCounts' => $jerseyCounts,
            'jerseyTaken' => $jerseyTaken,
            'eventName' => $event['eventName'] ?? 'Unknown Event',
            'eventDate' => $event['eventDate'] ?? '-',
        ]);
    }

    public function participantOperator()
    {
        // Jika bukan AJAX, tampilkan view
        return view('operator/Op_Participan', ['title' => 'Participan']);
    }

    public function getParticipantsAjax()
    {
        if ($this->request->isAJAX()) {
            $request = service('request');
            $model = new ParticipantModel();
            $eventID = session('eventID');

            $start  = (int) $request->getGet('start');
            $length = (int) $request->getGet('length');
            $search = $request->getGet('search')['value'];

            $orderColumnIndex = $request->getGet('order')[0]['column'];
            $orderDir = $request->getGet('order')[0]['dir'];
            $columns = ['invoice', 'firstname', 'bib', 'idNumber', 'phonenumber', 'jerseySize', 'race_category', 'status_repc'];

            $orderBy = $columns[$orderColumnIndex] ?? 'id';


            // Builder dasar
            $builder = $model->where('eventID', $eventID);

            // Filter pencarian jika ada
            if (!empty($search)) {
                $builder->groupStart()
                    ->like('firstname', $search)
                    ->orLike('lastname', $search)
                    ->orLike('idNumber', $search)
                    ->orLike('invoice', $search)
                    ->orLike('bib', $search)
                    ->groupEnd();
            }

            // Hitung total tanpa reset
            $totalRecords = $builder->countAllResults(false);

            // Ambil data paginated
            $data = $builder->orderBy($orderBy, $orderDir)
                ->limit($length, $start)
                ->get()
                ->getResultArray();


            return $this->response->setJSON([
                'draw' => intval($request->getGet('draw')),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $data
            ]);
        }

        // Jika bukan AJAX
        return redirect()->to('/');
    }







    public function searchParticipant()
    {
        return view('operator/SearchBarcode', ['title' => 'search Participant']);
    }


    public function searchParticipantAjax()
    {
        $keyword = trim($this->request->getGet('keyword'));
        $eventID = session('eventID');

        log_message('debug', '🔎 Keyword: ' . $keyword);
        log_message('debug', '📌 Session EventID: ' . $eventID);

        $model = new \App\Models\ParticipantModel();
        $results = $model->searchByKeywordWithEvent($keyword, $eventID);

        log_message('debug', '📦 Results: ' . json_encode($results));

        return $this->response->setJSON($results);
    }




    public function checkoutStart()
    {
        $id = $this->request->getPost('id');

        $model = new \App\Models\ParticipantModel();

        $model->update($id, [
            'processed_at' => date('Y-m-d H:i:s'),
            'processed_by' => session('user_id') // jika belum terset di awal
        ]);

        return $this->response->setJSON(['status' => true]);
    }


    public function checkout()
    {
        $id         = $this->request->getPost('id');
        $wakil      = $this->request->getPost('wakil');
        $nama       = $this->request->getPost('nama_pengambil');
        $telp       = $this->request->getPost('telp_pengambil');
        $bibScan    = $this->request->getPost('bib_scan');

        $model = new ParticipantModel();

        // ✅ Ambil data peserta berdasarkan ID
        $row = $model->find($id);

        // ❌ Jika tidak ditemukan
        if (!$row) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data peserta tidak ditemukan.'
            ]);
        }

        // ❌ Jika sudah check out sebelumnya
        if ($row['status_repc'] === 'Done') {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Peserta ini sudah di-check out sebelumnya.'
            ]);
        }

        // ✅ Siapkan data untuk update
        $data = [
            'status_repc'    => 'Done',
            'processed_End' => date('Y-m-d H:i:s'),
            'status_wakil'   => $wakil === 'diwakilkan' ? 'Diwakilkan' : 'Sendiri',
            'wakil_name'     => $wakil === 'diwakilkan' ? $nama : null,
            'wakil_phone'    => $wakil === 'diwakilkan' ? $telp : null,
            'check_bib'      => $bibScan
        ];

        // ✅ Simpan update
        $model->update($id, $data);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Check out berhasil!'
        ]);
    }
}
