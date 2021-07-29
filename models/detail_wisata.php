<?php 
include '../app/database/koneksi.php';
session_start();

if($_GET['id_paket_wisata']){
    $_SESSION['id_paket_wisata'] = $_GET['id_paket_wisata'];
}
else if(!$_GET['id_paket_wisata' && !$_SESSION['id_paket_wisata']]){
    header("Location: view_kelola_wisata");
}

$sqlpaketSelect = 'SELECT * FROM t_paket_wisata
                LEFT JOIN t_lokasi ON t_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                LEFT JOIN t_asuransi ON t_paket_wisata.id_asuransi = t_asuransi.id_asuransi
                WHERE t_paket_wisata.id_paket_wisata = :id_paket_wisata';

$stmt = $pdo->prepare($sqlpaketSelect);
$stmt->execute(['id_paket_wisata' => $_GET['id_paket_wisata']]);
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
                    <a href="view_kelola_asuransi">
                    <span class="fas fa-heartbeat"></span>
                        <span>Kelola Asuransi</span></a>
                </li>
                <li>
                    <a href="view_kelola_wisata" class="paimon-active">
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
            <!-- Button Kembali -->
            <div>
            <button class="button-kelola-kembali"><span class="fas fa-arrow-left"></span>
            <a href="view_kelola_wisata" style="color: white;">Kembali</a></button>
            </div>

            <!-- Full Area -->
            <div class="full-area-kelola">
                <!-- Area A -->
                <div class="area-A">
                    <div class="card">
                        <div class="card-header">
                            <h2>Detail Data wisata</h2>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    <?php 
                                        foreach ($rowPaket as $paket) {
                                    ?>
                                    <!-- Form Create Fasilitas Wisata -->
                                    <div class="kelola-detail-paket">
                                        <div class="input-box">
                                            <span class="details">Deskripsi Lengkap Paket</span>
                                            <input type="text" value="<?=$paket->deskripsi_lengkap_paket?>" readonly>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Foto Paket Wisata</span>
                                            <br><img src="<?=$paket->foto_paket_wisata?>?<?php if ($status='nochange'){echo time();}?>" width="300px">
                                        </div>

                                        <!-- Select Lokasi Wisata -->
                                        <div class="input-box">
                                            <span class="details">Nama Lokasi</span>
                                            <?php
                                            $sqllokasiSelect = 'SELECT nama_lokasi FROM t_paket_wisata
                                                                LEFT JOIN t_lokasi ON t_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                                                                WHERE t_paket_wisata.id_paket_wisata = :id_paket_wisata';

                                            $stmt = $pdo->prepare($sqllokasiSelect);
                                            $stmt->execute(['id_paket_wisata' => $paket->id_paket_wisata]);
                                            $rowLokasi = $stmt->fetchAll();

                                            foreach ($rowLokasi as $lokasi) { ?>
                                            <div class="detail-isi">
                                                <i class="detail-logo-bitch fas fa-umbrella-beach"></i>
                                                <?=$lokasi->nama_lokasi?>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        
                                        <!-- Asuransi -->
                                        <div class="input-box">
                                            <span class="details">Biaya Asuransi</span>
                                            <?php
                                            $sqlasuransiSelect = 'SELECT biaya_asuransi FROM t_paket_wisata
                                                                LEFT JOIN t_asuransi ON t_paket_wisata.id_asuransi = t_asuransi.id_asuransi
                                                                WHERE t_paket_wisata.id_paket_wisata = :id_paket_wisata';

                                            $stmt = $pdo->prepare($sqlasuransiSelect);
                                            $stmt->execute(['id_paket_wisata' => $paket->id_paket_wisata]);
                                            $rowAsuransi = $stmt->fetchAll();

                                            foreach ($rowAsuransi as $asuransi) { ?>
                                            <div class="detail-isi">
                                                <i class="detail-logo-asuransi fas fa-heartbeat"></i>
                                                Rp. <?=number_format($asuransi->biaya_asuransi, 0)?>
                                            </div>
                                            <?php } ?>
                                        </div>

                                        <!-- Select data biaya fasilitas untuk menentukan total sesuai harga wisata yang terdapat di paket wisata -->
                                        <div class="input-box">
                                            <span class="details">Biaya Wisata</span>
                                            <?php
                                            $sqlfasilitasSelect = 'SELECT SUM(biaya_fasilitas) AS total_biaya_fasilitas
                                                                FROM t_fasilitas_wisata 
                                                                LEFT JOIN t_wisata ON t_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                                                                LEFT JOIN t_paket_wisata ON t_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                                                                WHERE t_paket_wisata.id_paket_wisata = :id_paket_wisata
                                                                AND t_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata';

                                            $stmt = $pdo->prepare($sqlfasilitasSelect);
                                            $stmt->execute(['id_paket_wisata' => $paket->id_paket_wisata]);
                                            $rowFasilitas = $stmt->fetchAll();

                                            foreach ($rowFasilitas as $fasilitas) { ?>
                                            <div class="detail-isi">
                                                <i class="detail-logo-duid fas fa-money-bill-wave"></i>
                                                Rp. <?=number_format($fasilitas->total_biaya_fasilitas, 0)?>
                                            </div>
                                            <?php } ?>
                                        </div>

                                        <!-- Select Wisata -->
                                        <div class="input-box">
                                            <span class="details">Wisata</span>
                                            <?php
                                            $sqlwisataSelect = 'SELECT judul_wisata FROM t_wisata
                                                            WHERE id_paket_wisata = :id_paket_wisata';

                                            $stmt = $pdo->prepare($sqlwisataSelect);
                                            $stmt->execute(['id_paket_wisata' => $paket->id_paket_wisata]);
                                            $rowWisata = $stmt->fetchAll();

                                            foreach ($rowWisata as $wisata) { ?>
                                            <div class="detail-isi">
                                                <i class="detail-logo-wisata fas fa-luggage-cart"></i>
                                                <?=$wisata->judul_wisata?>
                                            </div>
                                            <?php } ?>
                                        </div>

                                        <!-- Select seluruh data Fasilitas -->
                                        <div class="input-box">
                                            <span class="details">Fasilitas Wisata</span>
                                            <?php
                                            $sqlfasilitasSelect = 'SELECT * FROM t_fasilitas_wisata 
                                                                LEFT JOIN t_wisata ON t_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                                                                LEFT JOIN t_paket_wisata ON t_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                                                                WHERE t_paket_wisata.id_paket_wisata = :id_paket_wisata
                                                                AND t_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata';

                                            $stmt = $pdo->prepare($sqlfasilitasSelect);
                                            $stmt->execute(['id_paket_wisata' => $paket->id_paket_wisata]);
                                            $rowFasilitas = $stmt->fetchAll();

                                            foreach ($rowFasilitas as $fasilitas) { ?>
                                            <div class="detail-isi">
                                                <i class="detail-logo-fasilitas fas fa-truck-loading"></i>
                                                <?=$fasilitas->nama_fasilitas?><br>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <!-- End Form -->
                                    <?php } ?>
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
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
        // function sum() {
        //     var fasilitas = document.getELementById('by_fasilitas').value;
        //     var asuransi = document.getELementById('by_asuransi').value;

        //     var total_biaya_paket = parseInt(fasilitas) + parseInt(asuransi);

        //     // Format untuk number.
        //     var formatter = new Intl.NumberFormat('id-ID', {
        //         style: 'currency',
        //         currency: 'IDR',
        //     });

        //     if (!isNaN(total_biaya_paket)) {
        //         document.getElementById('biaya_paket').value = formatter.format(total_biaya_paket);
        //     }
        // }

        $(document).ready(function() {
            $("#by_fasilitas, #by_asuransi").keyup(function() {
                var fasilitas   = $("#by_fasilitas").val();
                var asuransi    = $("#by_asuransi").val();

                var total_biaya_paket = parseInt(fasilitas) + parseInt(asuransi);

                // Format untuk number.
                var formatter = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                });

                $("#biaya_paket").val(formatter.format(total_biaya_paket));
            });
        });
    </script>

</body>
</html>