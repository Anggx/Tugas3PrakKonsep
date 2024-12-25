<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "crud_db");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Variabel untuk pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Pagination setup
$limit = 5;  // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Hitung total halaman
$sqlCount = "SELECT COUNT(*) as total FROM users WHERE name LIKE ?";
$stmtCount = $conn->prepare($sqlCount);
$searchLike = "%$search%";
$stmtCount->bind_param("s", $searchLike);
$stmtCount->execute();
$resultCount = $stmtCount->get_result();
$totalData = $resultCount->fetch_assoc()['total'];
$totalPages = ceil($totalData / $limit);

// Query untuk menampilkan data dengan filter pencarian dan batas pagination
$sql = "SELECT * FROM users WHERE name LIKE ? LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $searchLike, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Tambah data pengguna
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $usia = $_POST['usia'];  // Ambil data usia
    $cabang = $_POST['cabang'];  // Ambil data cabang
    $alamat = $_POST['alamat'];  // Ambil data alamat

    $sqlInsert = "INSERT INTO users (name, email, phone, usia, cabang, alamat) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("sssiss", $name, $email, $phone, $usia, $cabang, $alamat);  // Bind parameter
    if ($stmtInsert->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmtInsert->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD dengan Modal Tambah Pengguna</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #e0f7fa; /* Soft blue background */
            color: #4f4f4f;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full screen height */
            margin: 0;
        }

        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 900px;
            width: 100%;
        }

        h2 {
            color: #007acc; /* Soft blue for title */
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center; /* Center title */
        }

        .btn-primary, .btn-success {
            background-color: #64b5f6; /* Soft blue button */
            border: none;
        }

        .btn-primary:hover, .btn-success:hover {
            background-color: #42a5f5; /* Slightly darker on hover */
        }

        .btn-danger {
            background-color: #ef5350; /* Light red for delete button */
            border: none;
        }

        .btn-danger:hover {
            background-color: #e53935; /* Darker red on hover */
        }

        .table {
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 10px;
            width: 100%;
        }

        .thead-light {
            background-color: #e1f5fe; /* Light blue for table header */
            color: #4f4f4f;
            font-weight: 600;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #e3f2fd; /* Very light blue rows */
        }

        .table-striped tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        .pagination .page-item.active .page-link {
            background-color: #42a5f5;
            border-color: #42a5f5;
            color: #ffffff;
        }

        .pagination .page-item a {
            color: #007acc;
        }

        .modal-header {
            background-color: #64b5f6; /* Soft blue for modal header */
            color: white;
        }

        .btn-secondary {
            background-color: #e3f2fd; /* Light blue for secondary buttons */
            color: #4f4f4f;
            border: 1px solid #42a5f5;
        }

        .btn-secondary:hover {
            background-color: #bbdefb;
        }

        input:focus {
            border-color: #42a5f5 !important;
            box-shadow: 0 0 5px rgba(66, 165, 245, 0.5);
        }

        .form-inline {
            justify-content: center; /* Center search form */
            margin-bottom: 20px;
        }

        .form-inline input {
            border-radius: 5px;
            border: 1px solid #64b5f6;
        }

        .add-user-btn {
            text-align: left;
            margin-bottom: 15px; /* Keep button above the table */
        }

        .form-group label {
            font-weight: bold;
            color: #42a5f5;
        }

        .form-group input {
            border: 1px solid #90caf9;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Daftar Pengguna</h2>

    <!-- Form Pencarian -->
    <form method="GET" action="" class="form-inline">
        <input type="text" name="search" value="<?php echo $search; ?>" class="form-control mr-2" placeholder="Cari nama..." style="width: 250px;">
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>

    <!-- Tombol Tambah Pengguna -->
    <div class="add-user-btn">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addUserModal">
            Tambah Pengguna
        </button>
    </div>

    <!-- Tabel Data Pengguna -->
    <table class="table table-bordered table-striped">
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Usia</th>
                <th>Cabang</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['usia']; ?></td>
                <td><?php echo $row['cabang']; ?></td>
                <td><?php echo $row['alamat']; ?></td>
                <td>
                    <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                <a class="page-link" href="?search=<?php echo $search; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
            <?php } ?>
        </ul>
    </nav>
</div>

<!-- Modal Tambah Pengguna -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Tambah Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group">
                        <label for="name">Nama:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Telepon:</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="usia">Usia:</label>
                        <input type="number" class="form-control" id="usia" name="usia" required>
                    </div>
                    <div class="form-group">
                        <label for="cabang">Cabang:</label>
                        <input type="text" class="form-control" id="cabang" name="cabang" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat:</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
