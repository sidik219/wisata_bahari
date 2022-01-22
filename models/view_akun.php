<?php 
include '../app/database/koneksi.php';
session_start();

if (!$_SESSION['level_user']) {
    header('location: ../index?status=akses_terbatas');
} else {
    $id_user    = $_SESSION['id_user'];
    $level      = $_SESSION['level_user'];
}

$defaultpic = "../views/img/image_default.jpg";

// Select All Data User
$sqluserSelect = "SELECT * FROM t_user
                    WHERE id_user = :id_user";

$stmt = $pdo->prepare($sqluserSelect);
$stmt->execute(['id_user' => $_SESSION['id_user']]);
$rowUser = $stmt->fetch();

if (isset($_POST['submit'])) {
    $nama_user      = $_POST['nama_user'];
    $jenis_kelamin  = $_POST['jenis_kelamin'];
    $tempat_lahir   = $_POST['tempat_lahir'];
    $tanggal_lahir  = $_POST['tanggal_lahir'];
    $email          = $_POST['email'];
    $no_hp          = $_POST['no_hp'];
    $alamat         = $_POST['alamat'];

    //Image upload
    $randomstring = substr(md5(rand()), 0, 7);

    if ($_FILES["image_uploads"]["size"] == 0) {
        $foto_user = $rowUser->foto_user;
        $pic = "&none=";
    } else if (isset($_FILES['image_uploads'])) {
        if (($rowUser->foto_user == $defaultpic) || (!$rowUser->foto_user)){
            $target_dir  = "../views/img/foto_user/";
            $foto_user = $target_dir .'USR_'. $randomstring .'.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_user);
            $pic = "&new=";
        } else if (isset($rowUser->foto_user)){
            $foto_user = $rowUser->foto_user;
            unlink($rowUser->foto_user);
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $rowUser->foto_user);
            $pic = "&replace=";
        }
    }
    //---image upload end

    $sqlpaketUpdate = "UPDATE t_user
                        SET nama_user = :nama_user,
                            jenis_kelamin = :jenis_kelamin,
                            tempat_lahir = :tempat_lahir,
                            tanggal_lahir = :tanggal_lahir,
                            email = :email,
                            no_hp = :no_hp,
                            alamat = :alamat,
                            foto_user = :foto_user
                        WHERE id_user = :id_user";

    $stmt = $pdo->prepare($sqlpaketUpdate);
    $stmt->execute(['nama_user' => $nama_user,
                    'jenis_kelamin' => $jenis_kelamin,
                    'tempat_lahir' => $tempat_lahir,
                    'tanggal_lahir' => $tanggal_lahir,
                    'email' => $email,
                    'no_hp' => $no_hp,
                    'alamat' => $alamat,
                    'foto_user' => $foto_user,
                    'id_user' => $id_user]);
                    
    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: view_akun?status=updateGagal&id_user=$id_user");
    } else {
        header("Location: view_akun?status=updateBerhasil&id_user=$id_user");
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
            <!-- Hak Akses -->
            <?php if ($level == 1 || $level == 2 || $level == 3 || $level == 4) { ?>
            <h2><a href="view_dashboard_user" style="color: #fff"><span class="fas fa-atom"></span>
            <span>Wisata Bahari</span></a></h2>
            <?php } ?>
        </div>

        <!-- Hak Akses -->
        <?php if ($level == 1) { ?>
        <div class="sidebar-menu">
            <ul>
                <!-- Dahboard User -->
                <li>
                    <a href="view_dashboard_user">
                    <span class="icon fas fa-home"></span>
                        <span>Dashboard User</span></a>
                </li>
                <li>
                    <a href="view_reservasi_saya">
                    <span class="fas fa-umbrella-beach"></span>
                        <span>Reservasi Saya</span></a>
                </li>
                <li>
                    <a href="view_akun" class="paimon-active">
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
                    <a href="view_akun" class="paimon-active">
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
                    <a href="view_akun" class="paimon-active">
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
                    <a href="view_akun" class="paimon-active">
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

            <!-- Hak Akses -->
            <?php if ($level == 1 || $level == 2 || $level == 3 || $level == 4) { ?>
            <div class="user-wrapper">
                <img src="../views/img/paimon-5.png" width="50px" height="50px" alt="">
                <div>
                    <h2>Selamat Datang</h2>
                    <span class="dashboard"><?php echo $_SESSION['nama_user']; ?></span>
                </div>
            </div>
            <?php } ?>
        </header>
        
        <!-- Hak Akses -->
        <?php if ($level == 1 || $level == 2 || $level == 3 || $level == 4) { ?>
        <!-- Main -->
        <main>
            <!-- Notifikasi -->
            <?php
                if(!empty($_GET['status'])){
                    if($_GET['status'] == 'updateBerhasil'){
                        echo '<div class="notif-update" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data berhasil diupdate
                        </div>';
                    } else if($_GET['status'] == 'updateGagal'){
                        echo '<div class="notif-gagal" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data akun gagal diupdate, <b style="color: orange;">Dikarenakan tidak ada perubahan data</b>.
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
                            <h2>Data User</h2>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    
                                    <!-- Form User -->
                                    <div class="kelola-detail">
                                        <div class="input-box">
                                            <span class="details"><b>Nama User:</b></span>
                                            <input type="text" name="nama_user" value="<?=$rowUser->nama_user?>" placeholder="Nama User" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details" for="jenis_kelamin"><b>Jenis Kelamin:</b></span>
                                            <div class="flex-container">
                                                <input class="radio" type="radio" name="jenis_kelamin" id="laki_laki" value="laki-laki" <?php if ($rowUser->jenis_kelamin != "perempuan") echo "checked"; ?>>
                                                <label for="laki-laki" style="margin-left: 0.5rem;">Laki-laki</label>
                                            </div>
                                            <div class="flex-container" style="margin-top: 0.5rem;">
                                                <input class="radio" type="radio" name="jenis_kelamin" id="perempuan" value="perempuan" <?php if ($rowUser->jenis_kelamin == "perempuan") echo "checked"; ?>>
                                                <label for="perempuan" style="margin-left: 0.5rem;">Perempuan</label>
                                            </div>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Tempat Lahir:</b></span>
                                            <input type="text" name="tempat_lahir" value="<?=$rowUser->tempat_lahir?>" placeholder="Tempat Lahir" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Tanggal Lahir:</b></span>
                                            <input type="date" name="tanggal_lahir" value="<?=$rowUser->tanggal_lahir?>" placeholder="Tanggal Lahir" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Email:</b></span>
                                            <input type="email" name="email" value="<?=$rowUser->email?>" placeholder="Email" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>No HP:</b></span>
                                            <input type="number" name="no_hp" value="<?=$rowUser->no_hp?>" placeholder="No HP" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Alamat:</b></span>
                                            <input type="text" name="alamat" value="<?=$rowUser->alamat?>" placeholder="Alamat" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details"><b>Upload Foto User:</b></span>
                                            <input type="file" name="image_uploads" id="image_uploads" accept=".jpg, .jpeg, .png" onchange="readURL(this);">
                                        </div>
                                        <div class="input-box">
                                            <img src="#" id="preview" width="100px" alt="Preview Gambar"/>

                                            <a href="<?=$rowUser->foto_user?>">
                                            <img id="oldpic" src="<?=$rowUser->foto_user?>" width="20%" <?php if($rowUser->foto_user == NULL) echo "style='display: none;'"; ?>></a>
                                            <br>

                                            <small class="text-muted">
                                                <?php if ($rowUser->foto_user == NULL) {
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
    <!-- One time page refresh after first page load -->
    <!-- When I meet this problem, I search to here but most of answers are trying to modify existing url. Here is another answer which works for me using localStorage. -->
    <script type='text/javascript'>

    (function()
    {
    if( window.localStorage )
    {
        if( !localStorage.getItem('firstLoad') )
        {
        localStorage['firstLoad'] = true;
        window.location.reload();
        }  
        else
        localStorage.removeItem('firstLoad');
    }
    })();

    </script>

</body>
</html>