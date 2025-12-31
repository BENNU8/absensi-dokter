<?php
include "../config/db.php";

$nama = $_POST['nama'];
$user = $_POST['username'];
$pass = $_POST['password'];

mysqli_query($conn,"INSERT INTO dokter
(nama,username,password,status)
VALUES('$nama','$user','$pass','aktif')");

header("Location: ../admin/dashboard.php");
