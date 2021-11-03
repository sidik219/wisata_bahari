<?php 
include '../app/database/koneksi.php';
session_start();

if (!$_SESSION['level_user']) {
    header('location: ../index?status=akses_terbatas');
} else {
    $id_user    = $_SESSION['id_user'];
    $level      = $_SESSION['level_user'];
}

$sqlpaketSelect = 'SELECT * FROM t_paket_wisata
                    ORDER BY id_paket_wisata DESC';

$stmt = $pdo->prepare($sqlpaketSelect);
$stmt->execute();
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
                    <a href="view_kelola_wisata" class="paimon-active">
                    <span class="fas fa-hot-tub"></span>
                        <span>Kelola Wisata</span></a>
                </li>
                <li>
                    <a href="view_kelola_asuransi">
                    <span class="fas fa-heartbeat"></span>
                        <span>Kelola Asuransi</span></a>
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
                    <a href="view_kelola_wisata" class="paimon-active">
                    <span class="fas fa-hot-tub"></span>
                        <span>Kelola Wisata</span></a>
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
            <!-- Laporan Wisata -->
            <div>
            <a href="#" class="btn-kelola-laporan">
                <span class="fas fa-file-excel"></span> Laporan Data Wisata
            </a>
            </div>

            <!-- Notifikasi -->
            <?php
                if(!empty($_GET['status'])){
                    if($_GET['status'] == 'updateBerhasil'){
                        echo '<div class="notif-update" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data berhasil diupdate.
                        </div>';
                    } else if($_GET['status'] == 'tambahBerhasil'){
                        echo '<div class="notif" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data baru berhasil ditambahkan.
                        </div>';
                    } else if($_GET['status'] == 'hapusBerhasil'){
                        echo '<div class="notif-hapus" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data berhasil dihapus.
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
                            <h2>Data Wisata</h2>
                            <button class="button-kelola-kembali"><a href="view_kelola_fasilitas_wisata" style="color: white;">
                            Input Data Baru</a> <span class="fas fa-plus"></span></button>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <table>
                                    <thead>
                                        <tr>
                                            <td>ID Paket Wisata</td>
                                            <td>Nama Paket Wisata</td>
                                            <td>Status Paket</td>
                                            <td>Status Batas Pemesanan</td>
                                            <td>Aksi</td>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    <?php 
                                    foreach ($rowPaket as $paket) {
                                    $awaldate = strtotime($paket->tgl_awal_paket);
                                    $akhirdate = strtotime($paket->tgl_akhir_paket);
                                    ?>
                                        <tr>
                                            <td><?=$paket->id_paket_wisata?></td>
                                            <td><?=$paket->nama_paket_wisata?></td>
                                            <td>
                                                <?php 
                                                    if ($paket->status_paket == "Aktif") { ?>
                                                    <span class="status yaoyao"></span>
                                                    <?=$paket->status_paket?> <!-- Status Dalam Atifk -->
                                                <?php } elseif ($paket->status_paket == "Tidak Aktif") { ?>
                                                    <span class="status klee"></span>
                                                    <?=$paket->status_paket?> <!-- Status Dalam Tidak Atifk -->
                                                <?php } elseif ($paket->status_paket == "Perbaikan") {?>
                                                    <span class="status diona"></span>
                                                    <?=$paket->status_paket?> <!-- Status Dalam Perbaikan -->
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    // tanggal sekarang
                                                    $tgl_sekarang = date("Y-m-d");
                                                    // tanggal pembuatan batas pemesanan paket wisata
                                                    $tgl_awal = $paket->tgl_awal_paket;
                                                    // tanggal berakhir pembuatan batas pemesanan paket wisata
                                                    $tgl_akhir = $paket->tgl_akhir_paket;
                                                    // jangka waktu + 365 hari
                                                    $jangka_waktu = strtotime($tgl_akhir, strtotime($tgl_awal));
                                                    //tanggal expired
                                                    $tgl_exp = date("Y-m-d",$jangka_waktu);

                                                    if ($tgl_sekarang >= $tgl_exp) { ?>
                                                        <i class="fas fa-tag" style="color: #d43334;"></i>
                                                        Sudah Tidak Berlaku.
                                                    <?php } else { ?>
                                                        <i class="fas fa-tag" style="color: #0ec7a3;"></i>
                                                        Masih dalam jangka waktu.
                                                    <?php }?>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="modol-btn button-kelola-detail">
                                                    <a href="detail_data_wisata?id_paket_wisata=<?=$paket->id_paket_wisata?>" style="color: #fff">Detail</a></button>
                                                <button class="button-kelola-edit">
                                                    <a href="edit_data_wisata?id_paket_wisata=<?=$paket->id_paket_wisata?>" style="color: #fff">Edit</a></button>
                                                <button class="button-kelola-hapus">
                                                    <a href="all_hapus?type=paket_wisata&id_paket_wisata=<?=$paket->id_paket_wisata?>" style="color: #fff" onclick="return konfirmasiHapus(event)">Hapus</a></button>
                                            </td>
                                        </tr>
                                    </tbody>

                                    
                                    <?php } ?>
                                </table>
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
    <!-- Konfirmasi Hapus -->
    <script>
        function konfirmasiHapus(event){
        jawab = true
        jawab = confirm('Yakin ingin menghapus? Data Paket Wisata akan hilang permanen!')

        if (jawab){
            // alert('Lanjut.')
            return true
        }
        else{
            event.preventDefault()
            return false

        }
    }
    </script>
</body>
</html>