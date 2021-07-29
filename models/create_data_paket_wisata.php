<?php 
include '../app/database/koneksi.php';
session_start();

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
                    ORDER BY id_wisata DESC";

$stmt = $pdo->prepare($sqlwisataSelect);
$stmt->execute();
$rowWisata = $stmt->fetchAll();

if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Simpan') {
        $id_lokasi                  = $_POST['id_lokasi'];
        $id_asuransi                = $_POST['id_asuransi'];
        $nama_paket_wisata          = $_POST['nama_paket_wisata'];
        $deskripsi_paket_wisata     = $_POST['deskripsi_paket_wisata'];
        $deskripsi_lengkap_paket    = $_POST['deskripsi_lengkap_paket'];
        $status_paket               = $_POST['status_paket'];

        // image Uploads
        $randomstring = substr(md5(rand()), 0, 7);

        if($_FILES["image_uploads"]["size"] == 0) {
            $foto_paket_wisata = "../views/img/image_default.jpg";
        }
        else if (isset($_FILES['image_uploads'])) {
            $target_dir  = "../views/img/foto_wisata/";
            $foto_paket_wisata = $target_dir .'WIS_'. $randomstring .'.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_paket_wisata);
        }
        // image Uploads End

        $sqlpaketCreate = "INSERT INTO t_paket_wisata
                            (id_lokasi,
                            id_asuransi,
                            nama_paket_wisata,
                            deskripsi_paket_wisata,
                            deskripsi_lengkap_paket,
                            foto_paket_wisata,
                            status_paket)
                            VALUE (:id_lokasi,
                                :id_asuransi,
                                :nama_paket_wisata,
                                :deskripsi_paket_wisata,
                                :deskripsi_lengkap_paket,
                                :foto_paket_wisata,
                                :status_paket)";
    
        $stmt = $pdo->prepare($sqlpaketCreate);
        $stmt->execute(['id_lokasi' => $id_lokasi,
                        'id_asuransi' => $id_asuransi,
                        'nama_paket_wisata' => $nama_paket_wisata,
                        'deskripsi_paket_wisata' => $deskripsi_paket_wisata,
                        'deskripsi_lengkap_paket' => $deskripsi_lengkap_paket,
                        'foto_paket_wisata' => $foto_paket_wisata,
                        'status_paket' => $status_paket]);
        
        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
            // header("Location: view_kelola_fasilitas_wisata?status=tambahGagal");
        } else {
            $last_id_paket_wisata = $pdo->lastInsertId();
        }

        $i = 0;
        foreach ($_POST['paket_wisata'] as $paket_wisata) {
            $id_wisata          = $_POST['paket_wisata'][$i];
            $id_paket_wisata    = $last_id_paket_wisata;

            //Update dan set id_paket_wisata ke wisata pilihan
            $sqlwisataUpdate = "UPDATE t_wisata
                                SET id_paket_wisata = :id_paket_wisata
                                WHERE id_wisata = :id_wisata";

            $stmt = $pdo->prepare($sqlwisataUpdate);
            $stmt->execute(['id_wisata' => $id_wisata, 
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
                    <a href="view_kelola_wisata" class="paimon-active">
                    <span class="fas fa-hot-tub"></span>
                        <span>Kelola Wisata</span></a>
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
            <a href="create_data_wisata" style="color: white;">Kembali</a></button>
            </div>
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
                                    
                                    <!-- Form Create Fasilitas Wisata -->
                                    <div class="kelola-detail">
                                        <div class="input-box">
                                            <span class="details">Nama Paket Wisata</span>
                                            <input type="text" name="nama_paket_wisata" placeholder="Nama Paket Wisata" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Deskripsi Paket Wisata</span>
                                            <input type="text" name="deskripsi_paket_wisata" placeholder="Deskripsi Paket Wisata" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Deskripsi Lengkap Paket</span>
                                            <input type="text" name="deskripsi_lengkap_paket" placeholder="Deskripsi Lengkap Paket" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Upload Foto Paket Wisata</span>
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
                                        
                                        <!-- Lokasi -->
                                        <div class="input-box">
                                            <span class="details">ID Lokasi</span>
                                            <select name="id_lokasi">
                                                <option>Pilih Lokasi</option>
                                                <?php 
                                                    foreach ($rowLokasi as $lokasi) {
                                                ?>
                                                <option value="<?=$lokasi->id_lokasi?>">
                                                    <?=$lokasi->id_lokasi?> - <?=$lokasi->nama_lokasi?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        
                                        <!-- Asuransi -->
                                        <div class="input-box">
                                            <span class="details">ID Asuransi</span>
                                            <select name="id_asuransi">
                                                <option>Pilih Asuransi</option>
                                                <?php 
                                                    foreach ($rowAsuransi as $asuransi) {
                                                ?>
                                                <option value="<?=$asuransi->id_asuransi?>">
                                                    <?=$asuransi->id_asuransi?> - <?=$asuransi->biaya_asuransi?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <!-- Paket Wisata Untuk Membelah Diri :v -->
                                        <div class="input-box">
                                            <div class="fieldGroup">
                                            <div class="">
                                                <span class="details">Paket Wisata</span>
                                                <select name="paket_wisata[]">
                                                    <option>Pilih Wisata</option>
                                                    <?php 
                                                        foreach ($rowWisata as $wisata) {
                                                    ?>
                                                    <option value="<?=$wisata->id_wisata?>">
                                                        <?=$wisata->id_wisata?> - <?=$wisata->judul_wisata?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="input-box">
                                                <a href="javascript:void(0)" class="btn-tambah-fasilitas addMore">
                                                    <span class="fas fas fa-plus" aria-hidden="true"></span> Tambah Fasilitas
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="detail-pilihan">
                                        <input type="radio" name="status_paket" value="Aktif" id="dot-1">
                                        <input type="radio" name="status_paket" value="Tidak Aktif" id="dot-2">
                                        <input type="radio" name="status_paket" value="Perbaikan" id="dot-3">
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

                                <!-- copy of input fields group -->
                                <div class="input-box">
                                    <div class="fieldGroupCopy" style="display: none;">
                                        <div class="">
                                            <span class="details">Paket Wisata</span>
                                            <select name="paket_wisata[]">
                                                <option>Pilih Wisata</option>
                                                <?php 
                                                    foreach ($rowWisata as $wisata) {
                                                ?>
                                                <option value="<?=$wisata->id_wisata?>">
                                                    <?=$wisata->id_wisata?> - <?=$wisata->judul_wisata?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="input-box">
                                            <a href="javascript:void(0)" class="btn-hapus-fasilitas remove">
                                                <span class="fas fas fa-minus" aria-hidden="true"></span> Hapus Fasilitas
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
    <!-- Menambah jumlah form input -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
        //group add limit
        var maxGroup = 3;

        //add more fields group
        $(".addMore").click(function(){
            if($('body').find('.fieldGroup').length < maxGroup){
                var fieldHTML = '<div class="fieldGroup">'+$(".fieldGroupCopy").html()+'</div>';
                $('body').find('.fieldGroup:last').after(fieldHTML);
            }else{
                alert('Maksimal '+maxGroup+' fasilitas wisata yang boleh dibuat.');
            }
        });

        //remove fields group
        $("body").on("click",".remove",function(){
            $(this).parents(".fieldGroup").remove();
        });
    });
    </script>

</body>
</html>