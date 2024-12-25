<?php
require 'db.php';

// Proses Pembaruan (Update)
if (isset($_POST['update'])) {
    $id = $_GET['id']; // Ambil ID dari URL
    $nama = $_POST['nama'];
    $cabang = $_POST['cabang'];
    $umur = $_POST['umur'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];

    // Query untuk update data peserta
    $sql = "UPDATE peserta SET 
            nama = '$nama', 
            cabang = '$cabang', 
            umur = $umur, 
            email = '$email', 
            telepon = '$telepon' 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Setelah berhasil update, redirect ke halaman CRUD
        header("Location: crud.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Cek apakah parameter 'id' ada di URL untuk mengambil data peserta
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mengambil data berdasarkan ID
    $sql = "SELECT * FROM peserta WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("Peserta tidak ditemukan.");
    }
} else {
    die("ID tidak valid.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pendaftaran Peserta</title>
    <link href="https://fonts.googleapis.com/css2?family=Sancreek&display=swap" rel="stylesheet">
    <style>
        /* Styling dasar */
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Sancreek', sans-serif; /* Font Sancreek untuk keseluruhan */
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(to right, #D2B48C, #D0B49F); /* Gradien coklat muda */
        }

        /* Kontainer untuk form */
        .container {
            max-width: 400px;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.9); /* Transparansi putih */
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        h1 {
            font-size: 2.2em;
            color: #C49A6A; /* Coklat muda untuk judul */
            margin-bottom: 20px;
            text-align: center;
            font-family: 'Sancreek', cursive; /* Font untuk judul */
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 1.1em;
            color: #333;
            font-weight: bold;
        }

        input, select {
            padding: 10px;
            font-size: 1em;
            border: 1px solid #C49A6A; /* Coklat muda untuk border */
            border-radius: 6px;
            outline: none;
            background-color: #FFF5F8;
        }

        input:focus, select:focus {
            border-color: #D2B48C; /* Warna fokus */
        }

        button {
            padding: 12px;
            background-color: #C49A6A; /* Coklat muda untuk tombol */
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #B08B6E; /* Lebih gelap untuk hover */
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Edit Pendaftaran Peserta</h1>
        <!-- Form untuk edit data peserta -->
        <form action="edit.php?id=<?= $row['id'] ?>" method="POST">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" value="<?= $row['nama'] ?>" required>

            <label for="cabang">Cabang Olahraga:</label>
            <input type="text" id="cabang" name="cabang" value="<?= $row['cabang'] ?>" required>

            <label for="umur">Umur:</label>
            <input type="number" id="umur" name="umur" value="<?= $row['umur'] ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= $row['email'] ?>" required>

            <label for="telepon">Telepon:</label>
            <input type="text" id="telepon" name="telepon" value="<?= $row['telepon'] ?>" required>

            <button type="submit" name="update">Simpan Perubahan</button>
        </form>
    </div>

</body>
</html>
