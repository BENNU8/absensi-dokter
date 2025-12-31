<?php
session_start();
include "../config/db.php";

if(!isset($_SESSION['admin'])){
  header("Location: ../admin/login.php");
  exit;
}

$id = $_GET['id'] ?? '';

if($id!=''){
  mysqli_query($conn,"DELETE FROM dokter WHERE id='$id'");
}

header("Location: ../admin/dashboard.php");
