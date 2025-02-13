<?php
header('Content-Type: application/json');

// Konfigurasi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bacs5153_recode";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Koneksi gagal: " . $conn->connect_error]));
}

// Default start_date ke Senin minggu ini, end_date ke Jumat minggu ini
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('monday this week'));
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d', strtotime('friday this week'));

// Ambil data jumlah hadir dan terlambat secara total (untuk pie chart)
$sqlHadir = "SELECT COUNT(*) AS total_hadir FROM Absensi WHERE Kehadiran = 'Hadir' AND DATE(Waktu) BETWEEN '$startDate' AND '$endDate'";
$resultHadir = $conn->query($sqlHadir);
$totalHadir = ($resultHadir->num_rows > 0) ? $resultHadir->fetch_assoc()['total_hadir'] : 0;

$sqlTerlambat = "SELECT COUNT(*) AS total_terlambat FROM Absensi WHERE Kehadiran = 'Terlambat' AND DATE(Waktu) BETWEEN '$startDate' AND '$endDate'";
$resultTerlambat = $conn->query($sqlTerlambat);
$totalTerlambat = ($resultTerlambat->num_rows > 0) ? $resultTerlambat->fetch_assoc()['total_terlambat'] : 0;

// Hitung belum absen
$totalSiswa = 1214;
$totalBelumAbsen = $totalSiswa - ($totalHadir + $totalTerlambat);

// Ambil data jumlah hadir dan terlambat per hari untuk bar chart
$sqlBarChart = "
    SELECT DATE(Waktu) as tanggal, Kehadiran, COUNT(*) as jumlah 
    FROM Absensi 
    WHERE DATE(Waktu) BETWEEN '$startDate' AND '$endDate'
    GROUP BY DATE(Waktu), Kehadiran
    ORDER BY tanggal;
";

$resultBarChart = $conn->query($sqlBarChart);
$barChartData = [];

// Inisialisasi array default untuk Senin-Jumat
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
foreach ($days as $day) {
    $barChartData[$day] = ["Hadir" => 0, "Terlambat" => 0];
}

// Masukkan data dari database ke dalam struktur array
while ($row = $resultBarChart->fetch_assoc()) {
    $dayOfWeek = date('l', strtotime($row['tanggal']));
    if (in_array($dayOfWeek, $days)) {
        $barChartData[$dayOfWeek][$row['Kehadiran']] = (int)$row['jumlah'];
    }
}

// Buat array untuk dikirim sebagai JSON
$data = [
    "hadir" => $totalHadir,
    "terlambat" => $totalTerlambat,
    "belumAbsen" => $totalBelumAbsen,
    "barChart" => $barChartData // Tambahan data per hari
];

$conn->close();
echo json_encode($data, JSON_PRETTY_PRINT);
?>
