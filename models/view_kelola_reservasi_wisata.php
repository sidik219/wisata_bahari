<?php 
include '../app/database/koneksi.php';
session_start();

function ageCalculator($dob){
    $birthdate = new DateTime($dob);
    $today   = new DateTime('today');
    $ag = $birthdate->diff($today)->y;
    $mn = $birthdate->diff($today)->m;
    $dy = $birthdate->diff($today)->d;
    if ($mn == 0)
    {
        return "$dy Hari";
    }
    elseif ($ag == 0)
    {
        return "$mn Bulan  $dy Hari";
    }
    else
    {
        return "$ag Tahun $mn Bulan $dy Hari";
    }
}
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
                <!-- Dahboard Admin -->
                <li>
                    <a href="view_dashboard_admin">
                    <span class="icon fas fa-home"></span>
                        <span>Dashboard Admin</span></a>
                </li>
                <li>
                    <a href="view_kelola_reservasi_wisata" class="paimon-active">
                    <span class="fas fa-luggage-cart"></span>
                        <span>Kelola Reservasi Wisata</span></a>
                </li>
                <li>
                    <a href="view_kelola_asuransi">
                    <span class="fas fa-heartbeat"></span>
                        <span>Kelola Asuransi</span></a>
                </li>
                <li>
                    <a href="view_kelola_wisata">
                    <span class="fas fa-hot-tub"></span>
                        <span>Kelola Wisata</span></a>
                </li>
                <li>
                    <a href="view_kelola_lokasi">
                    <span class="fas fa-map-marked-alt"></span>
                        <span>Kelola Lokasi</span></a>
                </li>
                <li>
                    <a href="view_kelola_wilayah">
                    <span class="fas fa-place-of-worship"></span>
                        <span>Kelola Wilayah</span></a>
                </li>
                <li>
                    <a href="view_kelola_provinsi">
                    <span class="fas fa-globe-asia"></span>
                        <span>Kelola Provinsi</span></a>
                </li>
                <li>
                    <a href="view_kelola_user">
                    <span class="fas fa-users"></span>
                        <span>Kelola User</span></a>
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
            <!-- Full Area -->
            <div class="full-area-kelola">
                <!-- Area A -->
                <div class="area-A">
                    <div class="card">
                        <div class="card-header">
                            <h2>Data Reservasi Wisata</h2>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <table>
                                    <thead>
                                        <tr>
                                            <td>ID Reservasi</td>
                                            <td>Nama User</td>
                                            <td>Nama Lokasi</td>
                                            <td>Nama Paket Wisata</td>
                                            <td>Tanggal Reservasi</td>
                                            <td>Status Reservasi</td>
                                            <td>Aksi</td>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    <?php
                                        $truedate = strtotime(26-10-1997);
                                    ?>
                                        <tr>
                                            <td>111</td>
                                            <td>222</td>
                                            <td>333</td>
                                            <td>444</td>
                                            <td>555</td>
                                            <td>
                                                <span class="status klee"></span>
                                                666
                                                <br><small style="color: rgba(0, 0, 0, 0.5);">Update Terakhir:
                                                <br><?=strftime('%A, %d %B %Y', $truedate);?></small>
                                            </td>
                                            <td>
                                                <button class="modol-btn button-kelola-detail">Detail</button>
                                                <button class="button-kelola-edit">Edit</button>
                                                <button class="button-kelola-hapus">Hapus</button>
                                            </td>
                                        </tr>
                                    </tbody>

                                    <!-- POP UP -->
                                    <div class="modol-bg">
                                        <div class="modol">
                                            <div class="modol-header">
                                                <h2 class="modol-title">Detail Data Reservasi Wisata</h2>
                                            </div>
                                            
                                            <div class="modol-body">
                                                <div class="modol-input">
                                                    <label for="">Total Reservasi</label>
                                                    <div class="modol-isi">
                                                        <i class="modol-logo-duid fas fa-money-bill-wave"></i>
                                                        Rp. 1.500.000<br>
                                                    </div>
                                                </div>
                                                <div class="modol-input">
                                                    <label for="">Wisata</label>
                                                    <div class="modol-isi">
                                                        <i class="modol-logo-wisata fas fa-luggage-cart"></i>
                                                        Diving<br>
                                                        <i class="modol-logo-wisata fas fa-luggage-cart"></i>
                                                        Diving<br>
                                                        <i class="modol-logo-wisata fas fa-luggage-cart"></i>
                                                        Diving<br>
                                                    </div>
                                                </div>
                                                <div class="modol-input">
                                                    <label for="">Fasilitas Wisata</label>
                                                    <div class="modol-isi">
                                                        <i class="modol-logo-fasilitas fas fa-truck-loading"></i>
                                                        fasilitas<br>
                                                        <i class="modol-logo-fasilitas fas fa-truck-loading"></i>
                                                        fasilitas<br>
                                                        <i class="modol-logo-fasilitas fas fa-truck-loading"></i>
                                                        fasilitas<br>
                                                        <i class="modol-logo-fasilitas fas fa-truck-loading"></i>
                                                        fasilitas<br>
                                                        <i class="modol-logo-fasilitas fas fa-truck-loading"></i>
                                                        fasilitas<br>
                                                        <i class="modol-logo-fasilitas fas fa-truck-loading"></i>
                                                        fasilitas<br>
                                                        <i class="modol-logo-fasilitas fas fa-truck-loading"></i>
                                                        fasilitas<br>
                                                        <i class="modol-logo-fasilitas fas fa-truck-loading"></i>
                                                        fasilitas<br>
                                                        <i class="modol-logo-fasilitas fas fa-truck-loading"></i>
                                                        fasilitas<br>
                                                    </div>
                                                </div>
                                                <div class="modol-input">
                                                    <label for="">Keterangan Reservasi</label>
                                                    <input type="text" value="Sedang ada badai" readonly>
                                                </div>
                                                <div class="modol-input">
                                                    <label for="">No HP User</label>
                                                    <input type="text" value="12345" readonly>
                                                </div>
                                            </div>
                                            
                                            <span class="modol-keluar">X</span>
                                        </div>
                                    </div>
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
    <script src="../plugins/bootstrap-5/js/bootstrap.js"></script>

    <!-- All Javascript -->
    <!-- Modal -->
    <script>
        var modolBtn    = document.querySelector('.modol-btn');
        var modolBg     = document.querySelector('.modol-bg');
        var modolKeluar = document.querySelector('.modol-keluar');

        modolBtn.addEventListener('click', function(){
            modolBg.classList.add('modol-aktif');
        })
        modolKeluar.addEventListener('click', function(){
            modolBg.classList.remove('modol-aktif');
        })
    </script>

</body>
</html>