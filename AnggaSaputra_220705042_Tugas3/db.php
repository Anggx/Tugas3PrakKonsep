<?php
// Konfigurasi database
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'lomba_olahraga';

// Koneksi ke database
$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>