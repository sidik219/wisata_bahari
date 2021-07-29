<?php 
include '../app/database/koneksi.php';
session_start();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Wisata Bahari</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS -->
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
    <!-- Font Awasome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Css Dashboard -->
    <link rel="stylesheet" href="../views/css/style-dashboard.css">
    <!-- Bootstrap 5 -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous"> -->
</head>
<body>
    <!-- Menu -->
    <input type="checkbox" id="tombol-gacha"> 
    <div class="sidebar">
        <div class="sidebar-logo">
            <h2><a href="view_dashboard_user" style="color: #fff"><span class="fas fa-atom"></span>
            <span>Wisata Bahari</span></a></h2>
        </div>
        <div class="sidebar-menu">
            <ul>
                <!-- Dahboard User -->
                <li>
                    <a href="view_dashboard_user">
                    <span class="icon fas fa-home"></span>
                        <span>Dashboard User</span></a>
                </li>
                <li>
                    <a href="view_reservasi_saya" class="paimon-active">
                    <span class="fas fa-umbrella-beach"></span>
                        <span>Reservasi Saya</span></a>
                </li>
                <li>
                    <a href="view_akun">
                    <span class="fas fa-user-cog"></span>
                        <span>Akun Saya</span></a>
                </li>
                <li>
                    <a href="logout">
                    <span class="fas fa-sign-out-alt"></span>
                        <span>Log out</span></a>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header>
            <h2 class="dashboard-paimon">
                <label for="tombol-gacha"><span class="fas fa-bars"></span></label>
            </h2>
            <!--
            <div class="search-wrapper">
                <span class="fas fa-search"></span>
                <input type="text" placeholder="Cari lokasi pantai">
            </div>-->

            <div class="user-wrapper">
                <img src="../views/img/paimon-5.png" width="50px" height="50px" alt="">
                <div>
                    <h2>Paimon</h2>
                    <span class="dashboard">Dashboard User</span>
                </div>
            </div>
        </header>

        <!-- Main -->
        <main>
            <div class="cards">
                <div class="card-single">
                    <div>
                        <h1>6</h1>
                        <span>Reservasi wisata</span>
                    </div>
                    <div>
                        <span class="paimon-1 fas fa-suitcase"></span>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <h1>1</h1>
                        <span>Reservasi wisata baru</span>
                    </div>
                    <div>
                        <span class="paimon-2 fas fa-luggage-cart"></span>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <h1>5</h1>
                        <span>Reservasi wisata lama</span>
                    </div>
                    <div>
                        <span class="paimon-3 fas fa-suitcase-rolling"></span>
                    </div>
                </div>
            </div>

            <!-- Full Area -->
            <div class="full-area">
                <!-- Area A -->
                <div class="area-A">
                    <div class="card">
                        <div class="card-header">
                            <h3>Pilih lokasi wisata</h3>
                            <button class="button-map">Reservasi Saya <span class="fas fa-arrow-right"></span></button>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <table>
                                    <thead>
                                        <tr>
                                            <td>aaa</td>
                                            <td>bbb</td>
                                            <td>ccc</td>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>111</td>
                                            <td>222</td>
                                            <td>
                                                <span class="status klee"></span>
                                                333
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>111</td>
                                            <td>222</td>
                                            <td>
                                                <span class="status diona"></span>
                                                333
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>111</td>
                                            <td>222</td>
                                            <td>
                                                <span class="status qiqi"></span>
                                                333
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>111</td>
                                            <td>222</td>
                                            <td>
                                                <span class="status yaoyao"></span>
                                                333
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer>
            <h2 class="footer-paimon">
                <small>© 2021 Wisata Bahari</small> -
                <small>Kab. Karawang</small>
            </h2>
        </footer>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" 
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" 
    integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

</body>
</html>