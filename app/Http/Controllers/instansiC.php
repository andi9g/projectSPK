<?php

namespace App\Http\Controllers;

use App\Models\instansiM;
use App\Models\perumahanM;
use App\Models\kriteriaM;
use App\Models\nilaiM;
use Illuminate\Http\Request;

class instansiC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = empty($request->keyword)?"":$request->keyword;
        $instansi = instansiM::where('namainstansi', 'like', "%$keyword%")->paginate(15);

        $instansi->appends($request->only(['limit', 'keyword']));

        return view('pages.pagesinstansi', [
            'instansi' => $instansi,
        ]);
    }

    public function perumahan(Request $request, $idinstansi)
    {
        
        $kriteria = kriteriaM::orderBy('ket', 'DESC')->get();

        // dd($kriteria);
        $typerumah = nilaiM::select('idnilai','ket')
                     ->where('idkriteria', 2)->get();
        $luastanah = nilaiM::select('idnilai','ket')
        ->where('idkriteria', 3)->get();

        $spesifikasirumah = nilaiM::select('idnilai','ket')
        ->where('idkriteria', 4)->get();

        $kepadatanpenduduk = nilaiM::select('idnilai','ket')
        ->where('idkriteria', 6)->get();


        $perumahan = perumahanM::join('instansi', 'instansi.idinstansi', '=', 'perumahan.idinstansi')
        ->where('perumahan.idinstansi', $idinstansi)->get();
        
        
        $instansi = instansiM::where('idinstansi', $idinstansi)->first();

        return view('pages.pagesperumahan', [
            'perumahan' => $perumahan,
            'kriteria' => $kriteria,
            'instansi' => $instansi,
            'idinstansi' => $idinstansi,
            'typerumah' => $typerumah,
            'luastanah' => $luastanah,
            'spesifikasirumah' => $spesifikasirumah,
            'kepadatanpenduduk' => $kepadatanpenduduk,
        ]);
    }

    public function tambahperumahan(Request $request, $idinstansi)
    {
        $kriteria = kriteriaM::get();
        $validateku = [];
        $request->validate([
            "namaperumahan" => 'required',
        ]);
        foreach ($kriteria as $k) {
            $namakriteria = str_replace(" ", "", strtolower($k->namakriteria));

            $request->validate([
                "$namakriteria" => 'required',
            ]);
        }        
        try{
            $namaperumahan = $request->namaperumahan;
            $store = new perumahanM;
            $store->idinstansi = $idinstansi;
            $store->namaperumahan = $namaperumahan;
            foreach ($kriteria as $k) {
                $namakriteria = str_replace(" ", "", strtolower($k->namakriteria));
                $store->$namakriteria = $request->$namakriteria;
            }
            $store->save();

            if($store) {
                return redirect()->back()->with('toast_success', 'success');
            }

        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
    }

    public function ubahperumahan(Request $request, $idperumahan)
    {
        
        $kriteria = kriteriaM::get();
        $validateku = [];
        $request->validate([
            "namaperumahan" => 'required',
        ]);
        foreach ($kriteria as $k) {
            $namakriteria = str_replace(" ", "", strtolower($k->namakriteria));

            $request->validate([
                "$namakriteria" => 'required',
            ]);
        } 
        
        
        
        try{
            $namaperumahan = $request->namaperumahan;
            
            $update = perumahanM::where('idperumahan', $idperumahan)->update([
                "namaperumahan" => $namaperumahan,
            ]);

            foreach ($kriteria as $k) {
                $namakriteria = str_replace(" ", "", strtolower($k->namakriteria));

                $update = perumahanM::where('idperumahan', $idperumahan)->update([
                    "$namakriteria" => $request->$namakriteria,
                ]);
            }

            return redirect()->back()->with('toast_success', 'success');

        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
    }

    public function hapusperumahan(Request $request, $idperumahan)
    {
        try{
            $destroy = perumahanM::where('idperumahan', $idperumahan)->delete();
            if($destroy) {
                return redirect()->back()->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
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
        $request->validate([
            'namainstansi' => 'required',
            'links' => 'required',
            'alamat' => 'required',
            'hp' => 'required|numeric',
        ]);
        
        try{ 
            if ($request->hasFile('gambar')) {
                $originName = $request->file('gambar')->getClientOriginalName();
                $fileName = pathinfo($originName, PATHINFO_FILENAME);
                $extension = $request->file('gambar')->getClientOriginalExtension();

                $format = strtolower($extension);
                if($format == 'jpg' || $format == 'jpeg' || $format == 'png') {
                    $fileName = $fileName.'_'.time().'.'.$extension;
                    $upload = $request->file('gambar')->move(\base_path() ."/public/gambar/instansi", $fileName);
                }else {
                    $fileName= 'none.jpg';
                }

            }else {
                $fileName= 'none.jpg';
            }

            $alamat = $request->alamat;
            $hp = $request->hp;
            $namainstansi = $request->namainstansi;
            $links = $request->links;
        
            $store = new instansiM;
            $store->namainstansi = $namainstansi;
            $store->alamat = $alamat;
            $store->links = $links;
            $store->hp = $hp;
            $store->gambar = $fileName;
            $store->save();

            if($store) {
                return redirect('instansi')->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect('instansi')->with('toast_error', 'Terjadi kesalahan');
        }
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
    public function update(Request $request, instansiM $instansiM, $idinstansi)
    {
        $request->validate([
            'namainstansi' => 'required',
            'alamat' => 'required',
            'links' => 'required',
            'hp' => 'required|numeric',
        ]);
        
        
        try{
            $namainstansi = $request->namainstansi;
            $alamat = $request->alamat;
            $links = $request->links;
            $hp = $request->hp;

            if ($request->hasFile('gambar')) {
                $originName = $request->file('gambar')->getClientOriginalName();
                $fileName = pathinfo($originName, PATHINFO_FILENAME);
                $extension = $request->file('gambar')->getClientOriginalExtension();
                
                $format = strtolower($extension);
                if($format == 'jpg' || $format == 'jpeg' || $format == 'png') {
                    $fileName = $fileName.'_'.time().'.'.$extension;
                    $upload = $request->file('gambar')->move(\base_path() ."/public/gambar/instansi", $fileName);
                    $update = instansiM::where('idinstansi', $idinstansi)->update([
                        'namainstansi' => $namainstansi,
                        'alamat' => $alamat,
                        'links' => $links,
                        'hp' => $hp,
                        'gambar' => $fileName,
                    ]);
                }else {
                    return redirect()->back()->with('toast_error', 'File yang diupload bukan gambar!')->withInput();
                }

            }else {
                $fileName= 'none.jpg';
                $update = instansiM::where('idinstansi', $idinstansi)->update([
                    'namainstansi' => $namainstansi,
                    'alamat' => $alamat,
                    'links' => $links,
                    'hp' => $hp,
                    'gambar' => $fileName,
                ]);
            }
        

            if($update) {
                return redirect()->back()->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect('instansi')->with('toast_error', 'Terjadi kesalahan');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\instansiM  $instansiM
     * @return \Illuminate\Http\Response
     */
    public function destroy(instansiM $instansiM, $idinstansi)
    {
     
        try{
            $destroy = instansiM::where('idinstansi', $idinstansi)->delete();
            if($destroy) {
                return redirect('instansi')->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect('instansi')->with('toast_error', 'Terjadi kesalahan');
        }
        
    }
}
