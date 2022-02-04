<?php 
include 'app/database/koneksi.php';
session_start();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sqluserSelect = "SELECT id_user, nama_user, level_user, username, password
                        FROM t_user
                        WHERE username = :username";
    
    $stmt = $pdo->prepare($sqluserSelect);
    $stmt->execute(['username' => $username]);
    $rowUser = $stmt->fetch();

    if (!empty($rowUser)) {
        if (password_verify($password, $rowUser->password)) {
            if ($rowUser->level_user == "1") { // Wisatawan
                $_SESSION['id_user']    = $rowUser->id_user;
                $_SESSION['nama_user']  = $rowUser->nama_user;
                $_SESSION['level_user'] = $rowUser->level_user;
                $_SESSION['username']   = $rowUser->username;

                header("Location: models/view_dashboard_user?status=login_berhasil");
                // Jarak
            } elseif ($rowUser->level_user == "2") { // Pengelola Lokasi
                $sql  = "SELECT t_pengelola_lokasi.id_lokasi, nama_lokasi FROM t_pengelola_lokasi 
                        LEFT JOIN t_lokasi ON t_lokasi.id_lokasi = t_pengelola_lokasi.id_lokasi
                        WHERE id_user=:id_user";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['id_user' => $rowUser->id_user]);
                $rowLokasi = $stmt->fetch();

                if($stmt->rowCount() != 0){
                    $_SESSION['id_user']            = $rowUser->id_user; // cek ada
                    $_SESSION['nama_user']          = $rowUser->nama_user; // cek ada
                    $_SESSION['level_user']         = $rowUser->level_user; // cek ada
                    $_SESSION['username']           = $rowUser->username; // cek ada
                    $_SESSION['id_lokasi_dikelola'] = $rowLokasi->id_lokasi; // cek ada

                    header('Location: models/view_dashboard_admin?status=login_berhasil&id_lokasi='.$rowLokasi->id_lokasi);
                } else {
                    header('Location: index.php?status=akun_belum_diberi_akses');
                }
                // Jarak
            } elseif ($rowUser->level_user == "3") { // Pengelola Wilayah
                $sql  = "SELECT t_pengelola_wilayah.id_wilayah, nama_wilayah FROM t_pengelola_wilayah 
                        LEFT JOIN t_wilayah ON t_wilayah.id_wilayah = t_pengelola_wilayah.id_wilayah
                        WHERE id_user=:id_user";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['id_user' => $row->id_user]);
                $rowWilayah = $stmt->fetch();

                if($stmt->rowCount() != 0){
                    $_SESSION['id_user']                = $rowUser->id_user; // cek ada
                    $_SESSION['nama_user']              = $rowUser->nama_user; // cek ada
                    $_SESSION['level_user']             = $rowUser->level_user; // cek ada
                    $_SESSION['username']               = $rowUser->username; // cek ada
                    $_SESSION['id_wilayah_dikelola']    = $rowLokasi->id_lokasi; // cek ada

                    header('Location: models/view_dashboard_admin?status=login_berhasil&id_wilayah='.$rowWilayah->id_wilayah);
                } else {
                    header('Location: index.php?status=akun_belum_diberi_akses');
                }
                // Jarak
            } elseif ($rowUser->level_user == "4") { //Pengelola Provinsi
                $_SESSION['id_user']    = $rowUser->id_user;
                $_SESSION['nama_user']  = $rowUser->nama_user;
                $_SESSION['level_user'] = $rowUser->level_user;
                $_SESSION['username']   = $rowUser->username;

                header("Location: models/view_dashboard_admin?status=login_berhasil");
                // Jarak
            } else {
                header("Location: index?status=gagal_login_session");
            }
        } else {
            header("Location: index?status=gagal_login");
        }
    } else {
        header("Location: index?status=username_dan_password_salah");
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
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Css Dashboard -->
    <link rel="stylesheet" href="views/css/style-dashboard.css">
</head>
<body>
    <!-- Main Content -->
    <div class="main-content-login">
        <!-- Main -->
        <main class="main-login">
            <!-- Notifikasi -->
            <?php
                if(!empty($_GET['status'])){
                    if($_GET['status'] == 'Buat_Akun_Berhasil'){
                        echo '<div class="notif-login" role="alert">
                            <i class="far fa-smile-wink"></i>
                            Berhasil Membuat Akun
                        </div>';
                    }
                }
            ?>

            <!-- Full Area -->
            <div class="full-area-login">
                <!-- Area A -->
                <div class="area-A">
                    <div class="card">
                        <div class="card-header-login">
                            <div class="login-0">&nbsp</div> <!-- Jarak jangan di delete -->
                            <div class="login-1">Login</div>
                            <div class="login-2">Wisata Bahari</div>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    
                                    <!-- Form Create Fasilitas Wisata -->
                                    <div class="kelola-login">
                                        <div class="input-box">
                                            <span class="details">Username</span>
                                            <input type="text" name="username" placeholder="Masukan Username Anda" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Password</span>
                                            <input type="password" name="password" placeholder="Masukan Password Anda" required>
                                        </div>
                                    </div>
                                    <!-- <div class="lupa-pw">
                                        <a class="lupa" href="#"><p>Lupa Password?</p></a>
                                    </div> -->

                                    <div class="button-kelola-form-login">
                                        <input type="submit" name="login" value="Login">
                                    </div>
                                    <div class="pesan">
                                        <p>-- Atau --</p>
                                    </div>
                                    <div class="pesan">
                                        <p>Klik <a class="daftar" href="models/daftar">Daftar</a> Jika Tidak Punya Akun</p>
                                    </div>
                                    <!-- End Form -->

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>