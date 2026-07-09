<?php
$koneksi = mysqli_connect("localhost:3308", "root", "", "resto");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>
