<?php
require 'db.php';

$id = $_GET['id'];
$sql = "DELETE FROM peserta WHERE id = $id";

if ($conn->query($sql)) {
    header("Location: crud.php");
    exit();
} else {
    echo "Error: " . $conn->error;
}
?>