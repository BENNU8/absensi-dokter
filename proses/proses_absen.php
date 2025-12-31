<?php
session_start();
include "../config/db.php";

date_default_timezone_set("Asia/Jakarta");

/* ===============================
   CEK LOGIN
================================ */
if(!isset($_SESSION['dokter'])){
  header("Location: ../dokter/login.php");
  exit;
}

/* ===============================
   AMBIL ID DOKTER
================================ */
$dokter_id = $_SESSION['dokter'];

/* ===============================
   CEK FOTO
================================ */
$fotoBase64 = $_POST['foto'] ?? '';
if($fotoBase64 == ''){
  echo "<script>alert('Foto belum diambil');history.back();</script>";
  exit;
}

/* ===============================
   DECODE BASE64
================================ */
$fotoBase64 = str_replace('data:image/png;base64,','',$fotoBase64);
$fotoBase64 = str_replace(' ','+',$fotoBase64);
$dataFoto   = base64_decode($fotoBase64);

if(!$dataFoto){
  echo "<script>alert('Data foto rusak');history.back();</script>";
  exit;
}

/* ===============================
   FOLDER UPLOAD
================================ */
$folder = "../uploads/absen/";
if(!is_dir($folder)){
  mkdir($folder,0777,true);
}

/* ===============================
   NAMA FILE
================================ */
$namaFile = 'absen_'.$dokter_id.'_'.date('Ymd_His').'.png';
$pathFile = $folder.$namaFile;

/* ===============================
   SIMPAN FOTO AWAL
================================ */
file_put_contents($pathFile,$dataFoto);

/* ===============================
   TANGGAL & JAM
================================ */
$tanggal = date("Y-m-d");
$jam     = date("H:i:s");

/* ===============================
   STATUS TELAT
================================ */
$jamBatas = "08:00:00";
$status_waktu = ($jam <= $jamBatas) ? "Tepat Waktu" : "Telat";

/* ===============================
   WATERMARK (JIKA GD AKTIF)
================================ */
if(function_exists('imagecreatefrompng')){
  $img = @imagecreatefrompng($pathFile);

  if($img){
    $text = date("d-m-Y H:i:s");

    $warnaPutih = imagecolorallocatealpha($img,255,255,255,40);
    $warnaHitam = imagecolorallocatealpha($img,0,0,0,70);

    $x = 10;
    $y = imagesy($img) - 20;

    // shadow
    imagestring($img,5,$x+1,$y+1,$text,$warnaHitam);
    // text utama
    imagestring($img,5,$x,$y,$text,$warnaPutih);

    imagepng($img,$pathFile);
    imagedestroy($img);
  }
}

/* ===============================
   SIMPAN KE DATABASE
================================ */
mysqli_query($conn,"
INSERT INTO absensi 
(dokter_id,foto,tanggal,jam,status_waktu)
VALUES
('$dokter_id',
 '$namaFile',
 '$tanggal',
 '$jam',
 '$status_waktu')
");

/* ===============================
   SELESAI
================================ */
echo "<script>
alert('Absensi berhasil ($status_waktu)');
location='../dokter/dashboard.php';
</script>";
