<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <link rel="stylesheet" href="{{ url('assets/vendors/ti-icons/css/themify-icons.css', []) }}">
    <link rel="stylesheet" href="{{ url('assets/vendors/base/vendor.bundle.base.css', []) }}">
    <link rel="stylesheet" href="{{ url('assets/css/style.css', []) }}">
    <link rel="stylesheet" href="{{ url('assets/vendors/datatables/dataTables.bootstrap4.min.css', []) }}">

</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <h3 class="font-weight-light">Login</h3>
                            <form class="pt-3" method="post" action="{{ route('proses.login', []) }}">
                                @csrf
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg" name="username" id="username" placeholder="Username...">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-lg" name="password" id="password" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <select name="posisi" class="form-control form-control-lg text-dark" id="">
                                        {{-- <option value="">Pilih Posisi</option> --}}
                                        <option value="pengunjung">PENGUNJUNG</option>
                                        <option value="admin">ADMIN</option>
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="{{ url('welcome', []) }}" class="btn my-1 w-100 btn-secondary">Halaman Utama</a>
                                            
                                        </div>
                                        <div class="col-md-6 text-right my-1 justify-content-end align-content-end float-right" style="float: right !important">
                                            <button type="submit" name="button" style="float: right !important" class="btn w-100 d-inline btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Login</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <p class="mt-1">Belum punya akun? <a href="{{ url('daftar', []) }}" class="link-primary">Daftar disini</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <script src="{{ url('assets/vendors/base/vendor.bundle.base.js', []) }}"></script>
    <script src="{{ url('assets/js/jquery.cookie.js', []) }}" type="text/javascript"></script>
    <script src="{{ url('assets/js/off-canvas.js', []) }}"></script>
    <script src="{{ url('assets/js/hoverable-collapse.js', []) }}"></script>
    <script src="{{ url('assets/js/template.js', []) }}"></script>
    <script src="{{ url('assets/js/todolist.js', []) }}"></script>
    @include('sweetalert::alert')

</body>

</html>