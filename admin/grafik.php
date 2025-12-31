<?php
include "../config/db.php";
$q=mysqli_query($conn,"SELECT tanggal,COUNT(*) j FROM absensi GROUP BY tanggal");
while($r=mysqli_fetch_assoc($q)){
$t[]=$r['tanggal'];$j[]=$r['j'];
}
echo json_encode(['t'=>$t,'j'=>$j]);
