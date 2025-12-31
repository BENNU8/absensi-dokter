<?php
session_start();
include "../config/db.php";

if(!isset($_SESSION['admin'])){
  header("Location: login.php");
  exit;
}

$totalDokter = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM dokter"));
$totalAbsen  = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM absensi"));
$dokterAktif = mysqli_query($conn,"SELECT * FROM dokter");

$absensi = mysqli_query($conn,"
  SELECT a.tanggal,a.jam,a.foto,d.nama
  FROM absensi a
  JOIN dokter d ON a.dokter_id=d.id
  ORDER BY a.tanggal DESC,a.jam DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard | Best Clinic</title>

<style>
    /* ===== FLOATING CTA ===== */
.floating-cta{
  position:fixed;
  bottom:24px;
  right:24px;
  display:flex;
  flex-direction:column;
  gap:14px;
  z-index:9999;
}

.cta{
  display:flex;
  align-items:center;
  gap:10px;
  padding:12px 18px;
  border-radius:50px;
  color:#fff;
  font-size:14px;
  font-weight:600;
  text-decoration:none;
  box-shadow:0 10px 25px rgba(0,0,0,.25);
  animation:float 3s ease-in-out infinite;
}

.cta img{
  width:20px;
}

.cta.wa{
  background:#25D366;
}

.cta.ig{
  background:linear-gradient(45deg,#f58529,#dd2a7b,#8134af);
}

@keyframes float{
  0%,100%{transform:translateY(0)}
  50%{transform:translateY(-6px)}
}

.cta:hover{
  transform:scale(1.05);
}

:root{
  --primary:#2563eb;
  --dark:#1e40af;
  --bg:#f5f7fb;
  --soft:#e0e7ff;
  --danger:#ef4444;
  --success:#22c55e;
}
*{box-sizing:border-box}
body{
  margin:0;
  font-family:'Segoe UI',sans-serif;
  background:var(--bg);
}

/* ================= HEADER ================= */
.header{
  position:sticky;
  top:0;
  z-index:999;
  background:linear-gradient(270deg,#2563eb,#1e40af,#1d4ed8);
  background-size:600% 600%;
  animation:gradientMove 12s ease infinite;
  color:#fff;
  box-shadow:0 10px 30px rgba(0,0,0,.3);
}
@keyframes gradientMove{
  0%{background-position:0% 50%}
  50%{background-position:100% 50%}
  100%{background-position:0% 50%}
}
.header-top{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:18px 28px;
}
.brand{
  display:flex;
  align-items:center;
  gap:16px;
}
.brand img{
  width:58px;
  background:#fff;
  padding:6px;
  border-radius:16px;
  box-shadow:0 0 25px rgba(255,255,255,.6);
  animation:pulse 3s infinite;
}
@keyframes pulse{50%{transform:scale(1.08)}}

.clock{text-align:right}
.clock a{
  display:inline-block;
  margin-top:8px;
  transition:.3s;
}
.clock a:hover{
  transform:translateY(-2px) scale(1.05);
}

.header-info{
  background:rgba(0,0,0,.25);
  padding:10px 0;
  overflow:hidden;
  border-top:1px solid rgba(255,255,255,.2);
}
.marquee span{
  display:inline-block;
  white-space:nowrap;
  padding-left:100%;
  animation:marq 20s linear infinite;
  font-size:13px;
}
@keyframes marq{
  from{transform:translateX(0)}
  to{transform:translateX(-100%)}
}

/* ================= LAYOUT ================= */
.container{max-width:1200px;margin:auto;padding:30px}
.cards{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
  gap:20px;
}
.card{
  background:#fff;
  padding:26px;
  border-radius:18px;
  box-shadow:0 12px 30px rgba(0,0,0,.08);
  animation:fadeUp .6s ease;
}
@keyframes fadeUp{
  from{opacity:0;transform:translateY(20px)}
  to{opacity:1}
}

.box{
  background:#fff;
  padding:28px;
  border-radius:18px;
  box-shadow:0 12px 30px rgba(0,0,0,.08);
  margin-top:30px;
  animation:fadeUp .7s ease;
}

input{
  width:100%;
  padding:12px;
  border-radius:10px;
  border:1px solid #e5e7eb;
  margin-bottom:14px;
}

table{width:100%;border-collapse:collapse}
th,td{padding:14px;border-bottom:1px solid #e5e7eb}
tbody tr:hover{background:var(--soft)}

.btn{
  padding:10px 16px;
  border-radius:10px;
  border:none;
  color:#fff;
  cursor:pointer;
  font-size:13px;
  text-decoration:none;
}
.btn-danger{background:var(--danger)}
.btn-primary{background:var(--success)}

.foto-wrap{position:relative;width:90px;cursor:pointer}
.foto-wrap img{width:100%;border-radius:12px}
.watermark{
  position:absolute;
  bottom:0;left:0;right:0;
  background:rgba(0,0,0,.6);
  color:#fff;
  font-size:10px;
  padding:4px;
  border-radius:0 0 12px 12px;
}

/* MODAL */
.modal{
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.7);
  display:none;
  align-items:center;
  justify-content:center;
}
.modal-box{
  background:#fff;
  padding:28px;
  border-radius:18px;
  min-width:300px;
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
  <div class="header-top">
    <div class="brand">
      <img src="../assets/logo_rs.png">
      <div>
        <b style="font-size:18px">BEST CLINIC</b><br>
        <small>Admin Dashboard</small>
      </div>
    </div>
    <div class="clock">
      <div id="clock"></div>
      <small id="date"></small><br>
      <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
  </div>
  <div class="header-info">
    <div class="marquee">
      <span>Sistem Absensi Dokter • Best Clinic • TUGAS AKHIR PEMPROG • ini punya BENNU</span>
    </div>
  </div>
  
</div>

<div class="container">

<div class="cards">
  <div class="card"><h3>Total Dokter</h3><h1><?= $totalDokter ?></h1></div>
  <div class="card"><h3>Total Absensi</h3><h1><?= $totalAbsen ?></h1></div>
</div>

<div class="box">
<h3>Tambah Dokter</h3>
<form method="POST" action="../proses/proses_dokter.php">
  <input name="nama" placeholder="Nama Dokter" required>
  <input name="username" placeholder="Username" required>
  <input type="password" name="password" placeholder="Password" required>
  <button class="btn btn-primary">Simpan Dokter</button>
</form>
</div>

<div class="box">
<h3>Daftar Dokter</h3>
<input id="searchDokter" placeholder="Cari dokter...">
<table id="tableDokter">
<thead><tr><th>Nama</th><th>Username</th><th>Aksi</th></tr></thead>
<tbody>
<?php while($d=mysqli_fetch_assoc($dokterAktif)){ ?>
<tr>
<td><?= $d['nama'] ?></td>
<td><?= $d['username'] ?></td>
<td>
<button class="btn btn-primary" onclick="openPass('<?= $d['id'] ?>','<?= $d['nama'] ?>')">Ganti Password</button>
<a href="../proses/proses_hapus_dokter.php?id=<?= $d['id'] ?>" class="btn btn-danger"
onclick="return confirm('Hapus dokter?')">Hapus</a>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>

<div class="box">
<h3>Data Absensi</h3>
<table>
<thead><tr><th>Nama</th><th>Tanggal</th><th>Jam</th><th>Foto</th></tr></thead>
<tbody>
<?php while($a=mysqli_fetch_assoc($absensi)){ $src="../uploads/absen/".$a['foto']; ?>
<tr>
<td><?= $a['nama'] ?></td>
<td><?= $a['tanggal'] ?></td>
<td><?= $a['jam'] ?></td>
<td>
<div class="foto-wrap" onclick="openImg('<?= $src ?>','<?= $a['tanggal'] ?> | <?= $a['jam'] ?>')">
<img src="<?= $src ?>">
<div class="watermark"><?= $a['tanggal'] ?> | <?= $a['jam'] ?></div>
</div>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>

</div>

<!-- MODAL FOTO -->
<div class="modal" id="modal" onclick="this.style.display='none'">
  <div class="modal-box">
    <img id="modalImg" style="max-width:100%">
    <div id="modalWm"></div>
  </div>
</div>

<!-- MODAL PASSWORD -->
<div class="modal" id="modalPass" onclick="this.style.display='none'">
  <div class="modal-box" onclick="event.stopPropagation()">
    <h3 id="passTitle"></h3>
    <form method="POST" action="../proses/proses_ganti_password.php">
      <input type="hidden" name="id" id="passId">
      <input type="password" name="password" placeholder="Password baru" required>
      <button class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>

<script>
function updateClock(){
  let d=new Date();
  clock.innerText=d.toLocaleTimeString();
  date.innerText=d.toLocaleDateString('id-ID',{
    weekday:'long',day:'numeric',month:'long',year:'numeric'
  });
}
setInterval(updateClock,1000);updateClock();

function openImg(src,wm){
  modalImg.src=src;
  modalWm.innerText=wm;
  modal.style.display="flex";
}
function openPass(id,nama){
  passId.value=id;
  passTitle.innerText="Ganti Password: "+nama;
  modalPass.style.display="flex";
}
searchDokter.onkeyup=()=>{
  let v=searchDokter.value.toLowerCase();
  document.querySelectorAll("#tableDokter tbody tr").forEach(tr=>{
    tr.style.display=tr.innerText.toLowerCase().includes(v)?"":"none";
  });
}
</script>
<!-- FLOATING CTA -->
<div class="floating-cta">
  <a href="https://wa.me/6282122329256" target="_blank" class="cta wa">
    <img src="https://img.icons8.com/ios-filled/50/ffffff/whatsapp.png">
    <span>Chat WA</span>
  </a>
  <a href="https://instagram.com/nabukatneja8" target="_blank" class="cta ig">
    <img src="https://img.icons8.com/ios-filled/50/ffffff/instagram-new.png">
    <span>IG</span>
  </a>
</div>

</body>
</html>
