<?php include '../app/database/koneksi.php'; ?>

<script>
        $('#btn-wisatawan').click(function () {
            $('#wisatawan').get(0).toBlob(function (blob) {
                saveAs(blob, 'data_pengunjung.png')
            });
        });

        // Any of the following formats may be used
        var ctx = document.getElementById('wisatawan');
        var wisatawan = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($label); ?>, //12 Bulan
                datasets: [{
                    label: '2021',
                    data: <?php echo json_encode($total_wisatawan); ?>, //Total Pengunjung Berdasarkan Bulan
                    // data: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Data Wisatawan',
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    }
                }
            }
        });

        // Reservasi Wisata
        $('#btn-reservasi').click(function () {
            $('#reservasi').get(0).toBlob(function (blob) {
                saveAs(blob, 'data_pendapatan_wisata.png')
            });
        });

        // Any of the following formats may be used
        var ctx = document.getElementById('reservasi');
        var reservasi = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($label); ?>, //12 Bulan
                datasets: [{
                    label: '2021',
                    data: <?php echo json_encode($pendapatan_reservasi); ?>, //Total Pengunjung Berdasarkan Bulan
                    // data: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Data Wisatawan',
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    }
                }
            }
        });
    </script>