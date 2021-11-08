<?php 
include '../app/database/koneksi.php';
session_start();

if (!$_SESSION['level_user']) {
    header('location: ../index?status=akses_terbatas');
} else {
    $id_user    = $_SESSION['id_user'];
    $level      = $_SESSION['level_user'];
}

if($_GET['id_lokasi']){
    $_SESSION['id_lokasi'] = $_GET['id_lokasi'];
}
else if(!$_GET['id_lokasi' && !$_SESSION['id_lokasi']]){
    header("Location: view_dashboard_user");
}

// Select Paket Wisata
$sqlpaketSelect = 'SELECT * FROM t_paket_wisata
                LEFT JOIN t_lokasi ON t_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                WHERE t_paket_wisata.id_lokasi = :id_lokasi';

$stmt = $pdo->prepare($sqlpaketSelect);
$stmt->execute(['id_lokasi' => $_GET['id_lokasi']]);
$rowPaket = $stmt->fetchAll();
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
            <h2><a href="view_dashboard_user" style="color: #fff"><span class="fas fa-atom"></span>
            <span>Wisata Bahari</span></a></h2>
        </div>
        <div class="sidebar-menu">
            <ul>
                <!-- Dahboard User -->
                <li>
                    <a href="view_dashboard_user" class="paimon-active">
                    <span class="icon fas fa-home"></span>
                        <span>Dashboard User</span></a>
                </li>
                <li>
                    <a href="view_reservasi_saya">
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
                <img src="../views/img/paimon-5.png" width="50px" height="50px" alt="">
                <div>
                    <h2>Selamat Datang</h2>
                    <span class="dashboard"><?php echo $_SESSION['nama_user']; ?></span>
                </div>
            </div>
            <?php } ?>
        </header>

        <!-- Main -->
        <main>
            <!-- Button Kembali -->
            <div>
            <button class="button-kelola-kembali"><span class="fas fa-arrow-left"></span>
                <a href="view_dashboard_user" style="color: white;">Kembali</a></button>
            </div>
            
            <!-- Full Area -->
            <div class="full-area-kelola">
                <!-- Area A -->
                <div class="area-A">
                    <div class="card-reservasi">

                        <div class="cards-reservasi">
                            <?php foreach ($rowPaket as $paket) { ?>
                            <?php $reservasidate = strtotime($paket->tgl_akhir_paket); ?>
                                <?php if ($paket->status_paket == "Aktif") { ?>
                                <div class="card-paket">
                                    <div class="paket-header">
                                        <span class="paket-foto">
                                            <!-- carousel -->
                                            <div class="pic-ctn">
                                                <img class="pic" src="<?=$paket->foto_paket_wisata?>?<?php if ($status='nochange'){echo time();}?>" width="100%" height="200px">
                                                
                                                <!-- Select Wisata -->
                                                <?php
                                                $sqlpaketSelect = 'SELECT * FROM t_wisata
                                                                LEFT JOIN t_paket_wisata ON t_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                                                                WHERE t_paket_wisata.id_paket_wisata = :id_paket_wisata';

                                                $stmt = $pdo->prepare($sqlpaketSelect);
                                                $stmt->execute(['id_paket_wisata' => $paket->id_paket_wisata]);
                                                $rowWisata = $stmt->fetchAll();

                                                foreach ($rowWisata as $wisata) { ?>
                                                <img class="pic" src="<?=$wisata->foto_wisata?>" width="100%" height="200px">
                                                <?php } ?>
                                            </div>
                                        </span>
                                    </div>
                                    <div class="paket-body">
                                        <h3 class="paket-judul">
                                            <?=$paket->nama_paket_wisata?>
                                        </h3>

                                        <!-- Lokasi -->
                                        <?php 
                                        $sqlpaketSelect = 'SELECT * FROM t_lokasi
                                                        WHERE id_lokasi = :id_lokasi';

                                        $stmt = $pdo->prepare($sqlpaketSelect);
                                        $stmt->execute(['id_lokasi' => $_GET['id_lokasi']]);
                                        $rowLokasi = $stmt->fetchAll();

                                        foreach ($rowLokasi as $lokasi) {
                                        ?>
                                        <h4 class="paket-text">
                                            <i class="fas fa-map-marked-alt"></i>
                                            <?=$lokasi->nama_lokasi?>
                                        </h4>
                                        <?php } ?>

                                        <!-- Batas Paket Wisata -->
                                        <div class="flex-container">
                                            <div class="flex-item-left">
                                                <small style="font-weight:bold;">
                                                    Batas Pemesanan:
                                                </small>
                                            </div>
                                            <div class="flex-item-right">
                                                <small style="font-weight:normal;">
                                                    <?= strftime('%d %B %Y', $reservasidate); ?>
                                                </small>
                                            </div>
                                        </div>
                                        <h4 class="paket-batas">
                                            <?php
                                            // tanggal sekarang
                                            $tgl_sekarang = date("Y-m-d");
                                            // tanggal pembuatan batas pemesanan paket wisata
                                            $tgl_awal = $paket->tgl_awal_paket;
                                            // tanggal berakhir pembuatan batas pemesanan paket wisata
                                            $tgl_akhir = $paket->tgl_akhir_paket;
                                            // jangka waktu + 365 hari
                                            $jangka_waktu = strtotime($tgl_akhir, strtotime($tgl_awal));
                                            //tanggal expired
                                            $tgl_exp = date("Y-m-d",$jangka_waktu);

                                            if ($tgl_sekarang >= $tgl_exp) { ?>
                                                <i class="fas fa-tag" style="color: #d43334;"></i>
                                                Sudah Tidak Berlaku.
                                            <?php } else { ?>
                                                <i class="fas fa-tag" style="color: #0ec7a3;"></i>
                                                Masih dalam jangka waktu.
                                            <?php }?>
                                        </h4>

                                        <!-- Wisata -->
                                        <?php
                                        $sqlpaketSelect = 'SELECT * FROM t_wisata
                                                        LEFT JOIN t_paket_wisata ON t_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                                                        WHERE t_paket_wisata.id_paket_wisata = :id_paket_wisata';

                                        $stmt = $pdo->prepare($sqlpaketSelect);
                                        $stmt->execute(['id_paket_wisata' => $paket->id_paket_wisata]);
                                        $rowWisata = $stmt->fetchAll();

                                        foreach ($rowWisata as $wisata) { ?>
                                            <!-- Jadwal Wisata -->
                                            <div class="paket-jadwal">
                                                <span class="jadwal-wisata" style="font-size: 1rem;">
                                                    <?=$wisata->jadwal_wisata?>
                                                </span>
                                            </div>

                                            <!-- Judul Wisata -->
                                            <li class="paket-isi" style="font-weight:normal; font-size: 1.2rem;">
                                                <span>Wisata: </span><?=$wisata->judul_wisata?>
                                            </li>

                                            <!-- Select Fasilitas -->
                                            <?php
                                            $sqlviewfasilitas = 'SELECT * FROM t_fasilitas_wisata
                                                                LEFT JOIN t_kerjasama ON t_fasilitas_wisata.id_kerjasama = t_kerjasama.id_kerjasama
                                                                LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                                                                LEFT JOIN t_wisata ON t_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                                                                LEFT JOIN t_paket_wisata ON t_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                                                                WHERE t_paket_wisata.id_paket_wisata = :id_paket_wisata
                                                                AND t_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata
                                                                AND t_wisata.id_wisata = :id_wisata';

                                            $stmt = $pdo->prepare($sqlviewfasilitas);
                                            $stmt->execute(['id_wisata' => $wisata->id_wisata,
                                                            'id_paket_wisata' => $paket->id_paket_wisata]);
                                            $rowFasilitas = $stmt->fetchAll();

                                            foreach ($rowFasilitas as $Fasilitas) { ?> 
                                                <i class="paket-isi fas fa-chevron-circle-right" style="color: #fba442;"></i>
                                                <?=$Fasilitas->pengadaan_fasilitas?><br>
                                            <?php } ?>
                                        <?php } ?>

                                        <!-- Biaya Dari Hitungan Fasilitas-->
                                        <?php
                                        $sqlfasilitasSelect = 'SELECT SUM(biaya_kerjasama) 
                                                            AS total_biaya_fasilitas,
                                                                pengadaan_fasilitas,
                                                                biaya_kerjasama,
                                                                biaya_asuransi
                                                            FROM t_fasilitas_wisata
                                                            LEFT JOIN t_kerjasama ON t_fasilitas_wisata.id_kerjasama = t_kerjasama.id_kerjasama
                                                            LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                                                            LEFT JOIN t_wisata ON t_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                                                            LEFT JOIN t_paket_wisata ON t_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                                                            LEFT JOIN t_lokasi ON t_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                                                            LEFT JOIN t_asuransi ON t_paket_wisata.id_asuransi = t_asuransi.id_asuransi
                                                            WHERE t_paket_wisata.id_paket_wisata = :id_paket_wisata
                                                            AND t_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata';

                                        $stmt = $pdo->prepare($sqlfasilitasSelect);
                                        $stmt->execute(['id_paket_wisata' => $paket->id_paket_wisata]);
                                        $rowFasilitas = $stmt->fetchAll();

                                        foreach ($rowFasilitas as $fasilitas) { 
                                        
                                        $asuransi       = $fasilitas->biaya_asuransi;
                                        $wisata         = $fasilitas->total_biaya_fasilitas;
                                        $total_paket    = $asuransi + $wisata;
                                        ?>
                                        <h4 class="paket-harga">
                                            Rp. <?=number_format($total_paket, 0)?>
                                        </h4>
                                        <?php } ?>
                                        
                                        <!-- Rincian Reservasi -->
                                        <?php
                                        // tanggal sekarang
                                        $tgl_sekarang = date("Y-m-d");
                                        // tanggal pembuatan batas pemesanan paket wisata
                                        $tgl_awal = $paket->tgl_awal_paket;
                                        // tanggal berakhir pembuatan batas pemesanan paket wisata
                                        $tgl_akhir = $paket->tgl_akhir_paket;
                                        // jangka waktu + 365 hari
                                        $jangka_waktu = strtotime($tgl_akhir, strtotime($tgl_awal));
                                        //tanggal expired
                                        $tgl_exp = date("Y-m-d",$jangka_waktu);

                                        if ($tgl_sekarang >= $tgl_exp) { ?>
                                            <div>
                                                <button class="btn-paket-tutup">
                                                <i class="fas fa-exclamation"></i>
                                                Reservasi Ditutup
                                            </div>
                                        <?php } else { ?>
                                            <div>
                                                <button class="btn-detail-paket">
                                                <a href="view_detail_lokasi_wisata?id_paket_wisata=<?=$paket->id_paket_wisata?>" style="color: white;">Rincian Reservasi</a></button>
                                            </div>
                                        <?php }?>
                                    </div>
                                </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>

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