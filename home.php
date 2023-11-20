<?php 
session_start();
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

    try {
        // Pastikan path ke file tcpdf.php sesuai dengan struktur direktori Anda
        require_once('E:\xampp\htdocs\nilai_rapot\nilai_rapot\tcpdf\tcpdf\tcpdf.php');
    
        // Buat objek TCPDF
        $pdf = new TCPDF();
    } catch (Exception $e) {
        // Tangani kesalahan di sini
        echo 'Terjadi kesalahan: ' . $e->getMessage();
    }
    

    // Set informasi dokumen
    $pdf->SetCreator('Your School');
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Hasil Ujian');

    // Tambahkan halaman baru
    $pdf->AddPage();

  // Tambahkan konten ke PDF
  $pdf->Cell(0, 10, "RAPOR SEMESTER GENAP TAHUN PELAJARAN 2024/2025", 0, 1, 'C'); 
  $pdf->Ln(10);

  // Informasi siswa
  $pdf->Cell(0, 10, "Nama Peserta: $name", 0, 1);
  $pdf->Cell(0, 10, "Kelas: X (Sebelas)", 0,1);
  $pdf->Cell(0, 10, "Tahun Ajaran: 2024/2025", 0,1);
  $pdf->Cell(0, 10, "Semester: 2 (Genap)", 0,1);

  
  $pdf->Ln(5);
  // Nilai ujian
  $pdf->Cell(40, 10, "Mata Pelajaran", 1, 0, 'C');
  $pdf->Cell(30, 10, "Harian", 1, 0, 'C');
  $pdf->Cell(30, 10, "UTS", 1, 0, 'C');
  $pdf->Cell(30, 10, "UAS", 1, 0, 'C');
  $pdf->Cell(30, 10, "Total", 1, 1, 'C');

  $pdf->Cell(40, 10, "Matematika", 1,0, 'C');
  $pdf->Cell(30, 10, $daily, 1, 0, 'C');
  $pdf->Cell(30, 10, $uts, 1, 0, 'C');
  $pdf->Cell(30, 10, $uas, 1, 0, 'C');
  $pdf->Cell(30, 10, number_format($total_score, 2), 1, 1, 'C');

    // Simpan PDF ke file atau tampilkan di browser
    $pdf->Output('hasil_ujian.pdf', 'D'); // 'D' untuk menampilkan di browser, 'F' untuk menyimpan ke file

    // Redirect kembali ke halaman input setelah menyimpan dan menghasilkan PDF
    header("Location: input_nilai.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Exam Scores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
</head>
<style>
body {
    font-family: 'Arial', sans-serif;
    background-color: #f8f9fa;
    margin: 0px;
}

.navbar-brand {
    width: 100%;
}

.container-fluid {
    padding-left: 0;
    padding-right: 0;
}

.sidebar {
    background: linear-gradient(90deg, #2F69F6 -4.31%, #631ED0 104.31%);
    color: #fff;
    padding: 20px;
    border-radius: 0 10px 10px 0;
    text-align: center;
    height: 100vh;
}

h2 {
    text-align: center;
    color: #000000;
}

form {
    max-width: 400px;
    margin: 0 auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

label {
    display: block;
    margin-bottom: 5px;
}

input {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

button {
    background-color: #28a745;
    color: #fff;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background-color: #218838;
}


.btn-dark {
    background: #198754;
    color: #ffffff;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-dark:hover {
    background-color: #fff;
}



.mynav li a {
    color: #fff;
    text-decoration: none;
    width: 100%;
    display: block;
    border-radius: 5px;
    padding: 8px 5px;
}

.mynav li a:hover {
    background: linear-gradient(90deg, #2F69F6 -4.31%, #631ED0 104.31%);
}

.mynav li a i {
    width: 25px;
    text-align: center;
}
</style>
</head>

<body>
    <div class="container-fluid p-0 d-flex h-100">
        <div id="bdSidebar"
            class="d-flex flex-column flex-shrink-0 p-3 bg-success text-white offcanvas-md offcanvas-start"
            style="width: 280px; height: 600px;">
            <a href="#" class="navbar-brand">
                <h5>
                    <i class="fa-solid fa-hands-clapping" style="color: #ffffff, font-size: 28px;">
                    </i> Hello
                </h5>
            </a>
            <hr>
            <ul class="mynav nav nav-pills flex-column mb-auto">
                <li class="nav-item mb-1">
                    <a href="logout.php" class="">
                        <i class="fas fa-sign-out-alt">

                        </i> Logout
                        <span class="notification-badge">
                    </a>
                </li>

            </ul>
            <hr>
            <div class="d-flex">
                <span>
                    <h6 class="mt-1 mb-0">Sekolah Impian</h6>
                    <small>sekolahimpian@gmail.com</small>
                </span>
            </div>
        </div>

        <div class="bg-light flex-fill">
            <div class="p-2 d-md-none d-flex text-white bg-success">
                <a href="#" class="text-white" data-bs-toggle="offcanvas" data-bs-target="#bdSidebar">
                    <i class="fa-solid fa-bars"></i>
                </a>
                <span class="ms-3"> Hello, <?=$_SESSION['name']?>
                </span>
            </div>

            <div class="container">
                <h2 class="mt-4 mb-4">Input Exam Scores</h2>
                <form action="" method="post">
                    <label for="name">Student Name:</label>
                    <input type="text" id="name" name="name" class="form-control" required autocomplete="name">

                    <label for="daily">Daily Score:</label>
                    <input type="number" id="daily" name="daily" class="form-control" required autocomplete="off">

                    <label for="uts">UTS Score:</label>
                    <input type="number" id="uts" name="uts" class="form-control" required autocomplete="off">

                    <label for="uas">UAS Score:</label>
                    <input type="number" id="uas" name="uas" class="form-control" required autocomplete="off">

                    <button type="submit" class="btn btn-success">Calculate and Export to PDF</button>
                </form>



            </div>
</body>

</html>