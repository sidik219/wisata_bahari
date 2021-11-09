<?php 
include '../app/database/koneksi.php';
session_start();

if (!$_SESSION['level_user']) {
    header('location: ../index?status=akses_terbatas');
} else {
    $id_user    = $_SESSION['id_user'];
    $level      = $_SESSION['level_user'];
}

// Select Reservasi "Total Reservasi Wisata"
$sqlreservasiSelect = "SELECT COUNT(id_reservasi_wisata) 
                        AS total_reservasi
                        FROM t_reservasi_wisata
                        LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                        WHERE t_reservasi_wisata.id_user = :id_user";

$stmt = $pdo->prepare($sqlreservasiSelect);
$stmt->execute(['id_user' => $_SESSION['id_user']]);
$rowReservasi = $stmt->fetchAll();

// Select Status Reservasi wisata lama
$sqlstatusSelect = "SELECT * FROM t_status_reservasi
                    WHERE id_status_reservasi = 3";

$stmt = $pdo->prepare($sqlstatusSelect);
$stmt->execute();
$rowStatus3 = $stmt->fetchAll();

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

// Select Lokasi
$sqllokasiSelect = "SELECT * FROM t_lokasi
                    INNER JOIN t_wilayah ON t_lokasi.id_wilayah = t_wilayah.id_wilayah";

$stmt = $pdo->prepare($sqllokasiSelect);
$stmt->execute();
$rowLokasi = $stmt->fetchAll();
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
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    <link rel="stylesheet" href="../views/css/leaflet-panel-layers.css" />
    <link rel="stylesheet" href="../views/css/MarkerCluster.css" />
    <link rel="stylesheet" href="../views/css/MarkerCluster.Default.css" />
    <!-- Bootstrap 5 -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous"> -->
</head>
<body>
    <!-- Menu -->
    <input type="checkbox" id="tombol-gacha"> 
    <div class="sidebar">
        <div class="sidebar-logo">
            <!-- Hak Akses -->
            <?php if ($level == 1) { ?>
            <h2><a href="view_dashboard_user" style="color: #fff"><span class="fas fa-atom"></span>
            <span>Wisata Bahari</span></a></h2>
            <?php } ?>
        </div>

        <!-- Hak Akses -->
        <?php if ($level == 1) { ?>
        <div class="sidebar-menu">
            <ul>
                <!-- Dahboard User -->
                <li>
                    <a href="view_dashboard_user" class="paimon-active">
                    <span class="icon fas fa-home"></span>
                        <span>Dashboard User</span></a>
                </li>
                <li>
                    <a href="view_reservasi_saya">
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
        <?php } ?>
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

            <!-- Hak Akses -->
            <?php if ($level == 1) { ?>
            <div class="user-wrapper">
                <img src="../views/img/paimon-5.png" width="50px" height="50px" alt="">
                <div>
                    <h2>Selamat Datang</h2>
                    <span class="dashboard"><?php echo $_SESSION['nama_user']; ?></span>
                </div>
            </div>
            <?php } ?>
        </header>

        <!-- Hak Akses -->
        <?php if ($level == 1) { ?>
        <!-- Main -->
        <main>
            <div class="cards">
                <!-- Total Reservasi Wisata Baru -->
                <div class="card-single">
                    <div>
                        <?php 
                        foreach ($rowStatus1 as $status) {
                        ?>
                            <?php
                            $sqlfasilitasSelect = "SELECT COUNT(id_reservasi_wisata) AS total_reservasi
                                                FROM t_reservasi_wisata
                                                LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                                                LEFT JOIN t_status_reservasi ON t_reservasi_wisata.id_status_reservasi = t_status_reservasi.id_status_reservasi
                                                WHERE t_status_reservasi.id_status_reservasi = :id_status_reservasi
                                                AND t_reservasi_wisata.id_user = :id_user;";

                            $stmt = $pdo->prepare($sqlfasilitasSelect);
                            $stmt->execute(['id_status_reservasi' => $status->id_status_reservasi,
                                            'id_user' => $_SESSION['id_user']]);
                            $rowReservasi = $stmt->fetchAll();

                            foreach ($rowReservasi as $reservasi) { ?>
                            <h1><?=$reservasi->total_reservasi?></h1>
                            <?php } ?>
                        <?php } ?>
                        <span>Reservasi wisata baru</span>
                    </div>
                    <div>
                        <span class="paimon-1 fas fa-luggage-cart"></span>
                    </div>
                </div>
                
                <!-- Total Reservasi Wisata Lama -->
                <div class="card-single">
                    <div>
                        <?php 
                        foreach ($rowStatus2 as $status) {
                        ?>
                            <?php
                            $sqlfasilitasSelect = "SELECT COUNT(id_reservasi_wisata) AS total_reservasi
                                                FROM t_reservasi_wisata
                                                LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                                                LEFT JOIN t_status_reservasi ON t_reservasi_wisata.id_status_reservasi = t_status_reservasi.id_status_reservasi
                                                WHERE t_status_reservasi.id_status_reservasi = :id_status_reservasi
                                                AND t_reservasi_wisata.id_user = :id_user;";

                            $stmt = $pdo->prepare($sqlfasilitasSelect);
                            $stmt->execute(['id_status_reservasi' => $status->id_status_reservasi,
                                            'id_user' => $_SESSION['id_user']]);
                            $rowReservasi = $stmt->fetchAll();

                            foreach ($rowReservasi as $reservasi) { ?>
                            <h1><?=$reservasi->total_reservasi?></h1>
                            <?php } ?>
                        <?php } ?>
                        <span>Reservasi wisata lama</span>
                    </div>
                    <div>
                        <span class="paimon-4 fas fa-suitcase-rolling"></span>
                    </div>
                </div>

                <!-- Total Reservasi Wisata Bermasalah -->
                <div class="card-single">
                    <div>
                        <?php 
                        foreach ($rowStatus3 as $status) {
                        ?>
                            <?php
                            $sqlfasilitasSelect = "SELECT COUNT(id_reservasi_wisata) AS total_reservasi
                                                FROM t_reservasi_wisata
                                                LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                                                LEFT JOIN t_status_reservasi ON t_reservasi_wisata.id_status_reservasi = t_status_reservasi.id_status_reservasi
                                                WHERE t_status_reservasi.id_status_reservasi = :id_status_reservasi
                                                AND t_reservasi_wisata.id_user = :id_user;";

                            $stmt = $pdo->prepare($sqlfasilitasSelect);
                            $stmt->execute(['id_status_reservasi' => $status->id_status_reservasi,
                                            'id_user' => $_SESSION['id_user']]);
                            $rowReservasi = $stmt->fetchAll();

                            foreach ($rowReservasi as $reservasi) { ?>
                            <h1><?=$reservasi->total_reservasi?></h1>
                            <?php } ?>
                        <?php } ?>
                        <span>Reservasi wisata Bermasalah</span>
                    </div>
                    <div>
                        <span class="paimon-6 fas fa-suitcase"></span>
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
                            <button class="button-map"><a href="view_reservasi_saya" style="color: #fff">
                                Reservasi Saya <span class="fas fa-arrow-right"></span></a></button>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <div id="mapid" style="height: 400px; width: 100%;" class="leaflet-map"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Area B -->
                <div class="area-B">
                    <div class="card">
                        <div class="card-header">
                            <h3>Daftar Lokasi Wisata</h3>
                        </div>

                        <div class="card-body">
                            <?php 
                            foreach ($rowLokasi as $lokasi) {
                            ?>
                            <div class="list-lokasi">
                                <div class="info-wisata">
                                    <img src="<?=$lokasi->foto_lokasi?>?<?php if ($status='nochange'){echo time();}?>" width="50px" height="50px">
                                    <div>
                                        <h4><?=$lokasi->nama_lokasi?></h4>
                                        <small><?=$lokasi->nama_wilayah?></small>
                                    </div>
                                </div>
                                <div class="status-wisata">
                                    <a href="view_pilih_lokasi_wisata.php?id_lokasi=<?=$lokasi->id_lokasi?>">
                                    <span class="fas fa-map-marker-alt"></span></a>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
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
    <!-- Leaflet -->
     <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <!-- Leaflet Marker Cluster -->
    <script src="../views/js/leaflet.markercluster-src.js"></script>
    <!-- Leaflet Legend -->
    <script src="../views/js/leaflet-panel-layers.js"></script>
    <!-- Leaflet Geojson Ajax -->
    <script src="../views/js/leaflet.ajax.js"></script>
    <!-- Leaflet Map -->
    <?php include '../views/js/leaflet_map.php';?>

</body>
</html>