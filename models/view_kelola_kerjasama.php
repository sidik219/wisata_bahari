<?php 
include '../app/database/koneksi.php';
session_start();

if (!$_SESSION['level_user']) {
    header('location: ../index?status=akses_terbatas');
} else {
    $id_user    = $_SESSION['id_user'];
    $level      = $_SESSION['level_user'];
}

$sqlkerjasamaSelect = "SELECT * FROM t_kerjasama
                    LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                    ORDER BY id_kerjasama DESC";

$stmt = $pdo->prepare($sqlkerjasamaSelect);
$stmt->execute();
$rowKerjasama = $stmt->fetchAll();
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
                    <a href="view_kelola_kerjasama" class="paimon-active">
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
                    <a href="view_kelola_kerjasama" class="paimon-active">
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
                <img src="../views/img/paimon-5.png" width="50px" height="50px" alt="">
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
            <!-- Button Selanjutnya -->
            <div>
            <!-- Kembali -->
            <button class="button-kelola-kembali">
                <span class="fas fa-arrow-left"></span>
                <a href="view_kelola_pengadaan" style="color: white;">Kembali</a>
            </button>
            <!-- Selanjutnya -->
            <button class="button-kelola-kembali">
                <a href="view_kelola_asuransi" style="color: white;">Selanjutnya</a>
                <span class="fas fa-arrow-right"></span>
            </button>

            <!-- Laporan Kerjasama -->  
            <a href="all_laporan.php?type=kerjasama" class="btn-kelola-laporan">
                <span class="fas fa-file-excel"></span> Laporan Data Kerjasama
            </a>
            </div>

            <!-- Notifikasi -->
            <?php
                if(!empty($_GET['status'])){
                    if($_GET['status'] == 'updateBerhasil'){
                        echo '<div class="notif-update" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data berhasil diupdate
                        </div>';
                    } else if($_GET['status'] == 'tambahBerhasil'){
                        echo '<div class="notif" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data baru berhasil ditambahkan
                        </div>';
                    } else if($_GET['status'] == 'hapusBerhasil'){
                        echo '<div class="notif-hapus" role="alert">
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
                            <h2>Data Kerjasama</h2>
                            <button class="button-kelola-kembali"><a href="create_data_kerjasama" style="color: white;">
                            Input Data Baru</a> <span class="fas fa-plus"></span></button>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <table>
                                    <thead>
                                        <tr>
                                            <td>ID Kerjasama</td>
                                            <td>Pihak Kerjasama</td>
                                            <td>Pengadaan Fasilitas</td>
                                            <td>Status Kerjasama</td>
                                            <td>Pembagian Kerjasama</td>
                                            <td>Biaya Pengadaan</td>
                                            <td>Pembagian Hasil</td>
                                            <td>Aksi</td>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php 
                                        foreach ($rowKerjasama as $kerjasama) {
                                        $pembagian_kerjasama = round($kerjasama->pembagian_kerjasama * 100, 2);
                                        ?>
                                        <tr>
                                            <td><?=$kerjasama->id_kerjasama?></td>
                                            <?php if ($kerjasama->status_kerjasama == "Tidak Melakukan Kerjasama") : ?>
                                                <td>Tidak Ada Pihak Kerjasama</td>
                                            <?php elseif ($kerjasama->pihak_ketiga_kerjasama != null) : ?>
                                                <td><?=$kerjasama->pihak_ketiga_kerjasama?></td>
                                            <?php else : ?>
                                                <td>Data Kosong</td>
                                            <?php endif ?>
                                            <td><?=$kerjasama->pengadaan_fasilitas?></td>
                                            <td>
                                                <?php 
                                                    if ($kerjasama->status_kerjasama == "Melakukan Kerjasama") { ?>
                                                    <span class="status yaoyao"></span>
                                                    <?=$kerjasama->status_kerjasama?>
                                                <?php } elseif ($kerjasama->status_kerjasama == "Tidak Melakukan Kerjasama") { ?>
                                                    <span class="status klee"></span>
                                                    <?=$kerjasama->status_kerjasama?>
                                                <?php } ?>
                                            </td>
                                            <td><?= $pembagian_kerjasama ?>%</td>
                                            <td>Rp. <?=number_format($kerjasama->biaya_kerjasama, 0)?></td>
                                            <td>Rp. <?=number_format($kerjasama->pembagian_hasil_kerjasama, 0)?></td>
                                            <td>
                                                <button class="button-kelola-edit ">
                                                    <a href="edit_data_kerjasama?id_kerjasama=<?=$kerjasama->id_kerjasama?>" style="color: #fff">Edit</a></button>
                                                <button class="button-kelola-hapus">
                                                    <a href="all_hapus?type=kerjasama&id_kerjasama=<?=$kerjasama->id_kerjasama?>" style="color: #fff" onclick="return konfirmasiHapus(event)">Hapus</a></button>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
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
        jawab = confirm('Yakin ingin menghapus? Data Kerjasama akan hilang permanen!')

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