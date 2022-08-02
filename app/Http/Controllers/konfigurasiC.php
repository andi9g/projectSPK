<?php

namespace App\Http\Controllers;

use App\Models\kriteriaM;
use App\Models\nilaiM;
use Illuminate\Http\Request;

class konfigurasiC extends Controller
{
    public function kriteria(Request $request)
    {
        $kriteria = kriteriaM::get();

        return view('pages.pageskriteria', [
            'kriteria' => $kriteria,
        ]);
    }

    public function ubahkriteria(Request $request, $idkriteria)
    {

        
        $request->validate([
            'namakriteria' => 'required', 
            'bobot' => 'required', 
        ]);
        
        
        try{
            $namakriteria = $request->namakriteria;
            $bobot = $request->bobot;
        
            $update = kriteriaM::where('idkriteria', $idkriteria)->update([
                'namakriteria' => $namakriteria,
                'bobot' => $bobot,
            ]);

            if($update) {
                return redirect('kriteria')->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect('kriteria')->with('toast_error', 'Terjadi kesalahan');
        }
    }


    
    public function nilai(Request $request)
    {
        $kriteria = kriteriaM::get();

        
        return view('pages.pagesnilai', [
            'kriteria' => $kriteria,
        ]);
    }



    public function ubahnilai(Request $request, $idnilai)
    {

        
        $request->validate([
            'ket' => 'required', 
            'nilai' => 'required', 
        ]);
        
        
        try{
            $ket = $request->ket;
            $nilai = $request->nilai;
        
            $update = nilaiM::where('idnilai', $idnilai)->update([
                'ket' => $ket,
                'nilai' => $nilai,
            ]);

            if($update) {
                return redirect('nilai')->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect('nilai')->with('toast_error', 'Terjadi kesalahan');
        }
    }





}
