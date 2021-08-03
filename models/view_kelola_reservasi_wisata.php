<?php 
include '../app/database/koneksi.php';
session_start();

$sqlreservasiSelect = 'SELECT * FROM t_reservasi_wisata
                        LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                        LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
                        LEFT JOIN t_paket_wisata ON t_reservasi_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                        LEFT JOIN t_status_reservasi ON t_reservasi_wisata.id_status_reservasi = t_status_reservasi.id_status_reservasi
                        ORDER BY update_terakhir DESC';

$stmt = $pdo->prepare($sqlreservasiSelect);
$stmt->execute();
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
</head>
<body>
    <!-- Menu -->
    <input type="checkbox" id="tombol-gacha"> 
    <div class="sidebar">
        <div class="sidebar-logo">
            <h2><a href="view_dashboard_admin" style="color: #fff"><span class="fas fa-atom"></span>
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
                    <a href="view_kelola_reservasi_wisata" class="paimon-active">
                    <span class="fas fa-luggage-cart"></span>
                        <span>Kelola Reservasi Wisata</span></a>
                </li>
                <li>
                    <a href="view_kelola_asuransi">
                    <span class="fas fa-heartbeat"></span>
                        <span>Kelola Asuransi</span></a>
                </li>
                <li>
                    <a href="view_kelola_wisata">
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
            <!-- Notifikasi -->
            <?php
                if (!empty($_GET['status'])) {
                    if ($_GET['status'] == 'updateBerhasil') {
                        echo '<div class="notif role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data berhasil diupdate
                        </div>';
                    } else if($_GET['status'] == 'hapusBerhasil') {
                        echo '<div class="notif" role="alert">
                        <i class="fa fa-exclamation"></i>
                            Data berhasil dihapus
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
                            <h2>Data Reservasi Wisata</h2>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <table>
                                    <thead>
                                        <tr>
                                            <td>ID Reservasi</td>
                                            <td>Nama User</td>
                                            <td>Nama Lokasi</td>
                                            <td>Nama Paket Wisata</td>
                                            <td>Tanggal Reservasi</td>
                                            <td>Status Reservasi</td>
                                            <td>Aksi</td>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    <?php
                                        foreach ($rowReservasi as $reservasi) {

                                        $truedate = strtotime($reservasi->update_terakhir);
                                        $reservasidate = strtotime($reservasi->tgl_reservasi);
                                    ?>
                                        <tr>
                                            <td><?=$reservasi->id_reservasi_wisata?></td>
                                            <td><?=$reservasi->nama_user?></td>
                                            <td><?=$reservasi->nama_lokasi?></td>
                                            <td><?=$reservasi->nama_paket_wisata?></td>
                                            <td>
                                                <?=strftime('%A, %d %B %Y', $reservasidate);?>
                                                <br><small style="color: rgba(0, 0, 0, 0.5);">Update Terakhir:
                                                <br><?=strftime('%A, %d %B %Y', $truedate);?></small>
                                            </td>
                                            <td>
                                                <?php 
                                                    if ($reservasi->id_status_reservasi == "1") { ?>
                                                    <span class="status diona"></span>
                                                    <?=$reservasi->nama_status_reservasi?> <!-- Status Dalam Atifk -->
                                                <?php } elseif ($reservasi->id_status_reservasi == "2") { ?>
                                                    <span class="status yaoyao"></span>
                                                    <?=$reservasi->nama_status_reservasi?> <!-- Status Dalam Tidak Atifk -->
                                                <?php } elseif ($reservasi->id_status_reservasi == "3") {?>
                                                    <span class="status klee"></span>
                                                    <?=$reservasi->nama_status_reservasi?> <!-- Status Dalam Perbaikan -->
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <button class="modol-btn button-kelola-detail">
                                                    <a href="detail_data_reservasi_wisata?id_reservasi_wisata=<?=$reservasi->id_reservasi_wisata?>" style="color: #fff">Detail</a></button>
                                                <button class="button-kelola-edit">
                                                    <a href="edit_data_reservasi_wisata?id_reservasi_wisata=<?=$reservasi->id_reservasi_wisata?>" style="color: #fff">Edit</a></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php } ?>
                                </table>
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

</body>
</html>