<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin Bengkel
        $admin = User::firstOrCreate(
            ['email' => 'admin@tarida.com'],
            [
                'name' => 'Admin Tarida Motor',
                'password' => Hash::make('admin123')
            ]
        );

        // 2. Kategori Produk (Sesuai Permintaan Lengkap)
        $categoriesData = [
            [
                'nama_kategori' => 'Oli Mesin',
                'deskripsi' => 'Pelumas mesin 4 tak, matic, dan 2 tak berbagai merk resmi',
                'icon' => 'fa-oil-can'
            ],
            [
                'nama_kategori' => 'Oli Gardan',
                'deskripsi' => 'Oli transmisi & gardan khusus motor matic',
                'icon' => 'fa-gears'
            ],
            [
                'nama_kategori' => 'Pelumas / Gemuk',
                'deskripsi' => 'Grease CVT high-temp, chain lube, stempet & anti-karat',
                'icon' => 'fa-spray-can-sparkles'
            ],
            [
                'nama_kategori' => 'Ban Dalam & Ban Luar',
                'deskripsi' => 'Ban tubeless, standar, dan ban dalam semua ukuran ring',
                'icon' => 'fa-circle-notch'
            ],
            [
                'nama_kategori' => 'Sparepart CVT',
                'deskripsi' => 'V-Belt, roller, kampas ganda, slider, per CVT matic',
                'icon' => 'fa-cog'
            ],
            [
                'nama_kategori' => 'Baut dan Mur',
                'deskripsi' => 'Baut body klip, baut knalpot, mur roda, baut piringan cakram',
                'icon' => 'fa-screwdriver-wrench'
            ],
            [
                'nama_kategori' => 'Pewarna / Pilox',
                'deskripsi' => 'Cat semprot aerosol Diton Premium, Samurai Paint, RJ London',
                'icon' => 'fa-paint-roller'
            ],
            [
                'nama_kategori' => 'Umum (Perlengkapan Bengkel)',
                'deskripsi' => 'Tali tis, amplas, lem gasket tribond, cairan karbu cleaner, isolasi',
                'icon' => 'fa-toolbox'
            ],
            [
                'nama_kategori' => 'Kampas & Sistem Rem',
                'deskripsi' => 'Kampas rem tromol & cakram depan/belakang, minyak rem',
                'icon' => 'fa-shield-halved'
            ],
            [
                'nama_kategori' => 'Aki & Kelistrikan',
                'deskripsi' => 'Aki kering GTZ5S, busi standar/iridium, sekring tancap, bohlam',
                'icon' => 'fa-bolt'
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cData) {
            $categories[$cData['nama_kategori']] = Category::create($cData);
        }

        // 3. Produk-Produk Bengkel Lengkap Sesuai Kategori
        $productsData = [
            // Kategori: Oli Mesin
            [
                'category_id' => $categories['Oli Mesin']->id,
                'kode_barang' => 'OLI-MPX2-08',
                'nama_barang' => 'Oli Mesin AHM MPX 2 0.8L (Matic)',
                'tipe_kendaraan' => 'Honda Beat, Vario, Scoopy, Genio',
                'tahun' => 'Semua',
                'satuan' => 'Botol',
                'harga_beli' => 46000,
                'harga_jual' => 55000,
                'stok' => 45,
                'keterangan' => 'Oli original AHM SAE 10W-30'
            ],
            [
                'category_id' => $categories['Oli Mesin']->id,
                'kode_barang' => 'OLI-SPX2-08',
                'nama_barang' => 'Oli Mesin AHM SPX 2 Full Synthetic 0.8L',
                'tipe_kendaraan' => 'Honda Vario 160, PCX 160, ADV 160',
                'tahun' => 'Semua',
                'satuan' => 'Botol',
                'harga_beli' => 59000,
                'harga_jual' => 69000,
                'stok' => 20,
                'keterangan' => 'Full Synthetic Original AHM'
            ],
            [
                'category_id' => $categories['Oli Mesin']->id,
                'kode_barang' => 'OLI-YML-SS',
                'nama_barang' => 'Oli Yamalube Super Matic 1.0L',
                'tipe_kendaraan' => 'Yamaha NMAX, Aerox 155, Lexi',
                'tahun' => 'Semua',
                'satuan' => 'Botol',
                'harga_beli' => 63000,
                'harga_jual' => 75000,
                'stok' => 25,
                'keterangan' => 'SL 10W-40 JASO MB Yamaha'
            ],
            [
                'category_id' => $categories['Oli Mesin']->id,
                'kode_barang' => 'OLI-EVL-2T',
                'nama_barang' => 'Oli Samping Evalube 2T 0.7L',
                'tipe_kendaraan' => 'RX King, F1ZR, Ninja 150 2T',
                'tahun' => 'Semua',
                'satuan' => 'Botol',
                'harga_beli' => 28000,
                'harga_jual' => 35000,
                'stok' => 18,
                'keterangan' => 'Oli samping wangi legendaris'
            ],
            [
                'category_id' => $categories['Oli Mesin']->id,
                'kode_barang' => 'OLI-SHL-AX7',
                'nama_barang' => 'Shell Advance AX7 Scooter 10W-40 0.8L',
                'tipe_kendaraan' => 'Semua Motor Matic',
                'tahun' => 'Semua',
                'satuan' => 'Botol',
                'harga_beli' => 48000,
                'harga_jual' => 58000,
                'stok' => 15,
                'keterangan' => 'Semi Synthetic Shell PurePlus'
            ],

            // Kategori: Oli Gardan
            [
                'category_id' => $categories['Oli Gardan']->id,
                'kode_barang' => 'GRD-AHM-120',
                'nama_barang' => 'Oli Gardan AHM Gear Matic 120ml',
                'tipe_kendaraan' => 'Beat, Scoopy, Vario, Spacy',
                'tahun' => 'Semua',
                'satuan' => 'Tube',
                'harga_beli' => 14000,
                'harga_jual' => 18000,
                'stok' => 50,
                'keterangan' => 'Oli transmisi final gear matic Honda'
            ],
            [
                'category_id' => $categories['Oli Gardan']->id,
                'kode_barang' => 'GRD-YML-100',
                'nama_barang' => 'Oli Gardan Yamalube Gear Motor Matic 100ml',
                'tipe_kendaraan' => 'Mio, Fazzio, Soul GT, Gear 125',
                'tahun' => 'Semua',
                'satuan' => 'Tube',
                'harga_beli' => 13500,
                'harga_jual' => 18000,
                'stok' => 35,
                'keterangan' => 'Gear oil Yamaha original'
            ],
            [
                'category_id' => $categories['Oli Gardan']->id,
                'kode_barang' => 'GRD-CST-120',
                'nama_barang' => 'Oli Gardan Castrol Scooter Gear Oil 120ml',
                'tipe_kendaraan' => 'Universal Motor Matic',
                'tahun' => 'Semua',
                'satuan' => 'Tube',
                'harga_beli' => 16000,
                'harga_jual' => 22000,
                'stok' => 20,
                'keterangan' => '80W-90 GL-5 Perlindungan Gigi Gardan'
            ],

            // Kategori: Pelumas / Gemuk
            [
                'category_id' => $categories['Pelumas / Gemuk']->id,
                'kode_barang' => 'PLM-CVT-TOP1',
                'nama_barang' => 'Grease CVT High Temp TOP1 (Kuning)',
                'tipe_kendaraan' => 'Semua Motor Matic',
                'tahun' => 'Semua',
                'satuan' => 'Cup',
                'harga_beli' => 18000,
                'harga_jual' => 25000,
                'stok' => 30,
                'keterangan' => 'Tahan panas tinggi untuk pulley primer & sekunder'
            ],
            [
                'category_id' => $categories['Pelumas / Gemuk']->id,
                'kode_barang' => 'PLM-CHN-LUBE',
                'nama_barang' => 'Megacools Chain Lube Pelumas Rantai 300ml',
                'tipe_kendaraan' => 'Motor Bebek & Sport Berantai',
                'tahun' => 'Semua',
                'satuan' => 'Kaleng',
                'harga_beli' => 22000,
                'harga_jual' => 30000,
                'stok' => 14,
                'keterangan' => 'Formula anti debu dan anti cipratan'
            ],
            [
                'category_id' => $categories['Pelumas / Gemuk']->id,
                'kode_barang' => 'PLM-WD40-150',
                'nama_barang' => 'WD-40 Multi-Use Anti Karat Semprot 150ml',
                'tipe_kendaraan' => 'Universal',
                'tahun' => 'Semua',
                'satuan' => 'Kaleng',
                'harga_beli' => 32000,
                'harga_jual' => 42000,
                'stok' => 12,
                'keterangan' => 'Pelumas baut macet & pembersih karat'
            ],
            [
                'category_id' => $categories['Pelumas / Gemuk']->id,
                'kode_barang' => 'PLM-STP-COBRA',
                'nama_barang' => 'Gemuk / Stempet Cobra Putih 100gr',
                'tipe_kendaraan' => 'Universal Bearing & As Roda',
                'tahun' => 'Semua',
                'satuan' => 'Cup',
                'harga_beli' => 9000,
                'harga_jual' => 15000,
                'stok' => 25,
                'keterangan' => 'Pelumas laher bearing & komstir'
            ],

            // Kategori: Ban Dalam & Ban Luar
            [
                'category_id' => $categories['Ban Dalam & Ban Luar']->id,
                'kode_barang' => 'BAN-FDR-9080',
                'nama_barang' => 'Ban Luar FDR Sport Zevo 90/80-14 Tubeless',
                'tipe_kendaraan' => 'Beat, Vario, Scoopy Ring 14',
                'tahun' => 'Semua',
                'satuan' => 'Pcs',
                'harga_beli' => 195000,
                'harga_jual' => 235000,
                'stok' => 8,
                'keterangan' => 'Compound grip optimal basah & kering'
            ],
            [
                'category_id' => $categories['Ban Dalam & Ban Luar']->id,
                'kode_barang' => 'BAN-MXX-10080',
                'nama_barang' => 'Ban Luar Maxxis Victra S98 100/80-14 Tubeless',
                'tipe_kendaraan' => 'Vario 125/150 Belakang Ring 14',
                'tahun' => 'Semua',
                'satuan' => 'Pcs',
                'harga_beli' => 245000,
                'harga_jual' => 295000,
                'stok' => 6,
                'keterangan' => 'Dual compound grip cornering'
            ],
            [
                'category_id' => $categories['Ban Dalam & Ban Luar']->id,
                'kode_barang' => 'BAN-DLM-FED14',
                'nama_barang' => 'Ban Dalam Federal 2.50/2.75-14 (70/90-14)',
                'tipe_kendaraan' => 'Velg Jari-jari Ring 14',
                'tahun' => 'Semua',
                'satuan' => 'Pcs',
                'harga_beli' => 32000,
                'harga_jual' => 45000,
                'stok' => 25,
                'keterangan' => 'Karet lentur tebal tahan bocor'
            ],
            [
                'category_id' => $categories['Ban Dalam & Ban Luar']->id,
                'kode_barang' => 'BAN-DLM-IRC17',
                'nama_barang' => 'Ban Dalam IRC 2.50/2.75-17',
                'tipe_kendaraan' => 'Bebek & Sport Ring 17 (Supra, Jupiter)',
                'tahun' => 'Semua',
                'satuan' => 'Pcs',
                'harga_beli' => 33000,
                'harga_jual' => 45000,
                'stok' => 20,
                'keterangan' => 'Standar OEM pabrikan'
            ],

            // Kategori: Sparepart CVT
            [
                'category_id' => $categories['Sparepart CVT']->id,
                'kode_barang' => 'CVT-BELT-K25',
                'nama_barang' => 'V-Belt Kit AHM Beat FI / Scoopy FI (K25)',
                'tipe_kendaraan' => 'Beat FI Starter Kasar / Spacy FI',
                'tahun' => '2012-2014',
                'satuan' => 'Set',
                'harga_beli' => 98000,
                'harga_jual' => 125000,
                'stok' => 10,
                'keterangan' => 'Paket belt + roller original Honda'
            ],
            [
                'category_id' => $categories['Sparepart CVT']->id,
                'kode_barang' => 'CVT-BELT-2DP',
                'nama_barang' => 'V-Belt Original Yamaha NMAX 155 (2DP)',
                'tipe_kendaraan' => 'NMAX Old 155',
                'tahun' => '2015-2019',
                'satuan' => 'Pcs',
                'harga_beli' => 110000,
                'harga_jual' => 135000,
                'stok' => 9,
                'keterangan' => 'Sabuk penggerak CVT original YGP'
            ],
            [
                'category_id' => $categories['Sparepart CVT']->id,
                'kode_barang' => 'CVT-ROL-K44-09',
                'nama_barang' => 'Roller Racing K44 9 Gram (Isi 6 Pcs)',
                'tipe_kendaraan' => 'Beat ESP, Vario 110 ESP, Scoopy ESP',
                'tahun' => '2015-2020',
                'satuan' => 'Set',
                'harga_beli' => 45000,
                'harga_jual' => 65000,
                'stok' => 15,
                'keterangan' => 'Akselerasi tarikan bawah lebih enteng'
            ],
            [
                'category_id' => $categories['Sparepart CVT']->id,
                'kode_barang' => 'CVT-GND-DTN',
                'nama_barang' => 'Kampas Ganda Daytona Hi-Carbon Racing',
                'tipe_kendaraan' => 'Vario 125/150, PCX 150',
                'tahun' => 'Semua',
                'satuan' => 'Set',
                'harga_beli' => 125000,
                'harga_jual' => 160000,
                'stok' => 7,
                'keterangan' => 'Anti gredek CVT putaran bawah'
            ],
            [
                'category_id' => $categories['Sparepart CVT']->id,
                'kode_barang' => 'CVT-SLD-K44',
                'nama_barang' => 'Slider Piece Pulley Rumah Roller Beat/Vario (Set 3 Pcs)',
                'tipe_kendaraan' => 'Beat, Vario 110/125/150',
                'tahun' => 'Semua',
                'satuan' => 'Set',
                'harga_beli' => 12000,
                'harga_jual' => 20000,
                'stok' => 30,
                'keterangan' => 'Bantalan damper tutup rumah roller'
            ],

            // Kategori: Baut dan Mur
            [
                'category_id' => $categories['Baut dan Mur']->id,
                'kode_barang' => 'BAUT-BDY-KLIP',
                'nama_barang' => 'Baut Klip Body Honda 5x16 (Isi 10 Pcs)',
                'tipe_kendaraan' => 'Semua Motor Honda',
                'tahun' => 'Semua',
                'satuan' => 'Bungkus',
                'harga_beli' => 12000,
                'harga_jual' => 20000,
                'stok' => 40,
                'keterangan' => 'Baut body obeng plus + plat klip jepit'
            ],
            [
                'category_id' => $categories['Baut dan Mur']->id,
                'kode_barang' => 'BAUT-KNP-M8',
                'nama_barang' => 'Baut Flange Knalpot M8 x 25 Kunci 12',
                'tipe_kendaraan' => 'Universal Motor',
                'tahun' => 'Semua',
                'satuan' => 'Pcs',
                'harga_beli' => 2500,
                'harga_jual' => 5000,
                'stok' => 80,
                'keterangan' => 'Baut topi anti slek bahan baja galvanis'
            ],
            [
                'category_id' => $categories['Baut dan Mur']->id,
                'kode_barang' => 'MUR-RDA-M16',
                'nama_barang' => 'Mur As Roda Belakang Matic M16 Kunci 24',
                'tipe_kendaraan' => 'Matic Honda / Yamaha',
                'tahun' => 'Semua',
                'satuan' => 'Pcs',
                'harga_beli' => 6000,
                'harga_jual' => 12000,
                'stok' => 35,
                'keterangan' => 'Mur pengunci as roda belakang tebal'
            ],
            [
                'category_id' => $categories['Baut dan Mur']->id,
                'kode_barang' => 'BAUT-CKR-M8',
                'nama_barang' => 'Baut Piringan Cakram M8 x 20 Kepala Payung Bintang',
                'tipe_kendaraan' => 'Honda & Yamaha Cakram Depan',
                'tahun' => 'Semua',
                'satuan' => 'Pcs',
                'harga_beli' => 3000,
                'harga_jual' => 6000,
                'stok' => 50,
                'keterangan' => 'Kunci L bintang T40 baut piringan'
            ],

            // Kategori: Pewarna / Pilox
            [
                'category_id' => $categories['Pewarna / Pilox']->id,
                'kode_barang' => 'PLX-DTN-BLKD',
                'nama_barang' => 'Diton Premium 9184 Hitam Doff (Matte Black) 400cc',
                'tipe_kendaraan' => 'Universal Velg & Body',
                'tahun' => 'Semua',
                'satuan' => 'Kaleng',
                'harga_beli' => 28000,
                'harga_jual' => 38000,
                'stok' => 16,
                'keterangan' => 'Tahan bensin dan tidak retak'
            ],
            [
                'category_id' => $categories['Pewarna / Pilox']->id,
                'kode_barang' => 'PLX-DTN-CLR',
                'nama_barang' => 'Diton Premium 9128 Clear Gloss / Pernis Kilap 400cc',
                'tipe_kendaraan' => 'Universal Body',
                'tahun' => 'Semua',
                'satuan' => 'Kaleng',
                'harga_beli' => 28000,
                'harga_jual' => 38000,
                'stok' => 20,
                'keterangan' => 'Lapisan finishing anti pudar kilap basah'
            ],
            [
                'category_id' => $categories['Pewarna / Pilox']->id,
                'kode_barang' => 'PLX-SMR-EPX',
                'nama_barang' => 'Samurai Paint Epoxy 2K04 Grey Primer 400ml',
                'tipe_kendaraan' => 'Universal',
                'tahun' => 'Semua',
                'satuan' => 'Kaleng',
                'harga_beli' => 68000,
                'harga_jual' => 85000,
                'stok' => 10,
                'keterangan' => 'Pondasi cat anti karat 2 komponen dalam kaleng'
            ],
            [
                'category_id' => $categories['Pewarna / Pilox']->id,
                'kode_barang' => 'PLX-DTN-SLV',
                'nama_barang' => 'Diton Premium 9180 Silver Metallic 400cc',
                'tipe_kendaraan' => 'Universal Crankcase & Velg',
                'tahun' => 'Semua',
                'satuan' => 'Kaleng',
                'harga_beli' => 28000,
                'harga_jual' => 38000,
                'stok' => 14,
                'keterangan' => 'Warna perak metallic butiran halus'
            ],

            // Kategori: Umum (Perlengkapan Bengkel)
            [
                'category_id' => $categories['Umum (Perlengkapan Bengkel)']->id,
                'kode_barang' => 'UMM-TIS-15CM',
                'nama_barang' => 'Tali Tis / Cable Ties Hitam 15cm (1 Pack 100 Pcs)',
                'tipe_kendaraan' => 'Universal Ikat Kabel & Cover Body',
                'tahun' => 'Semua',
                'satuan' => 'Bungkus',
                'harga_beli' => 8000,
                'harga_jual' => 15000,
                'stok' => 45,
                'keterangan' => 'Nylon pengikat kabel kuat tahan putus'
            ],
            [
                'category_id' => $categories['Umum (Perlengkapan Bengkel)']->id,
                'kode_barang' => 'UMM-TIS-20CM',
                'nama_barang' => 'Tali Tis / Cable Ties Hitam 20cm (1 Pack 100 Pcs)',
                'tipe_kendaraan' => 'Universal Bengkel',
                'tahun' => 'Semua',
                'satuan' => 'Bungkus',
                'harga_beli' => 12000,
                'harga_jual' => 20000,
                'stok' => 30,
                'keterangan' => 'Ukuran tebal 3.6mm panjang 200mm'
            ],
            [
                'category_id' => $categories['Umum (Perlengkapan Bengkel)']->id,
                'kode_barang' => 'UMM-AMP-1000',
                'nama_barang' => 'Amplas Halus Nikken No. 1000 Waterproof',
                'tipe_kendaraan' => 'Universal Haluskan Cat & Kampas',
                'tahun' => 'Semua',
                'satuan' => 'Lembar',
                'harga_beli' => 3000,
                'harga_jual' => 6000,
                'stok' => 60,
                'keterangan' => 'Kertas amplas silikon karbida tahan air'
            ],
            [
                'category_id' => $categories['Umum (Perlengkapan Bengkel)']->id,
                'kode_barang' => 'UMM-AMP-240',
                'nama_barang' => 'Amplas Kasar No. 240',
                'tipe_kendaraan' => 'Universal Gosok Karat & Besi',
                'tahun' => 'Semua',
                'satuan' => 'Lembar',
                'harga_beli' => 3000,
                'harga_jual' => 6000,
                'stok' => 50,
                'keterangan' => 'Amplas gosok paking & permukaan kasar'
            ],
            [
                'category_id' => $categories['Umum (Perlengkapan Bengkel)']->id,
                'kode_barang' => 'UMM-LEM-THB',
                'nama_barang' => 'Lem Gasket ThreeBond 1104 Silikon Paking Mesin',
                'tipe_kendaraan' => 'Universal Blok Mesin & CVT',
                'tahun' => 'Semua',
                'satuan' => 'Tube',
                'harga_beli' => 22000,
                'harga_jual' => 30000,
                'stok' => 22,
                'keterangan' => 'Lem paking karet tahan oli dan panas'
            ],
            [
                'category_id' => $categories['Umum (Perlengkapan Bengkel)']->id,
                'kode_barang' => 'UMM-CRB-CLN',
                'nama_barang' => 'Megacools Carb Cleaner & Injector Cleaner 500ml',
                'tipe_kendaraan' => 'Universal Karburator & Throttle Body',
                'tahun' => 'Semua',
                'satuan' => 'Kaleng',
                'harga_beli' => 24000,
                'harga_jual' => 35000,
                'stok' => 18,
                'keterangan' => 'Cairan semprot pembersih kerak karbon membandel'
            ],
            [
                'category_id' => $categories['Umum (Perlengkapan Bengkel)']->id,
                'kode_barang' => 'UMM-ISL-NIT',
                'nama_barang' => 'Isolasi Listrik Kabel Nitto Hitam 3/4 x 20M',
                'tipe_kendaraan' => 'Universal Sambungan Kabel Motor',
                'tahun' => 'Semua',
                'satuan' => 'Roll',
                'harga_beli' => 6000,
                'harga_jual' => 10000,
                'stok' => 35,
                'keterangan' => 'Isolasi lentur rekat kuat standar bengkel'
            ],

            // Kategori: Kampas & Sistem Rem
            [
                'category_id' => $categories['Kampas & Sistem Rem']->id,
                'kode_barang' => 'KMP-DPN-AHM',
                'nama_barang' => 'Kampas Rem Depan Cakram Honda Beat / Vario FI (AHM)',
                'tipe_kendaraan' => 'Beat FI, Vario 110/125/150, Scoopy',
                'tahun' => '2014-2023',
                'satuan' => 'Set',
                'harga_beli' => 28000,
                'harga_jual' => 38000,
                'stok' => 28,
                'keterangan' => 'Brake pad original AHM pakem & awet'
            ],
            [
                'category_id' => $categories['Kampas & Sistem Rem']->id,
                'kode_barang' => 'KMP-BLK-TRM',
                'nama_barang' => 'Kampas Rem Belakang Tromol Honda (Brake Shoe)',
                'tipe_kendaraan' => 'Beat, Vario, Spacy, Scoopy Belakang',
                'tahun' => 'Semua',
                'satuan' => 'Set',
                'harga_beli' => 33000,
                'harga_jual' => 45000,
                'stok' => 25,
                'keterangan' => 'Kampas tromol original anti licin'
            ],
            [
                'category_id' => $categories['Kampas & Sistem Rem']->id,
                'kode_barang' => 'MIN-REM-JMB',
                'nama_barang' => 'Minyak Rem Jumbo Brake Fluid DOT 3 Merah 50ml',
                'tipe_kendaraan' => 'Universal Motor Cakram',
                'tahun' => 'Semua',
                'satuan' => 'Botol',
                'harga_beli' => 6500,
                'harga_jual' => 12000,
                'stok' => 30,
                'keterangan' => 'Titik didih tinggi rem tidak blong'
            ],

            // Kategori: Aki & Kelistrikan
            [
                'category_id' => $categories['Aki & Kelistrikan']->id,
                'kode_barang' => 'AKI-GS-GTZ5S',
                'nama_barang' => 'Aki Motor GS Astra Kering GTZ5S 12V 3.5Ah',
                'tipe_kendaraan' => 'Beat, Vario 110, Mio M3, Scoopy',
                'tahun' => 'Semua',
                'satuan' => 'Pcs',
                'harga_beli' => 165000,
                'harga_jual' => 205000,
                'stok' => 8,
                'keterangan' => 'Aki kering MF bebas perawatan langsung pasang'
            ],
            [
                'category_id' => $categories['Aki & Kelistrikan']->id,
                'kode_barang' => 'BUS-NGK-CPR9',
                'nama_barang' => 'Busi NGK CPR9EA-9 Standar Beat FI / Vario 125/150',
                'tipe_kendaraan' => 'Beat ESP, Vario 125/150, PCX 150',
                'tahun' => 'Semua',
                'satuan' => 'Pcs',
                'harga_beli' => 15000,
                'harga_jual' => 23000,
                'stok' => 40,
                'keterangan' => 'Pengapian stabil mesin mudah distarter'
            ],
            [
                'category_id' => $categories['Aki & Kelistrikan']->id,
                'kode_barang' => 'FUS-BLD-10A',
                'nama_barang' => 'Sekring Tancap Blade Fuse 10A Merah (Isi 5 Pcs)',
                'tipe_kendaraan' => 'Universal Motor Injeksi & Karbu',
                'tahun' => 'Semua',
                'satuan' => 'Bungkus',
                'harga_beli' => 4000,
                'harga_jual' => 8000,
                'stok' => 50,
                'keterangan' => 'Pengaman kelistrikan anti korsleting'
            ],
        ];

        foreach ($productsData as $p) {
            Product::create($p);
        }

        // 4. Data Contoh Jasa Bengkel
        Service::firstOrCreate(['nama_jasa' => 'Ganti Oli Mesin'], ['harga_jasa' => 10000]);
        Service::firstOrCreate(['nama_jasa' => 'Servis Ringan / Tune Up'], ['harga_jasa' => 35000]);
        Service::firstOrCreate(['nama_jasa' => 'Servis & Bersihkan CVT Matic'], ['harga_jasa' => 45000]);
        Service::firstOrCreate(['nama_jasa' => 'Ganti Kampas Rem'], ['harga_jasa' => 15000]);
        Service::firstOrCreate(['nama_jasa' => 'Pasang Ban Tubeless'], ['harga_jasa' => 20000]);

        // 5. Buat Contoh Transaksi Terakhir dengan 3 Metode Pembayaran (Cash, QRIS, Transfer BRI)
        // Transaksi 1: Tunai / Cash
        $prod1 = Product::where('kode_barang', 'OLI-MPX2-08')->first();
        $prod2 = Product::where('kode_barang', 'GRD-AHM-120')->first();
        $t1 = Transaction::create([
            'kode_transaksi' => 'TRD-20261011-0001',
            'user_id' => $admin->id,
            'nama_pelanggan' => 'Bambang Sudirjo',
            'nomor_polisi' => 'B 3892 KTZ',
            'metode_pembayaran' => 'Cash',
            'total_harga' => 73000,
            'bayar' => 100000,
            'kembalian' => 27000,
            'catatan' => 'Ganti oli mesin & oli gardan rutin',
            'created_at' => Carbon::now()->subHours(3),
            'updated_at' => Carbon::now()->subHours(3),
        ]);
        TransactionDetail::create([
            'transaction_id' => $t1->id,
            'product_id' => $prod1->id,
            'kode_barang' => $prod1->kode_barang,
            'nama_item' => $prod1->nama_barang,
            'satuan' => 'Botol',
            'jumlah' => 1,
            'harga_satuan' => 55000,
            'subtotal' => 55000,
        ]);
        TransactionDetail::create([
            'transaction_id' => $t1->id,
            'product_id' => $prod2->id,
            'kode_barang' => $prod2->kode_barang,
            'nama_item' => $prod2->nama_barang,
            'satuan' => 'Tube',
            'jumlah' => 1,
            'harga_satuan' => 18000,
            'subtotal' => 18000,
        ]);

        // Transaksi 2: QRIS
        $prod3 = Product::where('kode_barang', 'CVT-BELT-K25')->first();
        $prod4 = Product::where('kode_barang', 'PLM-CVT-TOP1')->first();
        $t2 = Transaction::create([
            'kode_transaksi' => 'TRD-20261011-0002',
            'user_id' => $admin->id,
            'nama_pelanggan' => 'Hendra Setiawan',
            'nomor_polisi' => 'B 6214 UVX',
            'metode_pembayaran' => 'QRIS',
            'total_harga' => 150000,
            'bayar' => 150000,
            'kembalian' => 0,
            'catatan' => 'Peremajaan transmisi CVT matic',
            'created_at' => Carbon::now()->subHours(2),
            'updated_at' => Carbon::now()->subHours(2),
        ]);
        TransactionDetail::create([
            'transaction_id' => $t2->id,
            'product_id' => $prod3->id,
            'kode_barang' => $prod3->kode_barang,
            'nama_item' => $prod3->nama_barang,
            'satuan' => 'Set',
            'jumlah' => 1,
            'harga_satuan' => 125000,
            'subtotal' => 125000,
        ]);
        TransactionDetail::create([
            'transaction_id' => $t2->id,
            'product_id' => $prod4->id,
            'kode_barang' => $prod4->kode_barang,
            'nama_item' => $prod4->nama_barang,
            'satuan' => 'Cup',
            'jumlah' => 1,
            'harga_satuan' => 25000,
            'subtotal' => 25000,
        ]);

        // Transaksi 3: Transfer Bank BRI
        $prod5 = Product::where('kode_barang', 'BAN-FDR-9080')->first();
        $prod6 = Product::where('kode_barang', 'UMM-TIS-15CM')->first();
        $t3 = Transaction::create([
            'kode_transaksi' => 'TRD-20261011-0003',
            'user_id' => $admin->id,
            'nama_pelanggan' => 'Rian Hidayat',
            'nomor_polisi' => 'B 4410 SRQ',
            'metode_pembayaran' => 'Transfer Bank BRI',
            'total_harga' => 250000,
            'bayar' => 250000,
            'kembalian' => 0,
            'catatan' => 'Ganti ban depan tubeless + tali tis',
            'created_at' => Carbon::now()->subMinutes(45),
            'updated_at' => Carbon::now()->subMinutes(45),
        ]);
        TransactionDetail::create([
            'transaction_id' => $t3->id,
            'product_id' => $prod5->id,
            'kode_barang' => $prod5->kode_barang,
            'nama_item' => $prod5->nama_barang,
            'satuan' => 'Pcs',
            'jumlah' => 1,
            'harga_satuan' => 235000,
            'subtotal' => 235000,
        ]);
        TransactionDetail::create([
            'transaction_id' => $t3->id,
            'product_id' => $prod6->id,
            'kode_barang' => $prod6->kode_barang,
            'nama_item' => $prod6->nama_barang,
            'satuan' => 'Bungkus',
            'jumlah' => 1,
            'harga_satuan' => 15000,
            'subtotal' => 15000,
        ]);
    }
}