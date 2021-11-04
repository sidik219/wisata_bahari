<?php 
include '../app/database/koneksi.php';
session_start();

if (!$_SESSION['level_user']) {
    header('location: ../index?status=akses_terbatas');
} else {
    $id_user    = $_SESSION['id_user'];
    $level      = $_SESSION['level_user'];
}

$id_reservasi_wisata = $_GET['id_reservasi_wisata'];
$defaultpic = "../views/img/image_default.jpg";

// Select Asuransi
$sqlstatusSelect = "SELECT * FROM t_status_reservasi";

$stmt = $pdo->prepare($sqlstatusSelect);
$stmt->execute();
$rowStatus = $stmt->fetchAll();

// Reservasi Wisata
$sqlreservasiSelect = 'SELECT * FROM t_reservasi_wisata
                    LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                    LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
                    LEFT JOIN t_paket_wisata ON t_reservasi_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                    LEFT JOIN t_asuransi ON t_paket_wisata.id_asuransi = t_asuransi.id_asuransi
                    LEFT JOIN t_status_reservasi ON t_reservasi_wisata.id_status_reservasi = t_status_reservasi.id_status_reservasi
                    WHERE t_reservasi_wisata.id_reservasi_wisata = :id_reservasi_wisata';

$stmt = $pdo->prepare($sqlreservasiSelect);
$stmt->execute(['id_reservasi_wisata' => $_GET['id_reservasi_wisata']]);
$rowReservasi = $stmt->fetch();

if (isset($_POST['submit'])) {
    $id_status_reservasi    = $_POST['status_reservasi'];
    $keterangan_reservasi   = $_POST['keterangan_reservasi'];

    $sqlpaketUpdate = "UPDATE t_reservasi_wisata
                        SET id_status_reservasi = :id_status_reservasi,
                            keterangan_reservasi = :keterangan_reservasi
                        WHERE id_reservasi_wisata = :id_reservasi_wisata";

    $stmt = $pdo->prepare($sqlpaketUpdate);
    $stmt->execute(['id_status_reservasi' => $id_status_reservasi,
                    'keterangan_reservasi' => $keterangan_reservasi,
                    'id_reservasi_wisata' => $id_reservasi_wisata]);

    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: edit_data_reservasi_wisata?status=updateGagal&id_reservasi_wisata=$id_reservasi_wisata");
    } else {
        header("Location: view_kelola_reservasi_wisata?status=updateBerhasil");
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
            <!-- Hak Akses Pengelola Lokasi -->
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
                    <a href="view_kelola_reservasi_wisata" class="paimon-active">
                    <span class="fas fa-luggage-cart"></span>
                        <span>Kelola Reservasi Wisata</span></a>
                </li>
                <li>
                    <a href="view_kelola_lokasi">
                    <span class="fas fa-map-marked-alt"></span>
                        <span>Kelola Lokasi</span></a>
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
                    <a href="view_kelola_reservasi_wisata" class="paimon-active">
                    <span class="fas fa-luggage-cart"></span>
                        <span>Kelola Reservasi Wisata</span></a>
                </li>
                <li>
                    <a href="view_kelola_wisata">
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

            <!-- Hak Akses Pengelola Lokasi-->
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
        
        <!-- Hak Akses Pengelola Lokasi-->
        <?php if ($level == 2 || $level == 4) { ?>
        <!-- Main -->
        <main>
            <!-- Button Kembali -->
            <div>
            <button class="button-kelola-kembali"><span class="fas fa-arrow-left"></span>
            <a href="view_kelola_reservasi_wisata" style="color: white;">Kembali</a></button>
            </div>

            <!-- Notifikasi -->
            <?php
                if(!empty($_GET['status'])){
                    if($_GET['status'] == 'updateGagal'){
                        echo '<div class="notif-gagal" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data reservasi gagal diupdate, <b style="color: orange;">Dikarenakan tidak ada perubahan data</b>.
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
                            <h2>Edit Data Paket wisata</h2>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    
                                    <!-- Form Create Fasilitas Wisata -->
                                    <div class="detail-pilihan">
                                        <div class="pilihan-title">Status Reservasi</div>
                                        <?php foreach ($rowStatus as $status) { ?>
                                        <input 
                                            type="radio" 
                                            name="status_reservasi" 
                                            value="<?=$status->id_status_reservasi?>" 
                                            id="dot-<?=$status->id_status_reservasi?>"
                                            <?php 
                                                if($rowReservasi->id_status_reservasi == $status->id_status_reservasi) 
                                                echo "checked";
                                            ?>
                                        >

                                        <!-- Jarak -->
                                        <div class="kategori<?php if($rowReservasi->id_status_reservasi == $status->id_status_reservasi)?>">
                                            <label for="dot-<?=$status->id_status_reservasi?>">
                                                <?php 
                                                    if ($status->id_status_reservasi == "1") { ?>
                                                    <span class="dot satu"></span>
                                                <?php } elseif ($status->id_status_reservasi == "2") { ?>
                                                    <span class="dot dua"></span>
                                                <?php } elseif ($status->id_status_reservasi == "3") { ?>
                                                    <span class="dot tiga"></span>
                                                <?php } ?>
                                                
                                                <span class="aktif"><?=$status->nama_status_reservasi?></span>
                                            </label>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <div class="kelola-detail">
                                        <div class="input-box">
                                            <span class="details"><b>Keterangan Reservasi:</b></span>
                                            <input type="text" name="keterangan_reservasi" value="<?=$rowReservasi->keterangan_reservasi?>" placeholder="Keterangan Reservasi" required>
                                            
                                            <div style="margin-top: 0.5rem;">
                                                <small style="color: gray;"><b>(Optional)</b></small><br>
                                                <small style="color: red;">
                                                * Keterangan bisa diisi jika lokasi pantai sedang tidak mendukung,<br>
                                                atau bukti reservasi wisata tidak sesuai.<br>
                                                * Hal Tersebut untuk menginformasikan kepada wisatawan.
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <hr class="jarak">
                                        
                                        <div class="input-box">
                                            <span class="details"><b>ID Reservasi:</b></span>
                                            <input type="text" name="nama_paket_wisata" value="<?=$rowReservasi->id_reservasi_wisata?>" readonly>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Nama User:</b></span>
                                            <input type="text" name="nama_paket_wisata" value="<?=$rowReservasi->nama_user?>" readonly>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Nama Paket Wisata:</b></span>
                                            <input type="text" name="nama_paket_wisata" value="<?=$rowReservasi->nama_paket_wisata?>" readonly>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Tanggal Reservasi:</b></span>
                                            <input type="date" name="tgl_reservasi" value="<?=$rowReservasi->tgl_reservasi?>" readonly>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Jumlah Reservasi:</b></span>
                                            <input type="text" name="jumlah_reservasi" value="<?=$rowReservasi->jumlah_reservasi?>" readonly>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Asuransi:</b></span>
                                            <input type="text" value="<?=$rowReservasi->nama_asuransi?>, Rp <?=number_format($rowReservasi->biaya_asuransi, 0)?>" readonly>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Total Reservasi:</b></span>
                                            <input type="text" name="total_reservasi" value="Rp. <?=number_format($rowReservasi->total_reservasi, 0)?>" readonly>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Bukti Reservasi:</b></span>

                                            <a href="<?=$rowReservasi->bukti_reservasi?>">
                                                <img id="oldpic" src="<?=$rowReservasi->bukti_reservasi?>" width="20%" <?php if($rowReservasi->bukti_reservasi == NULL) echo "style='display: none;'"; ?>></a>
                                            <br>

                                            <small class="text-muted">
                                                <?php if ($rowReservasi->bukti_reservasi == NULL) {
                                                    echo "Bukti transfer belum diupload<br>Format .jpg .jpeg .png";
                                                } else {
                                                    echo "Klik gambar untuk memperbesar";
                                                }

                                                ?>
                                            </small>

                                            <script>
                                                const actualBtn = document.getElementById('image_uploads');
                                                const fileChosen = document.getElementById('file-input-label');

                                                actualBtn.addEventListener('change', function() {
                                                    fileChosen.innerHTML = '<b>File dipilih :</b> ' + this.files[0].name
                                                })
                                                window.onload = function() {
                                                    document.getElementById('preview').style.display = 'none';
                                                };

                                                function readURL(input) {
                                                    //Validasi Size Upload Image
                                                    if (input.files[0].size > 2000000) { // ini untuk ukuran 800KB, 2000000 untuk 2MB.
                                                        alert("Maaf, Ukuran File Terlalu Besar. !Maksimal Upload 2MB");
                                                        input.value = "";
                                                    };

                                                    if (input.files && input.files[0]) {
                                                        var reader = new FileReader();
                                                        document.getElementById('oldpic').style.display = 'none';
                                                        reader.onload = function(e) {
                                                            $('#preview')
                                                                .attr('src', e.target.result)
                                                                .width(200);
                                                            document.getElementById('preview').style.display = 'block';
                                                        };

                                                        reader.readAsDataURL(input.files[0]);
                                                    }
                                                }
                                            </script>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>No HP User:</b></span>
                                            <input type="text" value="<?=$rowReservasi->no_hp?>" readonly>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Nama Bank Wisatawan:</b></span>
                                            <input type="text" name="deskripsi_lengkap_paket" value="<?=$rowReservasi->nama_bank_wisatawan?>" readonly>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Nama Rekening Wisatawan:</b></span>
                                            <input type="text" name="deskripsi_lengkap_paket" value="<?=$rowReservasi->nama_rekening_wisatawan?>" readonly>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Nomor Rekening Wisatawan:</b></span>
                                            <input type="text" name="deskripsi_lengkap_paket" value="<?=$rowReservasi->nomor_rekening_wisatawan?>" readonly>
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

    <!-- All Javascript -->
    <!-- Jquery Plugin -->
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
</body>
</html>