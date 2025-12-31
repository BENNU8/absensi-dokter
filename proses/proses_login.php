<?php
session_start();
include "../config/db.php";

$u=$_POST['username'];
$p=$_POST['password'];
$r=$_POST['role'];

if($r=="admin"){
$q=mysqli_query($conn,"SELECT * FROM admin WHERE username='$u' AND password='$p'");
if(mysqli_num_rows($q)){
$_SESSION['admin']=$u;
header("Location: ../admin/dashboard.php");
}
}else{
$q=mysqli_query($conn,"SELECT * FROM dokter WHERE username='$u' AND password='$p'");
if($d=mysqli_fetch_assoc($q)){
$_SESSION['dokter']=$d['id'];
$_SESSION['nama']=$d['nama'];
header("Location: ../dokter/dashboard.php");
}
}
echo "Login gagal";
