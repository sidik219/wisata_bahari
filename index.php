<?php 
include 'app/database/koneksi.php';
session_start();
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
                        echo '<div class="notif-login role="alert">
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
                                    <div class="lupa-pw">
                                        <a class="lupa" href="#"><p>Lupa Password?</p></a>
                                    </div>

                                    <div class="button-kelola-form-login">
                                        <input type="submit" name="submit" value="Login">
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