<?php

namespace Database\Seeders;

use App\Models\MedicineInventory;
use Illuminate\Database\Seeder;

class MedicineInventorySeeder extends Seeder
{
    public function run(): void
    {
        $medicines = [
            // Pain Relief / Fever
            ['name' => 'Paracetamol 500mg',           'category' => 'Pain Relief / Fever',           'stock' => 500, 'unit' => 'tablets'],
            ['name' => 'Mefenamic Acid 500mg',         'category' => 'Pain Relief',                   'stock' => 200, 'unit' => 'tablets'],
            ['name' => 'Ibuprofen 200mg',              'category' => 'Pain Relief / Anti-inflammatory','stock' => 300, 'unit' => 'tablets'],
            // Antibiotic
            ['name' => 'Amoxicillin 500mg',            'category' => 'Antibiotic',                    'stock' => 150, 'unit' => 'capsules'],
            ['name' => 'Amoxicillin 250mg (Syrup)',    'category' => 'Antibiotic',                    'stock' => 30,  'unit' => 'bottles'],
            ['name' => 'Cotrimoxazole 400/80mg',       'category' => 'Antibiotic',                    'stock' => 100, 'unit' => 'tablets'],
            // Antihistamine
            ['name' => 'Cetirizine 10mg',              'category' => 'Antihistamine',                 'stock' => 100, 'unit' => 'tablets'],
            ['name' => 'Loratadine 10mg',              'category' => 'Antihistamine',                 'stock' => 0,   'unit' => 'tablets'],
            // Hypertension
            ['name' => 'Amlodipine 5mg',               'category' => 'Hypertension',                  'stock' => 300, 'unit' => 'tablets'],
            ['name' => 'Amlodipine 10mg',              'category' => 'Hypertension',                  'stock' => 200, 'unit' => 'tablets'],
            ['name' => 'Losartan 50mg',                'category' => 'Hypertension',                  'stock' => 250, 'unit' => 'tablets'],
            // Diabetes
            ['name' => 'Metformin 500mg',              'category' => 'Diabetes',                      'stock' => 25,  'unit' => 'tablets'],
            ['name' => 'Glibenclamide 5mg',            'category' => 'Diabetes',                      'stock' => 100, 'unit' => 'tablets'],
            // Supplements
            ['name' => 'Ferrous Sulfate 325mg',        'category' => 'Supplement',                    'stock' => 400, 'unit' => 'tablets'],
            ['name' => 'Vitamin A 10,000 IU',          'category' => 'Vitamin / Supplement',          'stock' => 1000,'unit' => 'capsules'],
            ['name' => 'Vitamin B Complex',            'category' => 'Vitamin / Supplement',          'stock' => 600, 'unit' => 'tablets'],
            ['name' => 'Multivitamins',                'category' => 'Vitamin / Supplement',          'stock' => 800, 'unit' => 'tablets'],
            ['name' => 'Ascorbic Acid 500mg',          'category' => 'Vitamin / Supplement',          'stock' => 500, 'unit' => 'tablets'],
            // Others
            ['name' => 'ORS (Oral Rehydration Salts)', 'category' => 'Rehydration',                   'stock' => 200, 'unit' => 'sachets'],
            ['name' => 'Salbutamol 2mg',               'category' => 'Respiratory',                   'stock' => 0,   'unit' => 'tablets'],
            ['name' => 'Omeprazole 20mg',              'category' => 'Gastrointestinal',               'stock' => 80,  'unit' => 'capsules'],
            ['name' => 'Antacid (Aluminum Hydroxide)', 'category' => 'Gastrointestinal',               'stock' => 150, 'unit' => 'tablets'],
        ];

        foreach ($medicines as $med) {
            MedicineInventory::firstOrCreate(
                ['name' => $med['name']],
                array_merge($med, ['is_available' => true])
            );
        }

        $this->command->info('Medicine inventory seeded: ' . count($medicines) . ' medicines.');
    }
}