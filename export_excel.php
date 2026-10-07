<?php
require __DIR__ . '/config.php';
require_login();
$conn = db();
$result = $conn->query('SELECT id,nama_barang,lokasi,keterangan,foto,tanggal FROM barang ORDER BY id DESC');
$filename = 'rekap_lost_found_' . date('Ymd_His') . '.xls';
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');
echo "<html><head><meta charset='utf-8'></head><body>";
echo '<table border="1"><tr><th>ID</th><th>Nama Barang</th><th>Lokasi</th><th>Keterangan</th><th>Foto</th><th>Tanggal</th></tr>';
while ($r = $result->fetch_assoc()) {
    echo '<tr><td>' . (int)$r['id'] . '</td><td>' . e($r['nama_barang']) . '</td><td>' . e($r['lokasi']) . '</td><td>' . e($r['keterangan'] ?? '') . '</td><td>' . e($r['foto'] ?? '') . '</td><td>' . e($r['tanggal']) . '</td></tr>';
}
echo '</table></body></html>';
$conn->close();
