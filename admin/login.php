<?php
session_start();
if(isset($_SESSION['admin'])) header("Location: dashboard.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login Admin | Best Clinic</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
:root{
  --primary:#0d6efd;
  --secondary:#4f8cff;
  --glass:rgba(255,255,255,.18);
}

*{
  box-sizing:border-box;
  font-family:'Segoe UI',sans-serif;
}

body{
  margin:0;
  min-height:100vh;
  background:linear-gradient(120deg,#0b3c8c,#0d6efd,#4f8cff);
  background-size:400% 400%;
  animation:bgMove 14s ease infinite;
  display:flex;
  align-items:center;
  justify-content:center;
  overflow:hidden;
}

@keyframes bgMove{
  0%{background-position:0% 50%}
  50%{background-position:100% 50%}
  100%{background-position:0% 50%}
}

/* ===== FLOAT SHAPES ===== */
.shape{
  position:absolute;
  border-radius:50%;
  filter:blur(45px);
  opacity:.35;
  animation:float 14s infinite ease-in-out;
}
.shape.one{
  width:300px;height:300px;
  background:#ffffff;
  top:-100px;left:-100px;
}
.shape.two{
  width:240px;height:240px;
  background:#00c2ff;
  bottom:-90px;right:-90px;
  animation-delay:4s;
}

@keyframes float{
  0%,100%{transform:translateY(0)}
  50%{transform:translateY(-45px)}
}

/* ===== CLOCK ===== */
.clock-box{
  position:absolute;
  top:22px;
  right:26px;
  z-index:5;
  background:rgba(255,255,255,.2);
  backdrop-filter:blur(14px);
  padding:10px 18px;
  border-radius:18px;
  color:#fff;
  font-size:13px;
  box-shadow:0 10px 30px rgba(0,0,0,.25);
  animation:fadeDown .8s ease;
}
.clock-box b{font-size:15px}

@keyframes fadeDown{
  from{opacity:0;transform:translateY(-20px)}
  to{opacity:1;transform:none}
}

/* ===== CARD ===== */
.card{
  position:relative;
  z-index:2;
  width:100%;
  max-width:420px;
  padding:46px 40px;
  border-radius:28px;
  background:var(--glass);
  backdrop-filter:blur(20px);
  box-shadow:0 35px 80px rgba(0,0,0,.4);
  text-align:center;
  animation:cardIn .9s ease;
}

@keyframes cardIn{
  from{opacity:0;transform:scale(.9) translateY(35px)}
  to{opacity:1;transform:none}
}

/* ===== LOGO ===== */
.logo-wrap{
  display:flex;
  justify-content:center;
  margin-bottom:20px;
}
.logo{
  width:190px;
  max-width:90%;
  padding:14px;
  background:rgba(255,255,255,.95);
  border-radius:22px;
  box-shadow:0 20px 45px rgba(0,0,0,.35);
  animation:logoFloat 3s ease-in-out infinite;
}

@keyframes logoFloat{
  0%,100%{transform:translateY(0)}
  50%{transform:translateY(-10px)}
}

/* ===== TITLE ===== */
h2{
  margin:10px 0 4px;
  font-size:24px;
  color:#ffffff;
  letter-spacing:1px;
}
p{
  margin:0 0 30px;
  font-size:14px;
  color:#eaf1ff;
  opacity:.9;
}

/* ===== FORM ===== */
input{
  width:100%;
  padding:15px;
  border-radius:16px;
  border:none;
  margin-bottom:14px;
  font-size:14px;
  outline:none;
}

button{
  width:100%;
  padding:16px;
  border-radius:18px;
  border:none;
  font-size:15px;
  font-weight:600;
  cursor:pointer;
  color:#fff;
  background:linear-gradient(135deg,#0d6efd,#4f8cff);
  transition:.3s;
}
button:hover{
  transform:translateY(-3px);
  box-shadow:0 15px 35px rgba(13,110,253,.45);
}

/* ===== LINK ===== */
a{
  display:block;
  margin-top:18px;
  font-size:13px;
  color:#eaf1ff;
  text-decoration:none;
  opacity:.9;
}
a:hover{text-decoration:underline}
</style>
</head>

<body>

<!-- JAM -->
<div class="clock-box">
  <b id="clock"></b><br>
  <span id="date"></span>
</div>

<!-- BACKGROUND -->
<div class="shape one"></div>
<div class="shape two"></div>

<div class="card">

  <div class="logo-wrap">
    <img src="../assets/logo_rs.png" class="logo">
  </div>

  <h2>ADMIN PANEL</h2>
  <p>Best Clinic – Sistem Absensi Dokter</p>

  <form method="POST" action="../proses/proses_login.php">
    <input name="username" placeholder="Username Admin" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="hidden" name="role" value="admin">
    <button>Login Admin</button>
  </form>

  <a href="../index.php">← Kembali ke Halaman Utama</a>
</div>

<script>
function updateClock(){
  const now = new Date();
  clock.innerHTML = now.toLocaleTimeString('id-ID');
  date.innerHTML  = now.toLocaleDateString('id-ID',{
    weekday:'long',
    day:'numeric',
    month:'long',
    year:'numeric'
  });
}
setInterval(updateClock,1000);
updateClock();
</script>

</body>
</html>
