<?php 
include '../app/database/koneksi.php';
session_start();

if (!$_SESSION['level_user']) {
    header('location: ../index?status=akses_terbatas');
} else {
    $id_user    = $_SESSION['id_user'];
    $level      = $_SESSION['level_user'];
}

// Select Lokasi
$sqllokasiSelect = "SELECT * FROM t_lokasi
                    ORDER BY id_lokasi ASC";

$stmt = $pdo->prepare($sqllokasiSelect);
$stmt->execute();
$rowLokasi = $stmt->fetchAll();

// Select Asuransi
$sqlasuransiSelect = "SELECT * FROM t_asuransi
                    WHERE id_perusahaan_asuransi IS NULL
                    ORDER BY id_asuransi ASC";

$stmt = $pdo->prepare($sqlasuransiSelect);
$stmt->execute();
$rowAsuransi = $stmt->fetchAll();

// Select Wisata
$sqlwisataSelect = "SELECT * FROM t_wisata
                    WHERE id_paket_wisata IS NULL
                    ORDER BY id_wisata";

$stmt = $pdo->prepare($sqlwisataSelect);
$stmt->execute();
$rowWisata = $stmt->fetchAll();

if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Simpan') {
        $id_lokasi          = $_POST['id_lokasi'];
        $id_asuransi        = $_POST['id_asuransi'];
        $nama_paket_wisata  = $_POST['nama_paket_wisata'];
        $tgl_awal_paket     = $_POST['tgl_awal_paket'];
        $tgl_akhir_paket    = $_POST['tgl_akhir_paket'];
        $status_paket       = $_POST['status_paket'];
        $randomstring       = substr(md5(rand()), 0, 7);

        // image Uploads
        if ($_FILES["image_uploads"]["size"] == 0) {
            $foto_paket_wisata = "../views/img/image_default.jpg";
        } else if (isset($_FILES['image_uploads'])) {
            $target_dir  = "../views/img/foto_paket_wisata/";
            $foto_paket_wisata = $target_dir .'PAK_'. $randomstring .'.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_paket_wisata);
        }
        // image Uploads End

        $sqlpaketCreate = "INSERT INTO t_paket_wisata
                            (id_lokasi,
                            id_asuransi,
                            nama_paket_wisata,
                            tgl_awal_paket,
                            tgl_akhir_paket,
                            foto_paket_wisata,
                            status_paket)
                            VALUE (:id_lokasi,
                                :id_asuransi,
                                :nama_paket_wisata,
                                :tgl_awal_paket,
                                :tgl_akhir_paket,
                                :foto_paket_wisata,
                                :status_paket)";
    
        $stmt = $pdo->prepare($sqlpaketCreate);
        $stmt->execute(['id_lokasi' => $id_lokasi,
                        'id_asuransi' => $id_asuransi,
                        'nama_paket_wisata' => $nama_paket_wisata,
                        'tgl_awal_paket' => $tgl_awal_paket,
                        'tgl_akhir_paket' => $tgl_akhir_paket,
                        'foto_paket_wisata' => $foto_paket_wisata,
                        'status_paket' => $status_paket]);
        
        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
            header("Location: create_data_paket_wisata?status=tambahGagal");
        } else {
            $last_id_paket_wisata = $pdo->lastInsertId();
        }

        $i = 0;
        foreach ($_POST['nama_wisata'] as $nama_wisata) {
            $id_paket_wisata    = $last_id_paket_wisata;
            $id_wisata          = $_POST['nama_wisata'][$i];
            $jadwal_wisata      = $_POST['jadwal_wisata'][$i];
            $deskripsi_wisata   = $_POST['deskripsi_wisata'][$i];

            //Update dan set id_paket_wisata ke wisata pilihan
            $sqlwisataUpdate = "UPDATE t_wisata
                                SET id_paket_wisata = :id_paket_wisata,
                                    jadwal_wisata = :jadwal_wisata,
                                    deskripsi_wisata = :deskripsi_wisata
                                WHERE id_wisata = :id_wisata";

            $stmt = $pdo->prepare($sqlwisataUpdate);
            $stmt->execute(['id_wisata' => $id_wisata,
                            'jadwal_wisata' => $jadwal_wisata,
                            'deskripsi_wisata' => $deskripsi_wisata,
                            'id_paket_wisata' => $id_paket_wisata]);
        
            $affectedrows = $stmt->rowCount();
            if ($affectedrows == '0') {
                header("Location: view_kelola_wisata?status=tambahGagal");
            } else {
                header("Location: view_kelola_wisata?status=tambahBerhasil");
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
            <a href="create_data_wisata" style="color: white;">Kembali</a></button>
            </div>

            <!-- Notifikasi -->
            <?php
                if(!empty($_GET['status'])){
                    if($_GET['status'] == 'tambahBerhasil'){
                        echo '<div class="notif" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data wisata berhasil ditambahkan
                        </div>';
                    } else if($_GET['status'] == 'tambahGagal'){
                        echo '<div class="notif-gagal" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data paket wisata gagal ditambahkan.
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
                            <h2>Input Data Paket wisata</h2>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <form action="#" method="POST" enctype="multipart/form-data">

                                    <!-- Form Create Paket Wisata -->
                                    <div class="kelola-detail">
                                        <!-- Lokasi -->
                                        <div class="input-box">
                                            <span class="details">ID Lokasi</span>
                                            <select name="id_lokasi" required>
                                                <option selected value="">Pilih Lokasi</option>
                                                <?php foreach ($rowLokasi as $lokasi) { ?>
                                                <option value="<?=$lokasi->id_lokasi?>">
                                                    ID <?=$lokasi->id_lokasi?> - <?=$lokasi->nama_lokasi?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        
                                        <!-- Asuransi -->
                                        <div class="input-box">
                                            <span class="details">ID Asuransi</span>
                                            <select name="id_asuransi" required>
                                                <option selected value="">Pilih Asuransi</option>
                                                <?php foreach ($rowAsuransi as $asuransi) { ?>
                                                <option value="<?=$asuransi->id_asuransi?>">
                                                    ID <?=$asuransi->id_asuransi?> - <?=$asuransi->nama_asuransi?>, Rp <?=number_format($asuransi->biaya_asuransi, 0)?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <!-- Wisata -->
                                        <div class="input-box">
                                            <div class="fieldGroup">
                                                <div class="">
                                                    <span class="details">ID Wisata</span>
                                                    <select name="nama_wisata[]" required>
                                                        <option selected value="">Pilih Wisata</option>
                                                        <?php foreach ($rowWisata as $wisata) { ?>
                                                        <option value="<?=$wisata->id_wisata?>">
                                                            ID <?=$wisata->id_wisata?> - <?=$wisata->judul_wisata?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <input type="text" name="jadwal_wisata[]" value="Hari Pertama" placeholder="Hari" style="margin-top: 0.3rem;" required />
                                                    <input type="text" name="deskripsi_wisata[]" placeholder="Deskripsi Wisata" style="margin-top: 0.3rem;" required />
                                                </div>
                                                <div class="input-box">
                                                    <a href="javascript:void(0)" class="btn-tambah-fasilitas addMore">
                                                        <span class="fas fas fa-plus" aria-hidden="true"></span> Tambah wisata
                                                    </a>
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
                                            <small style="color: red;">* Untuk menambahkan wisata baru,
                                                harus <a href="create_data_fasilitas_wisata.php"><b>input fasilitas</b></a> terlebih dahulu
                                            </small>
                                        </div>
                                        
                                        <div class="input-box">
                                            <span class="details">Nama Paket Wisata</span>
                                            <input type="text" name="nama_paket_wisata" placeholder="Nama Paket Wisata" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Buat Batas Pemesanan Paket</span>
                                            <div style="margin-top: 1rem;">
                                                <small><b>Tanggal Awal</b></small>
                                                <input type="date" name="tgl_awal_paket" id="tgl_awal_paket" required>
                                            </div>
                                            <div style="margin-top: 1rem;">
                                                <small><b>Tanggal Akhir</b></small>
                                                <input type="date" name="tgl_akhir_paket" id="tgl_akhir_paket" required>
                                            </div>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Upload Foto Paket Wisata</span>
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
                                    <div class="detail-pilihan">
                                        <input type="radio" name="status_paket" value="Aktif" id="dot-1" checked required>
                                        <input type="radio" name="status_paket" value="Tidak Aktif" id="dot-2" required>
                                        <input type="radio" name="status_paket" value="Perbaikan" id="dot-3" required>
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

                                <!-- copy wisata -->
                                <div class="input-box">
                                    <div class="fieldGroupCopy" style="display: none;">
                                        <div class="">
                                            <span class="details">ID Wisata</span>
                                            <select name="nama_wisata[]" required>
                                                <option selected value="">Pilih Wisata</option>
                                                <?php foreach ($rowWisata as $wisata) { ?>
                                                <option value="<?=$wisata->id_wisata?>">
                                                    ID <?=$wisata->id_wisata?> - <?=$wisata->judul_wisata?></option>
                                                <?php } ?>
                                            </select>
                                            <input type="text" name="jadwal_wisata[]" value="Hari Pertama" placeholder="Hari" style="margin-top: 0.3rem;" required />
                                            <input type="text" name="deskripsi_wisata[]" placeholder="Deskripsi Wisata" style="margin-top: 0.3rem;" required />
                                        </div>
                                        <div class="input-box">
                                            <a href="javascript:void(0)" class="btn-hapus-fasilitas remove">
                                                <span class="fas fas fa-minus" aria-hidden="true"></span> Hapus wisata
                                            </a>
                                        </div>
                                    </div>
                                </div>

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
    <!-- Untuk Review Image -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <!-- All Javascript -->
    <!-- Menambah jumlah form input -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
        //group add limit
        var maxGroup = 7;

        //add more fields group
        $(".addMore").click(function(){
            if($('body').find('.fieldGroup').length < maxGroup){
                var fieldHTML = '<div class="fieldGroup">'+$(".fieldGroupCopy").html()+'</div>';
                $('body').find('.fieldGroup:last').after(fieldHTML);
            }else{
                alert('Maksimal '+maxGroup+' wisata yang boleh dibuat.');
            }
        });

        //remove fields group
        $("body").on("click",".remove",function(){
            $(this).parents(".fieldGroup").remove();
        });
    });
    </script>
    <!-- Pembatasan Date Pemesanan -->
    <script>
        var today = new Date().toISOString().split('T')[0];
        document.getElementsByName("tgl_awal_paket")[0].setAttribute('min', today);
        var today = new Date().toISOString().split('T')[0];
        document.getElementsByName("tgl_akhir_paket")[0].setAttribute('min', today);
    </script>

</body>
</html>