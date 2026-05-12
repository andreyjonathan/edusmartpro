<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'SPP Bulanan', 'amount' => 500000, 'description' => 'Sumbangan Pembinaan Pendidikan bulanan'],
            ['name' => 'Uang Gedung', 'amount' => 2000000, 'description' => 'Biaya pembangunan sarana prasarana'],
            ['name' => 'Seragam', 'amount' => 750000, 'description' => 'Paket seragam sekolah'],
            ['name' => 'Uang Pendaftaran', 'amount' => 250000, 'description' => 'Biaya pendaftaran siswa baru'],
        ];

        foreach ($categories as $category) {
            \App\Models\PaymentCategory::create($category);
        }
    }
}
