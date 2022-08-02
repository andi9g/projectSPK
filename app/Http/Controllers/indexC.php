<?php

namespace App\Http\Controllers;

use App\Models\instansiM;
use App\Models\perumahanM;
use App\Models\nilaiM;
use App\Models\pengunjungM;
use App\Models\adminM;
use App\Models\laporanM;
use App\Models\kriteriaM;
use PDF;
use Illuminate\Http\Request;

class indexC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function root()
    {
        return redirect('welcome');
    }

    public function indexkembali()
    {
        return redirect('welcome#pencarian');
    }

    public function index()
    {
        $instansi = instansiM::get();

        $open = false;
        $dataharga = nilaiM::where('idkriteria', 1)->orderBy('ket', 'asc')->get();
        $nilaimin = 500000000000;
        foreach ($dataharga as $item) {
            if($nilaimin > $item->ket) {
                $nilaimin = $item->ket;
            }
        }

        $typerumah = nilaiM::select('idnilai','ket')
                     ->where('idkriteria', 2)->get();
        $luastanah = nilaiM::select('idnilai','ket')
        ->where('idkriteria', 3)->get();

        $spesifikasirumah = nilaiM::select('idnilai','ket')
        ->where('idkriteria', 4)->get();

        $kepadatanpenduduk = nilaiM::select('idnilai','ket')
        ->where('idkriteria', 6)->get();


        return view('pages.pagesindex', [
            'instansi' => $instansi,
            'nilaimin' => $nilaimin,
            'typerumah' => $typerumah,
            'luastanah' => $luastanah,
            'spesifikasirumah' => $spesifikasirumah,
            'kepadatanpenduduk' => $kepadatanpenduduk,
            'open' => $open,
        ]);
    }

    public function cari(Request $request)
    {
        $request->validate([
            'hargarumah' => 'required',
            'jarakpusatkota' => 'required',
            'typerumah' => 'required',
            'luastanah' => 'required',
            'spesifikasirumah' => 'required',
            'kepadatanpenduduk' => 'required',
        ]);
        
        $dataharga = nilaiM::where('idkriteria', 1)->orderBy('ket', 'asc')->get();
        $nilaiminharga = 500000000000;
        foreach ($dataharga as $item) {
            if($nilaiminharga > $item->ket) {
                $nilaiminharga = $item->ket;
            }
        }

        $ceekinstansi = instansiM::join('perumahan','perumahan.idinstansi', '=','instansi.idinstansi')->count();
        if($ceekinstansi == 0) {
            return redirect()->back()->with('warning','Maaf, Data instansi belum ditambahkan')->withInput();
        }

        $request_hargarumah = $request->hargarumah;

        if($request_hargarumah < $nilaiminharga) {
            return redirect('welcome#pencarian')->with('toast_error', 'Harga Rumah tidak valid');
        }

        $request_jarakpusatkota = $request->jarakpusatkota;
        $request_typerumah = $request->typerumah;
        $request_luastanah = $request->luastanah;
        $request_spesifikasirumah = $request->spesifikasirumah;
        $request_kepadatanpenduduk = $request->kepadatanpenduduk;
        //data tampung
        $hargarumah = [];
        $typerumah = [];
        $luastanah = [];
        $spesifikasirumah = [];
        $kepadatanpenduduk = [];

        $nilaiharga = [];
        $harga = [];
        $i = 1;
        $instansi = instansiM::get();
        $ambilHarga = nilaiM::select('ket')->where('idkriteria', 1)->get();
        foreach ($ambilHarga as $harga2) {
            $hargaArr[] = (int)($harga2->ket); 
        }

        $ambiljarak = nilaiM::select('ket')->where('idkriteria', 5)->get();
        foreach ($ambiljarak as $jarak2) {
            $jarakArr[] = (int)($jarak2->ket); 
        }

        
        rsort($jarakArr);
        rsort($hargaArr);

        $index = 0;
        foreach ($instansi as $instansi_) {
            $perumahan = perumahanM::where('idinstansi', $instansi_->idinstansi)->get();
            foreach ($perumahan as $perumahan_) {
                
                
                //HARGA RUMAH
                $hargaArrTutup = 0;
                foreach ($hargaArr as $harga_) {
                    if(($perumahan_->hargarumah > $harga_ && $request_hargarumah >= $perumahan_->hargarumah) && $hargaArrTutup == 0) {
                        $hargarumah[] =  nilaiM::where('ket', $harga_)->first()->nilai;
                        $hargaArrTutup++;
                    }
                }
                if($hargaArrTutup === 0) {
                    $hargarumah[] = 0;
                }

                //TYPE RUMAH
                if($perumahan_->typerumah == $request_typerumah) {
                    $typerumah[] = nilaiM::where('idnilai', $perumahan_->typerumah)->first()->nilai;
                }else {
                    $typerumah[] = 0;
                }
                //LUAS TANAH
                if($perumahan_->luastanah == $request_luastanah) {
                    $luastanah[] = nilaiM::where('idnilai', $perumahan_->luastanah)->first()->nilai;
                }else {
                    $luastanah[] = 0;
                }
                //SPESIFIKASI RUMAH
                if($perumahan_->spesifikasirumah == $request_spesifikasirumah) {
                    $spesifikasirumah[] = nilaiM::where('idnilai', $perumahan_->spesifikasirumah)->first()->nilai;
                }else{
                    $spesifikasirumah[] = 0;
                }
                //KEPADATAN PENDUDUK
                if($perumahan_->kepadatanpenduduk == $request_kepadatanpenduduk) {
                    $kepadatanpenduduk[] = nilaiM::where('idnilai', $perumahan_->kepadatanpenduduk)->first()->nilai;
                }else {
                    $kepadatanpenduduk[] = 0;
                }

                //JARAK PUSAT KOTA
                $jarakArrTutup = 0;
                foreach ($jarakArr as $jarak_) {
                    if(($perumahan_->jarakpusatkota > $jarak_ && $request_jarakpusatkota >= $perumahan_->jarakpusatkota) && $jarakArrTutup == 0) {
                        $jarakpusatkota[] =  nilaiM::where('ket', $jarak_)->first()->nilai;
                        $jarakArrTutup++;
                    }
                }
                if($jarakArrTutup === 0) {
                    $jarakpusatkota[] = 0;
                }

                //DATA INSTANSI
                $dataInstansi[$index]['namainstansi'] = $instansi_->namainstansi;  
                $dataInstansi[$index]['gambar'] = $instansi_->gambar;  
                $dataInstansi[$index]['alamat'] = $instansi_->alamat;  
                $dataInstansi[$index]['hp'] = $instansi_->hp;  
                $dataInstansi[$index]['perumahan'] = $perumahan_->namaperumahan;  

                $index++;
            }
        }

        for ($i=0; $i < count($dataInstansi); $i++) { 
            //min, max, selisih harga
            $hargarumahManipulasi = $hargarumah;
            rsort($hargarumahManipulasi);
            $maxhargarumah = $hargarumahManipulasi[0];
            sort($hargarumahManipulasi);
            $minhargarumah = $hargarumahManipulasi[0];
            $selisihhargarumah = $maxhargarumah - $minhargarumah;
            //pembagian selisih hargarumah
            $bobot = kriteriaM::where('idkriteria', 1)->first()->bobot;
            if($selisihhargarumah != 0) {
                $tampung = $hargarumah[$i] / $selisihhargarumah * $bobot;
                //tampung baru
                $hargarumah[$i] = $tampung;
            }else {
                $tampung = 0;
            }


            //min, max, selisih harga
            $typerumahManipulasi = $typerumah;
            rsort($typerumahManipulasi);
            $maxtyperumah = $typerumahManipulasi[0];
            sort($typerumahManipulasi);
            $mintyperumah = $typerumahManipulasi[0];
            $selisihtyperumah = $maxtyperumah - $mintyperumah;
            //pembagian selisih typerumah
            $bobot = kriteriaM::where('idkriteria', 2)->first()->bobot;

            if($selisihtyperumah != 0) {
                $tampung = $typerumah[$i] / $selisihtyperumah * $bobot;
                //tampung baru
                $typerumah[$i] = $tampung;
            }else {
                $tampung = 0;
            }

            

            //min, max, selisih harga
            $luastanahManipulasi = $luastanah;
            rsort($luastanahManipulasi);
            $maxluastanah = $luastanahManipulasi[0];
            sort($luastanahManipulasi);
            $minluastanah = $luastanahManipulasi[0];
            $selisihluastanah = $maxluastanah - $minluastanah;
            //pembagian selisih luastanah
            $bobot = kriteriaM::where('idkriteria', 3)->first()->bobot;
            if($selisihluastanah != 0) {
                $tampung = $luastanah[$i] / $selisihluastanah * $bobot;
                //tampung baru
                $luastanah[$i] = $tampung;
            }else {
                $tampung = 0;
            }


            //min, max, selisih harga
            $spesifikasirumahManipulasi = $spesifikasirumah;
            rsort($spesifikasirumahManipulasi);
            $maxspesifikasirumah = $spesifikasirumahManipulasi[0];
            sort($spesifikasirumahManipulasi);
            $minspesifikasirumah = $spesifikasirumahManipulasi[0];
            $selisihspesifikasirumah = $maxspesifikasirumah - $minspesifikasirumah;
            //pembagian selisih spesifikasirumah
            $bobot = kriteriaM::where('idkriteria', 4)->first()->bobot;
            if($selisihspesifikasirumah != 0) {
                $tampung = $spesifikasirumah[$i] / $selisihspesifikasirumah * $bobot;
                //tampung baru
                $spesifikasirumah[$i] = $tampung;
            }else {
                $tampung = 0;
            }


            //min, max, selisih harga
            $jarakpusatkotaManipulasi = $jarakpusatkota;
            rsort($jarakpusatkotaManipulasi);
            $maxjarakpusatkota = $jarakpusatkotaManipulasi[0];
            sort($jarakpusatkotaManipulasi);
            $minjarakpusatkota = $jarakpusatkotaManipulasi[0];
            $selisihjarakpusatkota = $maxjarakpusatkota - $minjarakpusatkota;
            //pembagian selisih jarakpusatkota
            $bobot = kriteriaM::where('idkriteria', 5)->first()->bobot;
            if($selisihjarakpusatkota != 0) {
                $tampung = $jarakpusatkota[$i] / $selisihjarakpusatkota * $bobot;
                //tampung baru
                $jarakpusatkota[$i] = $tampung;
            }else {
                $tampung = 0;
            }

            //min, max, selisih harga
            $kepadatanpendudukManipulasi = $kepadatanpenduduk;
            rsort($kepadatanpendudukManipulasi);
            $maxkepadatanpenduduk = $kepadatanpendudukManipulasi[0];
            sort($kepadatanpendudukManipulasi);
            $minkepadatanpenduduk = $kepadatanpendudukManipulasi[0];
            $selisihkepadatanpenduduk = $maxkepadatanpenduduk - $minkepadatanpenduduk;
            //pembagian selisih kepadatanpenduduk
            $bobot = kriteriaM::where('idkriteria', 6)->first()->bobot;
            if($selisihkepadatanpenduduk != 0) {
                $tampung = $kepadatanpenduduk[$i] / $selisihkepadatanpenduduk * $bobot;
                //tampung baru
                $kepadatanpenduduk[$i] = $tampung;
            }else {
                $tampung = 0;
            }


        }

        

        $hasil = [];

        for ($i=0; $i < count($dataInstansi); $i++) { 
            $ambilnilai = $hargarumah[$i] + $typerumah[$i] +$luastanah[$i]+$spesifikasirumah[$i] + $jarakpusatkota[$i] + $kepadatanpenduduk[$i];
            $hasil[$i]['nilai'] = $ambilnilai; 
            $hasil[$i]['namainstansi'] = $dataInstansi[$i]['namainstansi']; 
            $hasil[$i]['gambar'] = $dataInstansi[$i]['gambar']; 
            $hasil[$i]['alamat'] = $dataInstansi[$i]['alamat']; 
            $hasil[$i]['perumahan'] = $dataInstansi[$i]['perumahan']; 
            $hasil[$i]['hp'] = $dataInstansi[$i]['hp']; 
            
        }
        rsort($hasil);

        $hasilUrut = [];
        $cekurut = [];
        for ($i=0; $i < count($hasil); $i++) { 
            if(empty($hasilUrut)) {
                $hasilUrut[$i]['nilai'] = $hasil[$i]['nilai'];
                $hasilUrut[$i]['namainstansi'] = $hasil[$i]['namainstansi'];
                $hasilUrut[$i]['gambar'] = $hasil[$i]['gambar'];
                $hasilUrut[$i]['alamat'] = $hasil[$i]['alamat'];
                $hasilUrut[$i]['hp'] = $hasil[$i]['hp'];
                $cekurut[] = $hasil[$i]['namainstansi'];
            }
            if(in_array($hasil[$i]['namainstansi'], $cekurut)) {    
            }else {
                $hasilUrut[$i]['nilai'] = $hasil[$i]['nilai'];
                $hasilUrut[$i]['namainstansi'] = $hasil[$i]['namainstansi'];
                $hasilUrut[$i]['gambar'] = $hasil[$i]['gambar'];
                $hasilUrut[$i]['alamat'] = $hasil[$i]['alamat'];
                $hasilUrut[$i]['hp'] = $hasil[$i]['hp'];
                $cekurut[] = $hasil[$i]['namainstansi'];
            }

        }
        
        $open = true;
        $instansi = instansiM::get();

        $dataharga = nilaiM::where('idkriteria', 1)->orderBy('ket', 'asc')->get();
        $nilaimin = 500000000000;
        foreach ($dataharga as $item) {
            if($nilaimin > $item->ket) {
                $nilaimin = $item->ket;
            }
        }

        $typerumah = nilaiM::select('idnilai','ket')
                     ->where('idkriteria', 2)->get();
        $luastanah = nilaiM::select('idnilai','ket')
        ->where('idkriteria', 3)->get();

        $spesifikasirumah = nilaiM::select('idnilai','ket')
        ->where('idkriteria', 4)->get();

        $kepadatanpenduduk = nilaiM::select('idnilai','ket')
        ->where('idkriteria', 6)->get();

        if($request->session()->get('login') === true && $request->session()->get('posisi') === 'pengunjung') {
            laporanM::where('idpengunjung', $request->session()->get('idpengunjung'))->delete();

            foreach ($hasilUrut as $data) {
                $tambah = new laporanM;
                $tambah->idpengunjung = $request->session()->get('idpengunjung');
                $tambah->gambar = $data['gambar'];
                $tambah->nilai = $data['nilai'];
                $tambah->namainstansi = $data['namainstansi'];
                $tambah->alamat = $data['alamat'];
                $tambah->hp = $data['hp'];
                $tambah->save();
            }

        }

        return view('pages.pagesindex', [
            'instansi' => $instansi,
            'nilaimin' => $nilaimin,
            'typerumah' => $typerumah,
            'luastanah' => $luastanah,
            'spesifikasirumah' => $spesifikasirumah,
            'kepadatanpenduduk' => $kepadatanpenduduk,
            'open' => $open,
            'hasilUrut' => $hasilUrut,
        ]);
        
    }

    public function cetak(Request $request)
    {
        if($request->session()->get('posisi')=='admin') {
            $request->session()->flush();
            return redirect('login')->with('warning', 'Silahkan login sebagai pengunjung!');
        }

        $idpengunjung = $request->session()->get('idpengunjung');
        $data = laporanM::where('idpengunjung', $idpengunjung)->orderBy('nilai', 'DESC')->get();
        
        $pdf = PDF::loadView('laporan.laporan',[
            'data' => $data,
        ]);

        return $pdf->stream();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\instansiM  $instansiM
     * @return \Illuminate\Http\Response
     */
    public function show(instansiM $instansiM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\instansiM  $instansiM
     * @return \Illuminate\Http\Response
     */
    public function edit(instansiM $instansiM)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\instansiM  $instansiM
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, instansiM $instansiM)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\instansiM  $instansiM
     * @return \Illuminate\Http\Response
     */
    public function destroy(instansiM $instansiM)
    {
        //
    }
}
