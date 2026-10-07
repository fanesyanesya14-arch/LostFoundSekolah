<?php
require __DIR__ . '/config.php';
require_login();

$conn = db();

$total = 0;
$latest = 0;
$today = 0;

if ($res = $conn->query('SELECT COUNT(*) AS c FROM barang')) {
    $total = (int)$res->fetch_assoc()['c'];
}
if ($res = $conn->query('SELECT COUNT(*) AS c FROM barang WHERE DATE(tanggal)=CURDATE()')) {
    $today = (int)$res->fetch_assoc()['c'];
}
if ($res = $conn->query('SELECT COUNT(*) AS c FROM barang WHERE id=(SELECT MAX(id) FROM barang)')) {
    $latest = (int)$res->fetch_assoc()['c'];
}

$search = trim($_GET['q'] ?? '');
$rows = [];

if ($search !== '') {
    $stmt = $conn->prepare('SELECT id,nama_barang,lokasi,keterangan,foto,tanggal FROM barang WHERE nama_barang LIKE CONCAT("%", ?, "%") OR lokasi LIKE CONCAT("%", ?, "%") OR keterangan LIKE CONCAT("%", ?, "%") ORDER BY id DESC');
    $stmt->bind_param('sss', $search, $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query('SELECT id,nama_barang,lokasi,keterangan,foto,tanggal FROM barang ORDER BY id DESC');
}

while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
$conn->close();
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard Admin — Lost &amp; Found Sekolah</title>
<style>
:root{--navy:#12233f;--blue:#2563eb;--sky:#eaf1ff;--bg:#f5f7fb;--white:#fff;--text:#172033;--muted:#667085;--line:#e6eaf0;--green:#0f766e}
*{box-sizing:border-box}body{margin:0;background:var(--bg);font-family:Inter,Segoe UI,Arial,sans-serif;color:var(--text)}a{text-decoration:none;color:inherit}.top{background:var(--navy);color:#fff;padding:18px 4vw;display:flex;align-items:center;justify-content:space-between;gap:20px}.brand{display:flex;align-items:center;gap:12px}.mark{width:42px;height:42px;border-radius:12px;background:#fff;color:var(--navy);display:grid;place-items:center;font-weight:900}.brand h1{font-size:19px;margin:0}.brand p{font-size:12px;margin:3px 0 0;opacity:.75}.logout{border:1px solid rgba(255,255,255,.25);padding:9px 13px;border-radius:10px;font-size:13px}.wrap{width:min(1220px,92%);margin:26px auto 42px}.head{display:flex;align-items:end;justify-content:space-between;gap:16px;margin-bottom:18px}.head h2{margin:0;font-size:26px}.head p{margin:6px 0 0;color:var(--muted)}.actions{display:flex;gap:9px;flex-wrap:wrap}.btn{display:inline-flex;align-items:center;justify-content:center;padding:10px 14px;border-radius:10px;font-weight:800;font-size:13px;border:1px solid var(--line);background:#fff}.btn.primary{background:var(--blue);color:#fff;border-color:var(--blue)}.btn.green{background:var(--green);color:#fff;border-color:var(--green)}.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:15px;margin-bottom:18px}.stat{background:#fff;border:1px solid var(--line);border-radius:16px;padding:18px}.stat .label{font-size:13px;color:var(--muted)}.stat .value{font-size:29px;font-weight:900;margin-top:6px}.card{background:#fff;border:1px solid var(--line);border-radius:18px;overflow:hidden}.bar{padding:16px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}.search{display:flex;gap:8px;flex:1;max-width:540px}.search input{flex:1;min-width:180px;padding:10px 12px;border:1px solid #d8dee8;border-radius:10px;font-size:14px}.tablewrap{overflow:auto}table{width:100%;border-collapse:collapse;min-width:840px}th,td{padding:13px 14px;border-bottom:1px solid #edf0f4;text-align:left;vertical-align:middle;font-size:13px}th{background:#fafbfd;color:#475467;font-size:12px;text-transform:uppercase;letter-spacing:.03em}tr:hover td{background:#fbfcff}.thumb{width:56px;height:44px;border-radius:9px;object-fit:cover;background:#edf2f7;border:1px solid #e2e8f0}.tag{display:inline-block;padding:5px 8px;border-radius:999px;background:var(--sky);color:#1d4ed8;font-weight:700;font-size:11px}.empty{padding:42px;text-align:center;color:var(--muted)}.foot{padding:13px 16px;color:var(--muted);font-size:12px}.modal-link{color:var(--blue);font-weight:700}
@media(max-width:760px){.stats{grid-template-columns:1fr}.head{align-items:flex-start;flex-direction:column}.top{padding:15px 4%}}
</style>
</head>
<body>
<header class="top"><div class="brand"><div class="mark">LF</div><div><h1>Lost &amp; Found Sekolah</h1><p>Dashboard Admin</p></div></div><a class="logout" href="logout.php">Keluar</a></header>
<main class="wrap">
  <div class="head"><div><h2>Rekapitulasi Barang</h2><p>Kelola dan pantau barang temuan dari aplikasi.</p></div><div class="actions"><a class="btn green" href="export_excel.php">Export Excel</a><a class="btn primary" href="print.php" target="_blank">Cetak Rekap</a></div></div>
  <section class="stats"><div class="stat"><div class="label">Total Barang</div><div class="value"><?= $total ?></div></div><div class="stat"><div class="label">Input Hari Ini</div><div class="value"><?= $today ?></div></div><div class="stat"><div class="label">Data Terbaru</div><div class="value"><?= $latest ?></div></div></section>
  <section class="card">
    <div class="bar"><form class="search" method="get"><input name="q" value="<?= e($search) ?>" placeholder="Cari nama, lokasi, atau keterangan..."><button class="btn primary" type="submit">Cari</button><?php if($search!==''): ?><a class="btn" href="index.php">Reset</a><?php endif; ?></form><div style="color:#667085;font-size:13px"><?= count($rows) ?> data ditampilkan</div></div>
    <?php if (!$rows): ?><div class="empty">Belum ada data yang cocok.</div><?php else: ?>
    <div class="tablewrap"><table><thead><tr><th>Foto</th><th>ID</th><th>Nama Barang</th><th>Lokasi</th><th>Keterangan</th><th>Tanggal</th></tr></thead><tbody>
    <?php foreach($rows as $row): ?>
      <tr>
        <td><?php if(!empty($row['foto'])): ?><a href="../lost_found_api/uploads/<?= rawurlencode($row['foto']) ?>" target="_blank"><img class="thumb" src="../lost_found_api/uploads/<?= rawurlencode($row['foto']) ?>" alt="Foto barang"></a><?php else: ?><div class="thumb"></div><?php endif; ?></td>
        <td><span class="tag">#<?= (int)$row['id'] ?></span></td>
        <td><strong><?= e($row['nama_barang']) ?></strong></td>
        <td><?= e($row['lokasi']) ?></td>
        <td><?= e($row['keterangan'] ?? '') ?></td>
        <td><?= e(date('d/m/Y H:i', strtotime($row['tanggal']))) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody></table></div>
    <div class="foot">Data diambil langsung dari database <strong>lost_found</strong>, tabel <strong>barang</strong>.</div>
    <?php endif; ?>
  </section>
</main>
</body>
</html>
