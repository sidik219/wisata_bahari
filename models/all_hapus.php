<?php 
include '../app/database/koneksi.php';
session_start();

$type = $_GET['type'];

if (empty($type)) {
    header('Location: index');
    // Jarak
} elseif ($type == 'user') {
    $id_user = $_GET['id_user'];

    $sql = 'DELETE FROM t_user
            WHERE id_user = :id_user';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_user' => $id_user]);
    
    header('Location: view_kelola_user?status=hapusBerhasil');
    // Jarak User
} elseif ($type == 'provinsi') {
    $id_provinsi = $_GET['id_provinsi'];

    $sql = 'DELETE FROM t_provinsi
            WHERE id_provinsi = :id_provinsi';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_provinsi' => $id_provinsi]);
    
    header('Location: view_kelola_provinsi?status=hapusBerhasil');
    // Jarak Provinsi
} elseif ($type == 'wilayah') {
    $id_wilayah = $_GET['id_wilayah'];

    $sql = 'DELETE FROM t_wilayah
            WHERE id_wilayah = :id_wilayah';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_wilayah' => $id_wilayah]);
    
    header('Location: view_kelola_wilayah?status=hapusBerhasil');
    // Jarak Wilayah
} elseif ($type == 'lokasi') {
    $id_lokasi = $_GET['id_lokasi'];

    $sql = 'DELETE FROM t_lokasi
            WHERE id_lokasi = :id_lokasi';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_lokasi' => $id_lokasi]);
    
    header('Location: view_kelola_lokasi?status=hapusBerhasil');
    
    // Jarak Lokasi
} elseif ($type == 'paket_wisata') {
    $id_paket_wisata = $_GET['id_paket_wisata'];

    $sql = 'DELETE t_paket_wisata , t_wisata, t_fasilitas_wisata  FROM t_paket_wisata  
            INNER JOIN t_wisata
            INNER JOIN t_fasilitas_wisata
            WHERE t_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata
            AND t_wisata.id_wisata = t_fasilitas_wisata.id_wisata
            AND t_paket_wisata.id_paket_wisata = :id_paket_wisata';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_paket_wisata' => $_GET['id_paket_wisata']]);

    header('Location: view_kelola_wisata?status=hapusBerhasil');
    // Jarak Paket Wisata
} elseif ($type == 'fasilitas') {
    $id_fasilitas_wisata = $_GET['id_fasilitas_wisata'];

    $sql = 'DELETE FROM t_fasilitas_wisata
            WHERE id_fasilitas_wisata = :id_fasilitas_wisata';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_fasilitas_wisata' => $id_fasilitas_wisata]);
    
    header('Location: view_kelola_fasilitas_wisata?status=hapusBerhasil');
    // Jarak Asuransi
} elseif ($type == 'asuransi') {
    $id_asuransi = $_GET['id_asuransi'];

    $sql = 'DELETE FROM t_asuransi
            WHERE id_asuransi = :id_asuransi';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_asuransi' => $id_asuransi]);
    
    header('Location: view_kelola_asuransi?status=hapusBerhasil');
    // Jarak Kerjasama
} elseif ($type == 'kerjasama') {
    $id_kerjasama = $_GET['id_kerjasama'];

    $sql = 'DELETE FROM t_kerjasama
            WHERE id_kerjasama = :id_kerjasama';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_kerjasama' => $id_kerjasama]);
    
    header('Location: view_kelola_kerjasama?status=hapusBerhasil');
    // Jarak Pengadaan
} elseif ($type == 'pengadaan') {
    $id_pengadaan = $_GET['id_pengadaan'];

    $sql = 'DELETE FROM t_pengadaan_fasilitas
            WHERE id_pengadaan = :id_pengadaan';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_pengadaan' => $id_pengadaan]);
    
    header('Location: view_kelola_pengadaan?status=hapusBerhasil');
    // Jarak Reservasi Wisata
} elseif ($type == 'reservasi_wisata') {
    $id_reservasi_wisata = $_GET['id_reservasi_wisata'];

    $sql = 'DELETE FROM t_reservasi_wisata
            WHERE id_reservasi_wisata = :id_reservasi_wisata';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_reservasi_wisata' => $id_reservasi_wisata]);
    header('Location: view_kelola_reservasi_wisata.php?status=hapusBerhasil');
    // Jarak
} elseif ($type == 'pengajuan') {
    $id_pengajuan = $_GET['id_pengajuan'];

    $sql = 'DELETE FROM t_pengajuan
            WHERE id_pengajuan = :id_pengajuan';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_pengajuan' => $id_pengajuan]);
    header('Location: view_kelola_pengajuan.php?status=hapusBerhasil');
    // Jarak
} elseif ($type == 'pengeluaran') {
    $id_reservasi_wisata = $_GET['id_reservasi_wisata'];
    $id_pengeluaran = $_GET['id_pengeluaran'];

    $sql = 'DELETE FROM t_pengeluaran
            WHERE id_pengeluaran = :id_pengeluaran';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_pengeluaran' => $id_pengeluaran]);
    header('Location: kelola_laporan_pengeluaran.php?id_reservasi_wisata=' . $id_reservasi_wisata . '&status=hapusBerhasil');
    // Jarak
}
?>