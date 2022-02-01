<?php 
include '../app/database/koneksi.php';
session_start(); 
?>

<?php if ($_GET['type'] == 'pengadaan') {
// Cetak Laporan Biasa
    // Laporan Data Pengadaan Fasilitas
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan_data_pengadaan_fasilitas.xls");

    // Tampil all data pengadaan fasilitas
    $sqlPengadaan = 'SELECT * FROM t_pengadaan_fasilitas 
                    ORDER BY id_pengadaan ASC';

    $stmt = $pdo->prepare($sqlPengadaan);
    $stmt->execute();
    $rowPengadaan = $stmt->fetchAll(); ?>

    <table border="1">
        <thead>
            <tr>
                <th scope="col" colspan="3"><h2>LAPORAN DATA PENGADAAN FASILITAS</h2></th>
            </tr>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Pengadaan Fasilitas</th>
                <th scope="col">Status Pengadaan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($rowPengadaan as $pengadaan) { ?>
                <tr>
                    <th><?= $no ?></th>
                    <td><?= $pengadaan->pengadaan_fasilitas ?></td>
                    <td align="center"><?= $pengadaan->status_pengadaan ?></td>
                </tr>
            <?php $no++;
            } ?>
        </tbody>
    </table>
<?php } elseif ($_GET['type'] == 'kerjasama') {
// Cetak Laporan Biasa
    // Laporan Data Kerjasama
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan_data_kerjasama.xls");

    // Tampil all data kerjasama
    $sqlKerjasama = 'SELECT * FROM t_kerjasama
                    LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                    ORDER BY id_kerjasama ASC';

    $stmt = $pdo->prepare($sqlKerjasama);
    $stmt->execute();
    $rowKerjasama = $stmt->fetchAll(); ?>

    <table border="1">
        <thead>
            <tr>
                <th scope="col" colspan="7"><h2>LAPORAN DATA KERJASAMA</h2></th>
            </tr>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Pihak Kerjasama</th>
                <th scope="col">Pengadaan Fasilitas</th>
                <th scope="col">Status Kerjasama</th>
                <th scope="col">Pembagian Kerjasama</th>
                <th scope="col">Biaya Kerjasama</th>
                <th scope="col">Pembagian Hasil</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($rowKerjasama as $kerjasama) { 
            $pembagian_kerjasama = round($kerjasama->pembagian_kerjasama * 100, 2);?>
                <tr>
                    <th><?= $no ?></th>
                    <?php if ($kerjasama->status_kerjasama == "Tidak Melakukan Kerjasama") : ?>
                        <td>Tidak Ada Pihak Kerjasama</td>
                    <?php elseif ($kerjasama->pihak_ketiga_kerjasama != null) : ?>
                        <td><?=$kerjasama->pihak_ketiga_kerjasama?></td>
                    <?php else : ?>
                        <td>Data Kosong</td>
                    <?php endif ?>
                    <td><?=$kerjasama->pengadaan_fasilitas?></td>
                    <td>
                        <?php 
                            if ($kerjasama->status_kerjasama == "Melakukan Kerjasama") { ?>
                            <span class="status yaoyao"></span>
                            <?=$kerjasama->status_kerjasama?>
                        <?php } elseif ($kerjasama->status_kerjasama == "Tidak Melakukan Kerjasama") { ?>
                            <span class="status klee"></span>
                            <?=$kerjasama->status_kerjasama?>
                        <?php } ?>
                    </td>
                    <td align="center"><?= $pembagian_kerjasama ?>%</td>
                    <td>Rp. <?=number_format($kerjasama->biaya_kerjasama, 0)?></td>
                    <td>Rp. <?=number_format($kerjasama->pembagian_hasil_kerjasama, 0)?></td>
                </tr>
            <?php $no++;
            } ?>
        </tbody>
    </table>
<?php } elseif ($_GET['type'] == 'asuransi') {
// Cetak Laporan Biasa
    // Laporan Data Asuransi
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan_data_asuransi.xls");

    // Tampil all data asuransi
    $sqlAsuransi = 'SELECT * FROM t_asuransi
                    LEFT JOIN t_perusahaan_asuransi ON t_asuransi.id_perusahaan_asuransi = t_perusahaan_asuransi.id_perusahaan_asuransi
                    ORDER BY id_asuransi ASC';

    $stmt = $pdo->prepare($sqlAsuransi);
    $stmt->execute();
    $rowAsuransi = $stmt->fetchAll(); ?>

    <table border="1">
        <thead>
            <tr>
                <th scope="col" colspan="6"><h2>LAPORAN DATA ASURANSI</h2></th>
            </tr>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama Asuransi</th>
                <th scope="col">Biaya Asuransi</th>
                <th scope="col">Perusahaan Asuransi</th>
                <th scope="col">Alamat Perusahaana</th>
                <th scope="col">No Telp Perusahaan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($rowAsuransi as $asuransi) { ?>
                <tr>
                    <th><?= $no ?></th>
                    <td><?=$asuransi->nama_asuransi?></td>
                    <td>Rp. <?=number_format($asuransi->biaya_asuransi, 0)?></td>
                    <td><?=$asuransi->nama_perusahaan_asuransi?></td>
                    <td><?=$asuransi->alamat_perusahaan_asuransi?></td>
                    <td><?=$asuransi->notlp_perusahaan_asuransi?></td>
                </tr>
            <?php $no++;
            } ?>
        </tbody>
    </table>
<?php } elseif ($_GET['type'] == 'paket_wisata') {
// Cetak Laporan Biasa
    // Laporan Data Paket Wisata
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan_data_kerjasama.xls");

    // Tampil all data paket wisata
    $sqlPaket = 'SELECT * FROM t_paket_wisata
                LEFT JOIN t_lokasi ON t_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                LEFT JOIN t_asuransi ON t_paket_wisata.id_asuransi = t_asuransi.id_asuransi
                ORDER BY id_paket_wisata ASC';

    $stmt = $pdo->prepare($sqlPaket);
    $stmt->execute();
    $rowPaket = $stmt->fetchAll(); ?>

    <table border="1">
        <thead>
            <tr>
                <th scope="col" colspan="7"><h2>LAPORAN DATA PAKET WISATA</h2></th>
            </tr>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama Paket Wisata</th>
                <th scope="col">Status Paket</th>
                <th scope="col">Status Batas Pemesanan</th>
                <th scope="col">Batas Pemesanan</th>
                <th scope="col">Nama Lokasi</th>
                <th scope="col">Foto Paket Wisata</th>
                <th scope="col">Asuransi Wisata</th>
                <th scope="col">Biaya Paket Wisata</th>
                <th scope="col">Wisata</th>
                <th scope="col">Fasilitas Wisata</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($rowPaket as $paket) { 
            $awaldate = strtotime($paket->tgl_awal_paket);
            $akhirdate = strtotime($paket->tgl_akhir_paket); ?>
                <tr>
                    <th><?= $no ?></th>
                    <td><?=$paket->nama_paket_wisata?></td>
                    <td>
                        <?php 
                            if ($paket->status_paket == "Aktif") { ?>
                            <span class="status yaoyao"></span>
                            <?=$paket->status_paket?> <!-- Status Dalam Atifk -->
                        <?php } elseif ($paket->status_paket == "Tidak Aktif") { ?>
                            <span class="status klee"></span>
                            <?=$paket->status_paket?> <!-- Status Dalam Tidak Atifk -->
                        <?php } elseif ($paket->status_paket == "Perbaikan") {?>
                            <span class="status diona"></span>
                            <?=$paket->status_paket?> <!-- Status Dalam Perbaikan -->
                        <?php } ?>
                    </td>
                    <td>
                        <div>
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
                        </div>
                    </td>
                    <td>
                        <div style="margin-bottom: 0.8rem;">
                            <i class="text-info fas fa-hourglass-half"></i>
                            <?=strftime('%A, %d %B %Y', $awaldate);?>
                            <strong>s/d</strong> 
                            <?=strftime('%A, %d %B %Y', $akhirdate);?>
                        </div>
                    </td>
                    <td>
                        <!-- Select Lokasi Wisata -->
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
                    </td>
                    <td>
                        <img src="<?=$paket->foto_paket_wisata?>?<?php if ($status='nochange'){echo time();}?>" width="300px">
                    </td>
                    <td>
                        <!-- Asuransi -->
                        <?php
                        $sqlasuransiSelect = 'SELECT nama_asuransi, biaya_asuransi FROM t_paket_wisata
                                            LEFT JOIN t_asuransi ON t_paket_wisata.id_asuransi = t_asuransi.id_asuransi
                                            WHERE t_paket_wisata.id_paket_wisata = :id_paket_wisata';

                        $stmt = $pdo->prepare($sqlasuransiSelect);
                        $stmt->execute(['id_paket_wisata' => $paket->id_paket_wisata]);
                        $rowAsuransi = $stmt->fetchAll();

                        foreach ($rowAsuransi as $asuransi) { ?>
                        <div class="detail-isi">
                            Rp. <?=number_format($asuransi->biaya_asuransi, 0)?>
                        </div>
                        <?php } ?>
                    </td>
                    <td>
                        <!-- Select data biaya fasilitas untuk menentukan total sesuai harga wisata yang terdapat di paket wisata -->
                        <?php
                        $sqlfasilitasSelect = 'SELECT SUM(biaya_kerjasama) AS total_biaya_fasilitas, biaya_asuransi
                                            FROM t_fasilitas_wisata 
                                            LEFT JOIN t_kerjasama ON t_fasilitas_wisata.id_kerjasama = t_kerjasama.id_kerjasama
                                            LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                                            LEFT JOIN t_wisata ON t_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                                            LEFT JOIN t_paket_wisata ON t_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
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
                        <div class="detail-isi">
                            <i class="detail-logo-duid fas fa-money-bill-wave"></i>
                            Rp. <?=number_format($total_paket, 0)?>
                        </div>
                        <?php } ?>
                    </td>
                    <td>
                        <!-- Select Wisata -->
                        <?php
                        $sqlwisataSelect = 'SELECT * FROM t_wisata
                                            LEFT JOIN t_paket_wisata ON t_wisata.id_paket_wisata = t_paket_wisata.id_paket_wisata
                                            WHERE t_paket_wisata.id_paket_wisata = :id_paket_wisata
                                            AND t_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata
                                            ORDER BY id_wisata DESC';

                        $stmt = $pdo->prepare($sqlwisataSelect);
                        $stmt->execute(['id_paket_wisata' => $paket->id_paket_wisata]);
                        $rowWisata = $stmt->fetchAll();

                        foreach ($rowWisata as $wisata) { ?>
                        <div class="detail-isi">
                            <i class="detail-logo-wisata fas fa-luggage-cart"></i>
                            <?=$wisata->judul_wisata?>
                        </div>
                        <?php } ?>
                    </td>
                    <td>
                        <!-- Select seluruh data Fasilitas -->
                        <?php
                        $sqlfasilitasSelect = 'SELECT * FROM t_fasilitas_wisata
                                            LEFT JOIN t_kerjasama ON t_fasilitas_wisata.id_kerjasama = t_kerjasama.id_kerjasama
                                            LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
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
                            <?=$fasilitas->pengadaan_fasilitas?><br>
                        </div>
                        <?php } ?>
                    </td>
                </tr>
            <?php $no++;
            } ?>
        </tbody>
    </table>
<?php } elseif ($_GET['type'] == 'fasilitas') {
// Cetak Laporan Biasa
    // Laporan Data Fasilitas Wisata
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan_data_fasilitas_wisata.xls");

    // Tampil all data fasilitas
    $sqlFasilitas = 'SELECT * FROM t_fasilitas_wisata
                        LEFT JOIN t_kerjasama ON t_fasilitas_wisata.id_kerjasama = t_kerjasama.id_kerjasama
                        LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                        ORDER BY id_fasilitas_wisata ASC';

    $stmt = $pdo->prepare($sqlFasilitas);
    $stmt->execute();
    $rowFasilitas = $stmt->fetchAll();

    // Tampil untuk total biaya fasilitas
    $sqlFasilitas = 'SELECT SUM(biaya_kerjasama) AS total_biaya_fasilitas FROM t_fasilitas_wisata
                        LEFT JOIN t_kerjasama ON t_fasilitas_wisata.id_kerjasama = t_kerjasama.id_kerjasama
                        LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan';

    $stmt = $pdo->prepare($sqlFasilitas);
    $stmt->execute();
    $sumFasilitas = $stmt->fetchAll();

    function ageCalculator($dob)
    {
        $birthdate = new DateTime($dob);
        $today   = new DateTime('today');
        $ag = $birthdate->diff($today)->y;
        $mn = $birthdate->diff($today)->m;
        $dy = $birthdate->diff($today)->d;
        if ($mn == 0) {
            return "$dy Hari";
        } elseif ($ag == 0) {
            return "$mn Bulan  $dy Hari";
        } else {
            return "$ag Tahun $mn Bulan $dy Hari";
        }
    } ?>

    <table border="1">
        <thead>
            <tr>
                <th scope="col" colspan="7"><h2>LAPORAN DATA FASILITAS WISATA</h2></th>
            </tr>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Kode Fasilitas Wisata</th>
                <th scope="col">Nama Pengadaan Fasilitas</th>
                <th scope="col">Update Terakhir</th>
                <th scope="col">Status Pengadaan Fasilitas</th>
                <th scope="col">Status Kerjasama</th>
                <th scope="col">Biaya Kerjasama</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($rowFasilitas as $fasilitas) {
                $truedate = strtotime($fasilitas->update_terakhir); ?>
                <tr>
                    <th><?= $no ?></th>
                    <th scope="row"><?= $fasilitas->kode_fasilitas_wisata ?></th>
                    <td><?= $fasilitas->pengadaan_fasilitas ?></td>
                    <td>
                        <small class="text-muted"><b>Update Terakhir</b>
                            <br><?= strftime('%A, %d %B %Y', $truedate) . '<br> (' . ageCalculator($fasilitas->update_terakhir) . ' yang lalu)'; ?></small>
                    </td>
                    <td>
                        <?php if ($fasilitas->status_pengadaan == "Baik") { ?>
                            <span class="badge badge-pill badge-success"><?= $fasilitas->status_pengadaan ?></span>
                        <?php } elseif ($fasilitas->status_pengadaan == "Rusak") { ?>
                            <span class="badge badge-pill badge-warning"><?= $fasilitas->status_pengadaan ?></span>
                        <?php } elseif ($fasilitas->status_pengadaan == "Hilang") { ?>
                            <span class="badge badge-pill badge-danger"><?= $fasilitas->status_pengadaan ?></span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if ($fasilitas->status_kerjasama == "Melakukan Kerjasama") { ?>
                            <span class="badge badge-pill badge-success"><?= $fasilitas->status_kerjasama ?></span>
                        <?php } elseif ($fasilitas->status_kerjasama == "Tidak Melakukan Kerjasama") { ?>
                            <span class="badge badge-pill badge-warning"><?= $fasilitas->status_kerjasama ?></span>
                        <?php } ?>
                    </td>
                    <td>Rp. <?= number_format($fasilitas->biaya_kerjasama, 0) ?></td>
                </tr>
            <?php $no++;
            } ?>
        </tbody>
        <tfoot>
            <?php foreach ($sumFasilitas as $fasilitas) { ?>
                <tr>
                    <th colspan="6">Total Biaya Fasilitas Wisata</th>
                    <th>
                        Rp. <?= number_format($fasilitas->total_biaya_fasilitas, 0) ?>
                    </th>
                </tr>
            <?php } ?>
        </tfoot>
    </table>
<?php } elseif ($_GET['type'] == 'reservasi_wisata') {
// Cetak Laporan Periode ?>

<?php } elseif ($_GET['type'] == 'pengajuan') {
// Cetak Laporan Periode ?>

<?php } ?>