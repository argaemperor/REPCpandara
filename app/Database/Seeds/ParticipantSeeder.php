<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class ParticipantSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('id_ID');
        $batchSize = 500;
        $total = 10000;
        $table = $this->db->table('master_regnow');

        for ($i = 0; $i < $total; $i += $batchSize) {
            $data = [];

            for ($j = 0; $j < $batchSize; $j++) {
                $data[] = [
                    'chipscode' => $faker->uuid,
                    'bib' => strtoupper($faker->bothify('BIB###')),
                    'date_reg' => $faker->date(),
                    'type_ofCode' => $faker->randomElement(['QR', 'BARCODE']),
                    'invoice' => 'INV' . $faker->numerify('#####'),
                    'reg_number' => strtoupper($faker->bothify('REG###')),
                    'race_category' => $faker->randomElement(['5K', '10K', '21K']),
                    'race_type' => $faker->randomElement(['Fun Run', 'Marathon']),
                    'period' => $faker->monthName(),
                    'teamname' => $faker->company,
                    'firstname' => $faker->firstName,
                    'lastname' => $faker->lastName,
                    'nameOnBib' => $faker->name,
                    'gender' => $faker->randomElement(['Male', 'Female']),
                    'code' => strtoupper($faker->bothify('CDE###')),
                    'birthdate' => $faker->date(),
                    'idNumber' => $faker->numerify('################'),
                    'chipCode' => $faker->numberBetween(1000, 9999),
                    'Age' => $faker->numberBetween(12, 65),
                    'blodType' => $faker->randomElement(['A', 'B', 'AB', 'O']),
                    'address' => $faker->address,
                    'VillageOffice' => $faker->citySuffix,
                    'SubDistrict' => $faker->city,
                    'city' => $faker->city,
                    'postalCode' => (int) $faker->postcode,
                    'province' => $faker->state,
                    'country' => 'Indonesia',
                    'nationality' => 'WNI',
                    'jerseySize' => $faker->randomElement(['S', 'M', 'L', 'XL', 'XXL']),
                    'finisher_teeSize' => $faker->randomElement(['S', 'M', 'L', 'XL', 'XXL']),
                    'EFT' => 'EFT-' . $faker->numerify('#####'),
                    'phonenumber' => $faker->phoneNumber,
                    'email_address' => $faker->email,
                    'medical_condition' => $faker->randomElement(['-', 'Asthma', 'None']),
                    'emergency_contactname' => $faker->name,
                    'emergency_contactnumber' => $faker->phoneNumber,
                    'relationship' => $faker->randomElement(['Saudara', 'Orang Tua', 'Teman']),
                    'running_community' => $faker->company,
                    'payment_method' => $faker->randomElement(['Transfer', 'QRIS', 'Gopay']),
                    'price' => '250000',
                    'discount' => '0',
                    'total_price' => '250000',
                    'payment_status' => $faker->randomElement(['Paid', 'Unpaid']),
                    'AddonsItem' => null,
                    'addonPrice' => 0,
                    'addonDeliveryAddress' => $faker->address,
                    'processed_by' => 1,
                    'status_repc' => $faker->randomElement(['Pending', 'Done', 'Proses']),
                    'processed_at' => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
                    'processed_End' => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
                    'status_wakil' => $faker->randomElement(['Sendiri', 'Diwakilkan']),
                    'wakil_name' => $faker->name,
                    'wakil_phone' => $faker->phoneNumber,
                    'repc_note' => $faker->sentence,
                    'check_bib' => 'Y',
                    'eventID' => $faker->numberBetween(1, 5)
                ];
            }

            $table->insertBatch($data);
        }
    }
}
