<?php 
include '../app/database/koneksi.php';
session_start();

if (!$_SESSION['level_user']) {
    header('location: ../index?status=akses_terbatas');
} else {
    $id_user    = $_SESSION['id_user'];
    $level      = $_SESSION['level_user'];
}

$id_asuransi = $_GET['id_asuransi'];

$defaultpic = "../views/img/image_default.jpg";

// Select All Data User
$sqluserSelect = "SELECT * FROM t_user
                    WHERE id_user = :id_user";

$stmt = $pdo->prepare($sqluserSelect);
$stmt->execute(['id_user' => $_SESSION['id_user']]);
$rowUser2 = $stmt->fetch();

// Select Asuransi
$sqlasuransiSelect = "SELECT * FROM t_asuransi
                    LEFT JOIN t_perusahaan_asuransi ON t_asuransi.id_perusahaan_asuransi = t_perusahaan_asuransi.id_perusahaan_asuransi
                    WHERE id_asuransi = :id_asuransi";

$stmt = $pdo->prepare($sqlasuransiSelect);
$stmt->execute(['id_asuransi' => $id_asuransi]);
$rowAsuransi = $stmt->fetch();

if (isset($_POST['submit'])) {
    $id_perusahaan_asuransi     = $_POST['nama_pihak'];
    $alamat_perusahaan_asuransi = $_POST['alamat_perusahaan_asuransi'];
    $notlp_perusahaan_asuransi  = $_POST['notlp_perusahaan_asuransi'];

    // Asuransi
    $id_asuransi                = $_POST["id_asuransi"];
    $nama_asuransi              = $_POST['nama_asuransi'];
    $biaya_asuransi             = $_POST['biaya_asuransi'];
    $no_kontrak_asuransi        = $_POST['no_kontrak_asuransi'];
    $tgl_kontrak_asuransi       = $_POST['tgl_kontrak_asuransi'];
    $lama_kontrak_asuransi      = $_POST['lama_kontrak_asuransi'];
    $perihal_kontrak_asuransi   = $_POST['perihal_kontrak_asuransi'];

    $hitung = count($id_asuransi);
    for ($x = 0; $x < $hitung; $x++) {
        $sqlasuransi = "UPDATE t_asuransi
        SET id_perusahaan_asuransi = :id_perusahaan_asuransi,
            nama_asuransi = :nama_asuransi,
            biaya_asuransi = :biaya_asuransi,
            no_kontrak_asuransi = :no_kontrak_asuransi,
            tgl_kontrak_asuransi = :tgl_kontrak_asuransi,
            lama_kontrak_asuransi = :lama_kontrak_asuransi,
            perihal_kontrak_asuransi = :perihal_kontrak_asuransi
        WHERE id_asuransi = :id_asuransi";

        $stmt = $pdo->prepare($sqlasuransi);
        $stmt->execute([
            'id_asuransi' => $id_asuransi[$x],
            'nama_asuransi' => $nama_asuransi[$x],
            'biaya_asuransi' => $biaya_asuransi[$x],
            'no_kontrak_asuransi' => $no_kontrak_asuransi[$x],
            'tgl_kontrak_asuransi' => $tgl_kontrak_asuransi[$x],
            'lama_kontrak_asuransi' => $lama_kontrak_asuransi[$x],
            'perihal_kontrak_asuransi' => $perihal_kontrak_asuransi[$x],
            'id_perusahaan_asuransi' => $id_perusahaan_asuransi
        ]);
    }

    $sqlpaketUpdate = "UPDATE t_perusahaan_asuransi
                        SET alamat_perusahaan_asuransi = :alamat_perusahaan_asuransi,
                            notlp_perusahaan_asuransi = :notlp_perusahaan_asuransi
                        WHERE id_perusahaan_asuransi = :id_perusahaan_asuransi";

    $stmt = $pdo->prepare($sqlpaketUpdate);
    $stmt->execute(['alamat_perusahaan_asuransi' => $alamat_perusahaan_asuransi,
                    'notlp_perusahaan_asuransi' => $notlp_perusahaan_asuransi,
                    'id_perusahaan_asuransi' => $id_perusahaan_asuransi]);

    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: edit_data_asuransi?status=updateGagal&id_asuransi=$id_asuransi");
    } else {
        header("Location: view_kelola_asuransi?status=updateBerhasil");
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
    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/img/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicons/favicon.ico">
    <link rel="manifest" href="../assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="../assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">
</head>
<body>
    <!-- Menu -->
    <input type="checkbox" id="tombol-gacha"> 
    <div class="sidebar">
        <div class="sidebar-logo">
            <!-- Hak Akses Pengelola Wilayah atau Provinsi-->
            <?php if ($level == 2 || $level == 4) { ?>
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
                    <a href="kelola_laporan_periode">
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
                    <a href="view_kelola_asuransi" class="paimon-active">
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
                    <a href="kelola_laporan_periode">
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
                    <a href="view_kelola_asuransi" class="paimon-active">
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
                <label for="tombol-gacha"><span class="fas fa-bars"></span></label>
            </h2>
            <!--
            <div class="search-wrapper">
                <span class="fas fa-search"></span>
                <input type="text" placeholder="Cari lokasi pantai">
            </div>-->

            <!-- Hak Akses Pengelola Wilayah atau Provinsi-->
            <?php if ($level == 2 || $level == 4) { ?>
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
        
        <!-- Hak Akses Pengelola Wilayah atau Provinsi -->
        <?php if ($level == 2 || $level == 4) { ?>
        <!-- Main -->
        <main>
            <!-- Button Kembali -->
            <div>
            <button class="button-kelola-kembali"><span class="fas fa-arrow-left"></span>
            <a href="view_kelola_asuransi" style="color: white;">Kembali</a></button>
            </div>

            <!-- Notifikasi -->
            <?php
                if(!empty($_GET['status'])){
                    if($_GET['status'] == 'updateGagal'){
                        echo '<div class="notif-gagal" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data asuransi gagal diupdate, <b style="color: orange;">Dikarenakan tidak ada perubahan data</b>.
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
                            <h2>Edit Data Asuransi</h2>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    <div class="kelola-detail">
                                        <!-- Hidden Id Asuransi -->
                                        <input type="hidden" name="id_asuransi" value="<?= $rowAsuransi->id_asuransi ?>">

                                        <div class="input-box">
                                            <span class="details"><b>Nama Asuransi:</b></span>
                                            <input type="text" name="nama_asuransi[]" value="<?=$rowAsuransi->nama_asuransi?>" placeholder="Nama Asuransi" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Biaya Asuransi:</b></span>
                                            <input type="text" name="biaya_asuransi[]" value="<?=$rowAsuransi->biaya_asuransi?>" placeholder="Biaya Asuransi" required>
                                        </div>

                                        <!-- Perusahaan Asuransi -->
                                        <div class="input-box">
                                            <span class="details"><b>Perusahaan Asuransi:</b></span>
                                            <select name="nama_pihak" required>
                                                <option selected value="">Pilih Perusahaan Asuransi:</option>
                                                <?php
                                                $sqlperusahaan = 'SELECT * FROM t_perusahaan_asuransi
                                                                    ORDER BY id_perusahaan_asuransi DESC';
                                                $stmt = $pdo->prepare($sqlperusahaan);
                                                $stmt->execute();
                                                $rowPerusahaan = $stmt->fetchAll();

                                                foreach ($rowPerusahaan as $Perusahaan) { ?>
                                                    <option <?php if ($Perusahaan->id_perusahaan_asuransi == $rowAsuransi->id_perusahaan_asuransi) echo 'selected'; ?> value="<?= $Perusahaan->id_perusahaan_asuransi ?>">
                                                        <?= $Perusahaan->nama_perusahaan_asuransi ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Alamat Perusahaan:</b></span>
                                            <input type="text" name="alamat_perusahaan_asuransi" value="<?=$rowAsuransi->alamat_perusahaan_asuransi?>" placeholder="Alamat Perusahaan" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>No Telp Perusahaan:</b></span>
                                            <input type="tel" name="notlp_perusahaan_asuransi" value="<?=$rowAsuransi->notlp_perusahaan_asuransi?>" placeholder="No Telp Perusahaan" pattern="^[0-9-+\s()]*$" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>No Kontrak Asuransi:</b></span>
                                            <input type="text" name="no_kontrak_asuransi[]" value="<?=$rowAsuransi->no_kontrak_asuransi?>" placeholder="No Kontrak Asuransi" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Tanggal Kontrak Asuransi:</b></span>
                                            <input type="date" name="tgl_kontrak_asuransi[]" value="<?=$rowAsuransi->tgl_kontrak_asuransi?>" placeholder="Tanggal Kontrak Asuransi" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Lama Kontrak Asuransi:</b></span>
                                            <input type="text" name="lama_kontrak_asuransi[]" value="<?=$rowAsuransi->lama_kontrak_asuransi?>" placeholder="Lama Kontrak Asuransi" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Perihal Kontrak Asuransi:</span>
                                            <input type="text" name="perihal_kontrak_asuransi[]" value="<?=$rowAsuransi->perihal_kontrak_asuransi?>" placeholder="Perihal Kontrak Asuransi" required>
                                        </div>
                                    </div>
                                    <div class="button-kelola-form">
                                        <input type="submit" name="submit" value="Simpan">
                                    </div>
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
                <small>© 2021 Wisata Bahari</small>
            </h2>
        </footer>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="../plugins/bootstrap-5/js/bootstrap.js"></script>

    <!-- All Javascript -->
    <!-- Jquery Plugin -->
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
</body>
</html>