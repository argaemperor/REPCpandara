<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('id_ID');

        for ($i = 0; $i < 20000; $i++) {
            $data = [
                'name'       => $faker->name,
                'email'      => $faker->email,
                'phone'      => $faker->numerify('08##########'),
                'address'    => $faker->address,
                'Username'   => $faker->userName,
                'Password'   => password_hash('password123', PASSWORD_DEFAULT),
                'Level'      => $faker->randomElement([0, 1, 2]), // 0=user, 1=staff, 2=admin
                'created_at' => $faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'),
            ];

            $this->db->table('users')->insert($data);
        }
    }
}
