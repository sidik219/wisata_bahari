<?php 
include '../app/database/koneksi.php';
session_start();

if (!$_SESSION['level_user']) {
    header('location: ../index?status=akses_terbatas');
} else {
    $id_user    = $_SESSION['id_user'];
    $level      = $_SESSION['level_user'];
}

// Select Fasilitas Wisata
$sqlfasilitasSelect = "SELECT * FROM t_fasilitas_wisata
                        ORDER BY id_fasilitas_wisata
                        DESC LIMIT 3";

$stmt = $pdo->prepare($sqlfasilitasSelect);
$stmt->execute();
$rowFasilitas = $stmt->fetchAll();

if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Simpan') {
        $judul_wisata   = $_POST['judul_wisata'];
        $randomstring   = substr(md5(rand()), 0, 7);

        //Image upload
        if ($_FILES["image_uploads"]["size"] == 0) {
            $foto_wisata = "../views/img/image_default.jpg";
        } else if (isset($_FILES['image_uploads'])) {
            $target_dir  = "../views/img/foto_wisata/";
            $foto_wisata = $target_dir . 'WIS_' . $randomstring . '.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_wisata);
        }
        //---image upload end

        $sqlasuransiCreate = "INSERT INTO t_wisata
                            (judul_wisata, foto_wisata)
                            VALUE (:judul_wisata, :foto_wisata)";
        
        $stmt = $pdo->prepare($sqlasuransiCreate);
        $stmt->execute(['judul_wisata' => $judul_wisata,
                        'foto_wisata' => $foto_wisata]);
        
        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
            // header("Location: view_kelola_fasilitas_wisata?status=tambahGagal");
        } else {
            $last_id_wisata = $pdo->lastInsertId();
        }

        $i = 0;
        foreach ($_POST['id_fasilitas_wisata'] as $id_fasilitas_wisata) {
            $id_fasilitas_wisata    = $_POST['id_fasilitas_wisata'][$i];
            $id_wisata              = $last_id_wisata;

            $sqlfasilitasUpdate = "UPDATE t_fasilitas_wisata
                                    SET id_wisata = :id_wisata
                                    WHERE id_fasilitas_wisata = :id_fasilitas_wisata";

            $stmt = $pdo->prepare($sqlfasilitasUpdate);
            $stmt->execute(['id_fasilitas_wisata' => $id_fasilitas_wisata,
                            'id_wisata' => $id_wisata]);
        
            $affectedrows = $stmt->rowCount();
            if ($affectedrows == '0') {
                header("Location: create_data_wisata?status=tambahGagal");
            } else {
                header("Location: create_data_paket_wisata?status=tambahBerhasil");
            }
            $i++;
        }
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
                    <a href="view_kelola_wisata">
                    <span class="fas fa-hot-tub" class="paimon-active"></span>
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
                    <a href="#">
                    <span class="fas fa-handshake"></span>
                        <span>Kelola Kerjasama</span></a>
                </li>
                <li>
                    <a href="#">
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
            <!-- Button Kembali -->
            <div>
            <button class="button-kelola-kembali"><span class="fas fa-arrow-left"></span>
            <a href="create_data_fasilitas_wisata" style="color: white;">Kembali</a></button>
            </div>

            <!-- Notifikasi -->
            <?php
                if(!empty($_GET['status'])){
                    if($_GET['status'] == 'tambahBerhasil'){
                        echo '<div class="notif" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data fasilitas wisata berhasil ditambahkan
                        </div>';
                    } else if($_GET['status'] == 'tambahGagal'){
                        echo '<div class="notif-gagal" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data wisata gagal ditambahkan!
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
                            <h2>Input Data wisata</h2>
                            <button class="button-map"><a href="create_data_paket_wisata" style="color: white;">
                            Selanjutnya Input Paket Wisata</a> <span class="fas fa-plus"></span></button>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    <!-- Data Fasilitas -->
                                    <?php 
                                        foreach ($rowFasilitas as $fasilitas) { ?>
                                        <input type="hidden" name="id_fasilitas_wisata[]" value="<?=$fasilitas->id_fasilitas_wisata?>">
                                    <?php } ?>
                                    
                                    <!-- Form Create Wisata -->
                                    <div class="kelola-detail">
                                        <div class="input-box">
                                            <span class="details">Judul Wisata</span>
                                            <input type="text" name="judul_wisata" placeholder="Judul Wisata" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Upload Foto Wisata</span>
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
                                    </div>
                                    <div class="button-kelola-form">
                                        <input type="submit" name="submit" value="Simpan">
                                    </div>
                                    <!-- End Form -->

                                </form>
                                
                            </div>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div style="margin-top: 2rem;">
                        <label for="">Keterangan:</label><br>
                        <small><b>Contoh Pengisian:</b></small><br>
                        <small>* Judul Wisata: Wisata Diving</small><br>
                        <small>* Upload Foto Wisata: "Foto tentang wisata tersebut". <b>Max Ukuran 2Mb</b></small><br>
                        <small style="color: red;">* Untuk menambahkan wisata baru,
                            harus <a href="create_data_fasilitas_wisata.php"><b>input fasilitas</b></a> terlebih dahulu</small>
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
    <!-- Untuk Review Image -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

</body>
</html>