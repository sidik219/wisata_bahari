<?php 
include '../app/database/koneksi.php';
session_start();

if (!$_SESSION['level_user']) {
    header('location: ../index?status=akses_terbatas');
} else {
    $id_user    = $_SESSION['id_user'];
    $level      = $_SESSION['level_user'];
}

$id_reservasi_wisata = $_GET['id_reservasi_wisata'];

$defaultpic = "../views/img/image_default.jpg";

// Select All Data User
$sqluserSelect = "SELECT * FROM t_user
                    WHERE id_user = :id_user";

$stmt = $pdo->prepare($sqluserSelect);
$stmt->execute(['id_user' => $_SESSION['id_user']]);
$rowUser2 = $stmt->fetch();

// Reservasi Wisata
$sqlreservasiSelect = 'SELECT * FROM t_reservasi_wisata
                    WHERE id_reservasi_wisata = :id_reservasi_wisata';

$stmt = $pdo->prepare($sqlreservasiSelect);
$stmt->execute(['id_reservasi_wisata' => $id_reservasi_wisata]);
$rowReservasi = $stmt->fetch();

// Pengeluaran
$sqlpengeluaranSelect = 'SELECT * FROM t_pengeluaran
                    LEFT JOIN t_reservasi_wisata ON t_pengeluaran.id_reservasi_wisata = t_reservasi_wisata.id_reservasi_wisata
                    WHERE t_reservasi_wisata.id_reservasi_wisata = :id_reservasi_wisata
                    ORDER BY id_pengeluaran DESC';

$stmt = $pdo->prepare($sqlpengeluaranSelect);
$stmt->execute(['id_reservasi_wisata' => $id_reservasi_wisata]);
$rowPengeluaran = $stmt->fetchAll();

if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Simpan') {
        $i = 0;
        foreach ($_POST['nama_pengeluaran'] as $nama_pengeluaran) {
            $id_reservasi_wisata    = $_POST['id_reservasi_wisata'];
            $nama_pengeluaran       = $_POST['nama_pengeluaran'][$i];
            $biaya_pengeluaran      = $_POST['biaya_pengeluaran'][$i];
            $tanggal_sekarang       = $_POST['tgl_pengeluaran'][$i];

            //Insert t_pengeluaran
            $sqlpengeluaran = "INSERT INTO t_pengeluaran (id_reservasi_wisata, nama_pengeluaran, biaya_pengeluaran, tgl_pengeluaran)
                                                VALUES (:id_reservasi_wisata, :nama_pengeluaran, :biaya_pengeluaran, :tgl_pengeluaran)";

            $stmt = $pdo->prepare($sqlpengeluaran);
            $stmt->execute([
                'id_reservasi_wisata' => $id_reservasi_wisata,
                'nama_pengeluaran' => $nama_pengeluaran,
                'biaya_pengeluaran' => $biaya_pengeluaran,
                'tgl_pengeluaran' => $tgl_pengeluaran
            ]);

            $affectedrows = $stmt->rowCount();
            if ($affectedrows == '0') {
                header("Location: kelola_laporan_pengeluaran.php?status=tambahGagal");
            } else {
                //echo "HAHAHAAHA GREAT SUCCESSS !";
                // echo "<meta http-equiv='refresh' content='0'>";
                header('Location: kelola_laporan_pengeluaran.php?id_reservasi_wisata=' . $id_reservasi_wisata . '&status=tambahBerhasil');
            }
            $i++;
        } //End Foreach
    } else {
        echo '<script>alert("Harap inputkan nama pengeluaran yang akan ditambahkan")</script>';
    }
}

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
            <!-- Hak Akses Pengelola Wilayah atau Provinsi -->
            <?php if ($level == 2 || $level == 4) { ?>
            <h2><a href="view_dashboard_admin" style="color: #fff"><span class="fas fa-atom"></span>
            <span>Wisata Bahari</span></a></h2>
            <?php } ?>
        </div>

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
                    <a href="kelola_laporan_periode">
                    <span class="icon far fa-file-alt"></span>
                        <span>Kelola Laporan Periode</span></a>
                </li>
                <li>
                    <a href="view_kelola_pengajuan">
                    <span class="fas fa-file-signature"></span>
                        <span>Kelola Pengajuan</span></a>
                </li>
                <li>
                    <a href="view_kelola_reservasi_wisata" class="paimon-active">
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
                    <a href="kelola_laporan_periode">
                    <span class="icon far fa-file-alt"></span>
                        <span>Kelola Laporan Periode</span></a>
                </li>
                <li>
                    <a href="view_kelola_pengajuan">
                    <span class="fas fa-file-signature"></span>
                        <span>Kelola Pengajuan</span></a>
                </li>
                <li>
                    <a href="view_kelola_reservasi_wisata" class="paimon-active">
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

            <!-- Hak Akses Pengelola Wilayah atau Provinsi -->
            <?php if ($level == 2 || $level == 4) { ?>
            <div class="user-wrapper">
                <!-- <img src="../views/img/paimon-5.png" width="50px" height="50px" alt=""> -->
                <img src="<?=$rowUser2->foto_user?>" width="50px" height="50px" <?php if($rowUser2->foto_user == NULL) echo "style='display: none;'"; ?>>
                <div>
                    <h2>Selamat Datang</h2>
                    <span class="dashboard"><?php echo $_SESSION['nama_user']; ?></span>
                </div>
            </div>
            <?php } ?>
        </header>
        
        <!-- Hak Akses Pengelola Wilayah atau Provinsi -->
        <?php if ($level == 2 || $level == 4) { ?>
        <!-- Main -->
        <main>
            <!-- Button Kembali -->
            <div>
            <button class="button-kelola-kembali"><span class="fas fa-arrow-left"></span>
                <a href="view_kelola_reservasi_wisata" style="color: white;">Kembali</a></button>
            </div>

            <!-- Full Area -->
            <div class="full-area-kelola">
                <!-- Area A -->
                <div class="area-A">
                    <div class="card">
                        <div class="card-header">
                            <h2>Data Pengeluaran Reservasi Wisata</h2>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id_reservasi_wisata" value="<?=$rowReservasi->id_reservasi_wisata?>">    

                                    <!-- Form Create Fasilitas Wisata -->
                                    <div class="kelola-detail fieldGroup">
                                        <div class="input-box">
                                            <span class="details"><b>Nama Pengeluaran:</b></span>
                                            <input type="text" name="nama_pengeluaran[]" placeholder="Nama Pengeluaran" required>
                                            <span class="details"><b>Biaya Pengeluaran:</b></span>
                                            <input type="text" name="biaya_pengeluaran[]" placeholder="Biaya Pengeluaran" required>
                                            <span class="details"><b>Tanggal Pengeluaran:</b></span>
                                            <input type="date" name="tgl_pengeluaran[]" placeholder="Tanggal Pengeluaran" required>
                                        </div>
                                        <div class="input-box">
                                            <a href="javascript:void(0)" class="btn-tambah-fasilitas addMore">
                                                <span class="fas fas fa-plus" aria-hidden="true"></span> Add Pengeluaran
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
                                        <span class="details"><b>Nama Pengeluaran:</b></span>
                                        <input type="text" name="nama_pengeluaran[]" placeholder="Nama Pengeluaran" required>
                                        <span class="details"><b>Biaya Pengeluaran:</b></span>
                                        <input type="text" name="biaya_pengeluaran[]" placeholder="Biaya Pengeluaran" required>
                                        <span class="details"><b>Tanggal Pengeluaran:</b></span>
                                        <input type="date" name="tgl_pengeluaran[]" placeholder="Tanggal Pengeluaran" required>
                                    </div>
                                    <div class="input-box">
                                        <a href="javascript:void(0)" class="btn-hapus-fasilitas remove">
                                            <span class="fas fas fa-minus" aria-hidden="true"></span> Hapus Pengeluaran
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <table>
                                    <thead>
                                        <tr>
                                            <td>ID Pengeluaran</td>
                                            <td>ID Reservasi</td>
                                            <td>Nama Pengeluaran</td>
                                            <td>Biaya Pengeluaran</td>
                                            <td>Tanggal Pengeluaran</td>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $sum_reservasi = 0;
                                        $sum_pengeluaran = 0;
                                        foreach ($rowPengeluaran as $pengeluaran) {
                                        $pengeluarandate = strtotime($pengeluaran->tgl_pengeluaran);
                                        $sum_pengeluaran += $pengeluaran->biaya_pengeluaran; ?>
                                        <tr>
                                            <td><?=$pengeluaran->id_pengeluaran?></td>
                                            <td><?=$pengeluaran->id_reservasi_wisata?></td>
                                            <td><?=$pengeluaran->nama_pengeluaran?></td>
                                            <td>Rp. <?=number_format($pengeluaran->biaya_pengeluaran, 0)?></td>
                                            <td><?=strftime('%A, %d %B %Y', $pengeluarandate);?></td>
                                            <td>
                                                <?php if ($level == 2 || $level == 4) { ?>
                                                <button class="button-kelola-hapus">
                                                    <a href="all_hapus?type=pengeluaran&id_pengeluaran=<?=$pengeluaran->id_pengeluaran?>&id_reservasi_wisata=<?=$id_reservasi_wisata?>" style="color: #fff" onclick="return konfirmasiHapus(event)">Hapus</button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>

                                        <!-- Hasil -->
                                        <?php
                                        $sum_reservasi = $rowReservasi->total_reservasi; // get data dari DB t_reservasi_wisata
                                        $total_saldo = $sum_reservasi - $sum_pengeluaran;
                                        ?>
                                        <tr>
                                            <th colspan="4"></th>
                                            <th>Biaya Reservasi</th>
                                            <td>: Rp. <?= number_format($rowReservasi->total_reservasi, 0) ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="4"></th>
                                            <th>Biaya Pengeluaran</th>
                                            <td>: Rp. <?= number_format($sum_pengeluaran, 0) ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="4"></th>
                                            <th>Total Sisa Biaya</th>
                                            <td>: Rp. <?= number_format($total_saldo, 0) ?></td>
                                        </tr>
                                    </tbody>
                                </table>
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
    <!-- Menambah jumlah form input -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
        //group add limit
        var maxGroup = 10;

        //add more fields group
        $(".addMore").click(function(){
            if($('body').find('.fieldGroup').length < maxGroup){
                var fieldHTML = '<div class="kelola-detail fieldGroup">'+$(".fieldGroupCopy").html()+'</div>';
                $('body').find('.fieldGroup:last').after(fieldHTML);
            }else{
                alert('Maksimal '+maxGroup+' pengeluaran yang boleh dibuat.');
            }
        });

        //remove fields group
        $("body").on("click",".remove",function(){
            $(this).parents(".fieldGroup").remove();
        });
    });
    </script>

    <!-- Konfirmasi Hapus -->
    <script>
        function konfirmasiHapus(event){
        jawab = true
        jawab = confirm('Yakin ingin menghapus? Data Pengajuan akan hilang permanen!')

        if (jawab){
            // alert('Lanjut.')
            return true
        }
        else{
            event.preventDefault()
            return false

        }
    }
    </script>

</body>
</html>