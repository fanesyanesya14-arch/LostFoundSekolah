<?php
// Menerima file mentah dari MIT App Inventor Web.PostFile.
header('Content-Type: text/plain; charset=utf-8');
require __DIR__ . '/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    exit('UPLOAD_FAIL');
}

$dir = __DIR__ . '/uploads';
if (!is_dir($dir) && !mkdir($dir, 0777, true)) {
    http_response_code(500);
    exit('UPLOAD_FAIL');
}

$bytes = file_get_contents('php://input');
if ($bytes === false || $bytes === '') {
    http_response_code(400);
    exit('UPLOAD_FAIL');
}

// Coba mengenali tipe gambar dari isi file.
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->buffer($bytes);
$exts = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
];
$ext = $exts[$mime] ?? 'jpg';

$filename = 'barang_' . $id . '_' . time() . '.' . $ext;
$path = $dir . '/' . $filename;

if (file_put_contents($path, $bytes) === false) {
    http_response_code(500);
    exit('UPLOAD_FAIL');
}

$stmt = $conn->prepare('UPDATE barang SET foto = ? WHERE id = ?');
if (!$stmt) {
    @unlink($path);
    http_response_code(500);
    exit('UPLOAD_FAIL');
}
$stmt->bind_param('si', $filename, $id);
if (!$stmt->execute()) {
    @unlink($path);
    $stmt->close();
    $conn->close();
    http_response_code(500);
    exit('UPLOAD_FAIL');
}
$stmt->close();
$conn->close();

echo 'UPLOAD_OK';
?>
