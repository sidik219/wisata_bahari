<?php 
include '../app/database/koneksi.php';
session_start();

if($_GET['id_lokasi']){
    $_SESSION['id_lokasi'] = $_GET['id_lokasi'];
}
else if(!$_GET['id_lokasi' && !$_SESSION['id_lokasi']]){
    header("Location: view_kelola_lokasi");
}

$sqllokasiSelect = 'SELECT * FROM t_lokasi
                LEFT JOIN t_wilayah ON t_lokasi.id_wilayah = t_wilayah.id_wilayah
                WHERE t_lokasi.id_lokasi = :id_lokasi';

$stmt = $pdo->prepare($sqllokasiSelect);
$stmt->execute(['id_lokasi' => $_GET['id_lokasi']]);
$rowLokasi = $stmt->fetch();
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
                    <a href="view_kelola_lokasi" class="paimon-active">
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
            <a href="view_kelola_lokasi" style="color: white;">Kembali</a></button>
            </div>

            <!-- Full Area -->
            <div class="full-area-kelola">
                <!-- Area A -->
                <div class="area-A">
                    <div class="card">
                        <div class="card-header">
                            <h2>Detail Data wisata</h2>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    <!-- Form Create Fasilitas Wisata -->
                                    <div class="kelola-detail-paket"> 
                                        <div class="input-box">
                                            <span class="details">Foto Lokasi</span>
                                            <br><img src="<?=$rowLokasi->foto_lokasi?>?<?php if ($status='nochange'){echo time();}?>" width="300px">
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Deskripsi Lokasi</span>
                                            <input type="text" value="<?=$rowLokasi->deskripsi_lokasi?>" readonly>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Kontak Lokasi</span>
                                            <input type="text" value="<?=$rowLokasi->kontak_lokasi?>" readonly>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Nama Bank</span>
                                            <input type="text" value="<?=$rowLokasi->nama_bank?>" readonly>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Nama Rekening</span>
                                            <input type="text" value="<?=$rowLokasi->nama_rekening?>" readonly>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Nomor Rekening</span>
                                            <input type="text" value="<?=$rowLokasi->nomor_rekening?>" readonly>
                                        </div>
                                    </div>
                                    <!-- End Form -->
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