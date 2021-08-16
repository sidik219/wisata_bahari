<?php 
include '../app/database/koneksi.php';

if (isset($_POST['submit'])) {
    // filer_input untuk mengenksripsi agar tidak bisa di injek / bypass
    $nama_user      = filter_input(INPUT_POST, 'nama_user', FILTER_SANITIZE_STRING);
    $email          = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $no_hp          = filter_input(INPUT_POST, 'no_hp', FILTER_SANITIZE_STRING);
    $alamat         = filter_input(INPUT_POST, 'alamat', FILTER_SANITIZE_STRING);
    $level_user     = $_POST['level_user'];
    $aktivasi_user  = 1;
    $username       = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password       = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $sqluserCreate = "INSERT INTO t_user
                        (nama_user,
                            email,
                            no_hp,
                            alamat,
                            level_user,
                            aktivasi_user,
                            username,
                            password)
                        VALUE (:nama_user,
                                :email,
                                :no_hp,
                                :alamat,
                                :level_user,
                                :aktivasi_user,
                                :username,
                                :password)";
    
    $stmt = $pdo->prepare($sqluserCreate);
    $stmt->execute(['nama_user' => $nama_user,
                    'email' => $email,
                    'no_hp' => $no_hp,
                    'alamat' => $alamat,
                    'level_user' => $level_user,
                    'aktivasi_user' => $aktivasi_user,
                    'username' => $username,
                    'password' => $password]);
                    
    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: daftar?status=Buat_Akun_Gagal");
    } else {
        header("Location: ../index?status=Buat_Akun_Berhasil");
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
    <!-- Main Content -->
    <div class="main-content-login">
        <!-- Main -->
        <main class="main-login">
            <!-- Notifikasi -->
            <?php
                if(!empty($_GET['status'])){
                    if($_GET['status'] == 'Buat_Akun_Gagal'){
                        echo '<div class="notif-daftar role="alert">
                            Gagal Membuat Akun
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
                            <div class="login-1">Buat Akun</div>
                            <div class="login-2">Wisata Bahari</div>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    
                                    <!-- Form Create Fasilitas Wisata -->
                                    <div class="kelola-login">
                                        <div class="input-box">
                                            <span class="details">Nama Akun</span>
                                            <input type="text" name="nama_user" placeholder="Masukan Nama Anda" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Email</span>
                                            <input type="email" name="email" placeholder="Masukan Email Anda" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">No HP</span>
                                            <input type="number" name="no_hp" placeholder="Masukan No HP Anda" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Alamat</span>
                                            <input type="text" name="alamat" placeholder="Masukan Alamat Anda" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Username</span>
                                            <input type="text" name="username" placeholder="Masukan Username Anda" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Password</span>
                                            <input type="password" name="password" placeholder="Masukan Password Anda" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Level User</span>
                                            <select name="level_user">
                                                <option>Pilih Level</option>
                                                <option value="1">Wisatawan</option>
                                                <option value="2">Pengelola Pantai</option>
                                                <option value="3">pengelola Wilayah</option>
                                                <option value="4">Pengelola Provinsi</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="button-kelola-form-login">
                                        <input type="submit" name="submit" value="Daftar">
                                    </div>
                                    <div class="pesan">
                                        <p>-- Atau --</p>
                                    </div>
                                    <div class="pesan">
                                        <p>Klik <a class="daftar" href="../index">Login</a> Jika Sudah Punya Akun</p>
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