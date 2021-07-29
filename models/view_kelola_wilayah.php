<?php 
include '../app/database/koneksi.php';
session_start();

$sqlwilayahSelect = "SELECT * FROM t_wilayah
                    INNER JOIN t_provinsi ON t_wilayah.id_provinsi = t_provinsi.id_provinsi
                    ORDER BY id_wilayah DESC";

$stmt = $pdo->prepare($sqlwilayahSelect);
$stmt->execute();
$rowWilayah = $stmt->fetchAll();
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
            <h2><a href="view_dashboard_user" style="color: #fff"><span class="fas fa-atom"></span>
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
                    <a href="view_kelola_lokasi">
                    <span class="fas fa-map-marked-alt"></span>
                        <span>Kelola Lokasi</span></a>
                </li>
                <li>
                    <a href="view_kelola_wilayah" class="paimon-active">
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
            <!-- Notifikasi -->
            <?php
                if(!empty($_GET['status'])){
                    if($_GET['status'] == 'updateBerhasil'){
                        echo '<div class="notif fas fa-exclamation" role="alert">
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
                            <h2>Data Wilayah</h2>
                            <button class="button-kelola-kembali"><a href="create_data_wilayah" style="color: white;">
                            Input Data Baru</a> <span class="fas fa-plus"></span></button>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <table>
                                    <thead>
                                        <tr>
                                            <td>ID Wilayah</td>
                                            <td>Nama Provinsi</td>
                                            <td>Nama Wilayah</td>
                                            <td>Aksi</td>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php 
                                            foreach ($rowWilayah as $wilayah) {
                                        ?>
                                        <tr>
                                            <td><?=$wilayah->id_wilayah?></td>
                                            <td><?=$wilayah->nama_provinsi?></td>
                                            <td><?=$wilayah->nama_wilayah?></td>
                                            <td>
                                                <button class="modol-btn button-kelola-detail">
                                                    <a href="detail_wilayah?id_wilayah=<?=$wilayah->id_wilayah?>" style="color: #fff">Detail</button>
                                                <button class="button-kelola-edit">
                                                    <a href="edit_wilayah?id_wilayah=<?=$wilayah->id_wilayah?>" style="color: #fff">Edit</a></button>
                                                <button class="button-kelola-hapus">
                                                    <a href="all_hapus?type=wilayah&id_wilayah=<?=$wilayah->id_wilayah?>" style="color: #fff">Hapus</button>
                                            </td>
                                        </tr>

                                        <!-- POP UP -->
                                        <div class="modol-bg">
                                            <div class="modol">
                                                <div class="modol-header">
                                                    <h2 class="modol-title">Detail Data Wilayah</h2>
                                                </div>
                                                
                                                <div class="modol-body">
                                                    <div class="modol-input">
                                                        <label for="">Deskripsi Wilayah</label>
                                                        <input type="text" value="<?=$wilayah->deskripsi_wilayah?>" readonly>
                                                    </div>
                                                    <div class="modol-input">
                                                        <label for="">Foto Wilayah</label>
                                                        <br><img src="<?=$wilayah->foto_wilayah?>?<?php if ($status='nochange'){echo time();}?>" width="100px">
                                                    </div>
                                                    <div class="modol-input">
                                                        <label for="">Sisi Pantai</label>
                                                        <input type="text" value="<?=$wilayah->sisi_pantai?>" readonly>
                                                    </div>
                                                </div>
                                                
                                                <span class="modol-keluar">X</span>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </tbody>

                                    
                                </table>
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

    <!-- All Javascript -->
    <!-- Modal -->
    <script>
        var modolBtn    = document.querySelector('.modol-btn');
        var modolBg     = document.querySelector('.modol-bg');
        var modolKeluar = document.querySelector('.modol-keluar');

        modolBtn.addEventListener('click', function(){
            modolBg.classList.add('modol-aktif');
        })
        modolKeluar.addEventListener('click', function(){
            modolBg.classList.remove('modol-aktif');
        })
    </script>

</body>
</html>