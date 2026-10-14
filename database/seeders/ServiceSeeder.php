<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'kode_jasa' => 'JS-OLI-01',
                'nama_jasa' => 'Jasa Ganti Oli Mesin',
                'kategori_jasa' => 'Perawatan Rutin',
                'harga_jasa' => 10000,
                'keterangan' => 'Kuras oli lama, pasang ring baut oli, & isi oli mesin baru'
            ],
            [
                'kode_jasa' => 'JS-OLI-02',
                'nama_jasa' => 'Jasa Ganti Oli Gardan',
                'kategori_jasa' => 'Perawatan Rutin',
                'harga_jasa' => 5000,
                'keterangan' => 'Ganti oli transmisi gearbox matic'
            ],
            [
                'kode_jasa' => 'JS-BAN-01',
                'nama_jasa' => 'Jasa Pasang Ban Tubeless',
                'kategori_jasa' => 'Ban & Roda',
                'harga_jasa' => 20000,
                'keterangan' => 'Bongkar pasang ban tubeless + ganti pentil / cek presisi velg'
            ],
            [
                'kode_jasa' => 'JS-BAN-02',
                'nama_jasa' => 'Jasa Pasang Ban Dalam',
                'kategori_jasa' => 'Ban & Roda',
                'harga_jasa' => 15000,
                'keterangan' => 'Bongkar pasang ban dalam velg jari-jari / racing'
            ],
            [
                'kode_jasa' => 'JS-BAN-03',
                'nama_jasa' => 'Jasa Tambal Ban Tubeless',
                'kategori_jasa' => 'Ban & Roda',
                'harga_jasa' => 15000,
                'keterangan' => 'Tambal ban tubeless dengan karet tire string berkualitas'
            ],
            [
                'kode_jasa' => 'JS-CVT-01',
                'nama_jasa' => 'Jasa Servis & Bersihkan CVT Matic',
                'kategori_jasa' => 'Transmisi CVT',
                'harga_jasa' => 45000,
                'keterangan' => 'Bongkar pulley, amplas mangkok ganda, cuci part & grease CVT'
            ],
            [
                'kode_jasa' => 'JS-CVT-02',
                'nama_jasa' => 'Jasa Pasang V-Belt / Roller CVT',
                'kategori_jasa' => 'Transmisi CVT',
                'harga_jasa' => 25000,
                'keterangan' => 'Pemasangan sabuk penggerak atau set roller matic'
            ],
            [
                'kode_jasa' => 'JS-REM-01',
                'nama_jasa' => 'Jasa Ganti Kampas Rem Depan / Belakang',
                'kategori_jasa' => 'Pengereman',
                'harga_jasa' => 15000,
                'keterangan' => 'Bongkar kaliper / tromol, bersihkan & pasang kampas rem baru'
            ],
            [
                'kode_jasa' => 'JS-REM-02',
                'nama_jasa' => 'Jasa Kuras Minyak Rem & Bleeding',
                'kategori_jasa' => 'Pengereman',
                'harga_jasa' => 20000,
                'keterangan' => 'Kuras angin palsu minyak rem cakram depan/belakang'
            ],
            [
                'kode_jasa' => 'JS-TUNE-01',
                'nama_jasa' => 'Servis Ringan / Tune Up Mesin',
                'kategori_jasa' => 'Servis Mesin',
                'harga_jasa' => 35000,
                'keterangan' => 'Bersihkan filter udara, cek busi, setel gas & semprot cleaner'
            ],
            [
                'kode_jasa' => 'JS-KLS-01',
                'nama_jasa' => 'Jasa Pasang / Ganti Aki Motor',
                'kategori_jasa' => 'Kelistrikan',
                'harga_jasa' => 10000,
                'keterangan' => 'Pasang aki baru & cek voltase pengisian kiprok'
            ],
            [
                'kode_jasa' => 'JS-KLS-02',
                'nama_jasa' => 'Jasa Pasang Busi / Bohlam Lampu',
                'kategori_jasa' => 'Kelistrikan',
                'harga_jasa' => 5000,
                'keterangan' => 'Pasang busi baru atau ganti bohlam lampu motor'
            ],
            [
                'kode_jasa' => 'JS-RNT-01',
                'nama_jasa' => 'Jasa Pasang Rantai & Gear Set',
                'kategori_jasa' => 'Penggerak Roda',
                'harga_jasa' => 25000,
                'keterangan' => 'Pasang gear set & rantai roda serta setel ketegangan'
            ],
        ];

        foreach ($services as $s) {
            Service::updateOrCreate(
                ['nama_jasa' => $s['nama_jasa']],
                $s
            );
        }
    }
}
