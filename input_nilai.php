<?php
include 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $daily = $_POST['daily'];
    $uts = $_POST['uts'];
    $uas = $_POST['uas'];

    // Menghitung nilai akhir
    $total_score = ($daily * 0.1) + ($uts * 0.3) + ($uas * 0.6);

    // Menyimpan data ke database
    $insert_student_query = "INSERT INTO students (name) VALUES ('$name')";
    mysqli_query($conn, $insert_student_query);
    $student_id = mysqli_insert_id($conn);

    $insert_score_query = "INSERT INTO exam_scores (student_id, daily_score, uts_score, uas_score) 
                            VALUES ('$student_id', '$daily', '$uts', '$uas')";
    mysqli_query($conn, $insert_score_query);

    // Membuat objek TCPDF
    require_once('tcpdf/tcpdf.php');
    $pdf = new TCPDF();

    // Set informasi dokumen
    $pdf->SetCreator('Your School');
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Hasil Ujian');

    // Tambahkan halaman baru
    $pdf->AddPage();

    // Tambahkan konten ke PDF
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, "Student Report", 0, 1, 'C'); // Centered title
    $pdf->Ln(10);

    // Informasi siswa
    $pdf->Cell(0, 10, "Student Name: $name", 0, 1);
    $pdf->Ln(5);

    // Nilai ujian
    $pdf->Cell(40, 10, "Subject", 1, 0, 'C');
    $pdf->Cell(30, 10, "Daily", 1, 0, 'C');
    $pdf->Cell(30, 10, "UTS", 1, 0, 'C');
    $pdf->Cell(30, 10, "UAS", 1, 0, 'C');
    $pdf->Cell(30, 10, "Total", 1, 1, 'C');

    // Nilai untuk satu subjek (contoh: Matematika)
    $pdf->Cell(40, 10, "Matematika", 1);
    $pdf->Cell(30, 10, $daily, 1, 0, 'C');
    $pdf->Cell(30, 10, $uts, 1, 0, 'C');
    $pdf->Cell(30, 10, $uas, 1, 0, 'C');
    $pdf->Cell(30, 10, number_format($total_score, 2), 1, 1, 'C');

    // Simpan PDF ke file atau tampilkan di browser

    // Simpan PDF ke file atau tampilkan di browser
    $pdf->Output('hasil_ujian.pdf', 'D'); // 'D' untuk menampilkan di browser, 'F' untuk menyimpan ke file

    // Redirect kembali ke halaman input setelah menyimpan dan menghasilkan PDF
    header("Location: input_nilai.php");
    exit();
} else {
    // Jika bukan request POST, kembalikan ke halaman input
    header("Location: input_nilai.php");
    exit();
}
?>