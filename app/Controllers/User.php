<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class User extends BaseController
{


    public function Dashboard()
    {
        return view('user/dashboard', ['title' => 'Dashboard']);
    }

    public function ajaxList()
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
                'name' => esc($row['name']),
                'email' => esc($row['email']),
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
}
