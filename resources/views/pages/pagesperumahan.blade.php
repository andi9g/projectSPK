@extends('layout.layoutAdmin')

@section('activekuinstansi')
    activeku
@endsection

@section('judul')
    <i class="fa fa-city"></i> Data Perumahan ({{$instansi->namainstansi}}) 
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <!-- Button trigger modal -->
            <a href="{{ url('instansi', []) }}" class="btn btn-secondary"><< Back</a>
            <button type="button" class="btn btn-primary my-2" data-toggle="modal" data-target="#tambahPerumahan">
                Tambah Perumahan
            </button>
            
            <!-- Modal -->
            <div class="modal fade" id="tambahPerumahan" tabindex="-1" aria-labelledby="tambahPerumahanLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="tambahPerumahanLabel">Tambah Perumahan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <form action="{{ route('tambah.perumahan', [$idinstansi]) }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="">Nama Perumahan</label>
                                <input type="text" name="namaperumahan" id="" class="form-control">    
                            </div>                    
                            <div class="form-group">
                                <label for="">Harga Rumah</label>
                                <input type="number" name="hargarumah" id="" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="">Jarak Pusat Kota (Km)</label>
                                <input type="number" step="0.01" name="jarakpusatkota" id="" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="">Type Rumah</label>
                                <select name="typerumah" id="" class="form-control">
                                    <option value="">Pilih</option>
                                    @foreach ($typerumah as $item)
                                        <option value="{{$item->idnilai}}">Type {{$item->ket}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Luas Tanah</label>
                                <select name="luastanah" id="" class="form-control">
                                    <option value="">Pilih</option>
                                    @foreach ($luastanah as $item)
                                        <option value="{{$item->idnilai}}">{{$item->ket}} m<sup>2</sup></option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Spesifikasi Rumah</label>
                                <select name="spesifikasirumah" id="" class="form-control">
                                    <option value="">Pilih</option>
                                    @foreach ($spesifikasirumah as $item)
                                        <option value="{{$item->idnilai}}">{{$item->ket}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Kepadatan Penduduk</label>
                                <select name="kepadatanpenduduk" id="" class="form-control">
                                    <option value="">Pilih</option>
                                    @foreach ($kepadatanpenduduk as $item)
                                        <option value="{{$item->idnilai}}">{{$item->ket}}</option>
                                    @endforeach
                                </select>
                            </div>
        
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah Instansi</button>
                        </div>
                    </form>
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
        </div>
    </div>

    <div class="card">
        <div class="card-header"></div>
        <div class="card-body">
            <table class="table table-sm table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Perumahan</th>
                        <th>Harga Rumah</th>
                        <th>Type Rumah</th>
                        <th>Luas Tanah</th>
                        <th>Spesifikasi Rumah</th>
                        <th>Jarak Pusat Kota</th>
                        <th>Kepadatan Penduduk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($perumahan as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td nowrap class="text-bold">{{$item->namaperumahan}}</td>
                        <td>Rp{{number_format($item->hargarumah, 0,',','.')}}</td>
                        @php
                            $typerumahTampil = DB::table('nilai')->select('ket')->where('idnilai', $item->typerumah)->first()->ket;
                            $luastanahTampil = DB::table('nilai')->select('ket')->where('idnilai', $item->luastanah)->first()->ket;
                            $spesifikasirumahTampil = DB::table('nilai')->select('ket')->where('idnilai', $item->spesifikasirumah)->first()->ket;
                            $kepadatanpendudukTampil = DB::table('nilai')->select('ket')->where('idnilai', $item->kepadatanpenduduk)->first()->ket;
                        @endphp
                        <td>{{"Type ".$typerumahTampil}}</td>
                        <td>{{$luastanahTampil}} m<sup>2</sup></td>
                        <td>{{$spesifikasirumahTampil}}</td>
                        <td>{{$item->jarakpusatkota}} km</td>
                        <td>{{$kepadatanpendudukTampil}}</td>
                        <td nowrap>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary btn-xs d-inline" data-toggle="modal" data-target="#edit{{$item->idperumahan}}">
                              <i class="fa fa-edit"></i> Edit
                            </button>
                            
                            <form action="{{ route('hapus.perumahan', [$item->idperumahan]) }}" method="post" class="d-inline">
                                @csrf
                                @method("DELETE")
                                <button type="submit" onclick="return confirm('Lanjutkan proses hapus?')" class="btn btn-danger btn-xs">
                                    <i class="fa fa-trash"></i> Hapus
                                </button>
                            </form>
                            <!-- Modal -->
                        </td>
                    </tr>
                    
                    <div class="modal fade" id="edit{{$item->idperumahan}}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Data Perumahan</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                </div>
                                <form action="{{ route('ubah.perumahan', [$item->idperumahan]) }}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="">Nama Perumahan</label>
                                            <input type="text" value="{{$item->namaperumahan}}" name="namaperumahan"  id="" class="form-control">    
                                        </div>                    
                                        <div class="form-group">
                                            <label for="">Harga Rumah</label>
                                            <input type="number" value="{{$item->hargarumah}}" name="hargarumah" id="" class="form-control">
                                        </div>
            
                                        <div class="form-group">
                                            <label for="">Jarak Pusat Kota (Km)</label>
                                            <input type="number" step="0.01" value="{{$item->jarakpusatkota}}" name="jarakpusatkota" id="" class="form-control">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="">Type Rumah</label>
                                            <select name="typerumah" id="" class="form-control">
                                                <option value="">Pilih</option>
                                                @foreach($typerumah as $item2)
                                                    <option value="{{$item2->idnilai}}" @if ($item2->idnilai == $item->typerumah)
                                                        selected
                                                    @endif>Type {{$item2->ket}}</option>
                                                @endforeach
                                            </select>
                                        </div>
            
                                        <div class="form-group">
                                            <label for="">Luas Tanah</label>
                                            <select name="luastanah" id="" class="form-control">
                                                <option value="">Pilih</option>
                                                @foreach ($luastanah as $item2)
                                                    <option value="{{$item2->idnilai}}" @if ($item2->idnilai == $item->luastanah)
                                                        selected
                                                    @endif>{{$item2->ket}} m<sup>2</sup></option>
                                                @endforeach
                                            </select>
                                        </div>
            
                                        <div class="form-group">
                                            <label for="">Spesifikasi Rumah</label>
                                            <select name="spesifikasirumah" id="" class="form-control">
                                                <option value="">Pilih</option>
                                                @foreach ($spesifikasirumah as $item2)
                                                    <option value="{{$item2->idnilai}}" @if ($item2->idnilai == $item->spesifikasirumah)
                                                        selected
                                                    @endif>{{$item2->ket}}</option>
                                                @endforeach
                                            </select>
                                        </div>
            
                                        <div class="form-group">
                                            <label for="">Kepadatan Penduduk</label>
                                            <select name="kepadatanpenduduk" id="" class="form-control">
                                                <option value="">Pilih</option>
                                                @foreach ($kepadatanpenduduk as $item2)
                                                    <option value="{{$item2->idnilai}}" @if ($item2->idnilai == $item->kepadatanpenduduk)
                                                        selected
                                                    @endif>{{$item2->ket}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                    
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Ubah Instansi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>

            </table>
        </div>

        <div class="card-footer">
            {{-- {{$instansi->links('vendor.pagination.bootstrap-4')}} --}}
        </div>
    </div>

</div>



@endsection