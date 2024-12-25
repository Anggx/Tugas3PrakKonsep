<?php
require 'db.php';

// Menghapus peserta jika ada request delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM peserta WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: crud.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Proses pencarian nama
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM peserta WHERE nama LIKE '%$search%'";
} else {
    // Ambil data peserta dari database jika tidak ada pencarian
    $sql = "SELECT * FROM peserta";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Peserta Lomba Olahraga</title>
    <link href="https://fonts.googleapis.com/css2?family=Sancreek&display=swap" rel="stylesheet">
    <style>
        /* Mengatur body dan html */
        body {
            font-family: 'Sancreek', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #D2B48C, #D0B49F); /* Gradien coklat muda */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }

        .container {
            width: 80%;
            max-width: 1000px;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.9); /* Transparansi putih */
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 28px;
            color: #C49A6A; /* Coklat muda untuk judul */
            font-family: 'Sancreek', cursive; /* Font khusus */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #C49A6A; /* Coklat muda untuk border */
        }

        th {
            background-color: #C49A6A; /* Coklat muda untuk header tabel */
            color: white;
        }

        a {
            text-decoration: none;
            color: #C49A6A; /* Coklat muda untuk link */
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .button {
            background-color: #C49A6A; /* Coklat muda untuk tombol */
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .button:hover {
            background-color: #B08B6E; /* Lebih gelap untuk hover */
        }

        .actions a {
            margin: 0 10px;
            background-color: #C49A6A; /* Coklat muda untuk tombol di kolom aksi */
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
        }

        .actions a:hover {
            background-color: #B08B6E; /* Lebih gelap untuk hover */
        }

        .search-bar {
            margin-top: 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: flex-start; /* Align kiri */
            align-items: center;
        }

        .search-bar input[type="text"] {
            padding: 12px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #C49A6A; /* Coklat muda untuk border */
            flex: 1; /* Input menyesuaikan ruang */
            max-width: 500px;
        }

        .search-bar button {
            padding: 12px 15px;
            background-color: #C49A6A; /* Coklat muda untuk tombol Cari */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px; /* Space antara input dan tombol */
        }

        .search-bar button:hover {
            background-color: #B08B6E; /* Lebih gelap untuk hover */
        }

        .top-buttons {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Daftar Peserta Lomba Olahraga</h1>
        
        <!-- Form pencarian diletakkan di atas tombol daftar peserta baru -->
        <div class="search-bar">
            <form action="crud.php" method="GET">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari berdasarkan nama..." required>
                <button type="submit">Cari</button>
            </form>
        </div>

        <div class="top-buttons">
            <a href="Pendaftaran.html" class="button">Daftar Peserta Baru</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Cabang Olahraga</th>
                    <th>Umur</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['nama'] ?></td>
                        <td><?= $row['cabang'] ?></td>
                        <td><?= $row['umur'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['telepon'] ?></td>
                        <td class="actions">
                            <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> | 
                            <a href="crud.php?delete=<?= $row['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus peserta ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
