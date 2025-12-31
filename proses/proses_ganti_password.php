<?php
session_start();
include "../config/db.php";

if(!isset($_SESSION['admin'])){
  header("Location: ../admin/login.php");
  exit;
}

$id = $_POST['id'];
$password = $_POST['password'];

if($password!=''){
  mysqli_query($conn,"
    UPDATE dokter SET password='$password'
    WHERE id='$id'
  ");
}

header("Location: ../admin/dashboard.php");
