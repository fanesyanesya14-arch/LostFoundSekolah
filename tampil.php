<?php
header('Content-Type: text/plain; charset=utf-8');
require __DIR__ . '/koneksi.php';

$sql = "SELECT id, nama_barang, lokasi, keterangan, tanggal
        FROM barang
        ORDER BY id DESC";

$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    exit('QUERY_ERROR: ' . $conn->error);
}

$lines = [];

while ($row = $result->fetch_assoc()) {
    $nama = trim($row['nama_barang']);
    $lokasi = trim($row['lokasi']);
    $keterangan = trim($row['keterangan'] ?? '');
    $tanggal = date('d-m-Y H:i', strtotime($row['tanggal']));

    $lines[] =
        'ID ' . $row['id'] .
        ' | Nama: ' . $nama .
        ' | Lokasi: ' . $lokasi .
        ' | Keterangan: ' . $keterangan .
        ' | Tanggal: ' . $tanggal;
}

echo implode(PHP_EOL, $lines);

$conn->close();
?>
