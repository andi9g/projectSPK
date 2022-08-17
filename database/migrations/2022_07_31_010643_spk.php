<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Spk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $kriteria = [
            [
                'namakriteria' => 'Harga Rumah',
                'bobot' => 0.3,
                'typedata' => 'kurensi',
                'ket' => 'dinamis',
            ], [
                
                'namakriteria' => 'Type Rumah',
                'bobot' => 0.15,
                'typedata' => 'angka',
                'ket' => 'statis',
            ], [
                'namakriteria' =>'Luas Tanah',
                'bobot' => 0.15,
                'typedata' => 'angka',
                'ket' => 'statis',
            ], [
                'namakriteria' =>'Spesifikasi Rumah',
                'bobot' => 0.2,
                'typedata' => 'huruf',
                'ket' => 'statis',
            ], [
                'namakriteria' =>'Jarak Pusat Kota',
                'bobot' => 0.1,
                'typedata' => 'angka',
                'ket' => 'dinamis',
            ], [
                'namakriteria' =>'Kepadatan Penduduk',
                'bobot' => 0.1,
                'typedata' => 'huruf',
                'ket' => 'statis',
            ]
        ];

        $hargarumah = [
            '300000000', '200000000', '150000000','50000000'
        ];
        $typerumah = [
            '28','30','33','36'
        ];
        $luastanah = [
            '60',
            '72',
            '84',
            '90'
        ];
        $spesifikasirumah = [
            'Lantai Semen Kasar + Dinding Bata Merah',
            'Lantai Semen Halus + Dinding Batako',
            'Lantai Keramik Standar + Dinding Bata Ringan atau Hebel',
            'Lantai Keramik Teraso + Dinding GRC'
        ];
        $jarakpusatkota = [
            '5',
            '3',
            '1',
            '0',
        ];
        $kepadatanpenduduk = [
            'Tidak Banyak',
            'Cukup',
            'Banyak',
            'Sangat Banyak'
        ];

        Schema::create('kriteria', function (Blueprint $table) {
            $table->bigIncrements('idkriteria');
            $table->String('namakriteria')->unique();
            $table->float('bobot');
            $table->enum('typedata', ['angka','huruf','kurensi']);
            $table->enum('ket', ['statis','dinamis']);
            $table->timestamps();
        });

        Schema::create('nilai', function (Blueprint $table) {
            $table->bigIncrements('idnilai');
            $table->Integer('idkriteria');
            $table->String('ket');
            $table->integer('nilai');
            $table->timestamps();
        });

        Schema::create('instansi', function (Blueprint $table) {
            $table->bigIncrements('idinstansi');
            $table->String('namainstansi');
            $table->Text('alamat');
            $table->char('hp');
            $table->String('links')->nullable();
            $table->String('gambar')->nullable();
            $table->timestamps();
        });

        Schema::create('perumahan', function (Blueprint $table) {
            $table->bigIncrements('idperumahan');
            $table->Integer('idinstansi');
            $table->String('namaperumahan');
            $table->timestamps();
        });
        
        $id = 1;
        $nomor = 1;
        foreach ($kriteria as $item) {
            $ket = $item['ket'];
            $nama_k = str_replace(" ", "", strtolower($item['namakriteria']));

            DB::table('kriteria')->insert([
                'idkriteria' => $id,
                'namakriteria' => $item['namakriteria'],
                'bobot' => $item['bobot'],
                'typedata' => $item['typedata'],
                'ket' => $item['ket'],
            ]);

            if($item['namakriteria']=='Harga Rumah'){
                foreach ($hargarumah as $item2) {
                    DB::table('nilai')->insert([
                        'idkriteria' => $id,
                        'ket' => $item2,
                        'nilai' => $nomor++,
                    ]);
                }
                $nomor = 1;
            }

            if($item['namakriteria']=='Type Rumah'){
                foreach ($typerumah as $item2) {
                    DB::table('nilai')->insert([
                        'idkriteria' => $id,
                        'ket' => $item2,
                        'nilai' => $nomor++,
                    ]);
                }
                $nomor = 1;
            }

            if($item['namakriteria']=='Luas Tanah'){
                foreach ($luastanah as $item2) {
                    DB::table('nilai')->insert([
                        'idkriteria' => $id,
                        'ket' => $item2,
                        'nilai' => $nomor++,
                    ]);
                }
                $nomor = 1;
            }

            if($item['namakriteria']=='Spesifikasi Rumah'){
                foreach ($spesifikasirumah as $item2) {
                    DB::table('nilai')->insert([
                        'idkriteria' => $id,
                        'ket' => $item2,
                        'nilai' => $nomor++,
                    ]);
                }
                $nomor = 1;
            }
            if($item['namakriteria']=='Jarak Pusat Kota'){
                foreach ($jarakpusatkota as $item2) {
                    DB::table('nilai')->insert([
                        'idkriteria' => $id,
                        'ket' => $item2,
                        'nilai' => $nomor++,
                    ]);
                }
                $nomor = 1;
            }
            if($item['namakriteria']=='Kepadatan Penduduk'){
                foreach ($kepadatanpenduduk as $item2) {
                    DB::table('nilai')->insert([
                        'idkriteria' => $id,
                        'ket' => $item2,
                        'nilai' => $nomor++,
                    ]);
                }
                $nomor = 1;
            }
            $id++;

            DB::statement("ALTER TABLE perumahan ADD $nama_k bigint");

        }


        

        Schema::create('pengunjung', function (Blueprint $table) {
            $table->bigIncrements('idpengunjung');
            $table->String('username')->unique();
            $table->String('nama');
            $table->String('password');
            $table->timestamps();
        });

        Schema::create('admin', function (Blueprint $table) {
            $table->bigIncrements('idadmin');
            $table->String('username')->unique();
            $table->String('nama');
            $table->String('password');
            $table->timestamps();
        });

        Schema::create('laporan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->Integer('idpengunjung');
            $table->float('nilai');
            $table->String('namainstansi');
            $table->String('alamat');
            $table->String('links');
            $table->String('hp');
            $table->String('gambar')->nullable();
            $table->timestamps();
        });

        DB::table('pengunjung')->insert([
            'username' => 'pengunjung@gmail.com',
            'nama' => 'pengunjung',
            'password' => Hash::make('pengunjung'),
        ]);

        DB::table('admin')->insert([
            'username' => 'admin',
            'nama' => 'admin',
            'password' => Hash::make('admin'),
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
