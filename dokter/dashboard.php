<?php
session_start();
include "../config/db.php";
date_default_timezone_set("Asia/Jakarta");

if(!isset($_SESSION['dokter'])){
  header("Location: login.php");
  exit;
}

$id   = $_SESSION['dokter'];
$nama = $_SESSION['nama'];
$today = date('Y-m-d');

/* ===== STAT ===== */
$total = mysqli_fetch_assoc(mysqli_query($conn,"
  SELECT COUNT(*) total FROM absensi WHERE dokter_id='$id'
"))['total'];

$bulanIni = mysqli_fetch_assoc(mysqli_query($conn,"
  SELECT COUNT(*) total FROM absensi
  WHERE dokter_id='$id'
  AND MONTH(tanggal)=MONTH(CURDATE())
  AND YEAR(tanggal)=YEAR(CURDATE())
"))['total'];

$cek = mysqli_fetch_assoc(mysqli_query($conn,"
  SELECT COUNT(*) total FROM absensi
  WHERE dokter_id='$id' AND tanggal='$today'
"));
$sudah = $cek['total'] > 0;

/* ===== RIWAYAT ===== */
$riwayat = mysqli_query($conn,"
  SELECT * FROM absensi
  WHERE dokter_id='$id'
  ORDER BY tanggal DESC, jam DESC
  LIMIT 5
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Dashboard Dokter | Best Clinic</title>

<style>
:root{
 --primary:#2563eb;
 --success:#16a34a;
 --danger:#dc3545;
 --bg:#f4f6fb;
}
*{box-sizing:border-box}
body{
 margin:0;
 background:var(--bg);
 font-family:'Segoe UI',sans-serif;
}

/* ===== HEADER ===== */
.rs-header{
 position:relative;
 background:linear-gradient(135deg,#2563eb,#1e40af);
 padding:22px 28px;
 color:#fff;
 overflow:hidden;
 animation:slideFade .6s ease;
 box-shadow:0 15px 35px rgba(0,0,0,.25);
}
@keyframes slideFade{
 from{opacity:0;transform:translateY(-25px)}
 to{opacity:1;transform:none}
}

/* SHINE */
.rs-header::before{
 content:"";
 position:absolute;
 inset:0;
 background:linear-gradient(120deg,
  transparent 30%,
  rgba(255,255,255,.25),
  transparent 70%);
 animation:shine 6s infinite;
 pointer-events:none;
}
@keyframes shine{
 from{transform:translateX(-100%)}
 to{transform:translateX(100%)}
}

/* PARTICLES */
.rs-header::after{
 content:"";
 position:absolute;
 inset:0;
 background:
  radial-gradient(circle at 10% 30%, rgba(255,255,255,.25) 2px, transparent 3px),
  radial-gradient(circle at 80% 20%, rgba(255,255,255,.2) 2px, transparent 3px),
  radial-gradient(circle at 50% 70%, rgba(255,255,255,.15) 2px, transparent 3px);
 animation:floatDots 18s linear infinite;
 pointer-events:none;
}
@keyframes floatDots{
 from{background-position:0 0,0 0,0 0}
 to{background-position:400px -300px,-300px 200px,200px 400px}
}

/* HEADER CONTENT */
.rs-top{
 display:flex;
 justify-content:space-between;
 align-items:center;
 position:relative;
 z-index:2;
}
.rs-left{
 display:flex;
 align-items:center;
 gap:16px;
}
.rs-logo{
 width:56px;
 height:56px;
 background:#fff;
 padding:6px;
 border-radius:14px;
 animation:glow 3s infinite;
}
@keyframes glow{
 0%{box-shadow:0 0 0 rgba(255,255,255,0)}
 50%{box-shadow:0 0 25px rgba(255,255,255,.6)}
 100%{box-shadow:0 0 0 rgba(255,255,255,0)}
}

.rs-title h1{margin:0;font-size:22px}
.rs-title span{font-size:13px;opacity:.85}

.rs-right{text-align:right}
.clock{font-size:18px;font-weight:600}
.date{font-size:13px;opacity:.85}

/* PROFILE */
.dokter-profile{
 display:flex;
 align-items:center;
 gap:14px;
 background:rgba(255,255,255,.15);
 padding:10px 18px;
 border-radius:18px;
 backdrop-filter:blur(8px);
 animation:fadeRight .8s ease;
}
@keyframes fadeRight{
 from{opacity:0;transform:translateX(30px)}
 to{opacity:1;transform:none}
}
.avatar{
 width:42px;height:42px;
 border-radius:50%;
 background:linear-gradient(135deg,#22c55e,#16a34a);
 display:flex;align-items:center;justify-content:center;
 color:#fff;font-weight:bold;font-size:18px;
 box-shadow:0 0 20px rgba(34,197,94,.6);
 animation:pulse 2.5s infinite;
}
@keyframes pulse{
 0%{box-shadow:0 0 0 rgba(34,197,94,0)}
 50%{box-shadow:0 0 25px rgba(34,197,94,.7)}
 100%{box-shadow:0 0 0 rgba(34,197,94,0)}
}
.logout{
 background:#fff;color:#2563eb;
 padding:8px 14px;border-radius:12px;
 text-decoration:none;font-size:13px;
 transition:.3s;
}
.logout:hover{transform:translateY(-2px);background:#e0e7ff}

/* MARQUEE */
.rs-bottom{
 margin-top:12px;
 display:flex;
 justify-content:space-between;
 align-items:center;
 position:relative;
 z-index:2;
}
.marquee{
 white-space:nowrap;
 overflow:hidden;
 max-width:70%;
 font-size:13px;
}
.marquee span{
 display:inline-block;
 padding-left:100%;
 animation:marq 16s linear infinite;
}
@keyframes marq{
 from{transform:translateX(0)}
 to{transform:translateX(-100%)}
}

/* ===== CONTENT ===== */
.container{max-width:1200px;margin:auto;padding:30px}
.grid{
 display:grid;
 grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
 gap:20px;margin-bottom:30px;
}
.card{
 background:#fff;padding:25px;border-radius:20px;
 box-shadow:0 20px 45px rgba(0,0,0,.08);
 transition:.3s;
}
.card:hover{transform:translateY(-6px)}
.stat{font-size:36px;font-weight:bold;color:var(--primary)}
.badge{padding:8px 16px;border-radius:20px;color:#fff;font-weight:bold}
.badge.success{background:var(--success)}
.badge.danger{background:var(--danger)}

video,canvas{width:100%;max-width:360px;border-radius:16px}
table{width:100%;border-collapse:collapse}
td,th{padding:12px;border-bottom:1px solid #eee}
</style>
</head>

<body>

<!-- ===== HEADER ===== -->
<div class="rs-header">

  <div class="rs-top">
    <div class="rs-left">
      <img src="../assets/logo_rs.png" class="rs-logo">
      <div class="rs-title">
        <h1>BEST CLINIC</h1>
        <span>Dashboard Dokter</span>
      </div>
    </div>

    <div class="rs-right">
      <div class="clock" id="clock"></div>
      <div class="date" id="date"></div>
    </div>
  </div>

  <div class="rs-bottom">
    <div class="marquee">
      <span>🏥 Selamat datang di Best Clinic • Absensi dokter berbasis kamera • Waktu mengikuti WIB •</span>
    </div>

    <div class="dokter-profile">
      <div class="avatar"><?= strtoupper(substr($nama,0,1)) ?></div>
      <div>
        <b><?= $nama ?></b><br>
        <span style="font-size:12px">Dokter Aktif</span>
      </div>
      <a href="logout.php" class="logout">Logout</a>
    </div>
  </div>

</div>

<div class="container">

<div class="grid">
  <div class="card"><h3>Total Kehadiran</h3><div class="stat"><?= $total ?></div></div>
  <div class="card"><h3>Bulan Ini</h3><div class="stat"><?= $bulanIni ?></div></div>
  <div class="card"><h3>Status Hari Ini</h3>
    <?= $sudah?'<span class="badge success">Sudah Absen</span>':'<span class="badge danger">Belum Absen</span>' ?>
  </div>
</div>

<div class="grid">

<div class="card">
<h3>Absensi Hari Ini</h3>
<?php if(!$sudah){ ?>
<form method="POST" action="../proses/proses_absen.php">
  <video id="video" autoplay></video>
  <canvas id="canvas" style="display:none"></canvas>
  <input type="hidden" name="foto" id="foto"><br><br>
  <button type="button" onclick="takePhoto()">Ambil Foto</button>
  <button id="btnAbsen" disabled>Absen Sekarang</button>
</form>
<?php } else { echo "<p>✅ Absensi hari ini sudah tercatat</p>"; } ?>
</div>

<div class="card">
<h3>Riwayat Terakhir</h3>
<table>
<tr><th>Tanggal</th><th>Jam</th></tr>
<?php while($r=mysqli_fetch_assoc($riwayat)){ ?>
<tr><td><?= $r['tanggal'] ?></td><td><?= $r['jam'] ?></td></tr>
<?php } ?>
</table>
</div>

</div>
</div>

<script>
function updateClock(){
 let d=new Date();
 clock.innerHTML=d.toLocaleTimeString('id-ID');
 date.innerHTML=d.toLocaleDateString('id-ID',{
  weekday:'long',day:'numeric',month:'long',year:'numeric'
 });
}
setInterval(updateClock,1000);updateClock();

navigator.mediaDevices.getUserMedia({video:true})
.then(s=>video.srcObject=s);

function takePhoto(){
 canvas.width=video.videoWidth;
 canvas.height=video.videoHeight;
 canvas.getContext('2d').drawImage(video,0,0);
 foto.value=canvas.toDataURL('image/png');
 btnAbsen.disabled=false;
 alert("Foto berhasil diambil");
}
</script>

</body>
</html>
