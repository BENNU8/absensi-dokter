<?php
require_once "../assets/fpdf/fpdf.php";
include "../config/db.php";

date_default_timezone_set("Asia/Jakarta");

// ================= FILTER TANGGAL =================
$dari = $_GET['dari'] ?? date("Y-m-d");
$sampai = $_GET['sampai'] ?? date("Y-m-d");

// ================= CLASS PDF =================
class PDF extends FPDF
{
    function Header()
    {
        // LOGO RS
        $this->Image('../assets/logo_rs.png',10,8,25);
        $this->SetFont('Arial','B',16);
        $this->Cell(0,10,'BEST CLINIC',0,1,'C');

        $this->SetFont('Arial','',11);
        $this->Cell(0,8,'Laporan Absensi Dokter',0,1,'C');

        $this->Ln(5);
        $this->Line(10,35,200,35);
        $this->Ln(8);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','I',9);
        $this->Cell(0,10,'Dicetak: '.date('d-m-Y H:i').' WIB',0,0,'C');
    }
}

// ================= PDF =================
$pdf = new PDF('L','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

// ================= HEADER TABEL =================
$pdf->Cell(10,8,'No',1,0,'C');
$pdf->Cell(40,8,'Nama Dokter',1,0,'C');
$pdf->Cell(35,8,'Tanggal',1,0,'C');
$pdf->Cell(30,8,'Jam',1,0,'C');
$pdf->Cell(50,8,'Foto Absen',1,1,'C');

// ================= DATA =================
$pdf->SetFont('Arial','',9);

$no = 1;
$query = mysqli_query($conn,"
    SELECT a.*, d.nama
    FROM absensi a
    JOIN dokter d ON a.dokter_id = d.id
    WHERE a.tanggal BETWEEN '$dari' AND '$sampai'
    ORDER BY a.tanggal DESC
");

while($row = mysqli_fetch_assoc($query)){
    $pdf->Cell(10,35,$no++,1,0,'C');
    $pdf->Cell(40,35,$row['nama'],1,0);
    $pdf->Cell(35,35,$row['tanggal'],1,0,'C');
    $pdf->Cell(30,35,$row['jam'],1,0,'C');

    $x = $pdf->GetX();
    $y = $pdf->GetY();

    $pdf->Cell(50,35,'',1,1);
    $fotoPath = "../uploads/absen/".$row['foto_file'];

    if(file_exists($fotoPath)){
        $pdf->Image($fotoPath,$x+5,$y+3,40,30);
    }
}

// ================= OUTPUT =================
$pdf->Output("I","laporan_absensi_dokter.pdf");
