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
$rowUser2 = $stmt->fetch();

// Lokasi
$sqllokasiSelect = "SELECT * FROM t_lokasi
                    LEFT JOIN t_wilayah ON t_lokasi.id_wilayah = t_wilayah.id_wilayah";

$stmt = $pdo->prepare($sqllokasiSelect);
$stmt->execute();
$rowLokasi = $stmt->fetch();

if($level == 2){
    $id_lokasi          = $_SESSION['id_lokasi_dikelola'];
    $extra_query        = " AND t_lokasi.id_lokasi = $id_lokasi ";
    $extra_query_noand  = " t_lokasi.id_lokasi = $id_lokasi ";
}
else if($level == 3){
    $id_wilayah         = $_SESSION['id_wilayah_dikelola'];
    $extra_query        = " AND t_lokasi.id_wilayah = $id_wilayah ";
    $extra_query_noand  = " t_lokasi.id_wilayah = $id_wilayah ";
}
else if($level == 4){
    $extra_query        = "  ";
    $extra_query_noand  = " 1 ";
}

// Umum
$sqltahunterawal = 'SELECT MIN(tgl_awal_paket) AS tahun_terawal FROM t_paket_wisata
                    LEFT JOIN t_lokasi ON t_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                    WHERE status_paket = "Aktif" '. $extra_query. ' LIMIT 1';

$stmt = $pdo->prepare($sqltahunterawal);
$stmt->execute();
$tahunterawal = $stmt->fetch();

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
    <!-- Daterangepicker -->
    <script src="../views/js/daterangepicker/jquery.min.js"></script>
    <script src="../views/js/daterangepicker/moment.min.js"></script>
    <script src="../views/js/daterangepicker/daterangepicker.min.js"></script>
    <script src="../views/js/loadingoverlay.min.js"></script>
    <script src="../views/js/jquery.tablesorter.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../views/js/daterangepicker/daterangepicker.css" />
    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/img/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicons/favicon.ico">
    <link rel="manifest" href="../assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="../assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">
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
                    <a href="kelola_laporan_periode" class="paimon-active">
                    <span class="icon far fa-file-alt"></span>
                        <span>Kelola Laporan Periode</span></a>
                </li>
                <li>
                    <a href="view_kelola_pengajuan">
                    <span class="fas fa-file-signature"></span>
                        <span>Kelola Pengajuan</span></a>
                </li>
                <li>
                    <a href="view_kelola_reservasi_wisata">
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
                    <a href="kelola_laporan_periode" class="paimon-active">
                    <span class="icon far fa-file-alt"></span>
                        <span>Kelola Laporan Periode</span></a>
                </li>
                <li>
                    <a href="view_kelola_pengajuan">
                    <span class="fas fa-file-signature"></span>
                        <span>Kelola Pengajuan</span></a>
                </li>
                <li>
                    <a href="view_kelola_reservasi_wisata">
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
    <div class="main-content" id="clientPrintContent">
        <!-- Header -->
        <header class="print-hide">
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
            <div class="print-hide">
            <button class="button-kelola-kembali"><span class="fas fa-arrow-left"></span>
                <a href="kelola_laporan_periode" style="color: white;">Kembali</a></button>
            </div>

            <div class="print-hide">
                <h3><span>Laporan Paket Wisata</span></h3>
                <small>
                    <i class="nav-icon text-info fas fa-info-circle"></i> 
                    Daftar laporan paket wisata yang sudah dibuat
                </small>
            </div>

            <!-- Untuk Atur Tanggal Periode -->
            <div class="print-hide" style="margin-top: 2rem">
                <div>Periode:</div>
            </div>
            <div class="print-hide">
                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 10px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down"></i>
                </div>

                <script type="text/javascript">
                    $(function() {
                        moment.locale('id')

                        var start = moment().subtract(29, 'days');
                        var end = moment().add(23, 'hours');
                        var tahunterawal = moment(`<?=$tahunterawal->tahun_terawal?>`).format('DD-MM-YYYY');     
                        
                        function cb(start, end) {
                        starto = start.format('Y-MM-DD');
                        endo = end.format('Y-MM-DD');

                            $('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY')); //apply date range to element
                            
                            updateTabelLaporan(starto, endo)
                            
                            $('#periode_laporan').text(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'))
                        }

                        $('#reportrange').daterangepicker({
                            "autoApply": true,
                            locale: 'id',
                            language: 'id',
                            startDate: start,
                            endDate: end,
                            ranges: {
                            'Hari ini': [moment(), moment().add(23, 'hours')],                              
                            '7 hari terakhir': [moment().subtract(6, 'days'), moment().add(23, 'hours')],
                            'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
                            'Bulan lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                            'Tahun ini': [moment().startOf('year'), moment().endOf('year')],
                            'Tahun lalu': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                            'Tampilkan semua': [tahunterawal, moment().add(23, 'hours')]
                            }
                        }, cb);

                        cb(start, end)
                    });
                </script>
            </div>
            <div class="print-hide" style="margin-top: 2rem;">
                <!-- Cetak Laporan Periode Pendapatan Reservasi Wisata -->  
                <a class="btn-kelola-laporan" onclick="savePDF()" href="#" role="button">
                    <i class="fas fa-file-pdf"></i> Unduh Laporan (PDF)
                </a>
            </div>

            <!-- Full Area -->
            <div class="full-area-laporan"> <!-- Ada Perubahan -->
                <!-- Area A -->
                <div class="area-A">
                    <div class="card">
                        <div class="card-header print-hide"> <!-- Ada Perubahan -->
                            <!-- Kosong -->
                        </div>

                        <div class="card-body">
                            <table> <!-- Ada Perubahan -->
                                <tbody>
                                    <tr>
                                        <td>
                                            <img src="<?=$rowLokasi->foto_wilayah?>?<?php if ($status='nochange'){echo time();}?>" width="100px" height="100px">
                                        </td>
                                        <td>
                                            <h2 style="text-align: center;">
                                                Wisata Bahari Pantai Tangkolak
                                            </h2>
                                            <h6 style="text-align: center; font-weight: normal;">
                                                <i><?=$rowLokasi->deskripsi_lokasi?></i>
                                            </h6>
                                        </td>
                                        <td>
                                            <img src="../views/img/white.jpg" width="100px" height="100px">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr style="margin-bottom: 1rem">
                            <table style="margin-bottom: 2rem">
                                <tr>
                                    <td></td>
                                    <td>
                                        <h3 style="text-align: center;">
                                            Laporan Periode Paket Wisata
                                        </h3>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <h5 style="text-align: center; font-weight: normal;">
                                            Periode <span id="periode_laporan"></span>
                                        </h5>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>

                            <div class="table-portable">
                                <!-- Response AJAX call filter tabel laporan ditaro dalam sini -->
                                <div id="table-container"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php } ?>

        <!-- Footer -->
        <footer class="print-hide">
            <h2 class="footer-paimon">
                <small>© 2021 Wisata Bahari</small>
            </h2>
        </footer>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="../plugins/bootstrap-5/js/bootstrap.js"></script>
    <!-- All Javascript -->
    <script src="../views/js/jspdf.min.js"></script>
    <script src="../views/js/standard_fonts_metrics.js"></script>
    <script src="../views/js/split_text_to_size.js"></script>
    <script src="../views/js/from_html.js"></script>
    <script src="../views/js/html2pdf.bundle.min.js"></script>
    
    <script>
        $(document).ready(function(){});

        function listenRincian(){
            $('.userinfo').click(function(){

            var id_donasi = $(this).data('id');

            // AJAX request
            $.ajax({
                    url: 'list_populate.php',
                    type: 'post',
                    data: {id_donasi: id_donasi, type : 'load_rincian_donasi'},
                    beforeSend : function(){
                    $('#empModal').modal('show');
                    $('#empModal').LoadingOverlay("show");
                    },
                    success: function(response){
                    // Add response in Modal body
                    $('.modal-body').html(response);
                    $('#empModal').LoadingOverlay("hide");
                    }
                });
            });
        }

        <?php 
            $sortquery = ' ';
            if (isset($_GET['sort'])){
                if($_GET['sort'] = 'sortByNominalDESC'){
                    $sortquery = ' ORDER BY nominal DESC ';
                }
                elseif($_GET['sort']= 'sortByNominalASC'){
                    $sortquery = ' ORDER BY nominal ASC ';
                }
            }
        ?>

        // Tabel Laporan
        function updateTabelLaporan(start, end, sortir){
            // starto = start
            // endo = end

            id_lokasi_dikelola = <?=!empty($_SESSION['id_lokasi_dikelola']) ? $_SESSION['id_lokasi_dikelola'] : '1'?>

            id_wilayah_dikelola =  <?=!empty($_SESSION['id_wilayah_dikelola']) ? $_SESSION['id_wilayah_dikelola'] : '1'?>

            level_user = <?=$_SESSION['level_user']?>

            // AJAX request
            $.ajax({
                url: 'list_populate.php',
                type: 'post',
                data: {start: start, 
                        end: end,
                        sortir: sortir,
                        level_user : level_user,
                        id_lokasi_dikelola : id_lokasi_dikelola,
                        id_wilayah_dikelola : id_wilayah_dikelola,
                        type : 'load_laporan_paket'},
                beforeSend : function(){$('#table-container').LoadingOverlay("show");},
                success: function(response){
                    // Attach response to target container/element
                    $('#table-container').html(response);
                    console.log(start, end)
                    listenRincian()
                    $('#table-container').LoadingOverlay("hide");
                }
            });
        }

        // Cetak PDF
        function savePDF(){

            $('body').LoadingOverlay("show");

            periode_laporan = $('#periode_laporan').text().split(" ").join("");

            $('#btn-unduh').css('left', '9999px')
            $('#clientPrintContent').css('background-color', 'white')
            $('.collapse').show()
            $('.sidebar').show()
            $('#clientPrintContent, .header').css('margin-left', 0)

            $('.capture-hide').remove()
            $('.print-hide').remove()
            $('body').addClass('text-sm')

            var today = new Date();
            var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
            var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();

            var dateTime = date+'_'+time;

            var element = document.getElementById('clientPrintContent');
            var opt = {
                margin:       [0,0,1,0], // Ada Perubahan
                filename:     `Laporan-Paket Wisata_Periode-${periode_laporan}_Diunduh-Pada${dateTime}.pdf`,
                image:        { type: 'jpeg', quality: 0.95 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'cm', format: 'a4', orientation: 'portrait' } // Ada Perubahan
            };

            // New Promise-based usage:
            html2pdf().set(opt).from(element).save();

            setTimeout(function (){
            $('#btn-unduh').css('left', '0')
            }, 1000)

            setTimeout(function (){
            location.reload()
            }, 3000)
        }

        $(function() {
            $("#tabel_laporan_wisata").tablesorter();
        });
    </script>

</body>
</html>