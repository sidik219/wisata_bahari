<?php 
include '../app/database/koneksi.php';
session_start();

if($_GET['id_lokasi']){
    $_SESSION['id_lokasi'] = $_GET['id_lokasi'];
}
else if(!$_GET['id_lokasi' && !$_SESSION['id_lokasi']]){
    header("Location: view_dashboard_user");
}

// Select Paket Wisata
$sqlpaketSelect = 'SELECT * FROM t_paket_wisata
                LEFT JOIN t_lokasi ON t_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                WHERE t_paket_wisata.id_lokasi = :id_lokasi';

$stmt = $pdo->prepare($sqlpaketSelect);
$stmt->execute(['id_lokasi' => $_GET['id_lokasi']]);
$rowPaket = $stmt->fetchAll();
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
            <!-- Button Kembali -->
            <div>
            <button class="button-kelola-kembali"><span class="fas fa-arrow-left"></span>
                <a href="view_dashboard_user" style="color: white;">Kembali</a></button>
            </div>
            
            <!-- Full Area -->
            <div class="full-area-kelola">
                <!-- Area A -->
                <div class="area-A">
                    <div class="card-reservasi">

                        <div class="cards-reservasi">
                            <?php 
                            foreach ($rowPaket as $paket) {
                            ?>
                            <?php if ($paket->status_paket == "Aktif") { ?>
                            <div class="card-paket">
                                <div class="paket-header">
                                    <span class="paket-foto">
                                        <img src="<?=$paket->foto_paket_wisata?>?<?php if ($status='nochange'){echo time();}?>" width="300px">
                                    </span>
                                </div>
                                <div class="paket-body">
                                    <h3 class="paket-judul">
                                        <?=$paket->nama_paket_wisata?>
                                    </h3>

                                    <!-- Lokasi -->
                                    <?php 
                                    $sqlpaketSelect = 'SELECT * FROM t_lokasi
                                                    WHERE id_lokasi = :id_lokasi';

                                    $stmt = $pdo->prepare($sqlpaketSelect);
                                    $stmt->execute(['id_lokasi' => $_GET['id_lokasi']]);
                                    $rowLokasi = $stmt->fetchAll();

                                     foreach ($rowLokasi as $lokasi) {
                                    ?>
                                    <h4 class="paket-text">
                                        <i class="fas fa-map-marked-alt"></i>
                                        <?=$lokasi->nama_lokasi?>
                                    </h4>
                                    <?php } ?>

                                    <!-- Wisata -->
                                    <?php
                                    $sqlpaketSelect = 'SELECT * FROM t_wisata
                                                    LEFT JOIN t_paket_wisata ON t_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                                                    WHERE t_paket_wisata.id_paket_wisata = :id_paket_wisata';

                                    $stmt = $pdo->prepare($sqlpaketSelect);
                                    $stmt->execute(['id_paket_wisata' => $paket->id_paket_wisata]);
                                    $rowWisata = $stmt->fetchAll();

                                    foreach ($rowWisata as $wisata) {
                                    ?>
                                    <ol start="1" class="paket-isi">
                                        <li>
                                            <i class="fas fa-chevron-circle-right"></i>
                                            <?=$wisata->judul_wisata?>
                                        </li>
                                    </ol>
                                    <?php } ?>

                                    <!-- Biaya -->
                                    <?php
                                    $sqlfasilitasSelect = 'SELECT SUM(biaya_fasilitas) AS total_biaya_fasilitas
                                                        FROM t_fasilitas_wisata 
                                                        LEFT JOIN t_wisata ON t_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                                                        LEFT JOIN t_paket_wisata ON t_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                                                        WHERE t_paket_wisata.id_paket_wisata = :id_paket_wisata
                                                        AND t_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata';

                                    $stmt = $pdo->prepare($sqlfasilitasSelect);
                                    $stmt->execute(['id_paket_wisata' => $paket->id_paket_wisata]);
                                    $rowFasilitas = $stmt->fetchAll();

                                    foreach ($rowFasilitas as $fasilitas) { ?>
                                    <h4 class="paket-harga">
                                        Rp. <?=number_format($fasilitas->total_biaya_fasilitas, 0)?>
                                    </h4>
                                    <?php } ?>

                                    <div>
                                    <button class="btn-detail-paket">
                                        <a href="view_detail_lokasi_wisata?id_paket_wisata=<?=$paket->id_paket_wisata?>" style="color: white;">Rincian Reservasi</a></button>
                                    </div>
                                </div>
                            </div>
                                <?php } ?>
                            <?php } ?>
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