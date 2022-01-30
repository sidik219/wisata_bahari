<?php 
include '../app/database/koneksi.php';
session_start();

if (!$_SESSION['level_user']) {
    header('location: ../index?status=akses_terbatas');
} else {
    $id_user    = $_SESSION['id_user'];
    $level      = $_SESSION['level_user'];
}

$id_paket_wisata = $_GET['id_paket_wisata'];
$id_status_reservasi = 1;
$keterangan_reservasi = '-';

$defaultpic = "../views/img/image_default.jpg";

// Select All Data User
$sqluserSelect = "SELECT * FROM t_user
                    WHERE id_user = :id_user";

$stmt = $pdo->prepare($sqluserSelect);
$stmt->execute(['id_user' => $_SESSION['id_user']]);
$rowUser = $stmt->fetch();

// Select Paket Wisata
$sqlpaketSelect = 'SELECT * FROM t_paket_wisata
                LEFT JOIN t_lokasi ON t_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                LEFT JOIN t_asuransi ON t_paket_wisata.id_asuransi = t_asuransi.id_asuransi
                WHERE t_paket_wisata.id_paket_wisata = :id_paket_wisata';

$stmt = $pdo->prepare($sqlpaketSelect);
$stmt->execute(['id_paket_wisata' => $_GET['id_paket_wisata']]);
$rowPaket = $stmt->fetchAll();

if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Simpan') {
        $id_paket_wisata            = $_POST['id_paket_wisata'];
        $tgl_reservasi              = $_POST['tgl_reservasi'];
        $jumlah_reservasi           = $_POST['jumlah_peserta'];
        $total_reservasi            = $_POST['total_reservasi'];
        $nama_bank_wisatawan        = $_POST['nama_bank_wisatawan'];
        $nama_rekening_wisatawan    = $_POST['nama_rekening_wisatawan'];
        $nomor_rekening_wisatawan   = $_POST['nomor_rekening_wisatawan'];

        //var_dump($jumlah_donasi); exit();
        $tanggal_sekarang = date ('Y-m-d H:i:s', time());
        $tanggal_pesan = date('Y-m-d', time());

        $sqlreservasiCreate = "INSERT INTO t_reservasi_wisata (id_user,
                                                            id_paket_wisata,
                                                            id_status_reservasi,
                                                            tgl_reservasi,
                                                            tanggal_pesan,
                                                            jumlah_reservasi,
                                                            total_reservasi,
                                                            keterangan_reservasi,
                                                            nama_bank_wisatawan,
                                                            nama_rekening_wisatawan,
                                                            nomor_rekening_wisatawan,
                                                            update_terakhir)
                                VALUES (:id_user,
                                        :id_paket_wisata,
                                        :id_status_reservasi,
                                        :tgl_reservasi,
                                        :tanggal_pesan,
                                        :jumlah_reservasi,
                                        :total_reservasi,
                                        :keterangan_reservasi,
                                        :nama_bank_wisatawan,
                                        :nama_rekening_wisatawan,
                                        :nomor_rekening_wisatawan,
                                        :update_terakhir)";
        
        $stmt = $pdo->prepare($sqlreservasiCreate);
        $stmt->execute(['id_user' => $id_user,
                        'id_paket_wisata' => $id_paket_wisata,
                        'id_status_reservasi' => $id_status_reservasi,
                        'tgl_reservasi' => $tgl_reservasi,
                        'tanggal_pesan' => $tanggal_pesan,
                        'jumlah_reservasi' => $jumlah_reservasi,
                        'total_reservasi' => $total_reservasi,
                        'keterangan_reservasi' => $keterangan_reservasi,
                        'nama_bank_wisatawan' => $nama_bank_wisatawan,
                        'nama_rekening_wisatawan' => $nama_rekening_wisatawan,
                        'nomor_rekening_wisatawan' => $nomor_rekening_wisatawan,
                        'update_terakhir' => $tanggal_sekarang]);
        
        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
            header("Location: view_reservasi_saya?status=tambahGagal");
        } else {
            header("Location: view_reservasi_saya?status=tambahBerhasil");
        }
        
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
            <h2><a href="view_dashboard_user" style="color: #fff"><span class="fas fa-atom"></span>
            <span>Wisata Bahari</span></a></h2>
        </div>

        <!-- Hak Akses -->
        <?php if ($level == 1) { ?>
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
            <!-- Button Kembali -->
            <div>
            <button class="button-kelola-kembali"><span class="fas fa-arrow-left"></span>
                <a href="javascript:history.go(-1)" style="color: white;">Kembali</a></button>
            </div>

            <!-- Full Area -->
            <div class="full-area-reservasi">
                <!-- Area A -->
                <div class="area-A">
                    <div class="card">
                        <div class="card-header">
                            <h3>Data Reservasi wisata</h3>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    <?php 
                                    foreach ($rowPaket as $paket) {
                                    ?>
                                    <!-- Hidden Output Id Lokasi -->
                                    <input type="hidden" name="id_lokasi" value="<?=$paket->id_lokasi?>">

                                    <!-- Hidden Output Paket Wisata -->
                                    <input type="hidden" name="id_paket_wisata" value="<?=$paket->id_paket_wisata?>">

                                    <!-- Hidden Output Total Reservasi Wisata -->
                                    <input type="hidden" name="total_reservasi" id="total_reservasi" value="">

                                    <!-- Form Create Reservasi Wisata -->
                                    <div class="kelola-detail-reservasi">
                                        <div class="input-box">
                                            <span class="details">Lokasi</span>
                                            <?php
                                            $sqllokasiSelect = 'SELECT nama_lokasi FROM t_paket_wisata
                                                                LEFT JOIN t_lokasi ON t_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                                                                WHERE t_paket_wisata.id_paket_wisata = :id_paket_wisata';

                                            $stmt = $pdo->prepare($sqllokasiSelect);
                                            $stmt->execute(['id_paket_wisata' => $paket->id_paket_wisata]);
                                            $rowLokasi = $stmt->fetchAll();

                                            foreach ($rowLokasi as $lokasi) { ?>
                                            <input type="text" value="<?=$lokasi->nama_lokasi?>" readonly>
                                            <?php } ?>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Tanggal Reservasi</span>
                                            <input type="date" name="tgl_reservasi" id="tgl_reservasi" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Jumlah Peserta</span>
                                            <input type="number" name="jumlah_peserta" id="jumlah_peserta" value="0" min="1" max='200' onchange="myFunction()" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Nama Bank Wisatawan</span>
                                            <input type="text" name="nama_bank_wisatawan" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Nama Rekening Wisatawan</span>
                                            <input type="text" name="nama_rekening_wisatawan" required>
                                        </div>
                                        <div class="input-box">
                                            <span class="details">Nomor Rekening Wisatawan</span>
                                            <input type="number" name="nomor_rekening_wisatawan" required>
                                        </div>
                                        
                                        <hr class="jarak">
                                        <!-- Deskripsi Rincian Reservasi Wisata -->
                                        <div class="input-box">
                                            <h4>Rincian <?=$paket->nama_paket_wisata?>:</h4>
                                        </div>
                                        
                                        <!-- Lokasi -->
                                        <div class="input-box">
                                            <span class="details"><b>Lokasi:</b></span>
                                            <?php
                                            $sqllokasiSelect = 'SELECT nama_lokasi FROM t_paket_wisata
                                                                LEFT JOIN t_lokasi ON t_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                                                                WHERE t_paket_wisata.id_paket_wisata = :id_paket_wisata';

                                            $stmt = $pdo->prepare($sqllokasiSelect);
                                            $stmt->execute(['id_paket_wisata' => $paket->id_paket_wisata]);
                                            $rowLokasi = $stmt->fetchAll();

                                            foreach ($rowLokasi as $lokasi) { ?>
                                            <i class="detail-reservasi-bitch fas fa-umbrella-beach"></i>
                                            <?=$lokasi->nama_lokasi?><br>
                                            <?php } ?>
                                        </div>

                                        <!-- Wisata -->
                                        <div class="input-box">
                                            <!-- <span class="details"></span> -->
                                            <?php
                                            $sqlwisataSelect = 'SELECT * FROM t_wisata
                                                                LEFT JOIN t_paket_wisata ON t_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                                                                WHERE t_paket_wisata.id_paket_wisata = :id_paket_wisata';

                                            $stmt = $pdo->prepare($sqlwisataSelect);
                                            $stmt->execute(['id_paket_wisata' => $paket->id_paket_wisata]);
                                            $rowWisata = $stmt->fetchAll();

                                            foreach ($rowWisata as $wisata) { ?>
                                                <!-- Jadwal Wisata -->
                                                <br>
                                                <span class="jadwal-wisata">
                                                    <?=$wisata->jadwal_wisata?>
                                                </span><br><br>
                                                
                                                <!-- Judul Wisata -->
                                                <span>
                                                    <i class="detail-reservasi-wisata fas fa-luggage-cart"></i>
                                                    <b>Wisata: </b>
                                                    <?=$wisata->judul_wisata?>
                                                </span><br>

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
                                                    <!-- <i class="detail-paket-fasilitas fas fa-truck-loading"></i> -->
                                                    <span>
                                                        <i class="fas fa-chevron-circle-right" style="color: #fba442;"></i>
                                                        <?=$Fasilitas->pengadaan_fasilitas?>
                                                    </span><br>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>

                                        <!-- Asuransi -->
                                        <div class="input-box">
                                            <span class="details">
                                                <b>Asuransi: </b><?=$paket->nama_asuransi?>
                                            </span>
                                            <?php
                                            $sqlasuransiSelect = 'SELECT biaya_asuransi FROM t_paket_wisata
                                                                LEFT JOIN t_asuransi ON t_paket_wisata.id_asuransi = t_asuransi.id_asuransi
                                                                WHERE t_paket_wisata.id_paket_wisata = :id_paket_wisata';

                                            $stmt = $pdo->prepare($sqlasuransiSelect);
                                            $stmt->execute(['id_paket_wisata' => $paket->id_paket_wisata]);
                                            $rowAsuransi = $stmt->fetchAll();

                                            foreach ($rowAsuransi as $asuransi) { ?>
                                            <!-- Hidden Output Total Biaya Fasilitas -->
                                            <input type="hidden" id="biaya_asuransi" value="<?=$asuransi->biaya_asuransi?>">

                                            <i class="detail-reservasi-asuransi fas fa-heartbeat"></i>
                                            Rp. <?=number_format($asuransi->biaya_asuransi, 0)?><br>
                                            <?php } ?>
                                        </div>

                                        <!-- Biaya Paket Di Dapat Dari Total Penggunaan Dari Fasilitas -->
                                        <div class="input-box">
                                            <span class="details"><b>Total Paket Wisata:</b></span>
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
                                            <!-- Hidden Output Total Biaya Fasilitas -->
                                            <input type="hidden" id="total_paket_wisata" value="<?=$fasilitas->total_biaya_fasilitas?>">

                                            <i class="detail-reservasi-duid fas fa-money-bill-wave"></i>
                                            Rp. <?=number_format($total_paket, 0)?><br>
                                            <?php } ?>
                                        </div>

                                        <hr class="jarak">
                                        <!-- Deskripsi Rincian Metode Pembayaran Reservasi Wisata -->
                                        <div class="input-box">
                                            <h4>Metode Pembayaran</h4>
                                            <span>Pilihan untuk lokasi:
                                                <b style="color: #fba442;"><?=$paket->nama_lokasi?></b></span>
                                            
                                            <p><br><i class="detail-reservasi-metode fas fa-chevron-circle-right"></i>
                                            Bank Transfer (Konfirmasi Manual)
                                            <br><small style="color: red;">Harap cek email anda dan upload bukti transfer agar reservasi wisata segera diproses pengelola lokasi.</small>
                                        </div>

                                        <div class="input-box">
                                            <div class="divTable">
                                                <div class="divTableBody">
                                                    <div class="divTableRow">
                                                        <div class="divTableCell">
                                                            <i class="detail-reservasi-bank fas fa-hashtag"></i>    
                                                            Nama Bank</div>
                                                        <div class="divTableCell">: <?=$paket->nama_bank?></div>
                                                    </div>
                                                    <div class="divTableRow">
                                                        <div class="divTableCell">
                                                            <i class="detail-reservasi-nama fas fa-user-tie"></i>    
                                                            Nama Rekening</div>
                                                        <div class="divTableCell">: <?=$paket->nama_rekening?></div>
                                                    </div>
                                                    <div class="divTableRow">
                                                        <div class="divTableCell">
                                                            <i class="detail-reservasi-nomor fas fa-university"></i>    
                                                            Nomor Rekening</div>
                                                        <div class="divTableCell">: <?=$paket->nomor_rekening?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- DivTable.com -->
                                        </div>
                                    </div>
                                    <div class="button-kelola-form">
                                        <input type="submit" name="submit" value="Simpan">
                                    </div>
                                <!-- End Form 1 -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Area B -->
                <div class="area-B">
                    <div class="card">
                        <div class="card-header">
                            <?php
                            $sqlpaketSelect = 'SELECT nama_paket_wisata FROM t_paket_wisata
                                                WHERE t_paket_wisata.id_paket_wisata = :id_paket_wisata';

                            $stmt = $pdo->prepare($sqlpaketSelect);
                            $stmt->execute(['id_paket_wisata' => $paket->id_paket_wisata]);
                            $rowPaket = $stmt->fetchAll();

                            foreach ($rowPaket as $paket) { ?>
                            <h3><?=$paket->nama_paket_wisata?></h3>
                            <?php } ?>
                        </div>

                        <div class="card-body">
                            <div class="list-lokasi">
                                <input type="text" id="deskripsi_wisata" value="Peserta: " style="border: none; font-size: 1rem" disabled>
                            </div>
                        </div>
                    </div><br>
                    <div class="card">
                        <div class="card-header">
                            <h3>Total:</h3>
                        </div>

                        <div class="card-body">
                            <div class="list-lokasi">
                                <input type="text" id="total" value="" style="border: none; font-size: 1rem" disabled>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                </form>
                <!-- End Form 2 -->
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
    
    <!-- All Javascript -->
    <!-- Menentukan total harga reservasi wisata -->
    <!--(づ｡◕‿‿◕｡)づ pika pika pikachu (づ｡◕‿‿◕｡)づ-->
    <script>
        function myFunction() {
            var jumlah_peserta  = document.getElementById("jumlah_peserta").value;
            var paket_wisata    = document.getElementById("total_paket_wisata").value;
            var asuransi        = document.getElementById("biaya_asuransi").value;

            var deskripsi       = jumlah_peserta;
            var reservasi       = parseInt(asuransi) + parseInt(paket_wisata); // asuransi 20.0000 + paket wisata 1.119.997
            var sub_total       = jumlah_peserta * reservasi; //5 x 1.119.997 = total reservasi 3.750.000
            var hasil           = sub_total;

            // Format untuk number.
            var formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
            });

            document.getElementById("deskripsi_wisata").value = "Peserta: "+ deskripsi;
            document.getElementById("total_reservasi").value = hasil; //total dari total_reservasi * donasi
            document.getElementById("total").value = formatter.format(hasil); //total dari total_reservasi * donasi
            // document.write(hasil);
            // console.log(hasil);
            
        }
    </script>
    <!-- Pembatasan Date Reservasi -->
    <script>
        var today = new Date().toISOString().split('T')[0];
        document.getElementsByName("tgl_reservasi")[0].setAttribute('min', today);
    </script>

</body>
</html>