<?php
session_start();
require "koneksi.php";

if ($db_error) {
    die("Koneksi database gagal: " . htmlspecialchars($db_error));
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM surat_kehilangan WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}
    $_SESSION['flash_msg'] = ['text' => 'Data berhasil dihapus dari database.', 'type' => 'success'];
header("Location: data.php");
exit;
?>