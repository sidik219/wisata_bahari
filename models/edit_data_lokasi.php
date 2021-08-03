<?php 
include '../app/database/koneksi.php';
session_start();

$id_lokasi = $_GET['id_lokasi'];
$defaultpic = "../views/img/image_default.jpg";

// Select Wilayah
$sqlwilayahSelect = "SELECT * FROM t_wilayah
                    ORDER BY id_wilayah ASC";

$stmt = $pdo->prepare($sqlwilayahSelect);
$stmt->execute();
$rowWilayah = $stmt->fetchAll();

// Select Lokasi
$sqllokasiSelect = 'SELECT * FROM t_lokasi
                LEFT JOIN t_wilayah ON t_lokasi.id_wilayah = t_wilayah.id_wilayah
                WHERE t_lokasi.id_lokasi = :id_lokasi';

$stmt = $pdo->prepare($sqllokasiSelect);
$stmt->execute(['id_lokasi' => $_GET['id_lokasi']]);
$rowLokasi = $stmt->fetch();

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

    //Image upload
    $randomstring = substr(md5(rand()), 0, 7);

    if ($_FILES["image_uploads"]["size"] == 0) {
        $foto_lokasi = $rowLokasi->foto_lokasi;
        $pic = "&none=";
    } else if (isset($_FILES['image_uploads'])) {
        if (($rowLokasi->foto_lokasi == $defaultpic) || (!$rowLokasi->foto_lokasi)){
            $target_dir  = "../views/img/foto_wisata/";
            $foto_lokasi = $target_dir .'LOK_'. $randomstring .'.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_lokasi);
            $pic = "&new=";
        } else if (isset($rowLokasi->foto_lokasi)){
            $foto_lokasi = $rowLokasi->foto_lokasi;
            unlink($rowLokasi->foto_lokasi);
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $rowLokasi->foto_lokasi);
            $pic = "&replace=";
        }
    }
    //---image upload end

    $sqllokasiUpdate = "UPDATE t_lokasi
                        SET id_wilayah = :id_wilayah,
                            nama_lokasi = :nama_lokasi,
                            latitude = :latitude,
                            longitude = :longitude,
                            deskripsi_lokasi = :deskripsi_lokasi,
                            foto_lokasi = :foto_lokasi,
                            kontak_lokasi = :kontak_lokasi,
                            nama_bank = :nama_bank,
                            nama_rekening = :nama_rekening,
                            nomor_rekening = :nomor_rekening
                        WHERE id_lokasi = :id_lokasi";

    $stmt = $pdo->prepare($sqllokasiUpdate);
    $stmt->execute(['id_wilayah' => $id_wilayah,
                    'nama_lokasi' => $nama_lokasi,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'deskripsi_lokasi' => $deskripsi_lokasi,
                    'foto_lokasi' => $foto_lokasi,
                    'kontak_lokasi' => $kontak_lokasi,
                    'nama_bank' => $nama_bank,
                    'nama_rekening' => $nama_rekening,
                    'nomor_rekening' => $nomor_rekening,
                    'id_lokasi' => $id_lokasi]);

    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        // header("Location: edit_data_lokasi?status=updateGagal");
    } else {
        header("Location: view_kelola_lokasi?status=updateBerhasil");
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
            <h2><a href="view_dashboard_admin" style="color: #fff"><span class="fas fa-atom"></span>
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
            <!-- Button Kembali -->
            <div>
            <button class="button-kelola-kembali"><span class="fas fa-arrow-left"></span>
            <a href="view_kelola_lokasi" style="color: white;">Kembali</a></button>
            </div>

            <!-- Notifikasi -->
            <?php
                if(!empty($_GET['status'])){
                    if($_GET['status'] == 'updateGagal'){
                        echo '<div class="notif role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data gagal diupdate
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
                            <h2>Edit Data Lokasi</h2>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    
                                    <!-- Form Create Fasilitas Wisata -->
                                    <div class="kelola-detail">
                                        <!-- Wilayah -->
                                        <div class="input-box">
                                            <span class="details">ID Wilayah</span>
                                            <select name="id_wilayah">
                                                <option>Pilih Wilayah</option>
                                                <?php 
                                                    foreach ($rowWilayah as $wilayah) {
                                                ?>
                                                <option value="<?=$wilayah->id_wilayah?>">
                                                    <?=$wilayah->id_wilayah?> - <?=$wilayah->nama_wilayah?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="input-box">
                                            <span class="details">Nama Lokasi</span>
                                            <input type="text" name="nama_lokasi" value="<?=$rowLokasi->nama_lokasi?>" placeholder="Nama Lokasi" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Deskripsi Lokasi</span>
                                            <input type="text" name="deskripsi_lokasi" value="<?=$rowLokasi->deskripsi_lokasi?>" placeholder="Deskripsi Lokasi" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Upload Foto Lokasi</span>
                                            <input type="file" name="image_uploads" id="image_uploads" accept=".jpg, .jpeg, .png" onchange="readURL(this);">
                                        </div>
                                        <div class="input-box">
                                            <img src="#" id="preview" width="100px" alt="Preview Gambar"/>

                                            <a href="<?=$rowLokasi->foto_lokasi?>">
                                            <img id="oldpic" src="<?=$rowLokasi->foto_lokasi?>" width="20%" <?php if($rowLokasi->foto_lokasi == NULL) echo "style='display: none;'"; ?>></a>
                                            <br>

                                            <small>
                                                <?php 
                                                if ($rowLokasi->foto_lokasi == NULL) {
                                                    echo "Bukti transfer belum diupload<br>Format .jpg .jpeg .png";
                                                } else {
                                                    echo "Klik gambar untuk memperbesar";
                                                } ?>
                                            </small>

                                            <!-- upload Image -->
                                            <div>
                                                <script>
                                                    const actualBtn = document.getElementById('image_uploads');
                                                    const fileChosen = document.getElementById('file-input-label');

                                                    actualBtn.addEventListener('change', function(){
                                                        fileChosen.innerHTML = '<b>File dipilih :</b> '+this.files[0].name
                                                    });
                                                    window.onload = function() {
                                                        document.getElementById('preview').style.display = 'none';
                                                    };

                                                    function readURL(input) {
                                                        if (input.files && input.files[0]) {
                                                            var reader = new FileReader();

                                                            document.getElementById('oldpic').style.display = 'none';
                                                            reader.onload = function (e) {
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
                                        <div class="input-box">
                                            <span class="details">Kontak Lokasi</span>
                                            <input type="text" name="kontak_lokasi" value="<?=$rowLokasi->kontak_lokasi?>" placeholder="Kontak Lokasi" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Nama Bank</span>
                                            <input type="text" name="nama_bank" value="<?=$rowLokasi->nama_bank?>" placeholder="Nama Bank" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Nama Rekening</span>
                                            <input type="text" name="nama_rekening" value="<?=$rowLokasi->nama_rekening?>" placeholder="Nama Rekening" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Nomor Rekening</span>
                                            <input type="text" name="nomor_rekening" value="<?=$rowLokasi->nomor_rekening?>" placeholder="Nomor Rekening" required>
                                        </div>

                                        <!-- untuk Memasukan Koordinat -->
                                        <h4 style="margin-top: 1.5rem;"><i class="fas fa-search-location"></i> 
                                            Koordinat Lokasi <br>(Diperlukan agar lokasi muncul di peta)</h4>
                                        <div class="input-box">
                                            <span class="details" for="tblatitude">Latitude Lokasi</span>
                                            <input type="text" name="latitude" id="tblatitude" value="<?=$rowLokasi->latitude?>" placeholder="Latitude Lokasi" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details" for="tblongitude">Longitude Lokasi</span>
                                            <input type="text" name="longitude" id="tblongitude" value="<?=$rowLokasi->longitude?>" placeholder="Longitude Lokasi" required>
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