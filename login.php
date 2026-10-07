<?php
require __DIR__ . '/config.php';

if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (hash_equals(ADMIN_USER, $username) && hash_equals(ADMIN_PASS, $password)) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = ADMIN_USER;
        header('Location: index.php');
        exit;
    }
    $error = 'Username atau password salah.';
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login Admin — Lost & Found Sekolah</title>
<style>
:root{--navy:#12233f;--blue:#2563eb;--bg:#f3f6fb;--text:#172033;--muted:#6b7280;--card:#fff}
*{box-sizing:border-box}body{margin:0;font-family:Inter,Segoe UI,Arial,sans-serif;background:linear-gradient(135deg,#eef4ff,#f8fbff);color:var(--text);min-height:100vh;display:grid;place-items:center;padding:20px}
.wrap{width:min(430px,100%)}.brand{text-align:center;margin-bottom:18px}.brand .mark{width:66px;height:66px;border-radius:18px;background:var(--navy);color:#fff;display:inline-grid;place-items:center;font-weight:800;font-size:24px;box-shadow:0 12px 30px rgba(18,35,63,.18)}
.brand h1{margin:14px 0 6px;font-size:27px}.brand p{margin:0;color:var(--muted)}.card{background:var(--card);border:1px solid #e5eaf2;border-radius:22px;padding:28px;box-shadow:0 18px 50px rgba(34,51,84,.10)}
label{display:block;font-weight:700;font-size:14px;margin:0 0 7px}.field{margin-bottom:17px}input{width:100%;padding:13px 14px;border:1px solid #d8dee9;border-radius:12px;font-size:15px;outline:none}input:focus{border-color:#7aa2ff;box-shadow:0 0 0 4px rgba(37,99,235,.10)}button{width:100%;border:0;border-radius:12px;padding:13px 16px;background:var(--blue);color:#fff;font-weight:800;font-size:15px;cursor:pointer}.error{background:#fff0f0;color:#b42318;border:1px solid #ffd0d0;border-radius:11px;padding:11px 12px;margin-bottom:16px;font-size:14px}.hint{font-size:12px;color:var(--muted);text-align:center;margin-top:14px}
</style>
</head>
<body>
<div class="wrap">
  <div class="brand"><div class="mark">LF</div><h1>Lost &amp; Found Sekolah</h1><p>Panel Administrasi</p></div>
  <div class="card">
    <?php if ($error): ?><div class="error"><?= e($error) ?></div><?php endif; ?>
    <form method="post" autocomplete="off">
      <div class="field"><label>Username</label><input name="username" required autofocus></div>
      <div class="field"><label>Password</label><input type="password" name="password" required></div>
      <button type="submit">Masuk ke Dashboard</button>
    </form>
    <div class="hint">Demo: Admin / 12345</div>
  </div>
</div>
</body>
</html>
