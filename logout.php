<?php
// Mulai session untuk memanggil brankas yang sedang aktif
session_start();

// Hancurkan seluruh isi brankas session (termasuk jwt_token, username, dan level)
session_destroy();

// Tendang kembali ke halaman login
header('Location: login.php');
exit;
?>