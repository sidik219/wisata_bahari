<?php 
include '../app/database/koneksi.php';
session_start();

if (!$_SESSION['level_user']) {
    header('location: ../index?status=akses_terbatas');
} else {
    $id_user    = $_SESSION['id_user'];
    $level      = $_SESSION['level_user'];
}

$defaultpic = "../views/img/image_default.jpg";

// Select All Data User
$sqluserSelect = "SELECT * FROM t_user
                    WHERE id_user = :id_user";

$stmt = $pdo->prepare($sqluserSelect);
$stmt->execute(['id_user' => $_SESSION['id_user']]);
$rowUser2 = $stmt->fetch();
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
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    <!-- Bootstrap 5 -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous"> -->
</head>
<body>
    <!-- Menu -->
    <input type="checkbox" id="tombol-gacha"> 
    <div class="sidebar">
        <div class="sidebar-logo">
            <!-- Hak Akses Pengelola Lokasi -->
            <?php if ($level == 2 || $level == 3 || $level == 4) { ?>
            <h2><a href="view_dashboard_admin" style="color: #fff"><span class="fas fa-atom"></span>
            <span>Wisata Bahari</span></a></h2>
            <?php } ?>
        </div>

        <!-- Hak Akses Pengelola Lokasi -->
        <?php if ($level == 2) { ?>
        <div class="sidebar-menu">
            <ul>
                <!-- Dahboard Admin -->
                <li>
                    <a href="view_dashboard_admin">
                    <span class="icon fas fa-home"></span>
                        <span>Dashboard Admin</span></a>
                </li>
                <li>
                    <a href="kelola_laporan_periode" class="paimon-active">
                    <span class="icon far fa-file-alt"></span>
                        <span>Kelola Laporan Periode</span></a>
                </li>
                <li>
                    <a href="view_kelola_pengajuan">
                    <span class="fas fa-file-signature"></span>
                        <span>Kelola Pengajuan</span></a>
                </li>
                <li>
                    <a href="view_kelola_reservasi_wisata">
                    <span class="fas fa-luggage-cart"></span>
                        <span>Kelola Reservasi Wisata</span></a>
                </li>
                <li>
                    <a href="view_kelola_wisata">
                    <span class="fas fa-hot-tub"></span>
                        <span>Kelola Paket Wisata</span></a>
                </li>
                <li>
                    <a href="view_kelola_asuransi">
                    <span class="fas fa-heartbeat"></span>
                        <span>Kelola Asuransi</span></a>
                </li>
                <li>
                    <a href="view_kelola_kerjasama">
                    <span class="fas fa-handshake"></span>
                        <span>Kelola Kerjasama</span></a>
                </li>
                <li>
                    <a href="view_kelola_pengadaan">
                    <span class="fas fa-truck-loading"></span>
                        <span>Kelola Pengadaan</span></a>
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
        <?php } ?>

        <!-- Hak Akses Pengelola Wilayah -->
        <?php if ($level == 3) { ?>
        <div class="sidebar-menu">
            <ul>
                <!-- Dahboard Admin -->
                <li>
                    <a href="view_dashboard_admin">
                    <span class="icon fas fa-home"></span>
                        <span>Dashboard Admin</span></a>
                </li>
                <li>
                    <a href="kelola_laporan_periode" class="paimon-active">
                    <span class="icon far fa-file-alt"></span>
                        <span>Kelola Laporan Periode</span></a>
                </li>
                <li>
                    <a href="view_kelola_pengajuan">
                    <span class="fas fa-file-signature"></span>
                        <span>Kelola Pengajuan</span></a>
                </li>
                <!-- <li>
                    <a href="view_kelola_reservasi_wisata">
                    <span class="fas fa-luggage-cart"></span>
                        <span>Kelola Reservasi Wisata</span></a>
                </li> -->
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
        <?php } ?>

        <!-- Hak Akses Pengelola Provinsi -->
        <?php if ($level == 4) { ?>
        <div class="sidebar-menu">
            <ul>
                <!-- Dahboard Admin -->
                <li>
                    <a href="view_dashboard_admin">
                    <span class="icon fas fa-home"></span>
                        <span>Dashboard Admin</span></a>
                </li>
                <li>
                    <a href="kelola_laporan_periode" class="paimon-active">
                    <span class="icon far fa-file-alt"></span>
                        <span>Kelola Laporan Periode</span></a>
                </li>
                <li>
                    <a href="view_kelola_pengajuan">
                    <span class="fas fa-file-signature"></span>
                        <span>Kelola Pengajuan</span></a>
                </li>
                <li>
                    <a href="view_kelola_reservasi_wisata">
                    <span class="fas fa-luggage-cart"></span>
                        <span>Kelola Reservasi Wisata</span></a>
                </li>
                <li>
                    <a href="view_kelola_wisata">
                    <span class="fas fa-hot-tub"></span>
                        <span>Kelola Paket Wisata</span></a>
                </li>
                <li>
                    <a href="view_kelola_asuransi">
                    <span class="fas fa-heartbeat"></span>
                        <span>Kelola Asuransi</span></a>
                </li>
                <li>
                    <a href="view_kelola_kerjasama">
                    <span class="fas fa-handshake"></span>
                        <span>Kelola Kerjasama</span></a>
                </li>
                <li>
                    <a href="view_kelola_pengadaan">
                    <span class="fas fa-truck-loading"></span>
                        <span>Kelola Pengadaan</span></a>
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
        <?php } ?>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header>
            <h2 class="dashboard-paimon">
                <label for="tombol-gacha"><span class="fas fa-bars gacha"></span></label>
            </h2>
            <!--
            <div class="search-wrapper">
                <span class="fas fa-search"></span>
                <input type="text" placeholder="Cari lokasi pantai">
            </div>-->

            <!-- Hak Akses Pengelola Lokasi-->
            <?php if ($level == 2 || $level == 3 || $level == 4) { ?>
            <div class="user-wrapper">
                <!-- <img src="../views/img/paimon-5.png" width="50px" height="50px" alt=""> -->
                <img src="<?=$rowUser2->foto_user?>" width="50px" height="50px" <?php if($rowUser2->foto_user == NULL) echo "style='display: none;'"; ?>>
                <div>
                    <h2>Selamat Datang</h2>
                    <span class="dashboard"><?php echo $_SESSION['nama_user']; ?></span>
                </div>
            </div>
            <?php } ?>
        </header>

        <!-- Hak Akses Pengelola Lokasi atau Wilayah atau Provinsi -->
        <?php if ($level == 2 || $level == 3 || $level == 4) { ?>
        <!-- Main -->
        <main>
            <div class="cards">
                <!-- Hak Akses Pengelola Lokasi atau Wilayah atau Provinsi -->
                <?php if ($level == 2 || $level == 4) {?>
                <!-- Reservasi Wisata -->
                <div class="card-single2">
                    <div>
                        <span>
                            <button class="button-kelola-kembali">
                                <a href="laporan_periode_pendapatan" style="color: white;">
                                    <h3>Laporan Periode Pendapatan</h3>
                                    <span class="fas fa-plus" style="color: white;"></span>
                                </a>
                            </button>
                        </span>
                    </div>
                    <div>
                        <span class="paimon-3 fas fa-hand-holding-usd"></span>
                    </div>
                </div>
                <?php } ?>

                <?php if ($level == 2 || $level == 4) {?>
                <!-- Reservasi Wisata -->
                <div class="card-single2">
                    <div>
                        <span>
                            <button class="button-kelola-kembali">
                                <a href="laporan_periode_pengeluaran" style="color: white;">
                                    <h3>Laporan Periode Pengeluaran</h3>
                                    <span class="fas fa-plus" style="color: white;"></span>
                                </a>
                            </button>
                        </span>
                    </div>
                    <div>
                        <span class="paimon-3 fas fa-money-bill-wave"></span>
                    </div>
                </div>
                <?php } ?>
                
                <!-- Hak Akses Pengelola Wilayah -->
                <?php if ($level == 2 || $level == 3 || $level == 4) {?>
                <!-- Pengajuan -->
                <div class="card-single2">
                    <div>
                        <span>
                            <button class="button-kelola-kembali">
                                <a href="laporan_periode_pengajuan" style="color: white;">
                                    <h3>Laporan Periode Pengajuan</h3>
                                    <span class="fas fa-plus" style="color: white;"></span>
                                </a>
                            </button>
                        </span>
                    </div>
                    <div>
                        <span class="paimon-3 fas fa-file-signature"></span>
                    </div>
                </div>
                <?php } ?>
                
                <?php if ($level == 2 || $level == 4) {?>
                <!-- Paket Wisata -->
                <div class="card-single2">
                    <div>
                        <span>
                            <button class="button-kelola-kembali">
                                <a href="laporan_periode_paket_wisata" style="color: white;">
                                    <h3>Laporan Periode Paket Wisata</h3>
                                    <span class="fas fa-plus" style="color: white;"></span>
                                </a>
                            </button>
                        </span>
                    </div>
                    <div>
                        <span class="paimon-3 fas fa-cubes"></span>
                    </div>
                </div>
                <?php } ?>

                <?php if ($level == 2 || $level == 4) {?>
                <!-- Asuransi -->
                <div class="card-single2">
                    <div>
                        <span>
                            <button class="button-kelola-kembali">
                                <a href="laporan_periode_asuransi" style="color: white;">
                                    <h3>Laporan Periode Asuransi</h3>
                                    <span class="fas fa-plus" style="color: white;"></span>
                                </a>
                            </button>
                        </span>
                    </div>
                    <div>
                        <span class="paimon-3 fas fa-heartbeat"></span>
                    </div>
                </div>
                <?php } ?>
                
                <?php if ($level == 2 || $level == 4) {?>
                <!-- Kerjasama -->
                <div class="card-single2">
                    <div>
                        <span>
                            <button class="button-kelola-kembali">
                                <a href="laporan_periode_kerjasama" style="color: white;">
                                    <h3>Laporan Periode Kerjasama</h3>
                                    <span class="fas fa-plus" style="color: white;"></span>
                                </a>
                            </button>
                        </span>
                    </div>
                    <div>
                        <span class="paimon-3 fas fa-handshake"></span>
                    </div>
                </div>
                <?php } ?>

                <?php if ($level == 2 || $level == 4) {?>
                <!-- Pengadaan Fasilitas -->
                <div class="card-single2">
                    <div>
                        <span>
                            <button class="button-kelola-kembali">
                                <a href="laporan_periode_pengadaan" style="color: white;">
                                    <h3>Laporan Periode Pengadaan</h3>
                                    <span class="fas fa-plus" style="color: white;"></span>
                                </a>
                            </button>
                        </span>
                    </div>
                    <div>
                        <span class="paimon-3 fas fa-truck-loading"></span>
                    </div>
                </div>
                <?php } ?>
            </div>

            <!-- Full Area -->
            <div class="full-area-kelola">
                <!-- Area A -->
                <!-- <div class="area-A">
                    <div class="card">
                        <div class="card-header">
                            <button class="button-kelola-kembali" id="btn-wisatawan">
                            <i class="far fa-file-image"></i> Export ke Image</button>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <canvas id="wisatawan" width="100%" height="100%"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="card" style="margin-top: 2rem;">
                        <div class="card-header">
                            <button class="button-kelola-kembali" id="btn-reservasi">
                            <i class="far fa-file-image"></i> Export ke Image</button>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <canvas id="reservasi" width="100%" height="100%"></canvas>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </main>
        <?php } ?>

        <!-- Footer -->
        <footer>
            <h2 class="footer-paimon">
                <small>© 2021 Wisata Bahari</small>
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
    
    <!-- All Javascript -->
    <!-- Jquery Plugin -->
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <!-- CharJs CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.min.js" integrity="sha512-asxKqQghC1oBShyhiBwA+YgotaSYKxGP1rcSYTDrB0U6DxwlJjU59B67U8+5/++uFjcuVM8Hh5cokLjZlhm3Vg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.js" integrity="sha512-XcsV/45eM/syxTudkE8AoKK1OfxTrlFpOltc9NmHXh3HF+0ZA917G9iG6Fm7B6AzP+UeEzV8pLwnbRNPxdUpfA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Export to Image -->
    <script src="../plugins/canvas-toBlob.js-master/canvas-toBlob.js"></script>
    <!-- FileSaver -->
    <script src="../plugins/FileSaver.js-master/dist/FileSaver.min.js"></script>
    <!-- Chartjs -->
    <?php include '../views/js/chartjs.php'; ?>

</body>
</html>