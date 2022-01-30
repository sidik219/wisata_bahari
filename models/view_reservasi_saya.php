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

$sqlreservasiSelect = 'SELECT * FROM t_reservasi_wisata
                        LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                        LEFT JOIN t_paket_wisata ON t_reservasi_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                        LEFT JOIN t_lokasi ON t_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                        LEFT JOIN t_status_reservasi ON t_reservasi_wisata.id_status_reservasi = t_status_reservasi.id_status_reservasi
                        WHERE t_reservasi_wisata.id_user = :id_user
                        ORDER BY update_terakhir DESC';

$stmt = $pdo->prepare($sqlreservasiSelect);
$stmt->execute(['id_user' => $_SESSION['id_user']]);
$rowReservasi = $stmt->fetchAll();

function ageCalculator($dob){
    $birthdate = new DateTime($dob);
    $today   = new DateTime('today');
    $ag = $birthdate->diff($today)->y;
    $mn = $birthdate->diff($today)->m;
    $dy = $birthdate->diff($today)->d;
    if ($mn == 0)
    {
        return "$dy Hari";
    }
    elseif ($ag == 0)
    {
        return "$mn Bulan  $dy Hari";
    }
    else
    {
        return "$ag Tahun $mn Bulan $dy Hari";
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
    <!-- Bootstrap 5 -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous"> -->
</head>
<body>
    <!-- Menu -->
    <input type="checkbox" id="tombol-gacha"> 
    <div class="sidebar">
        <div class="sidebar-logo">
            <!-- Hak Akses -->
            <?php if ($level == 1) { ?>
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
                    <a href="view_reservasi_saya" class="paimon-active">
                    <span class="fas fa-umbrella-beach"></span>
                        <span>Reservasi Saya</span></a>
                </li>
                <li>
                    <a href="view_akun">
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
            <?php if ($level == 1) { ?>
            <div class="user-wrapper">
                <!-- <img src="../views/img/paimon-5.png" width="50px" height="50px" alt=""> -->
                <img id="oldpic" src="<?=$rowUser->foto_user?>" width="50px" height="50px" <?php if($rowUser->foto_user == NULL) echo "style='display: none;'"; ?>>
                <div>
                    <h2>Selamat Datang</h2>
                    <span class="dashboard"><?php echo $_SESSION['nama_user']; ?></span>
                </div>
            </div>
            <?php } ?>
        </header>
        
        <!-- Hak Akses -->
        <?php if ($level == 1) { ?>
        <!-- Main -->
        <main>
            <!-- Deskripsi Reservasi Wisata -->
            <div class="jarak-desk">
                <span class="info-data-reservasi">Data Reservasi Saya</span>
            </div>

            <!-- Notifikasi -->
            <?php
                if(!empty($_GET['status'])){
                    if($_GET['status'] == 'updateBerhasil'){
                        echo '<div class="notif-update" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data berhasil diupdate.
                        </div>';
                    } else if($_GET['status'] == 'tambahBerhasil'){
                        echo '<div class="notif" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data baru berhasil ditambahkan.
                        </div>';
                    } else if($_GET['status'] == 'hapusBerhasil'){
                        echo '<div class="notif-hapus" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data berhasil dihapus.
                        </div>';
                    }
                }
            ?>

            <!-- Full Area -->
            <div class="full-area-kelola">
                <!-- Area A -->
                <div class="area-A">
                    <div class="card-reservasi">
                        <?php 
                            foreach ($rowReservasi as $reservasi) {

                            $truedate = strtotime($reservasi->update_terakhir);
                            $reservasidate = strtotime($reservasi->tgl_reservasi);
                        ?>
                        <div class="cards-detail">
                            <div class="cards-detail__img">
                                <img src="<?=$reservasi->foto_paket_wisata?>?<?php if ($status='nochange'){echo time();}?>" width="100px">
                            </div>
                            <div class="cards-detail__info">
                                <div class="cards-detail__date">
                                    <span>ID Reservasi - <?=$reservasi->id_reservasi_wisata?></span>
                                    <span>Nama User - <?=$reservasi->nama_user?></span>
                                </div>
                                <h1 class="cards-detail__title"><?=$reservasi->nama_paket_wisata?></h1>

                                <p class="cards-detail__text">
                                    <b>Lokasi Reservasi Wisata:</b><br>
                                    <i class="detail-reservasi-bitch fas fa-umbrella-beach"></i>
                                    <small><?=$reservasi->nama_lokasi?></small>
                                    <br><a 
                                    target="_blank" 
                                    href="http://maps.google.com/maps/search/?api=1&query=<?=$reservasi->latitude?>,
                                    <?=$reservasi->longitude?>&z=8"
                                    class="btn-kelola-map">
                                    <i class="nav-icon fas fa-map-marked-alt"></i> Lihat di Peta</a>
                                </p>
                                <p class="cards-detail__text">
                                    <b>Tanggal Reservasi:</b><br>
                                    <i class="detail-reservasi-tanggal far fa-calendar-alt"></i>
                                    <small><?=strftime('%A, %d %B %Y', $reservasidate);?></small>
                                </p>
                                <p class="cards-detail__text">
                                    <b>Jumlah Peserta:</b><br>
                                    <i class="detail-reservasi-peserta fas fa-users"></i>
                                    <small><?=$reservasi->jumlah_reservasi?></small><br>
                                </p>
                                <p class="cards-detail__text">
                                    <b>Total Reservasi:</b><br>
                                    <i class="detail-reservasi-total fas fa-money-bill-wave"></i>
                                    <small>Rp. <?=number_format($reservasi->total_reservasi, 0)?></small><br>
                                </p>
                                <p class="cards-detail__text">
                                    <b>Status Reservasi:</b><br>
                                    <i class="detail-reservasi-status far fa-bell"></i>
                                    <?=$reservasi->nama_status_reservasi?><br>
                                    <small style="color: rgba(0, 0, 0, 0.5);"><b>Update Terakhir:</b>
                                    <br><small><?=strftime('%A, %d %B %Y', $truedate);?></small></small><br>
                                </p>
                                <p class="cards-detail__text">
                                    <b>No Kontak Pengelola Lokasi:</b><br>
                                    <i class="detail-reservasi-kontak  fas fas fa-phone"></i>
                                    <small><?=$reservasi->kontak_lokasi?></small>
                                </p>
                                <p class="cards-detail__text">
                                    <b>Keterangan Pengelola Lokasi:</b><br>
                                    <i class="detail-reservasi-keterangan fas fa-bookmark"></i>
                                    <small><?=$reservasi->keterangan_reservasi?></small>
                                </p>
                                <!-- Old Button Bukti Pembayaran -->
                                <!-- <a href="edit_data_reservasi_saya?id_reservasi_wisata=" class="cards-detail__cta">Upload Bukti Transfer</a> -->
                                <?php
                                    if ($reservasi->id_status_reservasi == 2) {
                                        // Pembayaran Telah di Konfirmasi
                                        // Download Invoice Reservasi Wisata
                                        echo ($reservasi->id_status_reservasi <= 3) ? '<a href="invoice_wisata?id_reservasi_wisata='.$reservasi->id_reservasi_wisata.'" class="cards-detail__cta-2">Download Inovice</a>' : '';
                                    } else if ($reservasi->id_status_reservasi == 3) {
                                        // Pembayaran Tidak Sesuai
                                        echo ($reservasi->id_status_reservasi <= 3) ? '<a href="edit_data_reservasi_saya?id_reservasi_wisata='.$reservasi->id_reservasi_wisata.'" class="cards-detail__cta-2">Upload Bukti Transfer</a>' : '';
                                    } else {
                                        // Menunggu Konfirmasi Pembayaran
                                        echo ($reservasi->id_status_reservasi <= 3) ? '<a href="edit_data_reservasi_saya?id_reservasi_wisata='.$reservasi->id_reservasi_wisata.'" class="cards-detail__cta-2">Upload Bukti Transfer</a>' : '';
                                    }
                                ?>
                            </div>
                        </div><br><br>
                        <?php } ?>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" 
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" 
    integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

</body>
</html>