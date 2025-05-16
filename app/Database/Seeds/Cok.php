<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Cok extends Seeder
{
    public function run()
    {
        $data = [
            'Username' => 'user',
            'Password' => password_hash('user', PASSWORD_BCRYPT), // hash aman
            'name'     => 'user',
            'email'    => 'user@example.com',
            'phone'    => '081234567890',
            'address'  => 'Jl. user 123',
            'Level'    => '2'
        ];

        $this->db->table('users')->insert($data);
    }
}
