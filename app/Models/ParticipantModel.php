<?php

namespace App\Models;

use CodeIgniter\Model;

class ParticipantModel extends Model
{
    protected $table = 'master_regnow';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'invoice',
        'bib',
        'chipscode',
        'idNumber',
        'firstname',
        'lastname',
        'email_address',
        'jerseySize',
        'race_category',
        'phonenumber',

        // ✅ Tambahkan ini agar update berhasil:
        'status_repc',
        'processed_by',
        'processed_at',
        'processed_End',
        'status_wakil',
        'wakil_name',
        'wakil_phone',
        'check_bib'
    ];

    public function getActiveEventParticipants()
    {
        return $this->db->table($this->table)
            ->select('master_regnow.*, masterevent.eventName')
            ->join('masterevent', 'masterevent.eventId = master_regnow.eventID')
            ->where('masterevent.eventActive', 1)
            ->get()
            ->getResult();
    }

    public function searchByKeywordWithEvent($keyword, $eventID)
    {
        $keyword = trim($keyword); // Hilangkan spasi dari inputan

        return $this->db->table($this->table)
            ->select("{$this->table}.*,users.id AS processed_by_id, users.name AS processed_by")
            ->join('users', 'users.id = master_regnow.processed_by', 'left')
            ->where("master_regnow.eventID", $eventID)
            ->groupStart()
            ->where("REPLACE(REPLACE(TRIM(master_regnow.idNumber), ' ', ''), CHAR(160), '')", $keyword)
            ->orWhere("REPLACE(REPLACE(TRIM(master_regnow.invoice), ' ', ''), CHAR(160), '')", $keyword)
            ->groupEnd()
            ->limit(10)
            ->get()
            ->getResultArray();
    }

    public function debugSearch($keyword, $eventID)
    {
        $db = \Config\Database::connect();
        $sql = $db->table($this->table)
            ->select("id, idNumber, invoice, eventID, status_repc")
            ->where('eventID', $eventID)
            ->like('idNumber', $keyword)
            ->like('bib', $keyword)
            ->getCompiledSelect();

        dd($sql); // tampilkan query untuk cek
    }


    public function getDatatables($eventID, $start, $length, $search)
    {
        $builder = $this->db->table($this->table)
            ->select("{$this->table}.*, users.name AS processed_by")
            ->join('users', 'users.id = master_regnow.processed_by', 'left')
            ->where("master_regnow.eventID", $eventID);

        if (!empty($search)) {
            $builder->groupStart();
            $builder->like('master_regnow.firstname', $search);
            $builder->orLike('master_regnow.lastname', $search);
            $builder->orLike('master_regnow.invoice', $search);
            $builder->orLike('master_regnow.bib', $search);
            $builder->groupEnd();
        }

        $builder->limit($length, $start);
        return $builder->get()->getResult();
    }

    public function countAll($eventID)
    {
        return $this->where('eventID', $eventID)->countAllResults();
    }

    public function countFiltered($eventID, $search)
    {
        $builder = $this->db->table($this->table)
            ->where('eventID', $eventID);

        if (!empty($search)) {
            $builder->groupStart();
            $builder->like('firstname', $search);
            $builder->orLike('lastname', $search);
            $builder->orLike('invoice', $search);
            $builder->orLike('bib', $search);
            $builder->groupEnd();
        }

        return $builder->countAllResults();
    }
}
