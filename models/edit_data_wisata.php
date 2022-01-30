<?php 
include '../app/database/koneksi.php';
session_start();

if (!$_SESSION['level_user']) {
    header('location: ../index?status=akses_terbatas');
} else {
    $id_user    = $_SESSION['id_user'];
    $level      = $_SESSION['level_user'];
}

$id_paket_wisata = $_GET['id_paket_wisata'];

$defaultpic = "../views/img/image_default.jpg";

// Select All Data User
$sqluserSelect = "SELECT * FROM t_user
                    WHERE id_user = :id_user";

$stmt = $pdo->prepare($sqluserSelect);
$stmt->execute(['id_user' => $_SESSION['id_user']]);
$rowUser2 = $stmt->fetch();

// Select Lokasi
$sqllokasiSelect = "SELECT * FROM t_lokasi
                    ORDER BY id_lokasi ASC";

$stmt = $pdo->prepare($sqllokasiSelect);
$stmt->execute();
$rowLokasi = $stmt->fetchAll();

// Select Asuransi
$sqlasuransiSelect = "SELECT * FROM t_asuransi
                    ORDER BY id_asuransi ASC";

$stmt = $pdo->prepare($sqlasuransiSelect);
$stmt->execute();
$rowAsuransi = $stmt->fetchAll();

// Select Wisata
$sqlwisataSelect = "SELECT * FROM t_wisata
                    LEFT JOIN t_paket_wisata ON t_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                    WHERE t_paket_wisata.id_paket_wisata = :id_paket_wisata
                    ORDER BY id_wisata";

$stmt = $pdo->prepare($sqlwisataSelect);
$stmt->execute(['id_paket_wisata' => $id_paket_wisata]);
$rowWisata = $stmt->fetchAll();

// Paket Wisata
$sqlpaketSelect = 'SELECT * FROM t_paket_wisata
                LEFT JOIN t_lokasi ON t_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                LEFT JOIN t_asuransi ON t_paket_wisata.id_asuransi = t_asuransi.id_asuransi
                WHERE t_paket_wisata.id_paket_wisata = :id_paket_wisata';

$stmt = $pdo->prepare($sqlpaketSelect);
$stmt->execute(['id_paket_wisata' => $_GET['id_paket_wisata']]);
$rowPaket = $stmt->fetch();

if (isset($_POST['submit'])) {
    $id_lokasi          = $_POST['id_lokasi'];
    $id_asuransi        = $_POST['id_asuransi'];
    $nama_paket_wisata  = $_POST['nama_paket_wisata'];
    $tgl_awal_paket     = $_POST['tgl_awal_paket'];
    $tgl_akhir_paket    = $_POST['tgl_akhir_paket'];
    $status_paket       = $_POST['status_paket'];
    $randomstring       = substr(md5(rand()), 0, 7);

    // POST Wisata
    $id_wisata          = $_POST["nama_wisata"];
    $judul_wisata       = $_POST["judul_wisata"];
    $jadwal_wisata      = $_POST["jadwal_wisata"];
    $deskripsi_wisata   = $_POST["deskripsi_wisata"];

    $hitung = count($id_wisata);
    for ($x = 0; $x < $hitung; $x++) {
        // echo $id_wisata[$x];
        // echo $judul_wisata[$x];
        // echo $deskripsi_wisata[$x];
        $sqlupdatewisata = "UPDATE t_wisata
        SET id_paket_wisata = :id_paket_wisata,
            judul_wisata = :judul_wisata,
            jadwal_wisata = :jadwal_wisata,
            deskripsi_wisata = :deskripsi_wisata
        WHERE id_wisata = :id_wisata";

        $stmt = $pdo->prepare($sqlupdatewisata);
        $stmt->execute([
            'id_wisata' => $id_wisata[$x],
            'judul_wisata' => $judul_wisata[$x],
            'jadwal_wisata' => $jadwal_wisata[$x],
            'deskripsi_wisata' => $deskripsi_wisata[$x],
            'id_paket_wisata' => $id_paket_wisata
        ]);
    }

    //Image upload
    if ($_FILES["image_uploads"]["size"] == 0) {
        $foto_paket_wisata = $rowPaket->foto_paket_wisata;
        $pic = "&none=";
    } else if (isset($_FILES['image_uploads'])) {
        if (($rowPaket->foto_paket_wisata == $defaultpic) || (!$rowPaket->foto_paket_wisata)){
            $target_dir  = "../views/img/foto_paket_wisata/";
            $foto_paket_wisata = $target_dir .'PAK_'. $randomstring .'.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_paket_wisata);
            $pic = "&new=";
        } else if (isset($rowPaket->foto_paket_wisata)){
            $foto_paket_wisata = $rowPaket->foto_paket_wisata;
            unlink($rowPaket->foto_paket_wisata);
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $rowPaket->foto_paket_wisata);
            $pic = "&replace=";
        }
    }
    //---image upload end

    $sqlpaketUpdate = "UPDATE t_paket_wisata
                        SET id_lokasi = :id_lokasi,
                            id_asuransi = :id_asuransi,
                            nama_paket_wisata = :nama_paket_wisata,
                            tgl_awal_paket = :tgl_awal_paket,
                            tgl_akhir_paket = :tgl_akhir_paket,
                            foto_paket_wisata = :foto_paket_wisata,
                            status_paket = :status_paket
                        WHERE id_paket_wisata = :id_paket_wisata";

    $stmt = $pdo->prepare($sqlpaketUpdate);
    $stmt->execute(['id_lokasi' => $id_lokasi,
                    'id_asuransi' => $id_asuransi,
                    'nama_paket_wisata' => $nama_paket_wisata,
                    'tgl_awal_paket' => $tgl_awal_paket,
                    'tgl_akhir_paket' => $tgl_akhir_paket,
                    'foto_paket_wisata' => $foto_paket_wisata,
                    'status_paket' => $status_paket,
                    'id_paket_wisata' => $id_paket_wisata]);

    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: edit_data_wisata?status=updateGagal&id_paket_wisata=$id_paket_wisata");
    } else {
        header("Location: view_kelola_wisata?status=updateBerhasil");
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
                    <a href="view_kelola_wisata" class="paimon-active">
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

            <!-- Hak Akses Pengelola Wilayah atau Provinsi -->
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
            <a href="view_kelola_wisata" style="color: white;">Kembali</a></button>
            </div>

            <!-- Notifikasi -->
            <?php
                if(!empty($_GET['status'])){
                    if($_GET['status'] == 'updateGagal'){
                        echo '<div class="notif-gagal" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data paket wisata gagal diupdate, <b style="color: orange;">Dikarenakan tidak ada perubahan data</b>.
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
                                    <div class="kelola-detail">
                                        <!-- Lokasi -->
                                        <div class="input-box">
                                            <span class="details"><b>ID Lokasi:</b></span>
                                            <select name="id_lokasi" required>
                                                <option selected value="">Pilih Lokasi</option>
                                                <?php foreach ($rowLokasi as $lokasi) { ?>
                                                <option <?php if ($lokasi->id_lokasi == $rowPaket->id_lokasi) echo 'selected'; ?> value="<?=$lokasi->id_lokasi?>">
                                                    <?=$lokasi->nama_lokasi?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        
                                        <!-- Asuransi -->
                                        <div class="input-box">
                                            <span class="details"><b>ID Asuransi:</b></span>
                                            <select name="id_asuransi" required>
                                                <option selected value="">Pilih Asuransi</option>
                                                <?php foreach ($rowAsuransi as $asuransi) { ?>
                                                <option <?php if ($asuransi->id_asuransi == $rowPaket->id_asuransi) echo 'selected'; ?> value="<?=$asuransi->id_asuransi?>">
                                                    <?=$asuransi->nama_asuransi?>, Rp <?=number_format($asuransi->biaya_asuransi, 0)?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <!-- Wisata -->
                                        <div class="input-box">
                                            <div class="fieldGroup">
                                                <div class="">
                                                    <?php foreach ($rowWisata as $wisata) { ?>
                                                    <span class="details"><b>ID Wisata:</b></span>
                                                    <!-- Id Wisata -->
                                                    <input type="hidden" name="nama_wisata[]" value="<?= $wisata->id_wisata ?>" class="form-control" placeholder="Hari" style="margin-top: 0.3rem;" required />
                                                    <!-- Judul Wisata -->
                                                    <input type="text" name="judul_wisata[]" value="<?= $wisata->judul_wisata ?>" class="form-control mb-2" placeholder="Judul Wisata" style="margin-top: 0.3rem;" required />
                                                    <!-- Jadwal Wisata -->
                                                    <input type="text" name="jadwal_wisata[]" value="<?= $wisata->jadwal_wisata ?>" class="form-control mb-2" placeholder="Jadwal Wisata" style="margin-top: 0.3rem;" required />
                                                    <!-- Deskripsi Wisata -->
                                                    <input type="text" name="deskripsi_wisata[]" value="<?= $wisata->deskripsi_wisata ?>" class="form-control" placeholder="Deskripsi" style="margin-top: 0.3rem;" required /><br><br>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Keterangan -->
                                        <div class="input-box">
                                            <label for="">Keterangan:</label><br>
                                            <small><b>Contoh Pengisian:</b></small><br>
                                            <small>* Pilih Wisata: Wisata Diving dst</small><br>
                                            <small>* Hari: Hari Pertama dst</small><br>
                                            <small style="color: red;">* Perhari, hanya bisa satu wisata</small><br>
                                            <small style="color: red;">* Untuk menambahkan wisata baru, harus <a href="create_data_fasilitas_wisata.php"><b>input fasilitas wisata</b></a> terlebih dahulu</small><br>
                                            <small style="color: red;">* Belum Bisa menambahkan wisata baru, pada saat edit wisata</small>
                                        </div>

                                        <div class="input-box">
                                            <span class="details">Nama Paket Wisata</span>
                                            <input type="text" name="nama_paket_wisata" value="<?=$rowPaket->nama_paket_wisata?>" placeholder="Nama Paket Wisata" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Edit Batas Pemesanan Paket</span>
                                            <div style="margin-top: 1rem;">
                                                <small><b>Tanggal Awal</b></small>
                                                <input type="date" name="tgl_awal_paket" id="tgl_awal_paket" value="<?=$rowPaket->tgl_awal_paket?>" required>
                                            </div>
                                            <div style="margin-top: 1rem;">
                                                <small><b>Tanggal Akhir</b></small>
                                                <input type="date" name="tgl_akhir_paket" id="tgl_akhir_paket" value="<?=$rowPaket->tgl_akhir_paket?>" required>
                                            </div>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Upload Foto Paket Wisata</span>
                                            <input type="file" name="image_uploads" id="image_uploads" accept=".jpg, .jpeg, .png" onchange="readURL(this);">
                                        </div>
                                        <div class="input-box">
                                            <img id="preview" src="#" width="100px" alt="Preview Gambar" />
                                            <a href="<?= $rowPaket->foto_paket_wisata ?>">
                                                <img id="oldpic" src="<?= $rowPaket->foto_paket_wisata ?>" width="20%" <?php if ($rowPaket->foto_paket_wisata == NULL) echo "style='display: none;'"; ?>></a>
                                            <br>

                                            <small class="text-muted">
                                                <?php if ($rowPaket->foto_paket_wisata == NULL) {
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

                                    </div>
                                    <div class="detail-pilihan">
                                        <?php if ($rowPaket->status_paket == "Aktif") { ?>
                                            <input type="radio" name="status_paket" value="Aktif" id="dot-1" checked>
                                            <input type="radio" name="status_paket" value="Tidak Aktif" id="dot-2">
                                            <input type="radio" name="status_paket" value="Perbaikan" id="dot-3">
                                        <?php } elseif ($rowPaket->status_paket == "Tidak Aktif") { ?>
                                            <input type="radio" name="status_paket" value="Aktif" id="dot-1">
                                            <input type="radio" name="status_paket" value="Tidak Aktif" id="dot-2" checked>
                                            <input type="radio" name="status_paket" value="Perbaikan" id="dot-3">
                                        <?php } elseif ($rowPaket->status_paket == "Perbaikan") { ?>
                                            <input type="radio" name="status_paket" value="Aktif" id="dot-1">
                                            <input type="radio" name="status_paket" value="Tidak Aktif" id="dot-2">
                                            <input type="radio" name="status_paket" value="Perbaikan" id="dot-3" checked>
                                        <?php } ?>
                                        <div class="pilihan-title">Status</div>
                                        <div class="kategori">
                                            <label for="dot-1">
                                                <span class="dot satu"></span>
                                                <span class="aktif">Aktif</span>
                                            </label>
                                            <label for="dot-2">
                                                <span class="dot dua"></span>
                                                <span class="aktif">Tidak Aktif</span>
                                            </label>
                                            <label for="dot-3">
                                                <span class="dot tiga"></span>
                                                <span class="aktif">Perbaikan</span>
                                            </label>
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
    <!-- Jquery Plugin -->
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
</body>
</html>