@extends('layout.layoutAdmin')

@section('activekukriteria')
    activeku
@endsection

@section('judul')
    <i class="fa fa-edit"></i> Data Kriteria
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
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h5>DATA KRITERIA</h5></div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th style="width: fit-content">No</th>
                            <th>Kriteria</th>
                            <th>Bobot</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kriteria as $item)
                        <tr>
                            <td width="30px">{{$loop->iteration}}</td>
                            <td>{{$item->namakriteria}}</td>
                            <td>{{
                                    $item->bobot * 100
                                }}%</td>
                            <td><!-- Button trigger modal -->
                            <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#edit{{$item->idkriteria}}">
                              <i class="fa fa-edit"></i> Edit
                            </button>
                            
                            
                            
                            </td>
                        </tr>
                            
                            <!-- Modal -->
                            <div class="modal fade" id="edit{{$item->idkriteria}}" tabindex="-1" role="dialog" aria-labelledby="ditKriteria" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                            <div class="modal-header">
                                                    <h5 class="modal-title">Edit Kriteria</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                </div>
                                        <form action="{{ route('ubah.kriteria', [$item->idkriteria]) }}" method="post">
                                            @csrf

                                            <div class="modal-body">
                                                <div class="container-fluid">
                                                    <div class="form-group">
                                                        <label for="">Nama Kriteria</label>
                                                        <input type="text" name="namakriteria" value="{{$item->namakriteria}}" id="" class="form-control">
                                                    </div>
        
                                                    <div class="form-group">
                                                        <label for="">Bobot</label>
                                                        <input type="number" name="bobot" value="{{$item->bobot}}" step="0.01" id="" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Ubah Kriteria</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
    
    
                </table>
            </div>  
        </div>    
    </div>
</div>


@endsection