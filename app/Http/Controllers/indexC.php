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

        $kriteria = kriteriaM::orderBy('ket', 'DESC')->get();
        

        return view('pages.pagesindex', [
            'instansi' => $instansi,
            'nilaimin' => $nilaimin,
            'typerumah' => $typerumah,
            'luastanah' => $luastanah,
            'spesifikasirumah' => $spesifikasirumah,
            'kepadatanpenduduk' => $kepadatanpenduduk,
            'open' => $open,
            'kriteria' => $kriteria,
        ]);
    }

    public function cari(Request $request)
    {
        // $request->validate([
        //     'hargarumah' => 'required',
        //     'jarakpusatkota' => 'required',
        //     'typerumah' => 'required',
        //     'luastanah' => 'required',
        //     'spesifikasirumah' => 'required',
        //     'kepadatanpenduduk' => 'required',
        // ]);

        //validate
        $kriteria = kriteriaM::orderBy('ket', 'DESC');
        foreach ($kriteria->get() as $k) {
            $namakriteria = str_replace(" ","", strtolower($k->namakriteria));
            $request->validate([
                $namakriteria => 'required',
            ]);
        }
        
        
        //cek nominal
        $kurensi = $kriteria->where('typedata', 'kurensi')->first()->idkriteria;
        $dataharga = nilaiM::where('idkriteria', $kurensi)->orderBy('ket', 'asc')->get();
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

        //---------------------------------------------
        $kriteria = kriteriaM::orderBy('ket', 'DESC');

        foreach ($kriteria->get() as $k) {
            // $request_hargarumah = $request->hargarumah;
            $namakriteria = str_replace(" ","", strtolower($k->namakriteria));
            if($k->typedata == 'kurensi') {
                if($request->$namakriteria < $nilaiminharga) {
                    return redirect('welcome#pencarian')->with('toast_error', 'Harga Rumah tidak valid');
                }
            }

            //tampung data;
            ${$namakriteria} = [];


            if($k->ket == 'dinamis') {
                $ambilData = nilaiM::select('ket')->where('idkriteria', $k->idkriteria)->get();
                
                
                foreach ($ambilData as $harga2) {
                    // $hargaArr[] = (int)($harga2->ket); 
                    // dd($namakriteria);
                    ${"urutnilai_$namakriteria"}[] = (int)($harga2->ket); 
                    rsort(${"urutnilai_$namakriteria"});
                }
            }
            


        }
        

        $instansi = instansiM::get();
        $penampungInstansi = [];
        $penampungRumah = [];
        $index = 0;
        foreach ($instansi as $instansi_) {
            $penampungInstansi[] = $instansi_->namainstansi;
            
            $perumahan = perumahanM::where('idinstansi', $instansi_->idinstansi)->get();
            
            $ipr = 0;
            foreach ($perumahan as $perumahan_) {    
                $namaperumahan = str_replace(" ", "", strtolower($perumahan_->namaperumahan));    
                $ipr++;
                // dd($ipr++);
                //---------------------------------------------
                $kriteria = kriteriaM::orderBy('ket', 'DESC');

                foreach ($kriteria->get() as $k) {
                    $idkriteria = $k->idkriteria;
                    $namakriteria = str_replace(" ", "", strtolower($k->namakriteria));
                    $typedata = $k->typedata;
                    $ket = $k->ket;

                    if($ket == 'dinamis') {
                        ${"dinamis_$namakriteria"} = 0;
                        foreach (${"urutnilai_$namakriteria"} as $item) {
                            if(($perumahan_->$namakriteria > $item && $request->$namakriteria >= $perumahan_->$namakriteria) && ${"dinamis_$namakriteria"} == 0) {
                                ${$namakriteria}[] =  empty(nilaiM::where('ket', $item)->first()->nilai)?0:nilaiM::where('ket', $item)->first()->nilai;
                                ${"dinamis_$namakriteria"}++;
                            }
                        }
                        if(${"dinamis_$namakriteria"} === 0) {
                            ${$namakriteria}[] = 0;
                        }
                    }else if($ket == 'statis') {
                        if($perumahan_->$namakriteria == $request->$namakriteria) {
                            ${$namakriteria}[] = empty(nilaiM::where('idnilai', $perumahan_->$namakriteria)->first()->nilai)?0:nilaiM::where('idnilai', $perumahan_->$namakriteria)->first()->nilai;
                        }else {
                            ${$namakriteria}[] = 0;
                        }
                    }

                
                }

                //DATA INSTANSI
                $dataInstansi[$index]['namainstansi'] = $instansi_->namainstansi;  
                $dataInstansi[$index]['gambar'] = $instansi_->gambar;  
                $dataInstansi[$index]['links'] = $instansi_->links;  
                $dataInstansi[$index]['alamat'] = $instansi_->alamat;  
                $dataInstansi[$index]['hp'] = $instansi_->hp;  
                $dataInstansi[$index]['perumahan'] = $perumahan_->namaperumahan;  

                $index++;
            }
            $penampungRumah[] = $ipr;
            $ipr =0;
        }


        $kriteria = kriteriaM::orderBy('ket', 'DESC');

        $penampungMatriks = [];
        $nom = 0;
        foreach ($kriteria->get() as $k) {
            $namakriteria = str_replace(" ", "", strtolower($k->namakriteria));
            $penampungMatriks[$nom++] = ${$namakriteria}; 
        
        }

        
        // dd($penampungMatriks);
        //--------------------------------------------------------
        // dd($hargarumah);
        // dd($dataInstansi);

        $penampungMax = [];
        $penampungMin = [];
        $penampungSelisih = [];
        $penampungMatriksNormalisasi = [];
        $penampungMatriksBobot = [];


        $penampungNamakriteria = [];
        $penampungBobot = [];

        $kriteria = kriteriaM::orderBy('ket', 'DESC');

        foreach ($kriteria->get() as $k) {
            $penampungNamakriteria[] = $k->namakriteria;
            $penampungBobot[] = $k->bobot;
        }

        for ($i=0; $i < count($dataInstansi); $i++) { 
            $kriteria = kriteriaM::orderBy('ket', 'DESC');

            foreach ($kriteria->get() as $k) {
                $namakriteria = str_replace(" ", "", strtolower($k->namakriteria));

                //min, max, selisih harga
                ${"manipulasi_$namakriteria"} = ${$namakriteria};

                rsort(${"manipulasi_$namakriteria"});
                $max = ${"manipulasi_$namakriteria"}[0];
                $penampungMax[$namakriteria] = $max;
                
                sort(${"manipulasi_$namakriteria"});
                $min = ${"manipulasi_$namakriteria"}[0];
                $penampungMin[$namakriteria] = $min;
                
                $selisih = $max - $min;
                $penampungSelisih[$namakriteria] = $selisih;
                //pembagian selisih hargarumah
                $bobot = kriteriaM::where('idkriteria', $k->idkriteria)->first()->bobot;
                
                if($selisih != 0) {
                    $normalisasi = ${$namakriteria}[$i] / $selisih;
                    $tampung = (${$namakriteria}[$i] / $selisih) * $bobot;
                    $penampungMatriksNormalisasi[$i][$namakriteria] = $normalisasi;
                    //tampung baru
                    ${"tampung_$namakriteria"}[$i] = $tampung;
                    $penampungMatriksBobot[$i][$namakriteria] = ${"tampung_$namakriteria"}[$i];
                }else {
                    ${"tampung_$namakriteria"}[$i] = 0;
                    $penampungMatriksNormalisasi[$i][$namakriteria] = 0;
                    $penampungMatriksBobot[$i][$namakriteria] = 0;
                }

            }

        }


        $penampungHasilAkhir = [];
        $hasil = [];
        
        for ($i=0; $i < count($dataInstansi); $i++) { 
            $kriteria = kriteriaM::orderBy('ket', 'DESC');

            $ambilnilai = 0 ;
            foreach ($kriteria->get() as $k) {
                $namakriteria = str_replace(" ", "", strtolower($k->namakriteria));
                // echo (float)(${"tampung_$namakriteria"}[$i])." ";
                $tambahNilai = ${"tampung_$namakriteria"}[$i]; 
                $ambilnilai = $ambilnilai + $tambahNilai;
            }

            // dd('stop');
            $hasil[$i]['nilai'] = $ambilnilai; 
            $hasil[$i]['namainstansi'] = $dataInstansi[$i]['namainstansi']; 
            $hasil[$i]['gambar'] = $dataInstansi[$i]['gambar']; 
            $hasil[$i]['links'] = $dataInstansi[$i]['links']; 
            $hasil[$i]['alamat'] = $dataInstansi[$i]['alamat']; 
            $hasil[$i]['perumahan'] = $dataInstansi[$i]['perumahan']; 
            $hasil[$i]['hp'] = $dataInstansi[$i]['hp']; 
            
        }


        $hasilSementara = $hasil;
        // dd($hasilnormalisasi);

        rsort($hasil);
        // dd($hasilnormalisasi);

        $penampungHasilSementara = [];

        $hasilUrut = [];
        $hasilUrutTampung = [];
        $cekurut = [];
        $nom = 0;
        for ($i=0; $i < count($hasil); $i++) { 
            if(empty($cekurut)) {
                $cekurut[] = $hasil[$i]['namainstansi'];

                $hasilUrutTampung[$hasil[$i]['namainstansi']]['namainstansi'] = $hasil[$i]['namainstansi'];
                $hasilUrutTampung[$hasil[$i]['namainstansi']]['perumahan'] = $hasil[$i]['perumahan'];
                $hasilUrutTampung[$hasil[$i]['namainstansi']]['gambar'] = $hasil[$i]['gambar'];
                $hasilUrutTampung[$hasil[$i]['namainstansi']]['links'] = $hasil[$i]['links'];
                $hasilUrutTampung[$hasil[$i]['namainstansi']]['alamat'] = $hasil[$i]['alamat'];
                $hasilUrutTampung[$hasil[$i]['namainstansi']]['hp'] = $hasil[$i]['hp'];
            }
            if(in_array($hasil[$i]['namainstansi'], $cekurut)) {    
            }else {
                $cekurut[] = $hasil[$i]['namainstansi'];
                $hasilUrutTampung[$hasil[$i]['namainstansi']]['namainstansi'] = $hasil[$i]['namainstansi'];
                $hasilUrutTampung[$hasil[$i]['namainstansi']]['perumahan'] = $hasil[$i]['perumahan'];
                $hasilUrutTampung[$hasil[$i]['namainstansi']]['gambar'] = $hasil[$i]['gambar'];
                $hasilUrutTampung[$hasil[$i]['namainstansi']]['links'] = $hasil[$i]['links'];
                $hasilUrutTampung[$hasil[$i]['namainstansi']]['alamat'] = $hasil[$i]['alamat'];
                $hasilUrutTampung[$hasil[$i]['namainstansi']]['hp'] = $hasil[$i]['hp'];
            }

        }
        
        
        // dd($hasilUrutTampung);

        $nom = 0;
        foreach ($cekurut as $cek) {
            $icek = 0;
            $nilaiKeseluruhan = 0;

            for ($i=0; $i < count($hasil); $i++) { 
                
                if($hasil[$i]['namainstansi'] == $cek){
                    $icek++;
                    $nilaiKeseluruhan = $nilaiKeseluruhan + $hasil[$i]['nilai'];
                }
            }

            $nilaiKeseluruhan = $nilaiKeseluruhan / $icek;
            $hasilUrut[$nom]['nilai'] = $nilaiKeseluruhan;
            $hasilUrut[$nom]['namainstansi'] = $hasilUrutTampung[$cek]['namainstansi'];
            $hasilUrut[$nom]['perumahan'] = $hasilUrutTampung[$cek]['perumahan'];
            $hasilUrut[$nom]['gambar'] = $hasilUrutTampung[$cek]['gambar'];
            $hasilUrut[$nom]['links'] = $hasilUrutTampung[$cek]['links'];
            $hasilUrut[$nom]['alamat'] = $hasilUrutTampung[$cek]['alamat'];
            $hasilUrut[$nom]['hp'] = $hasilUrutTampung[$cek]['hp'];

            $nom++;
            $icek = 0;
        }

        rsort($hasilUrut);
        // dd($cekurut);
        // dd($hasilUrut);
        $open = true;
        $instansi = instansiM::get();

        $dataharga = nilaiM::where('idkriteria', 1)->orderBy('ket', 'asc')->get();
        $nilaimin = 500000000000;
        foreach ($dataharga as $item) {
            if($nilaimin > $item->ket) {
                $nilaimin = $item->ket;
            }
        }

        // $typerumah = nilaiM::select('idnilai','ket')
        //              ->where('idkriteria', 2)->get();
        // $luastanah = nilaiM::select('idnilai','ket')
        // ->where('idkriteria', 3)->get();

        // $spesifikasirumah = nilaiM::select('idnilai','ket')
        // ->where('idkriteria', 4)->get();

        // $kepadatanpenduduk = nilaiM::select('idnilai','ket')
        // ->where('idkriteria', 6)->get();

        if($request->session()->get('login') === true && $request->session()->get('posisi') === 'pengunjung') {
            laporanM::where('idpengunjung', $request->session()->get('idpengunjung'))->delete();

            foreach ($hasilUrut as $data) {
                $tambah = new laporanM;
                $tambah->idpengunjung = $request->session()->get('idpengunjung');
                $tambah->gambar = $data['gambar'];
                $tambah->nilai = $data['nilai'];
                $tambah->namainstansi = $data['namainstansi'];
                $tambah->links = $data['links'];
                $tambah->alamat = $data['alamat'];
                $tambah->hp = $data['hp'];
                $tambah->save();
            }

        }
        // dd(count($hasilSementara));
        // dd(count($penampungMatriksNormalisasi));
        // dd(count($penampungMatriks[0]));
        // dd($hasilUrut);
        $kriteria = kriteriaM::orderBy('ket', 'DESC')->get();

        $in = instansiM::get();
        return view('pages.pagesindex', [
            'instansi' => $instansi,
            'in' => $in,
            'nilaimin' => $nilaimin,
            'typerumah' => $typerumah,
            'luastanah' => $luastanah,
            'spesifikasirumah' => $spesifikasirumah,
            'kepadatanpenduduk' => $kepadatanpenduduk,
            'open' => $open,
            'hasilUrut' => $hasilUrut,
            'kriteria' => $kriteria,

            //penampung
            'penampungInstansi' => $penampungInstansi,
            'penampungRumah' => $penampungRumah,
            'penampungNamaKriteria' => $penampungNamakriteria,
            'penampungBobot' => $penampungBobot,
            'penampungMatriks' => $penampungMatriks,
            'penampungMatriksNormalisasi' => $penampungMatriksNormalisasi,
            'penampungMatriksBobot' => $penampungMatriksBobot,
            'penampungMax' => $penampungMax,
            'penampungMin' => $penampungMin,
            'penampungSelisih' => $penampungSelisih,

            //hasil
            'hasilSementara' => $hasilSementara,
            'hasilPengurutan' => $hasil,
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
