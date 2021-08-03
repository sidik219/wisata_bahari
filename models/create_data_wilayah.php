<?php 
include '../app/database/koneksi.php';
session_start();

// Select Provinsi
$sqlprovinsiSelect = "SELECT * FROM t_provinsi";

$stmt = $pdo->prepare($sqlprovinsiSelect);
$stmt->execute();
$rowProvinsi = $stmt->fetchAll();

if (isset($_POST['submit'])) {
    $id_provinsi        = $_POST['id_provinsi'];
    $nama_wilayah       = $_POST['nama_wilayah'];
    $deskripsi_wilayah  = $_POST['deskripsi_wilayah'];
    $sisi_pantai        = $_POST['sisi_pantai'];

    // image Uploads
    $randomstring = substr(md5(rand()), 0, 7);

    if($_FILES["image_uploads"]["size"] == 0) {
        $foto_wilayah = "../views/img/foto_wilayah/image_default.jpg";
    }
    else if (isset($_FILES['image_uploads'])) {
        $target_dir  = "../views/img/foto_wilayah/";
        $foto_wilayah = $target_dir .'WIL_'. $randomstring .'.jpg';
        move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_wilayah);
    }
    // image Uploads End

    $sqlwilayahCreate = "INSERT INTO t_wilayah
                        (id_provinsi, 
                        nama_wilayah, 
                        deskripsi_wilayah, 
                        foto_wilayah, 
                        sisi_pantai)
                        VALUE (:id_provinsi, 
                                :nama_wilayah, 
                                :deskripsi_wilayah, 
                                :foto_wilayah, 
                                :sisi_pantai)";
    
    $stmt = $pdo->prepare($sqlwilayahCreate);
    $stmt->execute(['id_provinsi' => $id_provinsi,
                    'nama_wilayah' => $nama_wilayah,
                    'deskripsi_wilayah' => $deskripsi_wilayah,
                    'foto_wilayah' => $foto_wilayah,
                    'sisi_pantai' => $sisi_pantai]);
    
    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: view_kelola_wilayah?status=tambahGagal");
    } else {
        header("Location: view_kelola_wilayah?status=tambahBerhasil");
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
            <!-- Button Kembali -->
            <div>
            <button class="button-kelola-kembali"><span class="fas fa-arrow-left"></span>
            <a href="view_kelola_wilayah" style="color: white;">Kembali</a></button>
            </div>
            <!-- Full Area -->
            <div class="full-area-kelola">
                <!-- Area A -->
                <div class="area-A">
                    <div class="card">
                        <div class="card-header">
                            <h2>Input Data Wilayah</h2>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    
                                    <!-- Form Create Fasilitas Wisata -->
                                    <div class="kelola-detail">
                                        <div class="input-box">
                                            <span class="details">ID Provinsi</span>
                                            <select name="id_provinsi">
                                                <option>Pilih Provinsi</option>
                                                <?php 
                                                    foreach ($rowProvinsi as $provinsi) {
                                                ?>
                                                <option value="<?=$provinsi->id_provinsi?>">
                                                    <?=$provinsi->id_provinsi?> - <?=$provinsi->nama_provinsi?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Nama Wilayah</span>
                                            <input type="text" name="nama_wilayah" placeholder="Nama Wilayah" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Deskripsi Wilayah</span>
                                            <input type="text" name="deskripsi_wilayah" placeholder="Deskripsi Wilayah" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Upload Foto Wilayah</span>
                                            <input type="file" name="image_uploads" id="image_uploads" accept=".jpg, .jpeg, .png" onchange="readURL(this);">
                                            
                                            <!-- upload Image -->
                                            <div>
                                                <br>
                                                <img id="preview"  width="100px" src="#" alt="Preview Gambar"/>

                                                <script>
                                                    window.onload = function() {
                                                        document.getElementById('preview').style.display = 'none';
                                                    };

                                                    function readURL(input) {
                                                        if (input.files && input.files[0]) {
                                                            var reader = new FileReader();

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
                                            <span class="details">Sisi Pantai</span>
                                            <input type="text" name="sisi_pantai" placeholder="Sisi Pantai" required>
                                        </div>
                                    </div>
                                    <div class="button-kelola-form">
                                        <input type="submit" name="submit" value="Submit">
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
    <!-- jQuery -->
    <script src="//code.jquery.com/jquery-2.2.0.min.js"></script>

</body>
</html>