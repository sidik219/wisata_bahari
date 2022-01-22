<?php 
include '../app/database/koneksi.php';
session_start();

if (!$_SESSION['level_user']) {
    header('location: ../index?status=akses_terbatas');
} else {
    $id_user    = $_SESSION['id_user'];
    $level      = $_SESSION['level_user'];
}

// Pengadaan Fasilitas
$sqlpengadaan = 'SELECT * FROM t_pengadaan_fasilitas
                ORDER BY id_pengadaan DESC';
$stmt = $pdo->prepare($sqlpengadaan);
$stmt->execute();
$rowPengadaan = $stmt->fetchAll();

if (isset($_POST['submit'])) {
    $id_pengadaan               = $_POST['id_pengadaan'];
    $status_kerjasama           = $_POST['status_kerjasama'];
    $pihak_ketiga_kerjasama     = $_POST['pihak_ketiga_kerjasama'];
    $pembagian_kerjasama        = $_POST['pembagian_kerjasama'];
    $biaya_kerjasama            = $_POST['biaya_kerjasama'];
    $pembagian_hasil_kerjasama  = $_POST['pembagian_hasil_kerjasama'];
    
    $sqlkerjasama = "INSERT INTO t_kerjasama (id_pengadaan, 
                                            status_kerjasama,
                                            pihak_ketiga_kerjasama,
                                            pembagian_kerjasama, 
                                            biaya_kerjasama, 
                                            pembagian_hasil_kerjasama)
                    VALUES (:id_pengadaan, 
                            :status_kerjasama,
                            :pihak_ketiga_kerjasama,
                            :pembagian_kerjasama, 
                            :biaya_kerjasama, 
                            :pembagian_hasil_kerjasama)";

    $stmt = $pdo->prepare($sqlkerjasama);
    $stmt->execute(['id_pengadaan'   => $id_pengadaan,
                    'status_kerjasama'  => $status_kerjasama,
                    'pihak_ketiga_kerjasama' => $pihak_ketiga_kerjasama,
                    'pembagian_kerjasama'  => $pembagian_kerjasama,
                    'biaya_kerjasama'  => $biaya_kerjasama,
                    'pembagian_hasil_kerjasama'  => $pembagian_hasil_kerjasama]);

    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: create_data_kerjasama.php?status=tambahGagal");
    } else {
        //echo "HAHAHAAHA GREAT SUCCESSS !";
        header("Location: view_kelola_kerjasama.php?status=tambahBerhasil");
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
            <!-- Button Kembali -->
            <div>
            <button class="button-kelola-kembali"><span class="fas fa-arrow-left"></span>
            <a href="view_kelola_kerjasama" style="color: white;">Kembali</a></button>
            </div>

            <!-- Notifikasi -->
            <?php
                if(!empty($_GET['status'])){
                    if($_GET['status'] == 'tambahGagal'){
                        echo '<div class="notif-gagal" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data kerjasama gagal ditambahkan.
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
                            <h2>Input Data Kerjasama</h2>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    
                                    <!-- Form Create Fasilitas Wisata -->
                                    <div class="kelola-detail">
                                        <div class="input-box">
                                            <div class="fieldGroup">
                                                <div class="">
                                                    <span class="details"><b>Pengadaan Fasilitas:</b></span>
                                                    <select name="id_pengadaan" required>
                                                        <option selected value="">Pilih Status Pengadaan</option>
                                                        <?php foreach ($rowPengadaan as $pengadaan) { ?>
                                                        <option value="<?= $pengadaan->id_pengadaan ?>">
                                                            <?= $pengadaan->pengadaan_fasilitas ?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-box">
                                            <div class="fieldGroup">
                                                <div class="">
                                                    <span class="details"><b>Status Kerjasama:</b></span>
                                                    <select name="status_kerjasama" required>
                                                        <option selected value="">Pilih Status Kerjasama</option>
                                                        <option value="Melakukan Kerjasama">Melakukan Kerjasama</option>
                                                        <option value="Tidak Melakukan Kerjasama">Tidak Melakukan Kerjasama</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Pihak Kerjasama:</b></span>
                                            <input type="text" name="pihak_ketiga_kerjasama" placeholder="Pihak Kerjasama" required>
                                            <small style="color: red;">*Jika tidak melakukan kerjasama, maka nama pihak kerjasama bisa dikosongkan dengan (-)</small>
                                        </div>
                                        <div class="input-box">
                                            <div class="fieldGroup">
                                                <div class="">
                                                    <span class="details"><b>Pembagian Kerjasama:</b></span>
                                                    <select name="pembagian_kerjasama" id="persentase" onchange="myPersentase();" required>
                                                        <option selected value="">Pilih Status Pengadaan</option>
                                                        <option value="0">0%</option>
                                                        <option value="0.1">10%</option>
                                                        <option value="0.2">20%</option>
                                                        <option value="0.3">30%</option>
                                                        <option value="0.4">40%</option>
                                                        <option value="0.5">50%</option>
                                                        <option value="0.6">60%</option>
                                                        <option value="0.7">70%</option>
                                                        <option value="0.8">80%</option>
                                                        <option value="0.9">90%</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Biaya Pengadaan:</b></span>
                                            <input type="text" name="biaya_kerjasama" id="biaya_kerjasama" placeholder="Biaya Kerjasama" onchange="myPersentase();" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Pembagian Hasil Kerjsama:</b></span>
                                            <!-- Output for display in form -->
                                            <input type="text" id="hasil" placeholder="Pembagian Hasil Kerjsama" readonly>
                                            <!-- Hidden Output insert to DB -->
                                            <input type="hidden" name="pembagian_hasil_kerjasama" id="pembagian_hasil" value="" required>
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
                <small>© 2021 Wisata Bahari</small>
            </h2>
        </footer>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="../plugins/bootstrap-5/js/bootstrap.js"></script>

    <!-- All Javascript -->
    <!-- Menambah jumlah form input -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        function myPersentase() {
            var persentase      = document.getElementById("persentase").value;
            var biaya_kerjasama = document.getElementById("biaya_kerjasama").value;

            var pembagian   = parseFloat(persentase) * biaya_kerjasama;
            var hasil       = pembagian;
            console.log(hasil);

            // Format untuk number.
            var formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
            });

            document.getElementById("hasil").value = formatter.format(hasil); //Untuk Ditampilkan
            document.getElementById("pembagian_hasil").value = hasil; //Untuk insert ke DB
        }
    </script>

</body>
</html>