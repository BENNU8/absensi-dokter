navigator.geolocation.getCurrentPosition(p=>{
let latRS=-6.200000,lonRS=106.816666;
let R=6371;
let dLat=(latRS-p.coords.latitude)*Math.PI/180;
let dLon=(lonRS-p.coords.longitude)*Math.PI/180;
let a=Math.sin(dLat/2)**2+
Math.cos(p.coords.latitude*Math.PI/180)*
Math.cos(latRS*Math.PI/180)*
Math.sin(dLon/2)**2;
let c=2*Math.atan2(Math.sqrt(a),Math.sqrt(1-a));
document.getElementById("jarak").value=(R*c).toFixed(2);
});
