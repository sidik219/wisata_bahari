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

// Select Reservasi "Total Reservasi Wisata"
$sqlreservasiSelect = "SELECT COUNT(id_reservasi_wisata) 
                        AS total_reservasi
                        FROM t_reservasi_wisata";

$stmt = $pdo->prepare($sqlreservasiSelect);
$stmt->execute();
$rowReservasi = $stmt->fetchAll();

// Select Status Reservasi wisata lama
$sqlstatusSelect = "SELECT * FROM t_status_reservasi
                    WHERE id_status_reservasi = 2";

$stmt = $pdo->prepare($sqlstatusSelect);
$stmt->execute();
$rowStatus2 = $stmt->fetchAll();

// Select Status Reservasi wisata Baru
$sqlstatusSelect = "SELECT * FROM t_status_reservasi
                    WHERE id_status_reservasi = 1";

$stmt = $pdo->prepare($sqlstatusSelect);
$stmt->execute();
$rowStatus1 = $stmt->fetchAll();

// Select Paket "Total Paket Wisata"
$sqlpaketSelect = "SELECT COUNT(id_paket_wisata) 
                        AS total_paket
                        FROM t_paket_wisata";

$stmt = $pdo->prepare($sqlpaketSelect);
$stmt->execute();
$rowPaket = $stmt->fetchAll();

// Select Wisata "Total Wisata"
$sqlwisataSelect = "SELECT COUNT(id_wisata) 
                        AS total_wisata
                        FROM t_wisata";

$stmt = $pdo->prepare($sqlwisataSelect);
$stmt->execute();
$rowWisata = $stmt->fetchAll();

// Select Fasilitas "Total Fasilitas"
$sqlfasilitasSelect = "SELECT COUNT(id_fasilitas_wisata) 
                        AS total_fasilitas
                        FROM t_fasilitas_wisata";

$stmt = $pdo->prepare($sqlfasilitasSelect);
$stmt->execute();
$rowFasilitas = $stmt->fetchAll();

// Select Asuransi "Total Asuransi"
$sqlasuransiSelect = "SELECT COUNT(id_asuransi) 
                        AS total_asuransi
                        FROM t_asuransi";

$stmt = $pdo->prepare($sqlasuransiSelect);
$stmt->execute();
$rowAsuransi = $stmt->fetchAll();

// Select Kerjasama "Total Kerjasama"
$sqlkerjasamaSelect = "SELECT COUNT(id_kerjasama) 
                        AS total_kerjasama
                        FROM t_kerjasama";

$stmt = $pdo->prepare($sqlkerjasamaSelect);
$stmt->execute();
$rowKerjasama = $stmt->fetchAll();

// Select Pengadaan "Total Pengadaan"
$sqlpengadaanSelect = "SELECT COUNT(id_pengadaan) 
                        AS total_pengadaan
                        FROM t_pengadaan_fasilitas";

$stmt = $pdo->prepare($sqlpengadaanSelect);
$stmt->execute();
$rowPengadaan = $stmt->fetchAll();

// Select User "Total User"
$sqluserSelect = "SELECT COUNT(id_user) 
                        AS total_user
                        FROM t_user";

$stmt = $pdo->prepare($sqluserSelect);
$stmt->execute();
$rowUser = $stmt->fetchAll();

// ChartJS
$label = ["Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"];

for($bulan = 1; $bulan < 13; $bulan++) {

    // Wisatawan
    $sqlreservasiSelect = 'SELECT COUNT(id_reservasi_wisata) AS total_wisatawan FROM t_reservasi_wisata
                            WHERE MONTH(tgl_reservasi) = :bulan';

    $stmt = $pdo->prepare($sqlreservasiSelect);
    $stmt->execute(['bulan' => $bulan]);
    $totalWisatawan = $stmt->fetch();

    $total_wisatawan[] = $totalWisatawan->total_wisatawan;

    // Pendapatan Reservasi Wisata
    $sqlreservasiSelect = 'SELECT SUM(total_reservasi) AS pendapatan_reservasi FROM t_reservasi_wisata
                            WHERE MONTH(tgl_reservasi) = :bulan';

    $stmt = $pdo->prepare($sqlreservasiSelect);
    $stmt->execute(['bulan' => $bulan]);
    $totalReservasi = $stmt->fetch();

    $pendapatan_reservasi[] = $totalReservasi->pendapatan_reservasi;
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
                    <a href="view_dashboard_admin" class="paimon-active">
                    <span class="icon fas fa-home"></span>
                        <span>Dashboard Admin</span></a>
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
                    <a href="view_dashboard_admin" class="paimon-active">
                    <span class="icon fas fa-home"></span>
                        <span>Dashboard Admin</span></a>
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
                    <a href="view_dashboard_admin" class="paimon-active">
                    <span class="icon fas fa-home"></span>
                        <span>Dashboard Admin</span></a>
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
                <img id="oldpic" src="<?=$rowUser2->foto_user?>" width="50px" height="50px" <?php if($rowUser2->foto_user == NULL) echo "style='display: none;'"; ?>>
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
                <?php if ($level == 2 || $level == 3 || $level == 4) {?>
                <!-- Reservasi Wisata -->
                <div class="card-single">
                    <div>
                        <?php 
                        foreach ($rowReservasi as $reservasi) {
                        ?>
                        <h1><?=$reservasi->total_reservasi?></h1>
                        <?php } ?>
                        <span>Mentoring Data Reservasi wisata</span>
                    </div>
                    <div>
                        <span class="paimon-1 fas fa-suitcase"></span>
                    </div>
                </div>

                <!-- Pembayaran Reservasi Wisata Belum Transfer -->
                <div class="card-single">
                    <div>
                        <?php 
                        foreach ($rowStatus1 as $status) {
                        ?>
                            <?php
                            $sqlfasilitasSelect = "SELECT COUNT(id_reservasi_wisata) AS total_reservasi
                                                FROM t_reservasi_wisata
                                                LEFT JOIN t_status_reservasi ON t_reservasi_wisata.id_status_reservasi = t_status_reservasi.id_status_reservasi
                                                WHERE t_status_reservasi.id_status_reservasi = :id_status_reservasi
                                                AND t_status_reservasi.id_status_reservasi = t_reservasi_wisata.id_status_reservasi;";

                            $stmt = $pdo->prepare($sqlfasilitasSelect);
                            $stmt->execute(['id_status_reservasi' => $status->id_status_reservasi]);
                            $rowReservasi = $stmt->fetchAll();

                            foreach ($rowReservasi as $reservasi) { ?>
                            <h1><?=$reservasi->total_reservasi?></h1>
                            <?php } ?>
                        <?php } ?>
                        <span>Mentoring Data Reservasi Wisata <small style="color: red;">(Belum Dicek)</small></span>
                    </div>
                    <div>
                        <span class="paimon-2 fas fa-money-bill-wave"></span>
                    </div>
                </div>

                <!-- Pembayaran Reservasi Wisata Sudah Transfer -->
                <div class="card-single">
                    <div>
                        <?php 
                        foreach ($rowStatus2 as $status) {
                        ?>
                            <?php
                            $sqlfasilitasSelect = "SELECT COUNT(id_reservasi_wisata) AS total_reservasi
                                                FROM t_reservasi_wisata
                                                LEFT JOIN t_status_reservasi ON t_reservasi_wisata.id_status_reservasi = t_status_reservasi.id_status_reservasi
                                                WHERE t_status_reservasi.id_status_reservasi = :id_status_reservasi
                                                AND t_status_reservasi.id_status_reservasi = t_reservasi_wisata.id_status_reservasi;";

                            $stmt = $pdo->prepare($sqlfasilitasSelect);
                            $stmt->execute(['id_status_reservasi' => $status->id_status_reservasi]);
                            $rowReservasi = $stmt->fetchAll();

                            foreach ($rowReservasi as $reservasi) { ?>
                            <h1><?=$reservasi->total_reservasi?></h1>
                            <?php } ?>
                        <?php } ?>
                        <span>Mentoring Data Reservasi Wisata <small style="color: blue;">(Sudah Dicek)</small></span>
                    </div>
                    <div>
                        <span class="paimon-2 fas fa-money-bill-wave"></span>
                    </div>
                </div>
                <?php } ?>
                
                <!-- Hak Akses Pengelola Lokasi atau Provinsi -->
                <?php if ($level == 2 || $level == 4) {?>
                <!-- Paket Wisata -->
                <div class="card-single">
                    <div>
                        <?php 
                        foreach ($rowPaket as $paket) {
                        ?>
                        <h1><?=$paket->total_paket?></h1>
                        <?php } ?>
                        <span>Mentoring Data Paket Wisata</span>
                    </div>
                    <div>
                        <span class="paimon-4 fas fa-cubes"></span>
                    </div>
                </div>

                <!-- Wisata -->
                <div class="card-single">
                    <div>
                        <?php 
                        foreach ($rowWisata as $wisata) {
                        ?>
                        <h1><?=$wisata->total_wisata?></h1>
                        <?php } ?>
                        <span>Mentoring Data Wisata</span>
                    </div>
                    <div>
                        <span class="paimon-5 fas fa-luggage-cart"></span>
                    </div>
                </div>

                <!-- Fasilitas Wisata -->
                <div class="card-single">
                    <div>
                        <?php 
                        foreach ($rowFasilitas as $fasilitas) {
                        ?>
                        <h1><?=$fasilitas->total_fasilitas?></h1>
                        <?php } ?>
                        <span>Mentoring Data Fasilitas Wisata</span>
                    </div>
                    <div>
                        <span class="paimon-6 fas fa-truck-loading"></span>
                    </div>
                </div>

                <!-- Asuransi -->
                <div class="card-single">
                    <div>
                        <?php 
                        foreach ($rowAsuransi as $asuransi) {
                        ?>
                        <h1><?=$asuransi->total_asuransi?></h1>
                        <?php } ?>
                        <span>Mentoring Data Asuransi</span>
                    </div>
                    <div>
                        <span class="paimon-3 fas fa-heartbeat"></span>
                    </div>
                </div>

                <!-- Kerjasama -->
                <div class="card-single">
                    <div>
                        <?php 
                        foreach ($rowKerjasama as $kerjasama) {
                        ?>
                        <h1><?=$kerjasama->total_kerjasama?></h1>
                        <?php } ?>
                        <span>Mentoring Data Kerjasama</span>
                    </div>
                    <div>
                        <span class="paimon-3 fas fa-handshake"></span>
                    </div>
                </div>

                <!-- Pengadaan -->
                <div class="card-single">
                    <div>
                        <?php 
                        foreach ($rowPengadaan as $pengadaan) {
                        ?>
                        <h1><?=$pengadaan->total_pengadaan?></h1>
                        <?php } ?>
                        <span>Mentoring Data Pengadaan</span>
                    </div>
                    <div>
                        <span class="paimon-3 fas fa-truck-loading"></span>
                    </div>
                </div>
                <?php } ?>
                
                <!-- Hak Akses Pengelola Wilayah atau Provinsi -->
                <?php if ($level == 4) {?>
                <!-- User -->
                <div class="card-single">
                    <div>
                        <?php 
                        foreach ($rowUser as $user) {
                        ?>
                        <h1><?=$user->total_user?></h1>
                        <?php } ?>
                        <span>Mentoring Data User</span>
                    </div>
                    <div>
                        <span class="paimon-7 fas fa-users"></span>
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