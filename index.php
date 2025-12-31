<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Best Clinic | Sistem Absensi Dokter</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
:root{
  --blue:#2563eb;
  --cyan:#38bdf8;
  --purple:#7c3aed;
}

*{box-sizing:border-box;font-family:'Segoe UI',sans-serif}

body{
  margin:0;
  min-height:100vh;
  background:linear-gradient(120deg,var(--blue),var(--cyan),var(--purple));
  background-size:400% 400%;
  animation:bgMove 12s ease infinite;
  display:flex;
  justify-content:center;
  align-items:center;
  overflow:hidden;
}

@keyframes bgMove{
  0%{background-position:0% 50%}
  50%{background-position:100% 50%}
  100%{background-position:0% 50%}
}

/* ===== LOADING ===== */
#loading{
  position:fixed;
  inset:0;
  background:linear-gradient(135deg,#1e3a8a,#2563eb,#38bdf8);
  background-size:300% 300%;
  animation:bgMove 6s ease infinite;
  display:flex;
  justify-content:center;
  align-items:center;
  z-index:999;
}

.loading-box{
  background:rgba(255,255,255,.25);
  backdrop-filter:blur(18px);
  padding:40px 46px;
  border-radius:30px;
  text-align:center;
  box-shadow:0 30px 80px rgba(0,0,0,.35);
}

.loading-logo{
  width:150px;
  background:#fff;
  padding:14px;
  border-radius:22px;
  box-shadow:0 0 40px rgba(255,255,255,.8);
  animation:logoPulse 2.5s infinite;
}

@keyframes logoPulse{
  50%{transform:scale(1.05)}
}

.loading-title{
  margin-top:14px;
  font-size:18px;
  letter-spacing:3px;
  font-weight:700;
  color:#fff;
}

.progress{
  width:260px;
  height:7px;
  background:rgba(255,255,255,.35);
  border-radius:10px;
  overflow:hidden;
  margin:18px auto;
}

.progress span{
  display:block;
  height:100%;
  width:0;
  background:linear-gradient(90deg,#fff,#dbeafe);
  animation:load 3s forwards;
}

@keyframes load{
  to{width:100%}
}

.loading-text{
  font-size:13px;
  color:#f1f5f9;
  letter-spacing:1px;
}

/* ===== CLOCK ===== */
.clock-box{
  position:absolute;
  top:20px;
  right:26px;
  background:rgba(255,255,255,.25);
  backdrop-filter:blur(14px);
  padding:10px 18px;
  border-radius:16px;
  color:#fff;
  font-size:13px;
}

/* ===== LOGIN CARD ===== */
.card{
  width:100%;
  max-width:420px;
  padding:44px 38px;
  background:rgba(255,255,255,.22);
  backdrop-filter:blur(18px);
  border-radius:28px;
  box-shadow:0 35px 80px rgba(0,0,0,.4);
  text-align:center;
  animation:cardIn .8s ease;
}

@keyframes cardIn{
  from{opacity:0;transform:translateY(30px)}
  to{opacity:1}
}
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


.logo{
  width:200px;
  background:#fff;
  padding:14px;
  border-radius:22px;
  box-shadow:0 20px 45px rgba(0,0,0,.35);
  margin-bottom:16px;
}

h1{
  margin:8px 0;
  color:#fff;
  letter-spacing:2px;
}

.sub{
  color:#e0e7ff;
  font-size:14px;
  margin-bottom:30px;
}

.btn{
  display:block;
  padding:16px;
  margin-bottom:16px;
  border-radius:18px;
  font-weight:600;
  color:#fff;
  text-decoration:none;
  transition:.3s;
}

.btn-admin{
  background:linear-gradient(135deg,#2563eb,#38bdf8);
}
.btn-dokter{
  background:linear-gradient(135deg,#22c55e,#4ade80);
}

.btn:hover{transform:translateY(-3px)}

.footer{
  margin-top:22px;
  font-size:12px;
  color:#e0e7ff;
}
</style>
</head>

<body>

<!-- LOADING -->
<div id="loading">
  <div class="loading-box">
    <img src="assets/logo_rs.png" class="loading-logo">
    <div class="loading-title">BEST CLINIC</div>
    <div class="progress"><span></span></div>
    <div class="loading-text" id="loadText">Menyiapkan sistem...</div>
  </div>
</div>

<!-- CLOCK -->
<div class="clock-box">
  <b id="clock"></b><br>
  <span id="date"></span>
</div>

<!-- LOGIN -->
<div class="card">
  <img src="assets/logo_rs.png" class="logo">
  <h1>BEST CLINIC</h1>
  <div class="sub">Sistem Absensi Dokter Digital</div>

  <a href="admin/login.php" class="btn btn-admin">Login Admin</a>
  <a href="dokter/login.php" class="btn btn-dokter">Login Dokter</a>

  <div class="footer">
    © <?= date('Y') ?> Best Clinic
  </div>
</div>

<script>
/* CLOCK */
function updateClock(){
  const d=new Date();
  clock.innerText=d.toLocaleTimeString('id-ID');
  date.innerText=d.toLocaleDateString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric'});
}
setInterval(updateClock,1000);updateClock();

/* LOADING TEXT */
const texts=[
  "Menyiapkan sistem...",
  "Memuat data klinik...",
  "Menghubungkan database...",
  "Hampir selesai..."
];
let i=0;
setInterval(()=>{
  loadText.innerText=texts[i++%texts.length];
},800);

/* HIDE LOADING */
setTimeout(()=>{
  loading.style.display="none";
},3200);
</script>
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
