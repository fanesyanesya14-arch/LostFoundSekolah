<?php
// Kompatibel dengan MIT App Inventor Web.PostText.
// Menerima application/x-www-form-urlencoded maupun raw text body.
header('Content-Type: text/plain; charset=utf-8');
require __DIR__ . '/koneksi.php';

$nama = $_POST['nama_barang'] ?? '';
$lokasi = $_POST['lokasi'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';

// App Inventor Web.PostText pada beberapa konfigurasi mengirim raw body.
if ($nama === '' && $lokasi === '' && $keterangan === '') {
    $raw = file_get_contents('php://input');
    if ($raw !== '') {
        $parsed = [];
        parse_str($raw, $parsed);
        $nama = $parsed['nama_barang'] ?? '';
        $lokasi = $parsed['lokasi'] ?? '';
        $keterangan = $parsed['keterangan'] ?? '';
    }
}

$nama = trim((string)$nama);
$lokasi = trim((string)$lokasi);
$keterangan = trim((string)$keterangan);

if ($nama === '' || $lokasi === '') {
    http_response_code(400);
    exit('0');
}

$stmt = $conn->prepare('INSERT INTO barang (nama_barang, lokasi, keterangan) VALUES (?, ?, ?)');
if (!$stmt) {
    http_response_code(500);
    exit('0');
}

$stmt->bind_param('sss', $nama, $lokasi, $keterangan);
if (!$stmt->execute()) {
    http_response_code(500);
    $stmt->close();
    $conn->close();
    exit('0');
}

$id = $conn->insert_id;
$stmt->close();
$conn->close();

echo (string)$id;
?>
