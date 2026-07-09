<?php
// Mulai session PHP
session_start();
header('Content-Type: application/json');

// Hanya terima method POST (karena ini akan ditembak oleh fetch JS)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method tidak diizinkan']);
    exit;
}

// Baca body JSON yang dikirim dari JavaScript
$body  = json_decode(file_get_contents('php://input'), true);
$token = trim($body['token']  ?? '');
$level = trim($body['level']  ?? '');
$uname = trim($body['username'] ?? '');

// Validasi jika token kosong
if (empty($token)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Token kosong']);
    exit;
}

// Simpan data penting ke session PHP 
// Ini yang dipakai halaman PHP lain untuk mengecek apakah user sudah login atau belum
$_SESSION['jwt_token'] = $token;
$_SESSION['level']     = $level;
$_SESSION['username']  = $uname;

// Beri balasan ke JavaScript bahwa token sukses disimpan
echo json_encode(['status' => 'ok']);
?>