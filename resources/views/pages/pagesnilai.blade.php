@extends('layout.layoutAdmin')

@section('activekunilai')
    activeku
@endsection

@section('judul')
    <i class="fa fa-star"></i> Data Kriteria Nilai
@endsection

@section('content')
<div class="row">
    {{-- <div class="col-md-6">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary my-2" data-toggle="modal" data-target="#exampleModal">
            Launch demo modal
        </button>
        
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                ...
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <form action="{{ url()->current() }}" class="form-inline justify-content-end">
            <div class="input-group mb-3">
                <input type="text" class="form-control" value="{{empty($_GET['keyword'])?'':$_GET['keyword']}}" name="keyword" aria-describedby="button-addon2">
                <div class="input-group-append">
                  <button class="btn btn-outline-success" type="submit" id="button-addon2">Cari</button>
                </div>
            </div>
            
        </form>
    </div> --}}
</div>

<div class="row">
    @foreach ($kriteria as $item)
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h5>{{strtoupper($item->namakriteria)}}</h5></div>
                <div class="card-body">
                    @php
                        $datanilai = DB::table('nilai')->where('idkriteria', $item->idkriteria)
                                 ->orderBy('nilai', 'ASC')->get();
                    @endphp     
                    <table class="table table-sm table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Penilaian</th>
                                <th>Nilai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($datanilai as $nilai)
                                <tr>
                                    <td class="">
                                        @php
                                            if($item->namakriteria == 'Harga Rumah') {
                                                echo 'Rp'.number_format($nilai->ket,0,',','.');
                                            }else {
                                                if($item->namakriteria == 'Type Rumah') {
                                                    echo 'Type ';
                                                }
                                                echo $nilai->ket;
                                                if($item->namakriteria == 'Luas Tanah') {
                                                    echo ' m<sup>2</sup>';
                                                }elseif($item->namakriteria == 'Jarak Pusat Kota') {
                                                    echo 'km';
                                                }
                                            }
                                        @endphp
                                    </td>
                                    <td class="text-center">{{$nilai->nilai}}</td>

                                    <td nowrap>
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#edit{{$nilai->idnilai}}">
                                          <i class="fa fa-edit d-inline"></i> Edit
                                        </button>
                                        
                                        <!-- Modal -->
                                        <div class="modal fade" id="edit{{$nilai->idnilai}}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Nilai ({{$item->namakriteria}})</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                    </div>
                                                    
                                                        @if (!($item->namakriteria=='Spesifikasi Rumah' || $item->namakriteria=='Kepadatan Penduduk'))
                                                            {{$type = 'number'}}
                                                        @else
                                                            {{$type = 'text'}}
                                                        @endif
                                                    <form action="{{ route('ubah.nilai', [$nilai->idnilai]) }}" method="post">
                                                        @csrf
                                                    
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="">Nama Penilaian</label>
                                                                <input type="{{$type}}" class="form-control" name="ket" value="{{$nilai->ket}}" id="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Nilai</label>
                                                                <input type="number" class="form-control" name="nilai" value="{{$nilai->nilai}}" id="">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Ubah</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    @endforeach


</div>


@endsection