<?php 
include '../app/database/koneksi.php';
session_start();

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

// Select Asuransi "Total Asuransi"
$sqlasuransiSelect = "SELECT COUNT(id_asuransi) 
                        AS total_asuransi
                        FROM t_asuransi";

$stmt = $pdo->prepare($sqlasuransiSelect);
$stmt->execute();
$rowAsuransi = $stmt->fetchAll();

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

// Select User "Total User"
$sqluserSelect = "SELECT COUNT(id_user) 
                        AS total_user
                        FROM t_user";

$stmt = $pdo->prepare($sqluserSelect);
$stmt->execute();
$rowUser = $stmt->fetchAll();
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
            <h2><a href="view_dashboard_admin" style="color: #fff"><span class="fas fa-atom"></span>
            <span>Wisata Bahari</span></a></h2>
        </div>
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

                <!-- Dahboard User -->
                <li>
                    <a href="view_dashboard_user">
                    <span class="icon fas fa-home"></span>
                        <span>Dashboard User</span></a>
                </li>
            </ul>
        </div>
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

            <div class="user-wrapper">
                <img src="../views/img/paimon-5.png" width="50px" height="50px" alt="">
                <div>
                    <h2>Selamat Datang</h2>
                    <span class="dashboard">Hi, Sidik Mulyana</span>
                </div>
            </div>
        </header>

        <!-- Main -->
        <main>
            <div class="cards">
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
                        <span>Mentoring Data Pembayaran Reservasi Wisata <small style="color: red;">(belum Transfer)</small></span>
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
                        <span>Mentoring Data Pembayaran Reservasi Wisata <small style="color: red;">(Sudah Transfer)</small></span>
                    </div>
                    <div>
                        <span class="paimon-2 fas fa-money-bill-wave"></span>
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