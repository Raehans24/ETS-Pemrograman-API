<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupermarketSale;

class SupermarketSaleSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = database_path('seeders/supermarket_data.csv'); 

        if (($handle = fopen($csvFile, 'r')) !== FALSE) {
            fgetcsv($handle); // Lewati header

            $batch = [];
            $batchSize = 1000; // Masukkan per 1000 baris sekaligus

            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $batch[] = [
                    'invoice'      => $data[0] ?? null,
                    'stock_code'   => $data[1] ?? null,
                    'description'  => $data[2] ?? null,
                    'quantity'     => isset($data[3]) ? (int)$data[3] : null,
                    'invoice_date' => isset($data[4]) ? date('Y-m-d H:i:s', strtotime($data[4])) : null,
                    'price'        => isset($data[5]) ? (float)$data[5] : null,
                    'customer_id'  => $data[6] ?? null,
                    'country'      => $data[7] ?? null,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];

                // Jika batch sudah mencapai 1000 data, masukkan ke database secara sekaligus
                if (count($batch) >= $batchSize) {
                    SupermarketSale::insert($batch);
                    $batch = []; // Reset batch
                }
            }

            // Masukkan sisa data terakhir jika ada
            if (!empty($batch)) {
                SupermarketSale::insert($batch);
            }

            fclose($handle);
        }
    }
}
