<?php 
include '../app/database/koneksi.php';
session_start();

if($_GET['id_reservasi_wisata']){
    $_SESSION['id_reservasi_wisata'] = $_GET['id_reservasi_wisata'];
}
else if(!$_GET['id_reservasi_wisata' && !$_SESSION['id_reservasi_wisata']]){
    header("Location: view_kelola_wisata");
}

$sqlreservasiSelect = 'SELECT * FROM t_reservasi_wisata
                    LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                    LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
                    LEFT JOIN t_paket_wisata ON t_reservasi_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                    LEFT JOIN t_status_reservasi ON t_reservasi_wisata.id_status_reservasi = t_status_reservasi.id_status_reservasi
                    WHERE t_reservasi_wisata.id_reservasi_wisata = :id_reservasi_wisata';

$stmt = $pdo->prepare($sqlreservasiSelect);
$stmt->execute(['id_reservasi_wisata' => $_GET['id_reservasi_wisata']]);
$rowReservasi = $stmt->fetchAll();
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
            <h2><a href="view_dashboard_admin" style="color: #fff"><span class="fas fa-atom"></span>
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
            <!-- Button Kembali -->
            <div>
            <button class="button-kelola-kembali"><span class="fas fa-arrow-left"></span>
            <a href="view_kelola_reservasi_wisata" style="color: white;">Kembali</a></button>
            </div>

            <!-- Full Area -->
            <div class="full-area-kelola">
                <!-- Area A -->
                <div class="area-A">
                    <div class="card">
                        <div class="card-header">
                            <h2>Detail Data Reservasi wisata</h2>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    <?php 
                                        foreach ($rowReservasi as $reservasi) {
                                    ?>
                                    <!-- Form Create Fasilitas Wisata -->
                                    <div class="kelola-detail-paket">
                                        <div class="input-box">
                                            <span class="details">Jumlah Reservasi</span>
                                            <input type="text" value="<?=$reservasi->jumlah_reservasi?>" readonly>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Total Reservasi</span>
                                            <input type="text" value="Rp. <?=number_format($reservasi->total_reservasi, 0)?>" readonly>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Keterangan Reservasi</span>
                                            <input type="text" value="<?=$reservasi->keterangan_reservasi?>" readonly>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Bukti Reservasi</span>
                                            <br><img src="<?=$reservasi->bukti_reservasi?>?<?php if ($status='nochange'){echo time();}?>" width="300px">
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Nama Bank Wisatawan</span>
                                            <input type="text" value="<?=$reservasi->nama_bank_wisatawan?>" readonly>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Nama Rekening Wisatawan</span>
                                            <input type="text" value="<?=$reservasi->nama_rekening_wisatawan?>" readonly>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Nomor Rekening Wisatawan</span>
                                            <input type="text" value="<?=$reservasi->nomor_rekening_wisatawan?>" readonly>
                                        </div>
                                    </div>
                                    <!-- End Form -->
                                    <?php } ?>
                                </form>
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

</body>
</html>