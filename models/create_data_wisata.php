<?php 
include '../app/database/koneksi.php';
session_start();

if (!$_SESSION['level_user']) {
    header('location: ../index?status=akses_terbatas');
} else {
    $id_user    = $_SESSION['id_user'];
    $level      = $_SESSION['level_user'];
}

// Select Fasilitas Wisata
$sqlfasilitasSelect = "SELECT * FROM t_fasilitas_wisata
                        ORDER BY id_fasilitas_wisata
                        DESC LIMIT 3";

$stmt = $pdo->prepare($sqlfasilitasSelect);
$stmt->execute();
$rowFasilitas = $stmt->fetchAll();

if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Simpan') {
        $judul_wisata       = $_POST['judul_wisata'];
        $deskripsi_wisata   = $_POST['deskripsi_wisata'];

        $sqlasuransiCreate = "INSERT INTO t_wisata
                            (judul_wisata,
                            deskripsi_wisata)
                            VALUE (:judul_wisata,
                                    :deskripsi_wisata)";
        
        $stmt = $pdo->prepare($sqlasuransiCreate);
        $stmt->execute(['judul_wisata' => $judul_wisata,
                        'deskripsi_wisata' => $deskripsi_wisata]);
        
        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
            // header("Location: view_kelola_fasilitas_wisata?status=tambahGagal");
        } else {
            $last_id_wisata = $pdo->lastInsertId();
        }

        $i = 0;
        foreach ($_POST['id_fasilitas_wisata'] as $id_fasilitas_wisata) {
            $id_fasilitas_wisata    = $_POST['id_fasilitas_wisata'][$i];
            $id_wisata              = $last_id_wisata;

            $sqlfasilitasUpdate = "UPDATE t_fasilitas_wisata
                                    SET id_wisata = :id_wisata
                                    WHERE id_fasilitas_wisata = :id_fasilitas_wisata";

            $stmt = $pdo->prepare($sqlfasilitasUpdate);
            $stmt->execute(['id_fasilitas_wisata' => $id_fasilitas_wisata,
                            'id_wisata' => $id_wisata]);
        
            $affectedrows = $stmt->rowCount();
            if ($affectedrows == '0') {
                header("Location: create_data_wisata?status=tambahGagal");
            } else {
                header("Location: create_data_wisata?status=tambahBerhasil");
            }
            $i++;
        }
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
            <!-- Hak Akses Pengelola Wilayah atau Provinsi -->
            <?php if ($level == 3 || $level == 4) { ?>
            <h2><a href="view_dashboard_admin" style="color: #fff"><span class="fas fa-atom"></span>
            <span>Wisata Bahari</span></a></h2>
            <?php } ?>
        </div>

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
                    <a href="view_kelola_asuransi">
                    <span class="fas fa-heartbeat"></span>
                        <span>Kelola Asuransi</span></a>
                </li>
                <li>
                    <a href="view_kelola_wisata" class="paimon-active">
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
                    <a href="view_kelola_wisata" class="paimon-active">
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

            <!-- Hak Akses Pengelola Wilayah atau Provinsi -->
            <?php if ($level == 3 || $level == 4) { ?>
            <div class="user-wrapper">
                <img src="../views/img/paimon-5.png" width="50px" height="50px" alt="">
                <div>
                    <h2>Selamat Datang</h2>
                    <span class="dashboard"><?php echo $_SESSION['nama_user']; ?></span>
                </div>
            </div>
            <?php } ?>
        </header>
        
        <!-- Hak Akses Pengelola Wilayah atau Provinsi -->
        <?php if ($level == 3 || $level == 4) { ?>
        <!-- Main -->
        <main>
            <!-- Button Kembali -->
            <div>
            <button class="button-kelola-kembali"><span class="fas fa-arrow-left"></span>
            <a href="create_data_fasilitas_wisata" style="color: white;">Kembali</a></button>
            </div>

            <!-- Notifikasi -->
            <?php
                if(!empty($_GET['status'])){
                    if($_GET['status'] == 'updateBerhasil'){
                        echo '<div class="notif role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data berhasil diupdate
                        </div>';
                    } else if($_GET['status'] == 'tambahBerhasil'){
                        echo '<div class="notif" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data baru berhasil ditambahkan
                        </div>';
                    } else if($_GET['status'] == 'hapusBerhasil'){
                        echo '<div class="notif" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data berhasil dihapus
                        </div>';
                    }
                }
            ?>
            <!-- Full Area -->
            <div class="full-area-kelola">
                <!-- Area A -->
                <div class="area-A">
                    <div class="card">
                        <div class="card-header">
                            <h2>Input Data wisata</h2>
                            <button class="button-map"><a href="create_data_paket_wisata" style="color: white;">
                            Selanjutnya Input Paket Wisata</a> <span class="fas fa-plus"></span></button>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    <!-- Data Fasilitas -->
                                    <?php 
                                        foreach ($rowFasilitas as $fasilitas) { ?>
                                        <input type="hidden" name="id_fasilitas_wisata[]" value="<?=$fasilitas->id_fasilitas_wisata?>">
                                    <?php } ?>
                                    
                                    <!-- Form Create Fasilitas Wisata -->
                                    <div class="kelola-detail">
                                        <div class="input-box">
                                            <span class="details">Judul Wisata</span>
                                            <input type="text" name="judul_wisata" placeholder="Judul Wisata" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Deskripsi Wisata</span>
                                            <input type="text" name="deskripsi_wisata" placeholder="Deskripsi Wisata" required>
                                        </div>
                                    </div>
                                    <div class="button-kelola-form">
                                        <input type="submit" name="submit" value="Simpan">
                                    </div>
                                    <!-- End Form -->

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php } ?>

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