<?php 
include '../app/database/koneksi.php';
session_start();

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

<!-- LAPORAN PERIODE PENDAPATAN -->
<?php
if ($_POST['type'] == 'load_laporan_pengeluaran' && !empty($_POST["start"])) {

$level = $_POST['level_user'];

$id_lokasi = $_POST['id_lokasi_dikelola'];
$id_wilayah = $_POST['id_wilayah_dikelola'];

$start = $_POST["start"];
$end = $_POST["end"];

if($level == 2){
    $extra_query        = " AND t_lokasi.id_lokasi = $id_lokasi ";
    $extra_query_noand  = " t_lokasi.id_lokasi = $id_lokasi ";
}
else if($level == 3){
    $extra_query        = " AND t_lokasi.id_wilayah = $id_wilayah ";
    $extra_query_noand  = " t_lokasi.id_wilayah = $id_wilayah ";
}
else if($level == 4){
    $extra_query        = "  ";
    $extra_query_noand  = " 1 ";
}

//Sortir berdasarkan nominal donasi

// Header Pengeluaran
$sqlpengeluaran = 'SELECT * FROM t_pengeluaran
                LEFT JOIN t_reservasi_wisata ON t_pengeluaran.id_reservasi_wisata = t_reservasi_wisata.id_reservasi_wisata
                LEFT JOIN t_paket_wisata ON t_reservasi_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                LEFT JOIN t_lokasi ON t_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                WHERE '.$extra_query_noand.' 
                AND tanggal_pesan BETWEEN "'.$start.'" 
                AND "'.$end.'"';

$stmt = $pdo->prepare($sqlpengeluaran);
$stmt->execute();
$row = $stmt->fetchAll();

// Reservasi
$sqlreservasi = 'SELECT COUNT(t_reservasi_wisata.id_reservasi_wisata) AS total_reservasi, 
                        SUM(t_reservasi_wisata.total_reservasi) AS subtotal
                FROM t_reservasi_wisata
                LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                LEFT JOIN t_status_reservasi ON t_reservasi_wisata.id_status_reservasi = t_status_reservasi.id_status_reservasi
                LEFT JOIN t_paket_wisata ON t_reservasi_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                LEFT JOIN t_lokasi ON t_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                WHERE '.$extra_query_noand.' 
                AND t_reservasi_wisata.id_status_reservasi = 2
                AND tanggal_pesan BETWEEN "'.$start.'" 
                AND "'.$end.'"';

$stmt = $pdo->prepare($sqlreservasi);
$stmt->execute();
$rowreservasi = $stmt->fetch();

// Pengeluaran
$sqlPengajuan = 'SELECT SUM(t_pengeluaran.biaya_pengeluaran) AS subtotal
                FROM t_pengeluaran
                LEFT JOIN t_reservasi_wisata ON t_pengeluaran.id_reservasi_wisata = t_reservasi_wisata.id_reservasi_wisata
                LEFT JOIN t_paket_wisata ON t_reservasi_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                LEFT JOIN t_lokasi ON t_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                WHERE '.$extra_query_noand.'
                AND tanggal_pesan BETWEEN "'.$start.'" 
                AND "'.$end.'"';

$stmt = $pdo->prepare($sqlPengajuan);
$stmt->execute();
$rowpengajuan = $stmt->fetch();
?>

<table id="tabel_laporan_wisata">
    <thead>
        <?php
        // $pemasukan = 15000;
        $jasa_pendapatan    = $rowreservasi->subtotal;
        $pendaptan_lain     = 0;
        $jml_pendapatan     = $rowreservasi->subtotal;
        ?>

        <tr>
            <th style="text-align: left;">Pendapatan Jasa</th>
            <td style="text-align: center; font-weight: normal;"></td>
            <td style="text-align: right; font-weight: normal;" colspan="2">Rp. <?=number_format($jasa_pendapatan, 0)?></td>
            <td></td>
        </tr>
        <tr>
            <th style="text-align: left;">Pendapatan Lain-lain</th>
            <td style="text-align: center; font-weight: normal;"></td>
            <td style="
                    text-align: right; 
                    font-weight: normal;
                    border-bottom: 1px solid #000;
                    padding: 0px 7px;" colspan="2">Rp. <?=number_format($pendaptan_lain, 0)?> (+)</td>
            <td></td>
        </tr>
        <tr>
            <th style="text-align: center;">Jumlah Pendapatan</th>
            <td style="text-align: center; font-weight: normal;"></td>
            <td style="text-align: right; font-weight: normal;" colspan="2">Rp. <?=number_format($jml_pendapatan, 0)?></td>
            <td></td>
        </tr>
    </thead>
    <thead id="tbody_laporan_donasi">
        <?php
        foreach ($row as $rowitem) {
        $laporandate = strtotime(date('d-m-Y'));
        ?>
        <tr>
            <th style="text-align: left;">Beban <?=$rowitem->nama_pengeluaran?></th>
            <td style="text-align: center; font-weight: normal;" colspan="2">Rp. <?=number_format($rowitem->biaya_pengeluaran, 0)?></td>
            <td></td>
        </tr>
        <?php } ?>
    </thead>
    <thead>
        <?php
        // $pemasukan = 15000;
        $jml_beban_usaha    = $rowpengajuan->subtotal;
        $jml_pendapatan     = $rowreservasi->subtotal;
        $laba_bersih        =  $jml_pendapatan- $jml_beban_usaha;
        ?>
        <tr>
            <tr>
            <th style="text-align: center;">Jumlah Beban Usaha</th>
            <td style="text-align: center; font-weight: normal;"></td>
            <td style="
                    text-align: right; 
                    font-weight: normal;
                    border-bottom: 1px solid #000;
                    padding: 0px 7px;" colspan="2">Rp. <?=number_format($jml_beban_usaha, 0)?> (-)</td>
            <td></td>
        </tr>
        <tr>
            <th style="text-align: left;">Laba Bersih</th>
            <td style="text-align: center; font-weight: normal;"></td>
            <td style="text-align: right; font-weight: normal;" colspan="2">Rp. <?=number_format($laba_bersih, 0)?></td>
            <td></td>
        </tr>
    </thead>
    <tfoot> <!-- Ada Perubahan -->
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <?php
            $laporandate = strtotime(date('d-m-Y'));
            ?>
            <td colspan="2" style="text-align: center;">
                <?=strftime('%A, %e %B %Y', $laporandate);?>
            </td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2" style="text-align: center;">
                <?php echo $_SESSION['nama_user']; ?>
            </td>
            <td></td>
        </tr>
    </tfoot>               
</table>

<script>       
    $(function() {
        $("#tabel_laporan_wisata").tablesorter();
    });
</script>
<?php } ?>

<!-- LAPORAN PERIODE PENDAPATAN -->
<?php
if ($_POST['type'] == 'load_laporan_reservasi' && !empty($_POST["start"])) {

$level = $_POST['level_user'];

$id_lokasi = $_POST['id_lokasi_dikelola'];
$id_wilayah = $_POST['id_wilayah_dikelola'];

$start = $_POST["start"];
$end = $_POST["end"];

if($level == 2){
    $extra_query        = " AND t_lokasi.id_lokasi = $id_lokasi ";
    $extra_query_noand  = " t_lokasi.id_lokasi = $id_lokasi ";
}
else if($level == 3){
    $extra_query        = " AND t_lokasi.id_wilayah = $id_wilayah ";
    $extra_query_noand  = " t_lokasi.id_wilayah = $id_wilayah ";
}
else if($level == 4){
    $extra_query        = "  ";
    $extra_query_noand  = " 1 ";
}

//Sortir berdasarkan nominal donasi

// Header Reservasi Wisata
$sqlreservasi = 'SELECT * FROM t_reservasi_wisata
                LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                LEFT JOIN t_status_reservasi ON t_reservasi_wisata.id_status_reservasi = t_status_reservasi.id_status_reservasi
                LEFT JOIN t_paket_wisata ON t_reservasi_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                LEFT JOIN t_lokasi ON t_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                WHERE '.$extra_query_noand.' 
                AND t_reservasi_wisata.id_status_reservasi = 2
                AND tanggal_pesan BETWEEN "'.$start.'" 
                AND "'.$end.'"';

$stmt = $pdo->prepare($sqlreservasi);
$stmt->execute();
$row = $stmt->fetchAll();

// Pengeluaran
$sqlhitungtotal = 'SELECT SUM(t_pengeluaran.biaya_pengeluaran) AS biaya_pengeluaran
                FROM t_pengeluaran
                LEFT JOIN t_reservasi_wisata ON t_pengeluaran.id_reservasi_wisata = t_reservasi_wisata.id_reservasi_wisata
                LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                LEFT JOIN t_status_reservasi ON t_reservasi_wisata.id_status_reservasi = t_status_reservasi.id_status_reservasi
                LEFT JOIN t_paket_wisata ON t_reservasi_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                LEFT JOIN t_lokasi ON t_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                WHERE '.$extra_query_noand.' 
                AND t_reservasi_wisata.id_status_reservasi = 2
                AND tanggal_pesan BETWEEN "'.$start.'" 
                AND "'.$end.'"';

$stmt = $pdo->prepare($sqlhitungtotal);
$stmt->execute();
$rowtotal = $stmt->fetch();

// Reservasi
$sqlreservasi = 'SELECT COUNT(t_reservasi_wisata.id_reservasi_wisata) AS total_reservasi, 
                        SUM(t_reservasi_wisata.total_reservasi) AS subtotal
                FROM t_reservasi_wisata
                LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                LEFT JOIN t_status_reservasi ON t_reservasi_wisata.id_status_reservasi = t_status_reservasi.id_status_reservasi
                LEFT JOIN t_paket_wisata ON t_reservasi_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                LEFT JOIN t_lokasi ON t_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                WHERE '.$extra_query_noand.' 
                AND t_reservasi_wisata.id_status_reservasi = 2
                AND tanggal_pesan BETWEEN "'.$start.'" 
                AND "'.$end.'"';

$stmt = $pdo->prepare($sqlreservasi);
$stmt->execute();
$rowreservasi = $stmt->fetch();
?>

<table id="tabel_laporan_wisata">
    <thead>
        <tr>
            <th scope="col">No</th>
            <th scope="col">Lokasi</th>
            <th scope="col">Nama Wisatawan</th>
            <th scope="col">Tanggal Reservasi</th>
            <th scope="col">Jumlah Pemasukan</th>
            <th scope="col">Biaya Pengeluaran</th>
        </tr>
    </thead>
    <tbody id="tbody_laporan_donasi">
        <?php
        $no = 1;
        $pemasukan = 0;
        $pengeluaran = 0;
        $sum_pemasukan = 0;
        foreach ($row as $rowitem) {
        $reservasidate = strtotime($rowitem->tgl_reservasi);
        $laporandate = strtotime(date('d-m-Y'));
        ?>
        <tr class="row_donasi">
            <th scope="row"><?= $no ?></th>
            <td><?=$rowitem->nama_lokasi?></td>
            <td><?=$rowitem->nama_user?></td>
            <td>
                <?=strftime('%A, %e %B %Y', $reservasidate);?>
            </td>
            
            <!-- Reservasi Pemasukan -->
            <td class="nominal">Rp. <?=number_format($rowitem->total_reservasi, 0)?></td>

            <!-- Pengeluaran Biaya -->
            <?php 
            $sqlLaporan = 'SELECT SUM(t_pengeluaran.biaya_pengeluaran) AS biaya_pengeluaran
                            FROM t_pengeluaran
                            LEFT JOIN t_reservasi_wisata ON t_pengeluaran.id_reservasi_wisata = t_reservasi_wisata.id_reservasi_wisata
                            LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                            LEFT JOIN t_status_reservasi ON t_reservasi_wisata.id_status_reservasi = t_status_reservasi.id_status_reservasi
                            LEFT JOIN t_paket_wisata ON t_reservasi_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                            LEFT JOIN t_lokasi ON t_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                            WHERE '.$extra_query_noand.' 
                            AND t_reservasi_wisata.id_status_reservasi = 2
                            AND tanggal_pesan BETWEEN "'.$start.'" 
                            AND "'.$end.'"
                            AND t_reservasi_wisata.id_reservasi_wisata = :id_reservasi_wisata';

            $stmt = $pdo->prepare($sqlLaporan);
            $stmt->execute(['id_reservasi_wisata' => $rowitem->id_reservasi_wisata]);
            $row = $stmt->fetchAll();
            
            $sum_pengeluaran = 0;

            foreach ($row as $pengeluaran) { 

            $sum_pengeluaran += $pengeluaran->biaya_pengeluaran;
            ?>
            <td class="nominal">Rp. <?=number_format($sum_pengeluaran, 0)?></td>
            <?php } ?>
        </tr>
        <?php $no++;
            }
        ?>

        <?php
        // $pemasukan = 15000;
        // $pengeluaran = 16000;
        $pemasukan = $rowreservasi->subtotal;
        $pengeluaran = $rowtotal->biaya_pengeluaran;
        // $total = $pemasukan - $pengeluaran;
        ?>

        <tr>
            <th colspan="5" style="text-align: right;">Total Jumlah Pemasukan:</th>
            <td style="text-align: center;">Rp. <?=number_format($pemasukan, 0)?></td>                          
        </tr>
        <tr>
            <th colspan="5" style="text-align: right;">Total Biaya Pengeluaran:</th>
            <td style="text-align: center;">Rp. <?=number_format($pengeluaran, 0)?></td>                                
        </tr>

        <!-- Untung Rugi -->
        <?php if ($pemasukan > $pengeluaran) {
	    $laba = $pemasukan - $pengeluaran; ?>
        <tr> <!-- Untung -->
            <th colspan="5" style="text-align: right;"><i class="text-success fas fa-plus"></i> LABA:</th>
            <td style="text-align: center;">Rp. <?=number_format($laba, 0)?></td>                                
        </tr>
        <?php } elseif ($pengeluaran > $pemasukan) { 
        $rugi = $pengeluaran - $pemasukan; ?>
        <tr> <!-- Rugi -->
            <th colspan="5" style="text-align: right;"><i class="text-danger fas fa-minus"></i> RUGI:</th>
            <td style="text-align: center;">Rp. <?=number_format($rugi, 0)?></td>                                
        </tr>
        <?php } else { ?>
        <tr>
            <th colspan="5" style="text-align: right;">Tidak laba tidak rugi</th>
            <th></th>
        </tr>
        <?php } ?>
    </tbody>
    <tfoot> <!-- Ada Perubahan -->
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2" style="text-align: center;">
                <?=strftime('%A, %e %B %Y', $laporandate);?>
            </td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2" style="text-align: center;">
                <?php echo $_SESSION['nama_user']; ?>
            </td>
            <td></td>
        </tr>
    </tfoot>               
</table>

<script>       
    $(function() {
        $("#tabel_laporan_wisata").tablesorter();
    });
</script>
<?php } ?>

<!-- LAPORAN PERIODE PENGAJUAN -->
<?php
if ($_POST['type'] == 'load_laporan_pengajuan' && !empty($_POST["start"])) {

$level = $_POST['level_user'];

$id_lokasi = $_POST['id_lokasi_dikelola'];
$id_wilayah = $_POST['id_wilayah_dikelola'];

$start = $_POST["start"];
$end = $_POST["end"];

if($level == 2){
    $extra_query        = " AND t_lokasi.id_lokasi = $id_lokasi ";
    $extra_query_noand  = " t_lokasi.id_lokasi = $id_lokasi ";
}
else if($level == 3){
    $extra_query        = " AND t_lokasi.id_wilayah = $id_wilayah ";
    $extra_query_noand  = " t_lokasi.id_wilayah = $id_wilayah ";
}
else if($level == 4){
    $extra_query        = "  ";
    $extra_query_noand  = " 1 ";
}

//Sortir berdasarkan nominal donasi

// Header Reservasi Wisata
$sqlpengajuan = 'SELECT * FROM t_pengajuan
                LEFT JOIN t_lokasi ON t_pengajuan.id_lokasi = t_lokasi.id_lokasi
                LEFT JOIN t_pengadaan_fasilitas ON t_pengajuan.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                WHERE '.$extra_query_noand.' 
                AND t_pengajuan.status_pengajuan = "Diterima"
                AND tanggal_pengajuan BETWEEN "'.$start.'" 
                AND "'.$end.'"';

$stmt = $pdo->prepare($sqlpengajuan);
$stmt->execute();
$row = $stmt->fetchAll();
?>

<table id="tabel_laporan_wisata">
    <thead>
        <tr>
            <th scope="col">No</th>
            <th scope="col">Judul Pengajuan</th>
            <th scope="col">Deskripsi Pengajuan</th>
            <th scope="col">Tanggal Pengajuan</th>
            <th scope="col">Status Pengajuan</th>
        </tr>
    </thead>
    <tbody id="tbody_laporan_donasi">
        <?php
        $no = 1;
        foreach ($row as $rowitem) {
        $pengajuandate = strtotime($rowitem->tanggal_pengajuan);
        $laporandate = strtotime(date('d-m-Y'));
        ?>
        <tr class="row_donasi">
            <th scope="row"><?= $no ?></th>
            <td><?=$rowitem->judul_pengajuan?></td>
            <td><?=$rowitem->deskripsi_pengajuan?></td>
            <td>
                <?=strftime('%A, %e %B %Y', $pengajuandate);?>
            </td>
            <td>
                <?php if ($rowitem->status_pengajuan == "Pending") { ?>
                    <span class="status diona print-hide"></span>
                    <?=$rowitem->status_pengajuan?>
                <?php } elseif ($rowitem->status_pengajuan == "Diterima") { ?>
                    <span class="status yaoyao print-hide"></span>
                    <?=$rowitem->status_pengajuan?>
                <?php } elseif ($rowitem->status_pengajuan == "Ditolak") {?>
                    <span class="status klee print-hide"></span>
                    <?=$rowitem->status_pengajuan?>
                <?php } ?>
            </td>
        </tr>
        <?php $no++;
            }
        ?>
    </tbody>
    <tfoot> <!-- Ada Perubahan -->
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2" style="text-align: center;">
                <?=strftime('%A, %e %B %Y', $laporandate);?>
            </td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2" style="text-align: center;">
                <?php echo $_SESSION['nama_user']; ?>
            </td>
            <td></td>
        </tr>
    </tfoot>                    
</table>

<script>       
    $(function() {
        $("#tabel_laporan_wisata").tablesorter();
    });
</script>
<?php } ?>

<!-- LAPORAN PERIODE PAKET WISATA -->
<?php
if ($_POST['type'] == 'load_laporan_paket' && !empty($_POST["start"])) {

$level = $_POST['level_user'];

$id_lokasi = $_POST['id_lokasi_dikelola'];
$id_wilayah = $_POST['id_wilayah_dikelola'];

$start = $_POST["start"];
$end = $_POST["end"];

if($level == 2){
    $extra_query        = " AND t_lokasi.id_lokasi = $id_lokasi ";
    $extra_query_noand  = " t_lokasi.id_lokasi = $id_lokasi ";
}
else if($level == 3){
    $extra_query        = " AND t_lokasi.id_wilayah = $id_wilayah ";
    $extra_query_noand  = " t_lokasi.id_wilayah = $id_wilayah ";
}
else if($level == 4){
    $extra_query        = "  ";
    $extra_query_noand  = " 1 ";
}

//Sortir berdasarkan nominal donasi

// Header Paket Wisata
$sqlpaket = 'SELECT * FROM t_paket_wisata
                LEFT JOIN t_lokasi ON t_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                WHERE '.$extra_query_noand.' 
                AND t_paket_wisata.status_paket = "Aktif"
                AND tgl_awal_paket BETWEEN "'.$start.'" 
                AND "'.$end.'"';

$stmt = $pdo->prepare($sqlpaket);
$stmt->execute();
$row = $stmt->fetchAll();
?>

<table id="tabel_laporan_wisata">
    <thead>
        <tr>
            <th scope="col">No</th>
            <th scope="col">Nama Lokasi</th>
            <th scope="col">Nama Paket Wisata</th>
            <th scope="col">Tanggal Awal Paket</th>
            <th scope="col">Tanggal Akhir Paket</th>
            <th scope="col">Foto Paket</th>
            <th scope="col">Status Paket</th>
        </tr>
    </thead>
    <tbody id="tbody_laporan_donasi">
        <?php
        $no = 1;
        foreach ($row as $rowitem) {
        $paketawaldate = strtotime($rowitem->tgl_awal_paket);
        $paketakhirdate = strtotime($rowitem->tgl_akhir_paket);
        $laporandate = strtotime(date('d-m-Y'));
        ?>
        <tr class="row_donasi">
            <th scope="row"><?= $no ?></th>
            <td><?=$rowitem->nama_lokasi?></td>
            <td><?=$rowitem->nama_paket_wisata?></td>
            <td>
                <?=strftime('%A, %e %B %Y', $paketawaldate);?>
            </td>
            <td>
                <?=strftime('%A, %e %B %Y', $paketakhirdate);?>
            </td>
            <td>
                <img src="<?=$rowitem->foto_paket_wisata?>?<?php if ($status='nochange'){echo time();}?>" width="100px">
            </td>
            <td>
                <?php if ($rowitem->status_paket == "Aktif") { ?>
                    <span class="status diona print-hide"></span>
                    <?=$rowitem->status_paket?>
                <?php } elseif ($rowitem->status_paket == "Tidak Aktif") { ?>
                    <span class="status yaoyao print-hide"></span>
                    <?=$rowitem->status_paket?>
                <?php } elseif ($rowitem->status_paket == "Perbaikan") {?>
                    <span class="status klee print-hide"></span>
                    <?=$rowitem->status_paket?>
                <?php } ?>
            </td>
        </tr>
        <?php $no++;
            }
        ?>
    </tbody>
    <tfoot> <!-- Ada Perubahan -->
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2" style="text-align: center;">
                <?=strftime('%A, %e %B %Y', $laporandate);?>
            </td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2" style="text-align: center;">
                <?php echo $_SESSION['nama_user']; ?>
            </td>
            <td></td>
        </tr>
    </tfoot>     
</table>

<script>       
    $(function() {
        $("#tabel_laporan_wisata").tablesorter();
    });
</script>
<?php } ?>

<!-- LAPORAN PERIODE ASURANSI -->
<?php
if ($_POST['type'] == 'load_laporan_asuransi' && !empty($_POST["start"])) {

$level = $_POST['level_user'];

$id_lokasi = $_POST['id_lokasi_dikelola'];
$id_wilayah = $_POST['id_wilayah_dikelola'];

$start = $_POST["start"];
$end = $_POST["end"];

if($level == 2){
    $extra_query        = " AND t_lokasi.id_lokasi = $id_lokasi ";
    $extra_query_noand  = " t_lokasi.id_lokasi = $id_lokasi ";
}
else if($level == 3){
    $extra_query        = " AND t_lokasi.id_wilayah = $id_wilayah ";
    $extra_query_noand  = " t_lokasi.id_wilayah = $id_wilayah ";
}
else if($level == 4){
    $extra_query        = "  ";
    $extra_query_noand  = " 1 ";
}

//Sortir berdasarkan nominal donasi

// Header Paket Wisata
$sqlasuransi = 'SELECT * FROM t_paket_wisata
                LEFT JOIN t_lokasi ON t_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                LEFT JOIN t_asuransi ON t_paket_wisata.id_asuransi = t_asuransi.id_asuransi
                LEFT JOIN t_perusahaan_asuransi ON t_asuransi.id_perusahaan_asuransi = t_perusahaan_asuransi.id_perusahaan_asuransi
                WHERE '.$extra_query_noand.' 
                AND tgl_kontrak_asuransi BETWEEN "'.$start.'" 
                AND "'.$end.'"';

$stmt = $pdo->prepare($sqlasuransi);
$stmt->execute();
$row = $stmt->fetchAll();
?>

<table id="tabel_laporan_wisata">
    <thead>
        <tr>
            <th scope="col">No</th>
            <th scope="col">Nama Asuransi</th>
            <th scope="col">Biaya Asuransi</th>
            <th scope="col">No Kontrak Asuransi</th>
            <th scope="col">Tanggal Kontrak Asuransi</th>
            <th scope="col">Lama Kontrak Asuransi</th>
            <th scope="col">Perihal Kontrak Asuransi</th>
        </tr>
    </thead>
    <tbody id="tbody_laporan_donasi">
        <?php
        $no = 1;
        foreach ($row as $rowitem) {
        $asuransidate = strtotime($rowitem->tgl_kontrak_asuransi);
        $laporandate = strtotime(date('d-m-Y'));
        ?>
        <tr class="row_donasi">
            <th scope="row"><?= $no ?></th>
            <td><?=$rowitem->nama_asuransi?></td>
            <td><?=$rowitem->biaya_asuransi?></td>
            <td><?=$rowitem->no_kontrak_asuransi?></td>
            <td><?=strftime('%A, %e %B %Y', $asuransidate);?></td>
            <td><?=$rowitem->lama_kontrak_asuransi?></td>
            <td><?=$rowitem->perihal_kontrak_asuransi?></td>
        </tr>
        <?php $no++;
            }
        ?>
    </tbody>
    <tfoot> <!-- Ada Perubahan -->
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2" style="text-align: center;">
                <?=strftime('%A, %e %B %Y', $laporandate);?>
            </td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2" style="text-align: center;">
                <?php echo $_SESSION['nama_user']; ?>
            </td>
            <td></td>
        </tr>
    </tfoot>                     
</table>

<script>       
    $(function() {
        $("#tabel_laporan_wisata").tablesorter();
    });
</script>
<?php } ?>

<!-- LAPORAN PERIODE KERJASAMA -->
<?php
if ($_POST['type'] == 'load_laporan_kerjasama' && !empty($_POST["start"])) {

$level = $_POST['level_user'];

$id_lokasi = $_POST['id_lokasi_dikelola'];
$id_wilayah = $_POST['id_wilayah_dikelola'];

$start = $_POST["start"];
$end = $_POST["end"];

if($level == 2){
    $extra_query        = " AND t_lokasi.id_lokasi = $id_lokasi ";
    $extra_query_noand  = " t_lokasi.id_lokasi = $id_lokasi ";
}
else if($level == 3){
    $extra_query        = " AND t_lokasi.id_wilayah = $id_wilayah ";
    $extra_query_noand  = " t_lokasi.id_wilayah = $id_wilayah ";
}
else if($level == 4){
    $extra_query        = "  ";
    $extra_query_noand  = " 1 ";
}

//Sortir berdasarkan nominal donasi

// Header Kerjasama
$sqlkerjasama = 'SELECT * FROM t_kerjasama
                LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                WHERE status_kerjasama = "Melakukan Kerjasama"
                AND tgl_kontrak_kerjasama BETWEEN "'.$start.'" 
                AND "'.$end.'"';

$stmt = $pdo->prepare($sqlkerjasama);
$stmt->execute();
$row = $stmt->fetchAll();
?>

<table id="tabel_laporan_wisata">
    <thead>
        <tr>
            <th scope="col">No</th>
            <th scope="col">Pihak Kerjasama</th>
            <th scope="col">Status Kerjasama</th>
            <th scope="col">No Kontrak Kerjasama</th>
            <th scope="col">Tanggal Kontrak Kerjsama</th>
            <th scope="col">Lama Kontrak Kerjasama</th>
            <th scope="col">Perihal Kontrak Kerjasama</th>
        </tr>
    </thead>
    <tbody id="tbody_laporan_donasi">
        <?php
        $no = 1;
        foreach ($row as $rowitem) {
        $kerjasamadate = strtotime($rowitem->tgl_kontrak_kerjasama);
        $laporandate = strtotime(date('d-m-Y'));
        ?>
        <tr class="row_donasi">
            <th scope="row"><?= $no ?></th>
            <td><?=$rowitem->pihak_ketiga_kerjasama?></td>
            <td>
                <?php if ($rowitem->status_kerjasama == "Tidak Melakukan Kerjsama") { ?>
                    <span class="status klee print-hide"></span>
                    <?=$rowitem->status_kerjasama?>
                <?php } elseif ($rowitem->status_kerjasama == "Melakukan Kerjasama") { ?>
                    <span class="status yaoyao print-hide"></span>
                    <?=$rowitem->status_kerjasama?>
                <?php } ?>
            </td>
            <td><?=$rowitem->no_kontrak_kerjasama?></td>
            <td><?=strftime('%A, %e %B %Y', $kerjasamadate);?></td>
            <td><?=$rowitem->lama_kontrak_kerjasama?></td>
            <td><?=$rowitem->perihal_kontrak_kerjasama?></td>
        </tr>
        <?php $no++;
            }
        ?>
    </tbody>
    <tfoot> <!-- Ada Perubahan -->
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2" style="text-align: center;">
                <?=strftime('%A, %e %B %Y', $laporandate);?>
            </td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2" style="text-align: center;">
                <?php echo $_SESSION['nama_user']; ?>
            </td>
            <td></td>
        </tr>
    </tfoot>           
</table>

<script>       
    $(function() {
        $("#tabel_laporan_wisata").tablesorter();
    });
</script>
<?php } ?>

<!-- LAPORAN PERIODE PENGADAAN FASILITAS -->
<?php
if ($_POST['type'] == 'load_laporan_pengadaan' && !empty($_POST["start"])) {

$level = $_POST['level_user'];

$id_lokasi = $_POST['id_lokasi_dikelola'];
$id_wilayah = $_POST['id_wilayah_dikelola'];

$start = $_POST["start"];
$end = $_POST["end"];

if($level == 2){
    $extra_query        = " AND t_lokasi.id_lokasi = $id_lokasi ";
    $extra_query_noand  = " t_lokasi.id_lokasi = $id_lokasi ";
}
else if($level == 3){
    $extra_query        = " AND t_lokasi.id_wilayah = $id_wilayah ";
    $extra_query_noand  = " t_lokasi.id_wilayah = $id_wilayah ";
}
else if($level == 4){
    $extra_query        = "  ";
    $extra_query_noand  = " 1 ";
}

//Sortir berdasarkan nominal donasi

// Header Pengadaan Fasilitas
$sqlpengadaan = 'SELECT * FROM t_pengadaan_fasilitas
                WHERE status_pengadaan = "Baik"
                AND tgl_pengadaan BETWEEN "'.$start.'" 
                AND "'.$end.'"';

$stmt = $pdo->prepare($sqlpengadaan);
$stmt->execute();
$row = $stmt->fetchAll();
?>

<table id="tabel_laporan_wisata">
    <thead>
        <tr>
            <th scope="col">No</th>
            <th scope="col">Pengadaan Fasilitas</th>
            <th scope="col">Status Pengadaan</th>
            <th scope="col">Tanggal Pengadaan</th>
        </tr>
    </thead>
    <tbody id="tbody_laporan_donasi">
        <?php
        $no = 1;
        foreach ($row as $rowitem) {
        $pengadaandate = strtotime($rowitem->tgl_pengadaan);
        $laporandate = strtotime(date('d-m-Y'));
        ?>
        <tr class="row_donasi">
            <th scope="row"><?= $no ?></th>
            <td><?=$rowitem->pengadaan_fasilitas?></td>
            <td>
                <?php if ($rowitem->status_pengadaan == "Baik") { ?>
                    <span class="status yaoyao print-hide"></span>
                    <?=$rowitem->status_pengadaan?>
                <?php } elseif ($rowitem->status_pengadaan == "Rusak") { ?>
                    <span class="status diona print-hide"></span>
                    <?=$rowitem->status_pengadaan?>
                <?php } elseif ($rowitem->status_pengadaan == "Hilang") { ?>
                    <span class="status klee print-hide"></span>
                    <?=$rowitem->status_pengadaan?>
                <?php } ?>
            </td>
            <td><?=strftime('%A, %e %B %Y', $pengadaandate);?></td>
        </tr>
        <?php $no++;
            }
        ?>
    </tbody>
    <tfoot> <!-- Ada Perubahan -->
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2" style="text-align: center;">
                <?=strftime('%A, %e %B %Y', $laporandate);?>
            </td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2" style="text-align: center;">
                <?php echo $_SESSION['nama_user']; ?>
            </td>
            <td></td>
        </tr>
    </tfoot>    
</table>

<script>       
    $(function() {
        $("#tabel_laporan_wisata").tablesorter();
    });
</script>
<?php } ?>