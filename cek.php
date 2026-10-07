<?php
header('Content-Type: text/plain; charset=utf-8');
require __DIR__ . '/koneksi.php';

$result = $conn->query("SELECT COUNT(*) AS jumlah FROM barang");
if (!$result) {
    http_response_code(500);
    exit('QUERY_ERROR: ' . $conn->error);
}

$row = $result->fetch_assoc();

echo "KONEKSI BERHASIL
";
echo "DATABASE: db_lostfound_sekolah
";
echo "TABEL: barang
";
echo "JUMLAH DATA: " . $row['jumlah'] . "
";

$conn->close();
?>
