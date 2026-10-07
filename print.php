<?php
require __DIR__ . '/config.php';
require_login();
$conn = db();
$result = $conn->query('SELECT id,nama_barang,lokasi,keterangan,foto,tanggal FROM barang ORDER BY id DESC');
$rows = [];
while ($r = $result->fetch_assoc()) $rows[] = $r;
$conn->close();
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><title>Cetak Rekap Lost &amp; Found</title><style>body{font-family:Arial,sans-serif;color:#111;margin:28px}h1{margin:0 0 5px}p{margin:0 0 20px;color:#555}table{width:100%;border-collapse:collapse}th,td{border:1px solid #aaa;padding:8px;font-size:12px}th{background:#eee;text-align:left}@media print{body{margin:10mm}.no-print{display:none}}</style></head><body><div class="no-print" style="margin-bottom:14px"><button onclick="window.print()">Cetak</button> <button onclick="window.close()">Tutup</button></div><h1>Rekap Barang Lost &amp; Found Sekolah</h1><p>Tanggal cetak: <?= e(date('d/m/Y H:i')) ?></p><table><thead><tr><th>ID</th><th>Nama Barang</th><th>Lokasi</th><th>Keterangan</th><th>Foto</th><th>Tanggal</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= (int)$r['id'] ?></td><td><?= e($r['nama_barang']) ?></td><td><?= e($r['lokasi']) ?></td><td><?= e($r['keterangan'] ?? '') ?></td><td><?= e($r['foto'] ?? '-') ?></td><td><?= e(date('d/m/Y H:i', strtotime($r['tanggal']))) ?></td></tr><?php endforeach; ?></tbody></table></body></html>
