<?php 
include '../app/database/koneksi.php';
session_start();

if (!$_SESSION['level_user']) {
    header('location: ../index?status=akses_terbatas');
} else {
    $id_user    = $_SESSION['id_user'];
    $level      = $_SESSION['level_user'];
}

$id_pengajuan = $_GET['id_pengajuan'];

$defaultpic = "../views/img/image_default.jpg";

// Select All Data User
$sqluserSelect = "SELECT * FROM t_user
                    WHERE id_user = :id_user";

$stmt = $pdo->prepare($sqluserSelect);
$stmt->execute(['id_user' => $_SESSION['id_user']]);
$rowUser2 = $stmt->fetch();

// Select Lokasi
$sqllokasiSelect = "SELECT * FROM t_lokasi";

$stmt = $pdo->prepare($sqllokasiSelect);
$stmt->execute();
$rowLokasi = $stmt->fetchAll();

// Select Pengadaan Fasilitas
$sqlfasilitasSelect = "SELECT * FROM t_pengadaan_fasilitas";

$stmt = $pdo->prepare($sqlfasilitasSelect);
$stmt->execute();
$rowPengadaan = $stmt->fetchAll();

// Select Pengadaan Fasilitas
$sqlpengajuanSelect = "SELECT * FROM t_pengajuan
                        LEFT JOIN t_lokasi ON t_pengajuan.id_lokasi = t_lokasi.id_lokasi
                        LEFT JOIN t_pengadaan_fasilitas ON t_pengajuan.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                        WHERE t_pengajuan.id_pengajuan = :id_pengajuan";

$stmt = $pdo->prepare($sqlpengajuanSelect);
$stmt->execute(['id_pengajuan' => $_GET['id_pengajuan']]);
$rowPengajuan = $stmt->fetch();

if (isset($_POST['submit'])) {
    $id_lokasi              = $_POST['id_lokasi'];
    $id_pengadaan           = $_POST['id_pengadaan'];
    $judul_pengajuan        = $_POST['judul_pengajuan'];
    $deskripsi_pengajuan    = $_POST['deskripsi_pengajuan'];
    $tanggal_pengajuan      = $_POST['tanggal_pengajuan'];
    $status_pengajuan       = $_POST['status_pengajuan'];
    $randomstring           = substr(md5(rand()), 0, 7);

    // dokumen Uploads
    if($_FILES['dokumen_pengajuan']['size'][0] != 0){
        if($_FILES["dokumen_pengajuan"]["size"] == 0) {
            $dokumen_pengajuan = "";
        }
        else if (isset($_FILES['dokumen_pengajuan'])) {
            $target_dir  = "../views/dokumen/pengajuan/";
            $target_file = $_FILES["dokumen_pengajuan"]['name'];
            $dokumen_pengajuan = $target_dir .'PENGAJUAN_'.$target_file.$randomstring.'.'. pathinfo($target_file,PATHINFO_EXTENSION);
            move_uploaded_file($_FILES["dokumen_pengajuan"]["tmp_name"], $dokumen_pengajuan);
        }
    }
    // dokumen Uploads End

    $sqlpengajuanUpdate = "UPDATE t_pengajuan
                            SET id_lokasi = :id_lokasi,
                                id_pengadaan = :id_pengadaan,
                                judul_pengajuan = :judul_pengajuan,
                                deskripsi_pengajuan = :deskripsi_pengajuan,
                                tanggal_pengajuan = :tanggal_pengajuan,
                                dokumen_pengajuan = :dokumen_pengajuan,
                                status_pengajuan = :status_pengajuan
                            WHERE id_pengajuan = :id_pengajuan";
    
    $stmt = $pdo->prepare($sqlpengajuanUpdate);
    $stmt->execute(['id_lokasi' => $id_lokasi,
                    'id_pengadaan' => $id_pengadaan,
                    'judul_pengajuan' => $judul_pengajuan,
                    'deskripsi_pengajuan' => $deskripsi_pengajuan,
                    'tanggal_pengajuan' => $tanggal_pengajuan,
                    'dokumen_pengajuan' => $dokumen_pengajuan,
                    'status_pengajuan' => $status_pengajuan,
                    'id_pengajuan' => $id_pengajuan]);
    
    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: create_data_pengajuan?status=tambahGagal");
    } else {
        header("Location: view_kelola_pengajuan?status=tambahBerhasil");
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
            <!-- Hak Akses Pengelola Wilayah atau Provinsi-->
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
                    <a href="view_kelola_pengajuan" class="paimon-active">
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
                    <a href="view_kelola_pengajuan" class="paimon-active">
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
                    <a href="view_kelola_pengajuan" class="paimon-active">
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
                <label for="tombol-gacha"><span class="fas fa-bars"></span></label>
            </h2>
            <!--
            <div class="search-wrapper">
                <span class="fas fa-search"></span>
                <input type="text" placeholder="Cari lokasi pantai">
            </div>-->

            <!-- Hak Akses Pengelola Wilayah atau Provinsi-->
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
        
        <!-- Hak Akses Pengelola Wilayah atau Provinsi-->
        <?php if ($level == 2 || $level == 3 || $level == 4) { ?>
        <!-- Main -->
        <main>
            <!-- Button Kembali -->
            <div>
            <button class="button-kelola-kembali"><span class="fas fa-arrow-left"></span>
            <a href="view_kelola_pengajuan" style="color: white;">Kembali</a></button>
            </div>

            <!-- Notifikasi -->
            <?php
                if(!empty($_GET['status'])){
                    if($_GET['status'] == 'tambahGagal'){
                        echo '<div class="notif-gagal" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data wilayah gagal ditambahkan.
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
                            <h2>Edit Data Pengajuan</h2>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    
                                    <!-- Form Create Fasilitas Wisata -->
                                    <div class="kelola-detail">
                                        <div class="input-box">
                                            <span class="details"><b>ID Lokasi:</b></span>
                                            <select name="id_lokasi" required>
                                                <option selected value="">Pilih Lokasi</option>
                                                <?php foreach ($rowLokasi as $lokasi) { ?>
                                                <option <?php if ($lokasi->id_lokasi == $rowPengajuan->id_lokasi) echo 'selected'; ?> value="<?=$lokasi->id_lokasi?>">
                                                    <?=$lokasi->nama_lokasi?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>ID Pengadaan Fasilitas:</b></span>
                                            <select name="id_pengadaan" required>
                                                <option selected value="">Pilih Pengadaan Fasilitas</option>
                                                <?php foreach ($rowPengadaan as $pengadaan) { ?>
                                                    <?php if ($pengadaan->status_pengadaan == "Rusak") : ?>
                                                        <option <?php if ($pengadaan->id_pengadaan == $rowPengajuan->id_pengadaan) echo 'selected'; ?> value="<?=$pengadaan->id_pengadaan?>">
                                                            <?=$pengadaan->pengadaan_fasilitas?> - <?=$pengadaan->status_pengadaan?>
                                                        </option>
                                                    <?php elseif ($pengadaan->status_pengadaan == "Hilang") : ?>
                                                        <option <?php if ($pengadaan->id_pengadaan == $rowPengajuan->id_pengadaan) echo 'selected'; ?> value="<?=$pengadaan->id_pengadaan?>">
                                                            <?=$pengadaan->pengadaan_fasilitas?> - <?=$pengadaan->status_pengadaan?>
                                                        </option>
                                                    <?php endif ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Judul Pengajuan:</b></span>
                                            <input type="text" name="judul_pengajuan" value="<?=$rowPengajuan->judul_pengajuan?>" placeholder="Judul Pengajuan" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Deskripsi Pengajuan:</b></span>
                                            <input type="text" name="deskripsi_pengajuan" value="<?=$rowPengajuan->deskripsi_pengajuan?>" placeholder="Deskripsi Pengajuan" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Tanggal Pengajuan:</b></span>
                                            <input type="date" name="tanggal_pengajuan" value="<?=$rowPengajuan->tanggal_pengajuan?>" placeholder="Tanggal Pengajuan" required>
                                        </div>
                                        <?php if ($level == 2 || $level == 4) { ?>
                                        <div class="input-box">
                                            <span class="details"><b>Upload Dokumen Pengajuan:</b></span>
                                            <div style="margin-bottom: 1rem">
                                                <small>Hanya Menerima Bentuk File .doc, .docx, .pdf, .xls, .xlsx, .ppt, .pptx, .csv, .zip, .rar</small>
                                            </div> 
                                            <input class='form-control' type='file' name='dokumen_pengajuan' id='dokumen_pengajuan' accept='.doc, .docx, .pdf, .xls, .xlsx, .ppt, .pptx, .csv, .zip, .rar' required>
                                        </div>
                                        <?php } ?>
                                        <?php if ($level == 3 || $level == 4) { ?>
                                        <div class="input-box">
                                            <button class="button-kelola-kembali"><span class="fas fa-download"></span>
                                                <a href="<?=$rowPengajuan->dokumen_pengajuan?>" style="color: white;">Unduh File</a>
                                            </button>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <?php if ($level == 3 || $level == 4) { ?>
                                    <div class="detail-pilihan">
                                        <?php if ($rowPengajuan->status_pengajuan == "Pending") { ?>
                                            <input type="radio" name="status_pengajuan" value="Pending" id="dot-1" checked>
                                            <input type="radio" name="status_pengajuan" value="Diterima" id="dot-2">
                                            <input type="radio" name="status_pengajuan" value="Ditolak" id="dot-3">
                                        <?php } elseif ($rowPengajuan->status_pengajuan == "Diterima") { ?>
                                            <input type="radio" name="status_pengajuan" value="Pending" id="dot-1">
                                            <input type="radio" name="status_pengajuan" value="Diterima" id="dot-2" checked>
                                            <input type="radio" name="status_pengajuan" value="Ditolak" id="dot-3">
                                        <?php } elseif ($rowPengajuan->status_pengajuan == "Ditolak") { ?>
                                            <input type="radio" name="status_pengajuan" value="Pending" id="dot-1">
                                            <input type="radio" name="status_pengajuan" value="Diterima" id="dot-2">
                                            <input type="radio" name="status_pengajuan" value="Ditolak" id="dot-3" checked>
                                        <?php } ?>
                                        <div class="pilihan-title">Status Pengajuan</div>
                                        <div class="kategori">
                                            <label for="dot-1">
                                                <span class="dot satu"></span>
                                                <span class="aktif">Pending</span>
                                            </label>
                                            <label for="dot-2">
                                                <span class="dot dua"></span>
                                                <span class="aktif">Diterima</span>
                                            </label>
                                            <label for="dot-3">
                                                <span class="dot tiga"></span>
                                                <span class="aktif">Ditolak</span>
                                            </label>
                                        </div>
                                    </div>
                                    <?php } ?>
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
                <small>© 2021 Wisata Bahari</small>
            </h2>
        </footer>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="../plugins/bootstrap-5/js/bootstrap.js"></script>

    <!-- All Javascript -->
    <!-- jQuery -->
    <script src="//code.jquery.com/jquery-2.2.0.min.js"></script>

</body>
</html>