<?php 
include '../app/database/koneksi.php';
session_start();

if (!$_SESSION['level_user']) {
    header('location: ../index?status=akses_terbatas');
} else {
    $id_user    = $_SESSION['id_user'];
    $level      = $_SESSION['level_user'];
}

// Select Wilayah
$sqlwilayahSelect = "SELECT * FROM t_wilayah";

$stmt = $pdo->prepare($sqlwilayahSelect);
$stmt->execute();
$rowWilayah = $stmt->fetchAll();

if (isset($_POST['submit'])) {
    $id_wilayah         = $_POST['id_wilayah'];
    $nama_lokasi        = $_POST['nama_lokasi'];
    $latitude           = $_POST['latitude'];
    $longitude          = $_POST['longitude'];
    $deskripsi_lokasi   = $_POST['deskripsi_lokasi'];
    $kontak_lokasi      = $_POST['kontak_lokasi'];
    $nama_bank          = $_POST['nama_bank'];
    $nama_rekening      = $_POST['nama_rekening'];
    $nomor_rekening     = $_POST['nomor_rekening'];
    $randomstring       = substr(md5(rand()), 0, 7);

    // image Uploads
    if($_FILES["image_uploads"]["size"] == 0) {
        $foto_lokasi = "../views/img/foto_lokasi/image_default.jpg";
    }
    else if (isset($_FILES['image_uploads'])) {
        $target_dir  = "../views/img/foto_lokasi/";
        $foto_lokasi = $target_dir .'LOK_'. $randomstring .'.jpg';
        move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_lokasi);
    }
    // image Uploads End

    $sqllokasiCreate = "INSERT INTO t_lokasi
                        (id_wilayah, 
                        nama_lokasi, 
                        latitude, 
                        longitude, 
                        deskripsi_lokasi, 
                        foto_lokasi, 
                        kontak_lokasi, 
                        nama_bank,
                        nama_rekening,
                        nomor_rekening)
                        VALUE (:id_wilayah, 
                                :nama_lokasi, 
                                :latitude, 
                                :longitude, 
                                :deskripsi_lokasi, 
                                :foto_lokasi, 
                                :kontak_lokasi, 
                                :nama_bank,
                                :nama_rekening,
                                :nomor_rekening)";
    
    $stmt = $pdo->prepare($sqllokasiCreate);
    $stmt->execute(['id_wilayah' => $id_wilayah,
                    'nama_lokasi' => $nama_lokasi,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'deskripsi_lokasi' => $deskripsi_lokasi,
                    'foto_lokasi' => $foto_lokasi,
                    'kontak_lokasi' => $kontak_lokasi,
                    'nama_bank' => $nama_bank,
                    'nama_rekening' => $nama_rekening,
                    'nomor_rekening' => $nomor_rekening,]);
    
    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: create_data_lokasi?status=tambahGagal");
    } else {
        header("Location: view_kelola_lokasi?status=tambahBerhasil");
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
                <!-- <li>
                    <a href="view_kelola_reservasi_wisata">
                    <span class="fas fa-luggage-cart"></span>
                        <span>Kelola Reservasi Wisata</span></a>
                </li> -->
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
        
        <!-- Hak Akses Pengelola Wilayah atau Provinsi-->
        <?php if ($level == 3 || $level == 4) { ?>
        <!-- Main -->
        <main>
            <!-- Button Kembali -->
            <div>
            <button class="button-kelola-kembali"><span class="fas fa-arrow-left"></span>
            <a href="view_kelola_lokasi" style="color: white;">Kembali</a></button>
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
                            <h2>Input Data Lokasi</h2>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    
                                    <!-- Form Create Fasilitas Wisata -->
                                    <div class="kelola-detail">
                                        <div class="input-box">
                                            <span class="details"><b>ID Wilayah:</b></span>
                                            <select name="id_wilayah" required>
                                                <option selected value="">Pilih Wilayah</option>
                                                <?php foreach ($rowWilayah as $wilayah) { ?>
                                                <option value="<?=$wilayah->id_wilayah?>">
                                                    <?=$wilayah->nama_wilayah?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Nama Lokasi:</b></span>
                                            <input type="text" name="nama_lokasi" placeholder="Nama Lokasi" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Deskripsi Lokasi:</b></span>
                                            <input type="text" name="deskripsi_lokasi" placeholder="Deskripsi Lokasi" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Upload Foto Lokasi:</b></span>
                                            <input class='form-control' type='file' name='image_uploads' id='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);" required>
                                        </div>
                                        <div class="input-box">
                                            <img id="preview" width="100px" src="#" alt="Preview Gambar"/>

                                            <script>
                                                window.onload = function() {
                                                    document.getElementById('preview').style.display = 'none';
                                                };

                                                function readURL(input) {
                                                    //Validasi Size Upload Image
                                                    // var uploadField = document.getElementById("image_uploads");

                                                    if (input.files[0].size > 2000000) { // ini untuk ukuran 800KB, 2000000 untuk 2MB.
                                                        alert("Maaf, Ukuran File Terlalu Besar. !Maksimal Upload 2MB");
                                                        input.value = "";
                                                    };

                                                    if (input.files && input.files[0]) {
                                                        var reader = new FileReader();

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
                                            <span class="details"><b>Kontak Lokasi:</b></span>
                                            <input type="text" name="kontak_lokasi" placeholder="Kontak Lokasi" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Nama Bank:</b></span>
                                            <input type="text" name="nama_bank" placeholder="Nama Bank" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Nama Rekening:</b></span>
                                            <input type="text" name="nama_rekening" placeholder="Nama Rekening" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Nomor Rekening:</b></span>
                                            <input type="text" name="nomor_rekening" placeholder="Nomor Rekening" required>
                                        </div>

                                        <!-- untuk Memasukan Koordinat -->
                                        <h4 style="margin-top: 1.5rem;"><i class="fas fa-search-location"></i> 
                                            Koordinat Lokasi <br>(Diperlukan agar lokasi muncul di peta)</h4>
                                        <div class="input-box">
                                            <span class="details" for="tblatitude"><b>Latitude Lokasi:</b></span>
                                            <input type="text" name="latitude" id="tblatitude" placeholder="Latitude Lokasi" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details" for="tblongitude"><b>Longitude Lokasi:</b></span>
                                            <input type="text" name="longitude" id="tblongitude" placeholder="Longitude Lokasi" required>
                                        </div>
                                        <button class="btn-kelola-koordinat" onclick="getCoordinates()"><i class="nav-icon fas fa-map-marked-alt"></i> Auto Deteksi Lokasi</button>
                                        <span class="" id="akurasi"></span><br>
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
    <!-- jQuery -->
    <script src="//code.jquery.com/jquery-2.2.0.min.js"></script>
    <!-- Untuk Auto Deteksi Koordinat -->
    <script>
        function getCoordinates(){
            event.preventDefault()
            var options = {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            };

        function success(pos) {

        var crd = pos.coords;

            console.log('Your current position is:');
            console.log(`Latitude : ${crd.latitude}`);
            document.getElementById('tblatitude').value = crd.latitude
            console.log(`Longitude: ${crd.longitude}`);
            document.getElementById('tblongitude').value = crd.longitude
            console.log();
            document.getElementById('akurasi').innerHTML = `Akurasi: ${crd.accuracy} meter`
        }

        function error(err) {
            console.warn(`ERROR(${err.code}): ${err.message}`);
        }

        navigator.geolocation.getCurrentPosition(success, error, options);
        }
    </script>

</body>
</html>