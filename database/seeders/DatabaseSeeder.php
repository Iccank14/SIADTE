<?php

namespace Database\Seeders;

use App\Models\Archive;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@arsip.com',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'User Demo',
            'email' => 'user@arsip.com',
            'password' => Hash::make('password123'),
        ]);

        $categories = ['Surat', 'Laporan', 'Dokumen', 'Sertifikat', 'Ijazah', 'Kontrak'];
        
        for ($i = 1; $i <= 20; $i++) {
            Archive::create([
                'document_number' => 'DOC-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'title' => 'Contoh Dokumen ' . $i,
                'description' => 'Ini adalah contoh dokumen arsip ke-' . $i,
                'category' => $categories[array_rand($categories)],
                'file_path' => 'archives/sample.pdf',
                'archive_date' => now()->subDays(rand(1, 365))->format('Y-m-d'),
                'user_id' => 1
            ]);
        }
    }
}