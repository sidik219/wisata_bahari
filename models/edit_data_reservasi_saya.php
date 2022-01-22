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

$id_reservasi_wisata = $_GET['id_reservasi_wisata'];

$sqlreservasiSelect = 'SELECT * FROM t_reservasi_wisata
                        LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                        LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
                        LEFT JOIN t_paket_wisata ON t_reservasi_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                        LEFT JOIN t_status_reservasi ON t_reservasi_wisata.id_status_reservasi = t_status_reservasi.id_status_reservasi
                        WHERE t_reservasi_wisata.id_reservasi_wisata = :id_reservasi_wisata
                        ORDER BY update_terakhir DESC';

$stmt = $pdo->prepare($sqlreservasiSelect);
$stmt->execute(['id_reservasi_wisata' => $_GET['id_reservasi_wisata']]);
$rowReservasi = $stmt->fetchAll();


if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Simpan') {
        //Image upload
        $randomstring = substr(md5(rand()), 0, 7);

        if ($_FILES["image_uploads"]["size"] == 0) {
            $bukti_reservasi = $reservasi->bukti_reservasi;
            $pic = "&none=";
        } else if (isset($_FILES['image_uploads'])) {
            if (($reservasi->bukti_reservasi == $defaultpic) || (!$reservasi->bukti_reservasi)){
                $target_dir  = "../views/img/foto_wisata/";
                $bukti_reservasi = $target_dir .'WIS_'. $randomstring .'.jpg';
                move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $bukti_reservasi);
                $pic = "&new=";
            } else if (isset($reservasi->bukti_reservasi)){
                $bukti_reservasi = $reservasi->bukti_reservasi;
                unlink($reservasi->bukti_reservasi);
                move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $reservasi->bukti_reservasi);
                $pic = "&replace=";
            }
        }
        //---image upload end

        $sqlreservasiUpdate = "UPDATE t_reservasi_wisata
                            SET bukti_reservasi = :bukti_reservasi
                            WHERE id_reservasi_wisata = :id_reservasi_wisata";

        $stmt = $pdo->prepare($sqlreservasiUpdate);
        $stmt->execute(['bukti_reservasi' => $bukti_reservasi,
                        'id_reservasi_wisata' => $id_reservasi_wisata]);

        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
            // header("Location: edit_data_reservasi_saya?status=updateGagal");
        } else {
            header("Location: view_reservasi_saya?status=updateBerhasil");
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
                            <h3>Rincian Pembayaran</h3>
                        </div>

                        <div class="card-body">
                            <div class="table-portable">
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    <?php 
                                    foreach ($rowReservasi as $reservasi) {

                                    $reservasidate = strtotime($reservasi->tgl_reservasi);
                                    ?>
                                    <div class="kelola-detail-reservasi">
                                        <!-- Deskripsi Rincian Metode Pembayaran Reservasi Wisata -->
                                        <div class="input-box">
                                            <h4>Metode Pembayaran</h4>
                                            <span>Pilihan untuk lokasi:
                                                <b style="color: #fba442;"><?=$reservasi->nama_lokasi?></b></span>
                                            
                                            <p><br><i class="detail-reservasi-metode fas fa-chevron-circle-right"></i>
                                            Bank Transfer (Konfirmasi Manual)
                                            <br><small style="color: red;">Harap cek email anda dan upload bukti transfer agar reservasi wisata segera diproses pengelola lokasi.</small>
                                        </div>

                                        <hr class="jarak">
                                        <div class="input-box">
                                            <div class="divTable">
                                                <div class="divTableBody">
                                                    <div class="divTableRow">
                                                        <div class="divTableCell">
                                                            <i class="detail-reservasi-bank fas fa-id-card-alt"></i>    
                                                            ID User</div>
                                                        <div class="divTableCell">: <?=$reservasi->id_user?></div>
                                                    </div>
                                                    <div class="divTableRow">
                                                        <div class="divTableCell">
                                                            <i class="detail-reservasi-nama fas fa-user-tie"></i>    
                                                            Nama User</div>
                                                        <div class="divTableCell">: <?=$reservasi->nama_user?></div>
                                                    </div>
                                                    <div class="divTableRow">
                                                        <div class="divTableCell">
                                                            <i class="detail-reservasi-bitch fas fa-umbrella-beach"></i>
                                                            Lokasi Reservasi Wisata</div>
                                                        <div class="divTableCell">: <?=$reservasi->nama_lokasi?></div>
                                                    </div>
                                                    <div class="divTableRow">
                                                        <div class="divTableCell">
                                                            <i class="detail-reservasi-tanggal far fa-calendar-alt"></i>
                                                            Tanggal Reservasi</div>
                                                        <div class="divTableCell">: <?=strftime('%A, %d %B %Y', $reservasidate);?></div>
                                                    </div>
                                                    <div class="divTableRow">
                                                        <div class="divTableCell">
                                                            <i class="detail-reservasi-peserta fas fa-users"></i>
                                                            Jumlah Peserta</div>
                                                        <div class="divTableCell">: <?=$reservasi->jumlah_reservasi?></div>
                                                    </div>
                                                    <div class="divTableRow">
                                                        <div class="divTableCell">
                                                            <i class="detail-reservasi-total fas fa-money-bill-wave"></i>
                                                            Total Reservasi</div>
                                                        <div class="divTableCell">: Rp. <?=number_format($reservasi->total_reservasi, 0)?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- DivTable.com -->
                                        </div>

                                        <hr class="jarak">
                                        <div class="input-box">
                                            <div class="divTable">
                                                <div class="divTableBody">
                                                    <div class="divTableRow">
                                                        <div class="divTableCell">
                                                            <i class="detail-reservasi-bank fas fa-hashtag"></i>    
                                                            Nama Bank</div>
                                                        <div class="divTableCell">: <?=$reservasi->nama_bank?></div>
                                                    </div>
                                                    <div class="divTableRow">
                                                        <div class="divTableCell">
                                                            <i class="detail-reservasi-nama fas fa-user-tie"></i>    
                                                            Nama Rekening</div>
                                                        <div class="divTableCell">: <?=$reservasi->nama_rekening?></div>
                                                    </div>
                                                    <div class="divTableRow">
                                                        <div class="divTableCell">
                                                            <i class="detail-reservasi-nomor fas fa-university"></i>    
                                                            Nomor Rekening</div>
                                                        <div class="divTableCell">: <?=$reservasi->nomor_rekening?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- DivTable.com -->
                                        </div>
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
                            <h3>Upload Bukti Transfer Reservasi Wisata</h3>
                        </div>

                        <div class="card-body">
                            <div class="list-lokasi">
                                <label class="button-kelola-reservasi center" for='image_uploads'>
                                    <span class="fas fa-camera"></span>
                                    Upload File
                                </label>
                                <input type="file" name="image_uploads" id="image_uploads" accept=".jpg, .jpeg, .png" style="display: none;" onchange="readURL(this);" required>
                            </div>
                            <div class="list-lokasi">
                                <div class="input-box">
                                    <img src="#" id="preview" width="100px" alt="Preview Gambar"/>

                                    <a href="<?=$reservasi->bukti_reservasi?>">
                                        <img id="oldpic" src="<?=$reservasi->bukti_reservasi?>" width="100%" height="288px" <?php if($reservasi->bukti_reservasi == NULL) echo "style='display: none;'"; ?>></a>
                                    <br>

                                    <small class="text-muted">
                                        <?php if ($reservasi->bukti_reservasi == NULL) {
                                            echo "Bukti transfer belum diupload<br>Format .jpg .jpeg .png";
                                        } else {
                                            echo "Klik gambar untuk memperbesar";
                                        }

                                        ?>
                                    </small>

                                    <script>
                                        const actualBtn = document.getElementById('image_uploads');
                                        const fileChosen = document.getElementById('file-input-label');

                                        actualBtn.addEventListener('change', function() {
                                            fileChosen.innerHTML = '<b>File dipilih :</b> ' + this.files[0].name
                                        })
                                        window.onload = function() {
                                            document.getElementById('preview').style.display = 'none';
                                        };

                                        function readURL(input) {
                                            //Validasi Size Upload Image
                                            if (input.files[0].size > 2000000) { // ini untuk ukuran 800KB, 2000000 untuk 2MB.
                                                alert("Maaf, Ukuran File Terlalu Besar. !Maksimal Upload 2MB");
                                                input.value = "";
                                            };

                                            if (input.files && input.files[0]) {
                                                var reader = new FileReader();
                                                document.getElementById('oldpic').style.display = 'none';
                                                reader.onload = function(e) {
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
                            <div class="list-lokasi">
                                <label class="button-kelola-kembali center" for='submit'>
                                    Simpan
                                </label>
                                <input type="submit" name="submit" value="Simpan" id="submit" style="display: none;">
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
    <!-- Jquery Plugin -->
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>

</body>
</html>