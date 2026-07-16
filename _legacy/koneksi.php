<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "db_surat";

// Nonaktifkan exception mysqli otomatis agar halaman tidak mati mendadak pada PHP 8+
mysqli_report(MYSQLI_REPORT_OFF);

$conn = mysqli_connect($host, $user, $password, $database);
$db_error = null;

if (!$conn || mysqli_connect_errno()) {
    $db_error = mysqli_connect_error();
}
?>