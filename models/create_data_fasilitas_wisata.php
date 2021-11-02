<?php 
include '../app/database/koneksi.php';
session_start();

if (!$_SESSION['level_user']) {
    header('location: ../index?status=akses_terbatas');
} else {
    $id_user    = $_SESSION['id_user'];
    $level      = $_SESSION['level_user'];
}

if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Simpan') {
        $i = 0;
        foreach ($_POST['nama_fasilitas'] as $nama_fasilitas) {
            $id_wisata          = $_POST['id_wisata'];
            $id_kerjasama       = $_POST['nama_fasilitas'][$i];
            $tanggal_sekarang   = date ('Y-m-d H:i:s', time());

            $sqlasuransiCreate = "INSERT INTO t_fasilitas_wisata
                                (id_wisata,
                                id_kerjasama,
                                update_terakhir)
                                VALUE (:id_wisata,
                                        :id_kerjasama,
                                        :update_terakhir)";
            
            $stmt = $pdo->prepare($sqlasuransiCreate);
            $stmt->execute(['id_wisata' => $id_wisata,
                            'id_kerjasama' => $id_kerjasama,
                            'update_terakhir' => $tanggal_sekarang]);
            
            $affectedrows = $stmt->rowCount();
            if ($affectedrows == '0') {
                header("Location: create_data_fasilitas_wisata?status=tambahGagal");
            } else {
                header("Location: create_data_wisata?status=tambahBerhasil");
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
            <a href="view_kelola_fasilitas_wisata" style="color: white;">Kembali</a></button>
            </div>

            <!-- Notifikasi -->
            <?php
                if (!empty($_GET['status'])) {
                    if ($_GET['status'] == 'tambahGagal') {
                        echo '<div class="notif-gagal" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data fasilitas wisata gagal ditambahkan!
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
                            <h2>Input Data Fasilitas wisata</h2>
                            <?php 
                                if(!empty($_GET['status'])){
                                    if($_GET['status'] == 'tambahBerhasil'){
                                        echo '<button class="button-map"><a href="create_data_wisata" style="color: white;">
                                        Selanjutnya Input Wisata</a> <span class="fas fa-plus"></span></button>';
                                    }
                                }
                            ?>  
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    
                                    <!-- Form Create Fasilitas Wisata -->
                                    <div class="kelola-detail fieldGroup">
                                        <div class="input-box">
                                            <span class="details">Nama Fasilitas Wisata</span>
                                            <select name="nama_fasilitas[]" required>
                                                <option selected value="">Pilih Fasilitas Wisata</option>
                                                <?php
                                                $sqlkerjasama = 'SELECT * FROM t_kerjasama
                                                                LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                                                                WHERE t_pengadaan_fasilitas.status_pengadaan = "baik"
                                                                ORDER BY id_kerjasama DESC';
                                                $stmt = $pdo->prepare($sqlkerjasama);
                                                $stmt->execute();
                                                $rowKerjasama = $stmt->fetchAll();

                                                foreach ($rowKerjasama as $kerjasama) { ?>
                                                    <?php if ($kerjasama->status_kerjasama == "Melakukan Kerjasama") : ?>
                                                        <option value="<?=$kerjasama->id_kerjasama?>">
                                                            <?=$kerjasama->pengadaan_fasilitas?> - <?=$kerjasama->pihak_ketiga_kerjasama?>
                                                        </option>
                                                    <?php elseif ($kerjasama->status_kerjasama == "Tidak Melakukan Kerjasama") : ?>
                                                        <option value="<?=$kerjasama->id_kerjasama?>">
                                                            <?=$kerjasama->pengadaan_fasilitas?> - Tidak Melakukan Kerjasama 
                                                        </option>
                                                    <?php endif ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="input-box">
                                            <a href="javascript:void(0)" class="btn-tambah-fasilitas addMore">
                                                <span class="fas fas fa-plus" aria-hidden="true"></span> Tambah Fasilitas
                                            </a>
                                        </div>
                                    </div>
                                    <div class="button-kelola-form">
                                        <input type="submit" name="submit" value="Simpan">
                                    </div>
                                    <!-- End Form -->

                                </form>

                                <!-- copy of input fields group -->
                                <div class="kelola-detail fieldGroupCopy" style="display: none;">
                                    <div class="input-box">
                                        <span class="details">Nama Fasilitas</span>
                                        <select name="nama_fasilitas[]" required>
                                            <option selected value="">Pilih Fasilitas Wisata</option>
                                            <?php
                                            $sqlkerjasama = 'SELECT * FROM t_kerjasama
                                                            LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                                                            WHERE t_pengadaan_fasilitas.status_pengadaan = "baik"
                                                            ORDER BY id_kerjasama DESC';
                                            $stmt = $pdo->prepare($sqlkerjasama);
                                            $stmt->execute();
                                            $rowKerjasama = $stmt->fetchAll();

                                            foreach ($rowKerjasama as $kerjasama) { ?>
                                                <?php if ($kerjasama->status_kerjasama == "Melakukan Kerjasama") : ?>
                                                    <option value="<?=$kerjasama->id_kerjasama?>">
                                                        <?=$kerjasama->pengadaan_fasilitas?> - <?=$kerjasama->pihak_ketiga_kerjasama?>
                                                    </option>
                                                <?php elseif ($kerjasama->status_kerjasama == "Tidak Melakukan Kerjasama") : ?>
                                                    <option value="<?=$kerjasama->id_kerjasama?>">
                                                        <?=$kerjasama->pengadaan_fasilitas?> - Tidak Melakukan Kerjasama 
                                                    </option>
                                                <?php endif ?>
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

                    <!-- Keterangan -->
                    <div style="margin-top: 2rem;">
                        <label for="">Keterangan:</label><br>
                        <small>* Fasilitas Wisata Yang Dibuat Max Harus 3</small><br>
                        <small style="color: red;">* Untuk menambahkan fasilitas wisata baru,
                            <br> harus <a href="create_data_pengadaan_fasilitas.php"><b>input pengadaan</b></a> terlebih dahulu selanjutnya input kerjasama
                        </small>
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
    <!-- Menambah jumlah form input -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
        //group add limit
        var maxGroup = 3;

        //add more fields group
        $(".addMore").click(function(){
            if($('body').find('.fieldGroup').length < maxGroup){
                var fieldHTML = '<div class="kelola-detail fieldGroup">'+$(".fieldGroupCopy").html()+'</div>';
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