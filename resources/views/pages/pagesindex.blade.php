<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>SPK UNTUK MENENTUKAN PEMILIHAN PERUMAHAN SUBSIDI TERBAIK PADA WILAYAH KOTA TANJUNGPINANG</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link href="{{url('assets-frontend/vendor/animate.css/animate.min.css')}}" rel="stylesheet">
    <link href="{{url('assets-frontend/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('assets-frontend/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{url('assets-frontend/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
    <link href="{{url('assets-frontend/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
    <link href="{{url('assets-frontend/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
    <link href="{{url('assets-frontend/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

    <link href="{{ url('assets-frontend/css/style.css', []) }}" rel="stylesheet">

</head>

<body style="background-image: linear-gradient(rgba(255,255,255,0.75), rgba(255,255,255,0.75)),url(assets-frontend/img/pattern.png)">

    <header id="header" class="fixed-top header-inner-pages">
        <div class="container d-flex align-items-center justify-content-between">

            <h1 class="logo"><a href="#">SPK Maut</a></h1>
            <nav id="navbar" class="navbar">
                <ul>
                    @if ($open==false)
                    <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
                    <li><a class="nav-link scrollto" href="#services">Cara Kerja</a></li>
                    @endif
                    @if (Session::get('login') != true) 
                        <li><a class="nav-link scrollto" href="{{ url('login', []) }}">Login</a></li>
                    @endif
                    @if (Session::get('login') == true) 
                        <li><a class="nav-link scrollto" href="{{ url('logout', []) }}">Logout</a></li>
                    @endif
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav>
        </div>
    </header>

    @if ($open==false)
    <section id="hero">
        <div id="heroCarousel" data-bs-interval="5000" class="carousel slide carousel-fade" data-bs-ride="carousel">

            <div class="carousel-item active" style="background-image: url(assets-frontend/img/hero.jpg)">
                <div class="carousel-container">
                    <div class="container">
                        <h2 class="animated fadeInDown">Spk Untuk Menentukan Pemilihan Perumahan Subsidi Terbaik Pada Wilayah Kota Tanjungpinang Menggunakan Metode Maut</h2>
                        <p class="animated fadeInUp"></p>
                        <a href="#pencarian" class="btn-get-started animated fadeInUp scrollto">Analisa</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main id="main">
        <section id="services" class="services">
            <div class="container-fluid">

                <div class="section-title">
                    <h3>Cara <span>Kerja</span></h3>
                </div>

                <div class="row justify-content-center">
                    <div class="col-xl-10">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 icon-box">
                                <div class="icon"><i class="ri-pie-chart-line"></i></div>
                                <h4 class="title"><a href="">Data Perumahan</a></h4>
                                <p class="description">Data perumahan yang telah terdaftar, <a href="#perumahan">Klik Disini</a></p>
                            </div>
                            <div class="col-lg-3 col-md-6 icon-box">
                                <div class="icon"><i class="ri-stack-line"></i></div>
                                <h4 class="title"><a href="">Pencarian Data Perumahan</a></h4>
                                <p class="description">Mencari data perumahan yang efektif dan sesuai keinginan yang tersedia, <a href="#pencarian">Klik Disini</a></p>
                            </div>
                            <div class="col-lg-3 col-md-6 icon-box">
                                <div class="icon"><i class="ri-markup-line"></i></div>
                                <h4 class="title"><a href="">Proses Pencarian</a></h4>
                                <p class="description">Proses dilakukan dengan metode SPK Maut dalam menentukan hasil yang inginkan</p>
                            </div>
                            <div class="col-lg-3 col-md-6 icon-box">
                                <div class="icon"><i class="ri-shape-line"></i></div>
                                <h4 class="title"><a href="">Hasil</a></h4>
                                <p class="description">Menampilkan hasil kedalam tabel</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section id="perumahan" class="services">
            <div class="container-fluid">

                <div class="section-title">
                    <h3>Data <span>Perumahan</span></h3>
                </div>

                <div class="row justify-content-center">
                    <div class="col-xl-10">
                        <table class="table table-sm bg-white table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Gambar/Logo</th>
                                    <th>Nama Instansi</th>
                                    <th>Alamat</th>
                                    <th>No Hp</th>
                                    <th>Jumlah Perumahan</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($instansi as $item)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td><img src="{{ url('gambar/instansi', [$item->gambar]) }}" width="60px" alt=""></td>
                                    <td>{{$item->namainstansi}}</td>
                                    <td>{{$item->alamat}}</td>
                                    <td>{{$item->hp}}</td>
                                    <td>
                                        @php
                                            $perumahan = DB::table('perumahan')->where('idinstansi', $item->idinstansi)->count();
                                        @endphp

                                        {{$perumahan}}
                                    </td>
                                </tr>
                                    
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>


        <section id="pencarian" class="services " >
            <div class="container-fluid" >

                <div class="section-title ">
                    <h3>Analisa <span>Pencarian</span></h3>
                </div>

                <div class="row justify-content-center">
                    <div class="col-xl-10">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6 px-5 text-center align-content-center justify-content-center">
                                <div class="card">
                                    <div class="card-header bg-dark text-light"></div>
                                    <form action="{{ route('cari.data') }}" method="post">
                                        @csrf
                                        <div class="card-body text-center">
                                            <div class="form-group mb-4">
                                                <label class="text-bold" for=""><b>HARGA RUMAH</b></label>
                                                <input type="number" name="hargarumah" id="" class="form-control text-center">
                                                <small><i>@if ($nilaimin != 500000000)
                                                    harga minimum Rp{{number_format($nilaimin)}}
                                                @endif</i></small>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label class="text-bold text-uppercase" for=""><b>Jarak Pusat Kota (Km)</b></label>
                                                <input type="number" step="0.01" name="jarakpusatkota" id="" class="form-control text-center">
                                            </div>
                                            
                                            <div class="form-group mb-4">
                                                <label class="text-bold text-uppercase" for=""><b>Type Rumah</b></label>
                                                <select name="typerumah" id="" class="form-control text-center">
                                                    <option value="">Pilih</option>
                                                    @foreach ($typerumah as $item)
                                                        <option value="{{$item->idnilai}}">Type {{$item->ket}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                
                                            <div class="form-group mb-4">
                                                <label class="text-bold text-uppercase" for=""><b>Luas Tanah</b></label>
                                                <select name="luastanah" id="" class="form-control text-center">
                                                    <option value="">Pilih</option>
                                                    @foreach ($luastanah as $item)
                                                        <option value="{{$item->idnilai}}">{{$item->ket}} m<sup>2</sup></option>
                                                    @endforeach
                                                </select>
                                            </div>
                
                                            <div class="form-group mb-4">
                                                <label class="text-bold text-uppercase" for=""><b>Spesifikasi Rumah</b></label>
                                                <select name="spesifikasirumah" id="" class="form-control text-center">
                                                    <option value="">Pilih</option>
                                                    @foreach ($spesifikasirumah as $item)
                                                        <option value="{{$item->idnilai}}">{{$item->ket}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                
                                            <div class="form-group mb-4">
                                                <label class="text-bold text-uppercase" for=""><b>Kepadatan Penduduk</b></label>
                                                <select name="kepadatanpenduduk" id="" class="form-control text-center">
                                                    <option value="">Pilih</option>
                                                    @foreach ($kepadatanpenduduk as $item)
                                                        <option value="{{$item->idnilai}}">{{$item->ket}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
    
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-block w-100 btn-success">Proses</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
        
    @endif



    @if ($open==true)
    <main id="main">
        <section id="services" class="services">
            <div class="container-fluid">

                <div class="section-title">
                    <h3>Hasil <span>Ranking</span></h3>
                </div>

                <div class="row justify-content-center">
                    <div class="col-xl-10">
                        <a href="{{ route('back.welcome', []) }}" class="btn btn-danger py-1 d-inline">Kembali</a>
                        <form action="{{ route('cetak.laporan') }}" class="d-inline" method="GET" target="_blank">
                            <button type="submit" class="btn btn-secondary my-2 py-1">Cetak Hasil Ranking</button>
                        </form>

                        <table class="table table-sm table-striped table-bordered bg-white">
                            <thead>
                                <th>No</th>
                                <th>Gambar/Logo</th>
                                <th>Nama Instansi</th>
                                <th>Alamat</th>
                                <th>Hp</th>
                                <th>Nilai Ranking</th>
                            </thead>
                            <tbody>
                                @php
                                    $i=1;
                                @endphp
                                @foreach ($hasilUrut as $item)
                                <tr>
                                    <td width="10px">{{$i++}}</td>
                                    <th class="text-center"><img src="{{ url('gambar/instansi', [$item['gambar']]) }}" width="60px" alt=""></th>
                                    <th>{{$item['namainstansi']}}</th>
                                    <td>{{$item['alamat']}}</td>
                                    <td>{{$item['hp']}}</td>
                                    <td>{{$item['nilai']}}</td>
                                </tr>    
                                    
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>
    @endif

    @if ($open == false)
    <footer id="footer">
        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong><span>SPK Maut</span></strong>
            </div>
        </div>
    </footer>
    @endif

    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <script src="{{ url('assets-frontend/vendor/purecounter/purecounter.js', []) }}"></script>
    <script src="{{ url('assets-frontend/vendor/bootstrap/js/bootstrap.bundle.min.js', []) }}"></script>
    <script src="{{ url('assets-frontend/vendor/glightbox/js/glightbox.min.js', []) }}"></script>
    <script src="{{ url('assets-frontend/vendor/isotope-layout/isotope.pkgd.min.js', []) }}"></script>
    <script src="{{ url('assets-frontend/vendor/swiper/swiper-bundle.min.js', []) }}"></script>
    <script src="{{ url('assets-frontend/vendor/waypoints/noframework.waypoints.js', []) }}"></script>
    <script src="{{ url('assets-frontend/vendor/php-email-form/validate.js', []) }}"></script>

    <script src="{{ url('assets-frontend/js/main.js', []) }}"></script>

    @include('sweetalert::alert')

</body>

</html>