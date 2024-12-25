<?php
// Menghubungkan ke database
require 'db.php';

// Proses saat form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $nama = $_POST['nama'];
    $cabang = $_POST['cabang'];
    $umur = $_POST['umur'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];

    // Memasukkan data ke dalam database
    $sql = "INSERT INTO peserta (nama, cabang, umur, email, telepon) 
            VALUES ('$nama', '$cabang', '$umur', '$email', '$telepon')";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect ke halaman daftar peserta setelah berhasil menyimpan
        header("Location: crud.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
