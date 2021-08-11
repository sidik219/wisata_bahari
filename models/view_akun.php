<?php 
include '../app/database/koneksi.php';
session_start();

// if($_GET['id_lokasi']){
//     $_SESSION['id_lokasi'] = $_GET['id_lokasi'];
// }
// else if(!$_GET['id_lokasi' && !$_SESSION['id_lokasi']]){
//     header("Location: view_dashboard_user");
// }

$defaultpic = "../views/img/image_default.jpg";

// Select All Data User
$sqluserSelect = 'SELECT * FROM t_user';

$stmt = $pdo->prepare($sqluserSelect);
$stmt->execute();
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
        // header("Location: edit_data_wisata?status=updateGagal");
    } else {
        header("Location: view_akun?status=updateBerhasil");
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
                            <h2>Edit Data User</h2>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    
                                    <!-- Form User -->
                                    <div class="kelola-detail">
                                        <div class="input-box">
                                            <span class="details">Nama User</span>
                                            <input type="text" name="nama_user" value="<?=$rowUser->nama_user?>" placeholder="Nama User" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Pilih Jenis Kelamin</span>
                                            <select name="jenis_kelamin">
                                                <option>Pilih Jenis Kelamin</option>
                                                <option value="laki-laki">Laki-laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Jenis Kelamin Sekarang</span>
                                            <input type="text" value="<?=$rowUser->jenis_kelamin?>" placeholder="Jenis Kelamin" readonly>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Tempat Lahir</span>
                                            <input type="text" name="tempat_lahir" value="<?=$rowUser->tempat_lahir?>" placeholder="Tempat Lahir" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Tanggal Lahir</span>
                                            <input type="date" name="tanggal_lahir" value="<?=$rowUser->tanggal_lahir?>" placeholder="Tanggal Lahir" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Email</span>
                                            <input type="email" name="email" value="<?=$rowUser->email?>" placeholder="Email" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">No HP</span>
                                            <input type="number" name="no_hp" value="<?=$rowUser->no_hp?>" placeholder="No HP" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Alamat</span>
                                            <input type="text" name="alamat" value="<?=$rowUser->alamat?>" placeholder="Alamat" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Upload Foto User</span>
                                            <input type="file" name="image_uploads" id="image_uploads" accept=".jpg, .jpeg, .png" onchange="readURL(this);">
                                        </div>
                                        <div class="input-box">
                                            <img src="#" id="preview" width="100px" alt="Preview Gambar"/>

                                            <a href="<?=$rowUser->foto_user?>">
                                            <img id="oldpic" src="<?=$rowUser->foto_user?>" width="20%" <?php if($rowUser->foto_user == NULL) echo "style='display: none;'"; ?>></a>
                                            <br>

                                            <small>
                                                <?php 
                                                if ($rowUser->foto_user == NULL) {
                                                    echo "Upload foto baru<br>Format .jpg .jpeg .png";
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

</body>
</html>