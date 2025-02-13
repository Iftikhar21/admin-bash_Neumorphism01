<?php
// Konfigurasi database
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "bacs5153_recode";

	date_default_timezone_set('Asia/Jakarta');

	// Membuat koneksi
	$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
	die("Koneksi gagal: " . $conn->connect_error);
}

// Mendapatkan data dari database
$sql = "SELECT * FROM Absensi";
$result = $conn->query($sql);

// Menyimpan data dalam array untuk digunakan di JavaScript
$dataAbsensi = [];
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		$dataAbsensi[] = $row;
	}
}

$dateAbsenToday = date('Y-m-d');

$sqlAbsen = "SELECT COUNT(*) AS total_absen 
		   FROM Absensi 
		   WHERE DATE(Waktu) = '$dateAbsenToday'";

$resultAbsen = $conn->query($sqlAbsen);

$totalAbsen = 0;

if ($resultAbsen && $resultAbsen->num_rows > 0) {
	$rowAbsen = $resultAbsen->fetch_assoc();
	$totalAbsen = $rowAbsen['total_absen'];
} else {
	echo $conn->error;
}


$dateToday = date('Y-m-d');
$sqlToday = "SELECT COUNT(*) AS total_hadir 
		   FROM Absensi 
		   WHERE Kehadiran = 'Hadir' 
		   AND DATE(Waktu) = '$dateToday'";

$resultToday = $conn->query($sqlToday);

$totalHadir = 0;

if ($resultToday && $resultToday->num_rows > 0) {
	$rowToday = $resultToday->fetch_assoc();
	$totalHadir = $rowToday['total_hadir'];
} else {
	echo $conn->error;
}

$lateToday = date('Y-m-d');
$sqlLateToday = "SELECT COUNT(*) AS total_terlambat 
		   FROM Absensi 
		   WHERE Kehadiran = 'Terlambat' 
		   AND DATE(Waktu) = '$lateToday'";

$resultLateToday = $conn->query($sqlLateToday);

$totalLate = 0;

if ($resultLateToday && $resultLateToday->num_rows > 0) {
	$rowLateToday = $resultLateToday->fetch_assoc();
	$totalLate = $rowLateToday['total_terlambat'];
} else {
	echo $conn->error;
}

  $today = date('Y-m-d');  // Get today's date
  
  $sqlBadMoodToday = "SELECT COUNT(*) AS total_buruk 
					  FROM Absensi 
					  WHERE Mood = 'Buruk' 
					  AND DATE(Waktu) = '$today'";  // Adjusting Mood to 'buruk' for bad mood
  
  $resultBadMoodToday = $conn->query($sqlBadMoodToday);
  
  $totalBadMood = 0;  // Initialize count variable
  
  if ($resultBadMoodToday && $resultBadMoodToday->num_rows > 0) {
	  $rowBadMoodToday = $resultBadMoodToday->fetch_assoc();
	  $totalBadMood = $rowBadMoodToday['total_buruk'];  // Retrieve the count of students with a bad mood
  } else {
	  echo $conn->error;  // Display error if query fails
  }



  $persentaseKehadiran = ($totalAbsen > 0) ? ($totalHadir / $totalAbsen) * 100 : 0;
  $persentaseTerlambat = ($totalAbsen > 0) ? ($totalLate / $totalAbsen) * 100 : 0;
  $persentaseAbsen = ($totalAbsen > 0) ? ($totalAbsen / 1214) * 100 : 0;
  
  $persentaseBadMood = ($totalAbsen > 0) ? ($totalBadMood / $totalAbsen) * 100: 0;
  
  $tidakHadir = 1214 - $totalAbsen;
  
  $persentaseTidakHadir = ($totalAbsen > 0) ? ($tidakHadir / 1214) * 100 : 0;

  
  $sqlLateStudents = "SELECT Nama, Kelas, Jurusan, Waktu FROM Absensi WHERE Kehadiran = 'Terlambat' AND DATE(Waktu) = '$lateToday'";
  $resultLateStudents = $conn->query($sqlLateStudents);
  
  $lateStudents = [];
  if ($resultLateStudents && $resultLateStudents->num_rows > 0) {
	  while ($row = $resultLateStudents->fetch_assoc()) {
		  $lateStudents[] = $row;
	  }
  } else {
	  echo $conn->error;
  }

  // Query to fetch students with a bad mood
  $sqlBadMoodStudents = "SELECT Nama, Kelas, Jurusan, Catatan FROM Absensi WHERE Mood = 'Buruk' AND DATE(Waktu) = '$today'";  // Make sure 'Mood' is checked for 'Buruk'
  
  // Execute the query
  $resultBadMoodStudents = $conn->query($sqlBadMoodStudents);
  
  // Initialize an array to hold the students with bad mood
  $badMoodStudents = [];
  
  if ($resultBadMoodStudents && $resultBadMoodStudents->num_rows > 0) {
	  // Fetch each student data into the array
	  while ($row = $resultBadMoodStudents->fetch_assoc()) {
		  $badMoodStudents[] = $row;  // Add the student details to the array
	  }
  } else {
	  // If query failed, show error
	  echo $conn->error;
  }
// Menutup koneksi
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- PAGE TITLE HERE -->
	<title>Admin</title>
	
	<link href="vendor/jquery-nice-select/css/nice-select.css" rel="stylesheet">
	<link rel="stylesheet" href="vendor/nouislider/nouislider.min.css">
	<link rel="stylesheet" type="text/css" href="your_website_domain/css_root/flaticon.css">
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

	<!-- Style css -->
    <link href="css/style.css" rel="stylesheet">
	
</head>
<body>

    <!--*******************
        Preloader start
    ********************-->
    <!-- <div id="preloader">
        <div class="waviy">
		   <span style="--i:1">L</span>
		   <span style="--i:2">o</span>
		   <span style="--i:3">a</span>
		   <span style="--i:4">d</span>
		   <span style="--i:5">i</span>
		   <span style="--i:6">n</span>
		   <span style="--i:7">g</span>
		   <span style="--i:8">.</span>
		   <span style="--i:9">.</span>
		   <span style="--i:10">.</span>
		</div>
    </div> -->
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="index.php" class="brand-logo">
			<svg xmlns="http://www.w3.org/2000/svg" width="53" height="53" fill="currentColor" class="bi bi-qr-code" viewBox="0 0 16 16">
				<path class="svg-logo-primary-path" d="M2 2h2v2H2z"/>
				<path class="svg-logo-primary-path" d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z"/>
				<path class="svg-logo-primary-path" d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z"/>
				<path class="svg-logo-primary-path" d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1zm0 5V4h1v2zM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8zm0 0v1H2V8H1v1H0V7h3v1zm10 1h-1V7h1zm-1 0h-1v2h2v-1h-1zm-4 0h2v1h-1v1h-1zm2 3v-1h-1v1h-1v1H9v1h3v-2zm0 0h3v1h-2v1h-1zm-4-1v1h1v-2H7v1z"/>
				<path class="svg-logo-primary-path" d="M7 12h1v3h4v1H7zm9 2v2h-3v-1h2v-1z"/>
			</svg>
                
				<p class="brand-title" width="124px" height="33px"  style="font-size: 30px;">Re-Code</p>
            </a>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->
		
		
		
		<!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
							<div class="dashboard_bar">
                                Dashboard 
                            </div>
                        </div>
						<div class="real-time-clock">
							<h3 id="clock" class="m-0"></h3>
						</div>
                        <!-- <ul class="navbar-nav header-right">
							<li class="nav-item">
								<div class="input-group search-area">
									<input type="text" class="form-control" placeholder="Search here...">
									<span class="input-group-text"><a href="javascript:void(0)"><i class="flaticon-381-search-2"></i></a></span>
								</div>
							</li>
							<li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link" href="javascript:void(0);" data-bs-toggle="dropdown">
									<svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M3.88552 6.2921C1.95571 6.54135 0.439911 8.19656 0.439911 10.1896V10.7253C0.439911 12.8874 2.21812 14.6725 4.38019 14.6725H12.7058V24.9768H7.01104C5.77451 24.9768 4.82009 24.0223 4.82009 22.7858V18.4039C4.84523 16.6262 2.16581 16.6262 2.19096 18.4039V22.7858C2.19096 25.4334 4.36345 27.6059 7.01104 27.6059H21.0331C23.6807 27.6059 25.8532 25.4334 25.8532 22.7858V13.9981C26.9064 13.286 27.6042 12.0802 27.6042 10.7253V10.1896C27.6042 8.17115 26.0501 6.50077 24.085 6.28526C24.0053 0.424609 17.6008 -1.28785 13.9827 2.48534C10.3936 -1.60185 3.7545 1.06979 3.88552 6.2921ZM12.7058 5.68103C12.7058 5.86287 12.7033 6.0541 12.7058 6.24246H6.50609C6.55988 2.31413 11.988 1.90765 12.7058 5.68103ZM21.4559 6.24246H15.3383C15.3405 6.05824 15.3538 5.87664 15.3383 5.69473C15.9325 2.04532 21.3535 2.18829 21.4559 6.24246ZM4.38019 8.87502H12.7058V12.0382H4.38019C3.62918 12.0382 3.06562 11.4764 3.06562 10.7253V10.1896C3.06562 9.43859 3.6292 8.87502 4.38019 8.87502ZM15.3383 8.87502H23.6656C24.4166 8.87502 24.9785 9.43859 24.9785 10.1896V10.7253C24.9785 11.4764 24.4167 12.0382 23.6656 12.0382H15.3383V8.87502ZM15.3383 14.6725H23.224V22.7858C23.224 24.0223 22.2696 24.9768 21.0331 24.9768H15.3383V14.6725Z" fill="#4f7086"/> 
									</svg>
									<span class="badge light text-white bg-primary rounded-circle">2</span>
                                </a>
								
							</li>
                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link  ai-icon" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                                   <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M12.638 4.9936V2.3C12.638 1.5824 13.2484 1 14.0006 1C14.7513 1 15.3631 1.5824 15.3631 2.3V4.9936C17.3879 5.2718 19.2805 6.1688 20.7438 7.565C22.5329 9.2719 23.5384 11.5872 23.5384 14V18.8932L24.6408 20.9966C25.1681 22.0041 25.1122 23.2001 24.4909 24.1582C23.8709 25.1163 22.774 25.7 21.5941 25.7H15.3631C15.3631 26.4176 14.7513 27 14.0006 27C13.2484 27 12.638 26.4176 12.638 25.7H6.40705C5.22571 25.7 4.12888 25.1163 3.50892 24.1582C2.88759 23.2001 2.83172 22.0041 3.36039 20.9966L4.46268 18.8932V14C4.46268 11.5872 5.46691 9.2719 7.25594 7.565C8.72068 6.1688 10.6119 5.2718 12.638 4.9936ZM14.0006 7.5C12.1924 7.5 10.4607 8.1851 9.18259 9.4045C7.90452 10.6226 7.18779 12.2762 7.18779 14V19.2C7.18779 19.4015 7.13739 19.6004 7.04337 19.7811C7.04337 19.7811 6.43703 20.9381 5.79662 22.1588C5.69171 22.3603 5.70261 22.6008 5.82661 22.7919C5.9506 22.983 6.16996 23.1 6.40705 23.1H21.5941C21.8298 23.1 22.0492 22.983 22.1732 22.7919C22.2972 22.6008 22.3081 22.3603 22.2031 22.1588C21.5627 20.9381 20.9564 19.7811 20.9564 19.7811C20.8624 19.6004 20.8133 19.4015 20.8133 19.2V14C20.8133 12.2762 20.0953 10.6226 18.8172 9.4045C17.5391 8.1851 15.8073 7.5 14.0006 7.5Z" fill="#4f7086"/>
									</svg>
                                    <span class="badge light text-white bg-primary rounded-circle">12</span>
                                </a>
                              
                            </li>
							<li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link bell bell-link" href="javascript:void(0);">
                                 <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M27 7.88883C27 5.18897 24.6717 3 21.8 3C17.4723 3 10.5277 3 6.2 3C3.3283 3 1 5.18897 1 7.88883V23.7776C1 24.2726 1.31721 24.7174 1.80211 24.9069C2.28831 25.0963 2.8473 24.9912 3.2191 24.6417C3.2191 24.6417 5.74629 22.2657 7.27769 20.8272C7.76519 20.3688 8.42561 20.1109 9.11591 20.1109H21.8C24.6717 20.1109 27 17.922 27 15.2221V7.88883ZM24.4 7.88883C24.4 6.53951 23.2365 5.44441 21.8 5.44441C17.4723 5.44441 10.5277 5.44441 6.2 5.44441C4.7648 5.44441 3.6 6.53951 3.6 7.88883V20.8272L5.4382 19.0989C6.4132 18.1823 7.73661 17.6665 9.11591 17.6665H21.8C23.2365 17.6665 24.4 16.5726 24.4 15.2221V7.88883ZM7.5 15.2221H17.9C18.6176 15.2221 19.2 14.6745 19.2 13.9999C19.2 13.3252 18.6176 12.7777 17.9 12.7777H7.5C6.7824 12.7777 6.2 13.3252 6.2 13.9999C6.2 14.6745 6.7824 15.2221 7.5 15.2221ZM7.5 10.3333H20.5C21.2176 10.3333 21.8 9.7857 21.8 9.11104C21.8 8.43638 21.2176 7.88883 20.5 7.88883H7.5C6.7824 7.88883 6.2 8.43638 6.2 9.11104C6.2 9.7857 6.7824 10.3333 7.5 10.3333Z" fill="#4f7086"/>
									</svg>
									<span class="badge light text-white bg-primary rounded-circle">5</span>
                                </a>
							</li>
                            <li class="nav-item">
								<a href="javascript:void(0);" class="btn btn-primary d-sm-inline-block d-none">Generate Report<i class="las la-signal ms-3 scale5"></i></a>
							</li>
                        </ul> -->
                    </div>
				</nav>
			</div>
		</div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="dlabnav">
            <div class="dlabnav-scroll">
				<ul class="metismenu" id="menu">
					<li class="dropdown header-profile">
						<a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
							<img src="images/ion/man (1).png" width="20" alt=""/>
							<div class="header-info ms-3">
								<span class="font-w600 ">Hi,<b>Iftikhar !</b></span>
								<small class="text-end font-w400">xyz@gmail.com</small>
							</div>
						</a>
						
					</li>
                    <li><a href="javascript:void()" aria-expanded="false">
							<i class="flaticon-025-dashboard"></i>
							<span class="nav-text">Dashboard</span>
						</a>
                    </li>
                    <li><a href="absensi.php" aria-expanded="false">
						<i class='bx bxs-bar-chart-alt-2'></i>
							<span class="nav-text">Absensi</span>
						</a>
                    </li>
                    <li><a href="javascript:void()" aria-expanded="false">
							<i class="flaticon-051-info"></i>
							<span class="nav-text">About</span>
						</a>
                    </li>
                    <!-- <li><a href="javascript:void()" aria-expanded="false">
							<i class="flaticon-086-star"></i>
							<span class="nav-text">Bootstrap</span>
						</a>
                    </li>
                    <li><a href="javascript:void()" aria-expanded="false">
							<i class="flaticon-045-heart"></i>
							<span class="nav-text">Plugins</span>
						</a>
                    </li>
                    <li><a href="widget-basic.html" class="ai-icon" aria-expanded="false">
							<i class="flaticon-013-checkmark"></i>
							<span class="nav-text">Widget</span>
						</a>
					</li>
                    <li><a href="javascript:void()" aria-expanded="false">
							<i class="flaticon-072-printer"></i>
							<span class="nav-text">Forms</span>
						</a>
                    </li>
                    <li><a href="javascript:void()" aria-expanded="false">
							<i class="flaticon-043-menu"></i>
							<span class="nav-text">Table</span>
						</a>
                    </li>
                    <li><a href="javascript:void()" aria-expanded="false">
							<i class="flaticon-022-copy"></i>
							<span class="nav-text">Pages</span>
						</a>
                    </li> -->
                </ul>
			</div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->
		
		<!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
			<div class="container-fluid">
				<div class="row invoice-card-row">
					<div class="col-xl-3 col-xxl-8 col-sm-6" >
						<div class="card bg-warning invoice-card" style="cursor: pointer;">
							<div class="card-body d-flex">
								<div class="icon me-3">
								<svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24" style="fill: #9AA6B2 ;transform: msFilter; "><path d="M13 2.051V11h8.949c-.47-4.717-4.232-8.479-8.949-8.949zm4.969 17.953c2.189-1.637 3.694-4.14 3.98-7.004h-8.183l4.203 7.004z"></path><path d="M11 12V2.051C5.954 2.555 2 6.824 2 12c0 5.514 4.486 10 10 10a9.93 9.93 0 0 0 4.255-.964s-5.253-8.915-5.254-9.031A.02.02 0 0 0 11 12z"></path></svg>
									
								</div>
								<div>
									<h2 class="text-white invoice-num">1.214</h2>
									<span class="text-white fs-18">Jumlah Siswa</span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-xxl-4 col-sm-6">
						<div class="card bg-success invoice-card" style="cursor: pointer;">
							<div class="card-body d-flex">
								<div class="icon me-3">
									<svg width="35px" height="34px">
									<path fill-rule="evenodd"  fill="#9AA6B2"
									 d="M32.482,9.730 C31.092,6.789 28.892,4.319 26.120,2.586 C22.265,0.183 17.698,-0.580 13.271,0.442 C8.843,1.458 5.074,4.140 2.668,7.990 C0.255,11.840 -0.509,16.394 0.514,20.822 C1.538,25.244 4.224,29.008 8.072,31.411 C10.785,33.104 13.896,34.000 17.080,34.000 L17.286,34.000 C20.456,33.960 23.541,33.044 26.213,31.358 C26.991,30.866 27.217,29.844 26.725,29.067 C26.234,28.291 25.210,28.065 24.432,28.556 C22.285,29.917 19.799,30.654 17.246,30.687 C14.627,30.720 12.067,29.997 9.834,28.609 C6.730,26.671 4.569,23.644 3.752,20.085 C2.934,16.527 3.546,12.863 5.486,9.763 C9.488,3.370 17.957,1.418 24.359,5.414 C26.592,6.808 28.360,8.793 29.477,11.157 C30.568,13.460 30.993,16.016 30.707,18.539 C30.607,19.448 31.259,20.271 32.177,20.371 C33.087,20.470 33.911,19.820 34.011,18.904 C34.363,15.764 33.832,12.591 32.482,9.730 L32.482,9.730 Z"/>
									<path fill-rule="evenodd"  fill="#9AA6B2"
									 d="M22.593,11.237 L14.575,19.244 L11.604,16.277 C10.952,15.626 9.902,15.626 9.250,16.277 C8.599,16.927 8.599,17.976 9.250,18.627 L13.399,22.770 C13.725,23.095 14.150,23.254 14.575,23.254 C15.001,23.254 15.427,23.095 15.753,22.770 L24.940,13.588 C25.592,12.937 25.592,11.888 24.940,11.237 C24.289,10.593 23.238,10.593 22.593,11.237 L22.593,11.237 Z"/>
									</svg>
									
								</div>
								<div>
									<h2 class="text-white invoice-num"><?=$tidakHadir?></h2>
									<span class="text-white fs-18">Jumlah Murid yang belum Absen Hari ini</span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-xxl-6 col-sm-6">
						<div class="card bg-info invoice-card" data-bs-toggle="modal" data-bs-target="#badMoodStudentsModal" style="cursor: pointer;">
							<div class="card-body d-flex">
								<div class="icon me-3">
								<svg xmlns="http://www.w3.org/2000/svg" width="35" height="34" viewBox="0 0 24 24" style="fill: #E52020;transform: msFilter;"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm-5 8.5a1.5 1.5 0 1 1 3.001.001A1.5 1.5 0 0 1 7 10.5zM8 17s1-3 4-3 4 3 4 3H8zm7.493-5.014a1.494 1.494 0 1 1 .001-2.987 1.494 1.494 0 0 1-.001 2.987z"></path></svg>
									
								</div>
								<div>
									<h2 class="text-white invoice-num"><?=$totalBadMood;?></h2>
									<span class="text-white fs-18">Jumlah Murid Bad Mood</span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-xxl-6 col-sm-6">
						<div class="card bg-secondary invoice-card view-late-students" data-bs-toggle="modal" data-bs-target="#lateStudentsModal"style="cursor: pointer;">
							<div class="card-body d-flex">
								<div class="icon me-3">
								<svg xmlns="http://www.w3.org/2000/svg" width="35" height="34" viewBox="0 0 24 24" style="fill: #E52020;transform: msFilter;"><path d="M5 3H3v18h18v-2H5z"></path><path d="M13 12.586 8.707 8.293 7.293 9.707 13 15.414l3-3 4.293 4.293 1.414-1.414L16 9.586z"></path></svg>
								
								</div>
								<div>
									<h2 class="text-white invoice-num"><?=$totalLate;?></h2>
									<span class="text-white fs-18">Jumlah Murid Terlambat</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-9 col-xxl-5">
						<div class="card">
							<div class="card-body">
								<div class="row align-items-center">
									<!-- <div class="col-xl-6">
										<div class="card-bx bg-blue">
											<img class="pattern-img" src="images/pattern/pattern6.png" alt="">
											<div class="card-info text-white">
												<img src="images/pattern/circle.png" class="mb-4" alt="">
												<h2 class="text-white card-balance">$824,571.93</h2>
												<p class="fs-16">Wallet Balance</p>
												<span>+0,8% than last week</span>
											</div>
											<a class="change-btn" href="javascript:void(0);"><i class="fa fa-caret-up up-ico"></i>Change<span class="reload-icon"><i class="fas fa-sync-alt reload active"></i></span></a>
										</div>
									</div> -->
									<div class="col-xl-12">
										<div class="row  mt-xl-0 mt-4">
											<div class="col-md-12">
												<h4 class="card-title">Data Absensi Siswa Hari Ini</h4>
												<span>Berikut Data Absensi Siswa yang terlampir pada hari ini</span>
												<ul class="card-list mt-4">
												<li><span class="bg-blue circle"></span>Belum Absen<span>
													<?php echo number_format($persentaseTidakHadir, 2); ?>%</span></li>
												<li><span class="bg-success circle"></span>Hadir<span>
													<?php echo number_format($persentaseKehadiran, 2); ?>%</span></li>
												<li><span class="bg-warning circle"></span>Terlambat<span>
													<?php echo number_format($persentaseTerlambat, 2); ?>%</span></li>
												</ul>
											</div>
											<div class="col-md-12">
												<canvas id="polarChart"></canvas>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- <div class="col-xl-3 col-xxl-5">
						<div class="card">
							<div class="card-header pb-0 border-0">
								<div>
									<h4 class="card-title mb-2">Activity</h4>
									<h2 class="mb-0">$78120</h2>
								</div>
								<ul class="card-list">
									<li class="justify-content-end">Income<span class="bg-success circle me-0 ms-2"></span></li>
									<li class="justify-content-end">Outcome<span class="bg-danger circle me-0 ms-2"></span></li>
								</ul>
							</div>
							<div class="card-body pb-0 pt-3">
								<div id="chartBar" class="bar-chart"></div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-xxl-7">
						<div class="card">
							<div class="card-header border-0 pb-0">
								<div>
									<h4 class="card-title mb-2">Quick Transfer</h4>
									<span class="fs-12">Lorem ipsum dolor sit amet, consectetur</span>
								</div>
								<div class="dropdown">
									<a href="javascript:void(0);" class="btn-link" data-bs-toggle="dropdown" aria-expanded="false">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
											<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
											<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
										</svg>
									</a>
									<div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item" href="javascript:void(0);">Delete</a>
										<a class="dropdown-item" href="javascript:void(0);">Edit</a>
									</div>
								</div>
							</div>
							<div class="card-body">	
								<div class="user-bx">
									<img src="images/ion/man (1).png" alt="">
									<div>
										<h6 class="user-name">Samuel</h6>
										<span class="meta">@sam224</span>
									</div>
									<i class="las la-check-circle check-icon"></i>
								</div>
								<h4 class="mt-3 mb-3">Recent Friend<a href="javascript:void(0);" class="fs-16 float-end text-secondary font-w600">See More</a></h4>
								<ul class="user-list">
									<li><img src="images/ion/bussiness-man.png" alt=""></li>
									<li><img src="images/ion/hacker.png" alt=""></li>
									<li><img src="images/ion/man (2).png" alt=""></li>
									<li><img src="images/ion/woman.png" alt=""></li>
									<li><img src="images/ion/man.png" alt=""></li>
									<li><img src="images/ion/woman.png" alt=""></li>
								
								</ul>
								<h4 class="mt-3 mb-0">Insert Amount</h4>
								<div class="format-slider">
                                    <input class="form-control amount-input"  title="Formatted number" id="input-format">
                                    <div id="slider-format"></div>
                                </div>
								<div class="text-secondary fs-16 d-flex justify-content-between font-w600 mt-4">
									<span>Your Balance</span>
									<span>$ 456,345.62</span>
								</div>
							</div>
							<div class="card-footer border-0 pt-0">
								<a href="javascript:void(0);" class="btn btn-primary d-block btn-lg text-uppercase">Transfer Now</a>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-xxl-5">
						<div class="card">
							<div class="card-header border-0 pb-0">
								<div>
									<h4 class="card-title mb-2">Spendings</h4>
									<span class="fs-12">Lorem ipsum dolor sit amet, consectetur</span>
								</div>
								<div class="dropdown">
									<a href="javascript:void(0);" class="btn-link" data-bs-toggle="dropdown" aria-expanded="false">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
											<path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
											<path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
										</svg>
									</a>
									<div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item" href="javascript:void(0);">Delete</a>
										<a class="dropdown-item" href="javascript:void(0);">Edit</a>
									</div>
								</div>
							</div>
							<div class="card-body">	
								<div class="progress default-progress">
                                    <div class="progress-bar bg-gradient-1 progress-animated" style="width: 45%; height:20px;" role="progressbar">
                                        <span class="sr-only">45% Complete</span>
                                    </div>
                                </div>
								<div class="d-flex align-items-end mt-2 pb-3 justify-content-between">
									<span>Investment</span>
									<span class="fs-18"><span class="text-black pe-2">$1415</span>/$2000</span>
								</div>
								<div class="progress default-progress mt-4">
                                    <div class="progress-bar bg-gradient-2 progress-animated" style="width: 70%; height:20px;" role="progressbar">
                                        <span class="sr-only">70% Complete</span>
                                    </div>
                                </div>
								<div class="d-flex align-items-end mt-2 pb-3 justify-content-between">
									<span>Restaurant</span>
									<span class="fs-18"><span class="text-black pe-2">$1567</span>/$5000</span>
								</div>
								<div class="progress default-progress mt-4">
                                    <div class="progress-bar bg-gradient-3 progress-animated" style="width: 35%; height:20px;" role="progressbar">
                                        <span class="sr-only">35% Complete</span>
                                    </div>
                                </div>
								<div class="d-flex align-items-end mt-2 pb-3 justify-content-between">
									<span>Installment</span>
									<span class="fs-18"><span class="text-black pe-2">$487</span>/$10000</span>
								</div>
								<div class="progress default-progress mt-4">
                                    <div class="progress-bar bg-gradient-4 progress-animated" style="width: 95%; height:20px;" role="progressbar">
                                        <span class="sr-only">95% Complete</span>
                                    </div>
                                </div>
								<div class="d-flex align-items-end mt-2 justify-content-between">
									<span>Property</span>
									<span class="fs-18"><span class="text-black pe-2">$3890</span>/$4000</span>
								</div>
							</div>
							<div class="card-footer border-0 pt-0">
								<a href="javascript:void(0);" class="btn btn-outline-primary d-block btn-lg">View More</a>
							</div>
						</div>
					</div> -->
					<div class="col-xl-6 col-xxl-7">
						<div class="card">
							<div class="card-header d-flex flex-wrap border-0 pb-0">
								<div class="me-auto mb-sm-0 mb-3">
									<h4 class="card-title mb-2">Data Absensi Siswa</h4>
									<span class="fs-12">Berikut Data Absensi Siswa per-Minggu</span>
								</div>
							</div>
							<div class="card-body pb-2">
								<!-- Date Range Selectors -->
								<div class="d-flex align-items-center gap-2 mt-3 mb-3">
									<label for="startDate" class="form-label">Start Date:</label>
									<input type="date" id="startDate" name="startDate" class="form-control">
									
									<label for="endDate" class="form-label">End Date:</label>
									<input type="date" id="endDate" name="endDate" class="form-control">
									
									<button id="updateButton" class="btn btn-primary">Update Chart</button>
								</div>
								<div id="chartBar2" class="bar-chart"></div>
								<ul class="card-list d-flex justify-content-center mt-sm-0 mt-3">
									<li class="me-3"><span class="bg-success circle"></span>Hadir</li>
									<li><span class="bg-danger circle"></span>Terlambat</li>
								</ul>
							</div>

						</div>
					</div>

					<!-- <div class="col-xl-6 col-xxl-12">
						<div class="card">
							<div class="card-header d-block d-sm-flex border-0">
								<div class="me-3">
									<h4 class="card-title mb-2">Previous Transactions</h4>
									<span class="fs-12">Lorem ipsum dolor sit amet, consectetur</span>
								</div>
								<div class="card-tabs mt-3 mt-sm-0">
									<ul class="nav nav-tabs" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" data-bs-toggle="tab" href="#monthly" role="tab">Monthly</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-bs-toggle="tab" href="#Weekly" role="tab">Weekly</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-bs-toggle="tab" href="#Today" role="tab">Today</a>
										</li>
									</ul>
								</div>
							</div>
							<div class="card-body tab-content p-0">
								<div class="tab-pane active show fade" id="monthly" role="tabpanel">
									<div class="table-responsive">
										<table class="table table-responsive-md card-table transactions-table">
											<tbody>
												<tr>
													<td>
														<svg class="bgl-success tr-icon" width="63" height="63" viewBox="0 0 63 63" fill="none" xmlns="http://www.w3.org/2000/svg">
															<g><path d="M35.2219 42.9875C34.8938 42.3094 35.1836 41.4891 35.8617 41.1609C37.7484 40.2531 39.3453 38.8422 40.4828 37.0758C41.6477 35.2656 42.2656 33.1656 42.2656 31C42.2656 24.7875 37.2125 19.7344 31 19.7344C24.7875 19.7344 19.7344 24.7875 19.7344 31C19.7344 33.1656 20.3523 35.2656 21.5117 37.0813C22.6437 38.8477 24.2461 40.2586 26.1328 41.1664C26.8109 41.4945 27.1008 42.3094 26.7727 42.993C26.4445 43.6711 25.6297 43.9609 24.9461 43.6328C22.6 42.5063 20.6148 40.7563 19.2094 38.5578C17.7656 36.3047 17 33.6906 17 31C17 27.2594 18.4547 23.743 21.1016 21.1016C23.743 18.4547 27.2594 17 31 17C34.7406 17 38.257 18.4547 40.8984 21.1016C43.5453 23.7484 45 27.2594 45 31C45 33.6906 44.2344 36.3047 42.7852 38.5578C41.3742 40.7508 39.3891 42.5063 37.0484 43.6328C36.3648 43.9555 35.55 43.6711 35.2219 42.9875Z" fill="#2BC155"></path><path d="M36.3211 31.7274C36.5891 31.9953 36.7203 32.3453 36.7203 32.6953C36.7203 33.0453 36.5891 33.3953 36.3211 33.6633L32.8812 37.1031C32.3781 37.6063 31.7109 37.8797 31.0055 37.8797C30.3 37.8797 29.6273 37.6008 29.1297 37.1031L25.6898 33.6633C25.1539 33.1274 25.1539 32.2633 25.6898 31.7274C26.2258 31.1914 27.0898 31.1914 27.6258 31.7274L29.6437 33.7453L29.6437 25.9742C29.6437 25.2196 30.2562 24.6071 31.0109 24.6071C31.7656 24.6071 32.3781 25.2196 32.3781 25.9742L32.3781 33.7508L34.3961 31.7328C34.9211 31.1969 35.7852 31.1969 36.3211 31.7274Z" fill="#2BC155"></path>
															</g>
														</svg>
													</td>
													<td>
														<h6 class="fs-16 font-w600 mb-0"><a href="javascript:void(0);" class="text-black">XYZ Store ID</a></h6>
														<span class="fs-14">Cashback</span>
													</td>
													<td>
														<h6 class="fs-16 text-black font-w600 mb-0">June 4, 2020</h6>
														<span class="fs-14">05:34:45 AM</span>
													</td>
													<td><span class="fs-16 text-black font-w600">+$5,553</span></td>
													<td><span class="text-success fs-16 font-w500 text-end d-block">Completed</span></td>
												</tr>
												<tr>
													<td>
														<svg class="bgl-danger tr-icon" width="63" height="63" viewBox="0 0 63 63" fill="none" xmlns="http://www.w3.org/2000/svg">
															<g><path d="M35.2219 19.0125C34.8937 19.6906 35.1836 20.5109 35.8617 20.8391C37.7484 21.7469 39.3453 23.1578 40.4828 24.9242C41.6476 26.7344 42.2656 28.8344 42.2656 31C42.2656 37.2125 37.2125 42.2656 31 42.2656C24.7875 42.2656 19.7344 37.2125 19.7344 31C19.7344 28.8344 20.3523 26.7344 21.5117 24.9187C22.6437 23.1523 24.2461 21.7414 26.1328 20.8336C26.8109 20.5055 27.1008 19.6906 26.7726 19.007C26.4445 18.3289 25.6297 18.0391 24.9461 18.3672C22.6 19.4937 20.6148 21.2437 19.2094 23.4422C17.7656 25.6953 17 28.3094 17 31C17 34.7406 18.4547 38.257 21.1015 40.8984C23.743 43.5453 27.2594 45 31 45C34.7406 45 38.257 43.5453 40.8984 40.8984C43.5453 38.2516 45 34.7406 45 31C45 28.3094 44.2344 25.6953 42.7851 23.4422C41.3742 21.2492 39.389 19.4937 37.0484 18.3672C36.3648 18.0445 35.55 18.3289 35.2219 19.0125Z" fill="#FF2E2E"></path><path d="M36.3211 30.2726C36.589 30.0047 36.7203 29.6547 36.7203 29.3047C36.7203 28.9547 36.589 28.6047 36.3211 28.3367L32.8812 24.8969C32.3781 24.3937 31.7109 24.1203 31.0055 24.1203C30.3 24.1203 29.6273 24.3992 29.1297 24.8969L25.6898 28.3367C25.1539 28.8726 25.1539 29.7367 25.6898 30.2726C26.2258 30.8086 27.0898 30.8086 27.6258 30.2726L29.6437 28.2547L29.6437 36.0258C29.6437 36.7804 30.2562 37.3929 31.0109 37.3929C31.7656 37.3929 32.3781 36.7804 32.3781 36.0258L32.3781 28.2492L34.3961 30.2672C34.9211 30.8031 35.7851 30.8031 36.3211 30.2726Z" fill="#FF2E2E"></path></g>
														</svg>
													</td>
													<td>
														<h6 class="fs-16 font-w600 mb-0"><a href="javascript:void(0);" class="text-black">Chef Renata</a></h6>
														<span class="fs-14">Transfer</span>
													</td>
													<td>
														<h6 class="fs-16 text-black font-w600 mb-0">June 5, 2020</h6>
														<span class="fs-14">05:34:45 AM</span>
													</td>
													<td><span class="fs-16 text-black font-w600">-$167</span></td>
													<td><span class="text-light fs-16 font-w500 text-end d-block">Pending</span></td>
												</tr>
												<tr>
													<td>
														<svg class="bgl-success tr-icon" width="63" height="63" viewBox="0 0 63 63" fill="none" xmlns="http://www.w3.org/2000/svg">
															<g><path d="M35.2219 42.9875C34.8938 42.3094 35.1836 41.4891 35.8617 41.1609C37.7484 40.2531 39.3453 38.8422 40.4828 37.0758C41.6477 35.2656 42.2656 33.1656 42.2656 31C42.2656 24.7875 37.2125 19.7344 31 19.7344C24.7875 19.7344 19.7344 24.7875 19.7344 31C19.7344 33.1656 20.3523 35.2656 21.5117 37.0813C22.6437 38.8477 24.2461 40.2586 26.1328 41.1664C26.8109 41.4945 27.1008 42.3094 26.7727 42.993C26.4445 43.6711 25.6297 43.9609 24.9461 43.6328C22.6 42.5063 20.6148 40.7563 19.2094 38.5578C17.7656 36.3047 17 33.6906 17 31C17 27.2594 18.4547 23.743 21.1016 21.1016C23.743 18.4547 27.2594 17 31 17C34.7406 17 38.257 18.4547 40.8984 21.1016C43.5453 23.7484 45 27.2594 45 31C45 33.6906 44.2344 36.3047 42.7852 38.5578C41.3742 40.7508 39.3891 42.5063 37.0484 43.6328C36.3648 43.9555 35.55 43.6711 35.2219 42.9875Z" fill="#2BC155"></path><path d="M36.3211 31.7274C36.5891 31.9953 36.7203 32.3453 36.7203 32.6953C36.7203 33.0453 36.5891 33.3953 36.3211 33.6633L32.8812 37.1031C32.3781 37.6063 31.7109 37.8797 31.0055 37.8797C30.3 37.8797 29.6273 37.6008 29.1297 37.1031L25.6898 33.6633C25.1539 33.1274 25.1539 32.2633 25.6898 31.7274C26.2258 31.1914 27.0898 31.1914 27.6258 31.7274L29.6437 33.7453L29.6437 25.9742C29.6437 25.2196 30.2562 24.6071 31.0109 24.6071C31.7656 24.6071 32.3781 25.2196 32.3781 25.9742L32.3781 33.7508L34.3961 31.7328C34.9211 31.1969 35.7852 31.1969 36.3211 31.7274Z" fill="#2BC155"></path>
															</g>
														</svg>
													</td>
													<td>
														<h6 class="fs-16 font-w600 mb-0"><a href="javascript:void(0);" class="text-black">Cindy Alexandro</a></h6>
														<span class="fs-14">Transfer</span>
													</td>
													<td>
														<h6 class="fs-16 text-black font-w600 mb-0">June 5, 2020</h6>
														<span class="fs-14">05:34:45 AM</span>
													</td>
													<td><span class="fs-16 text-black font-w600">+$5,553</span></td>
													<td><span class="text-danger fs-16 font-w500 text-end d-block">Canceled</span></td>
												</tr>
												<tr>
													<td>
														<svg class="bgl-success tr-icon" width="63" height="63" viewBox="0 0 63 63" fill="none" xmlns="http://www.w3.org/2000/svg">
															<g><path d="M35.2219 42.9875C34.8938 42.3094 35.1836 41.4891 35.8617 41.1609C37.7484 40.2531 39.3453 38.8422 40.4828 37.0758C41.6477 35.2656 42.2656 33.1656 42.2656 31C42.2656 24.7875 37.2125 19.7344 31 19.7344C24.7875 19.7344 19.7344 24.7875 19.7344 31C19.7344 33.1656 20.3523 35.2656 21.5117 37.0813C22.6437 38.8477 24.2461 40.2586 26.1328 41.1664C26.8109 41.4945 27.1008 42.3094 26.7727 42.993C26.4445 43.6711 25.6297 43.9609 24.9461 43.6328C22.6 42.5063 20.6148 40.7563 19.2094 38.5578C17.7656 36.3047 17 33.6906 17 31C17 27.2594 18.4547 23.743 21.1016 21.1016C23.743 18.4547 27.2594 17 31 17C34.7406 17 38.257 18.4547 40.8984 21.1016C43.5453 23.7484 45 27.2594 45 31C45 33.6906 44.2344 36.3047 42.7852 38.5578C41.3742 40.7508 39.3891 42.5063 37.0484 43.6328C36.3648 43.9555 35.55 43.6711 35.2219 42.9875Z" fill="#2BC155"></path><path d="M36.3211 31.7274C36.5891 31.9953 36.7203 32.3453 36.7203 32.6953C36.7203 33.0453 36.5891 33.3953 36.3211 33.6633L32.8812 37.1031C32.3781 37.6063 31.7109 37.8797 31.0055 37.8797C30.3 37.8797 29.6273 37.6008 29.1297 37.1031L25.6898 33.6633C25.1539 33.1274 25.1539 32.2633 25.6898 31.7274C26.2258 31.1914 27.0898 31.1914 27.6258 31.7274L29.6437 33.7453L29.6437 25.9742C29.6437 25.2196 30.2562 24.6071 31.0109 24.6071C31.7656 24.6071 32.3781 25.2196 32.3781 25.9742L32.3781 33.7508L34.3961 31.7328C34.9211 31.1969 35.7852 31.1969 36.3211 31.7274Z" fill="#2BC155"></path>
															</g>
														</svg>
													</td>
													<td>
														<h6 class="fs-16 font-w600 mb-0"><a href="javascript:void(0);" class="text-black">Paipal</a></h6>
														<span class="fs-14">Transfer</span>
													</td>
													<td>
														<h6 class="fs-16 text-black font-w600 mb-0">June 4, 2020</h6>
														<span class="fs-14">05:34:45 AM</span>
													</td>
													<td><span class="fs-16 text-black font-w600">+$5,553</span></td>
													<td><span class="text-success fs-16 font-w500 text-end d-block">Completed</span></td>
												</tr>
												<tr>
													<td>
														<svg class="bgl-danger tr-icon" width="63" height="63" viewBox="0 0 63 63" fill="none" xmlns="http://www.w3.org/2000/svg">
															<g><path d="M35.2219 19.0125C34.8937 19.6906 35.1836 20.5109 35.8617 20.8391C37.7484 21.7469 39.3453 23.1578 40.4828 24.9242C41.6476 26.7344 42.2656 28.8344 42.2656 31C42.2656 37.2125 37.2125 42.2656 31 42.2656C24.7875 42.2656 19.7344 37.2125 19.7344 31C19.7344 28.8344 20.3523 26.7344 21.5117 24.9187C22.6437 23.1523 24.2461 21.7414 26.1328 20.8336C26.8109 20.5055 27.1008 19.6906 26.7726 19.007C26.4445 18.3289 25.6297 18.0391 24.9461 18.3672C22.6 19.4937 20.6148 21.2437 19.2094 23.4422C17.7656 25.6953 17 28.3094 17 31C17 34.7406 18.4547 38.257 21.1015 40.8984C23.743 43.5453 27.2594 45 31 45C34.7406 45 38.257 43.5453 40.8984 40.8984C43.5453 38.2516 45 34.7406 45 31C45 28.3094 44.2344 25.6953 42.7851 23.4422C41.3742 21.2492 39.389 19.4937 37.0484 18.3672C36.3648 18.0445 35.55 18.3289 35.2219 19.0125Z" fill="#FF2E2E"></path><path d="M36.3211 30.2726C36.589 30.0047 36.7203 29.6547 36.7203 29.3047C36.7203 28.9547 36.589 28.6047 36.3211 28.3367L32.8812 24.8969C32.3781 24.3937 31.7109 24.1203 31.0055 24.1203C30.3 24.1203 29.6273 24.3992 29.1297 24.8969L25.6898 28.3367C25.1539 28.8726 25.1539 29.7367 25.6898 30.2726C26.2258 30.8086 27.0898 30.8086 27.6258 30.2726L29.6437 28.2547L29.6437 36.0258C29.6437 36.7804 30.2562 37.3929 31.0109 37.3929C31.7656 37.3929 32.3781 36.7804 32.3781 36.0258L32.3781 28.2492L34.3961 30.2672C34.9211 30.8031 35.7851 30.8031 36.3211 30.2726Z" fill="#FF2E2E"></path></g>
														</svg>
													</td>
													<td>
														<h6 class="fs-16 font-w600 mb-0"><a href="javascript:void(0);" class="text-black">Hawkins Jr.</a></h6>
														<span class="fs-14">Cashback</span>
													</td>
													<td>
														<h6 class="fs-16 text-black font-w600 mb-0">June 4, 2020</h6>
														<span class="fs-14">05:34:45 AM</span>
													</td>
													<td><span class="fs-16 text-black font-w600">+$5,553</span></td>
													<td><span class="text-danger fs-16 font-w500 text-end d-block">Canceled</span></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="tab-pane" id="Weekly" role="tabpanel">
									<div class="table-responsive">
										<table class="table table-responsive-md card-table transactions-table">
											<tbody>
												<tr>
													<td>
														<svg class="bgl-success tr-icon" width="63" height="63" viewBox="0 0 63 63" fill="none" xmlns="http://www.w3.org/2000/svg">
															<g><path d="M35.2219 42.9875C34.8938 42.3094 35.1836 41.4891 35.8617 41.1609C37.7484 40.2531 39.3453 38.8422 40.4828 37.0758C41.6477 35.2656 42.2656 33.1656 42.2656 31C42.2656 24.7875 37.2125 19.7344 31 19.7344C24.7875 19.7344 19.7344 24.7875 19.7344 31C19.7344 33.1656 20.3523 35.2656 21.5117 37.0813C22.6437 38.8477 24.2461 40.2586 26.1328 41.1664C26.8109 41.4945 27.1008 42.3094 26.7727 42.993C26.4445 43.6711 25.6297 43.9609 24.9461 43.6328C22.6 42.5063 20.6148 40.7563 19.2094 38.5578C17.7656 36.3047 17 33.6906 17 31C17 27.2594 18.4547 23.743 21.1016 21.1016C23.743 18.4547 27.2594 17 31 17C34.7406 17 38.257 18.4547 40.8984 21.1016C43.5453 23.7484 45 27.2594 45 31C45 33.6906 44.2344 36.3047 42.7852 38.5578C41.3742 40.7508 39.3891 42.5063 37.0484 43.6328C36.3648 43.9555 35.55 43.6711 35.2219 42.9875Z" fill="#2BC155"></path><path d="M36.3211 31.7274C36.5891 31.9953 36.7203 32.3453 36.7203 32.6953C36.7203 33.0453 36.5891 33.3953 36.3211 33.6633L32.8812 37.1031C32.3781 37.6063 31.7109 37.8797 31.0055 37.8797C30.3 37.8797 29.6273 37.6008 29.1297 37.1031L25.6898 33.6633C25.1539 33.1274 25.1539 32.2633 25.6898 31.7274C26.2258 31.1914 27.0898 31.1914 27.6258 31.7274L29.6437 33.7453L29.6437 25.9742C29.6437 25.2196 30.2562 24.6071 31.0109 24.6071C31.7656 24.6071 32.3781 25.2196 32.3781 25.9742L32.3781 33.7508L34.3961 31.7328C34.9211 31.1969 35.7852 31.1969 36.3211 31.7274Z" fill="#2BC155"></path>
															</g>
														</svg>
													</td>
													<td>
														<h6 class="fs-16 font-w600 mb-0"><a href="javascript:void(0);" class="text-black">XYZ Store ID</a></h6>
														<span class="fs-14">Cashback</span>
													</td>
													<td>
														<h6 class="fs-16 text-black font-w600 mb-0">June 4, 2020</h6>
														<span class="fs-14">05:34:45 AM</span>
													</td>
													<td><span class="fs-16 text-black font-w600">+$5,553</span></td>
													<td><span class="text-success fs-16 font-w500 text-end d-block">Completed</span></td>
												</tr>
												<tr>
													<td>
														<svg class="bgl-danger tr-icon" width="63" height="63" viewBox="0 0 63 63" fill="none" xmlns="http://www.w3.org/2000/svg">
															<g><path d="M35.2219 19.0125C34.8937 19.6906 35.1836 20.5109 35.8617 20.8391C37.7484 21.7469 39.3453 23.1578 40.4828 24.9242C41.6476 26.7344 42.2656 28.8344 42.2656 31C42.2656 37.2125 37.2125 42.2656 31 42.2656C24.7875 42.2656 19.7344 37.2125 19.7344 31C19.7344 28.8344 20.3523 26.7344 21.5117 24.9187C22.6437 23.1523 24.2461 21.7414 26.1328 20.8336C26.8109 20.5055 27.1008 19.6906 26.7726 19.007C26.4445 18.3289 25.6297 18.0391 24.9461 18.3672C22.6 19.4937 20.6148 21.2437 19.2094 23.4422C17.7656 25.6953 17 28.3094 17 31C17 34.7406 18.4547 38.257 21.1015 40.8984C23.743 43.5453 27.2594 45 31 45C34.7406 45 38.257 43.5453 40.8984 40.8984C43.5453 38.2516 45 34.7406 45 31C45 28.3094 44.2344 25.6953 42.7851 23.4422C41.3742 21.2492 39.389 19.4937 37.0484 18.3672C36.3648 18.0445 35.55 18.3289 35.2219 19.0125Z" fill="#FF2E2E"></path><path d="M36.3211 30.2726C36.589 30.0047 36.7203 29.6547 36.7203 29.3047C36.7203 28.9547 36.589 28.6047 36.3211 28.3367L32.8812 24.8969C32.3781 24.3937 31.7109 24.1203 31.0055 24.1203C30.3 24.1203 29.6273 24.3992 29.1297 24.8969L25.6898 28.3367C25.1539 28.8726 25.1539 29.7367 25.6898 30.2726C26.2258 30.8086 27.0898 30.8086 27.6258 30.2726L29.6437 28.2547L29.6437 36.0258C29.6437 36.7804 30.2562 37.3929 31.0109 37.3929C31.7656 37.3929 32.3781 36.7804 32.3781 36.0258L32.3781 28.2492L34.3961 30.2672C34.9211 30.8031 35.7851 30.8031 36.3211 30.2726Z" fill="#FF2E2E"></path></g>
														</svg>
													</td>
													<td>
														<h6 class="fs-16 font-w600 mb-0"><a href="javascript:void(0);" class="text-black">Chef Renata</a></h6>
														<span class="fs-14">Transfer</span>
													</td>
													<td>
														<h6 class="fs-16 text-black font-w600 mb-0">June 5, 2020</h6>
														<span class="fs-14">05:34:45 AM</span>
													</td>
													<td><span class="fs-16 text-black font-w600">-$167</span></td>
													<td><span class="text-light fs-16 font-w500 text-end d-block">Pending</span></td>
												</tr>
												<tr>
													<td>
														<svg class="bgl-success tr-icon" width="63" height="63" viewBox="0 0 63 63" fill="none" xmlns="http://www.w3.org/2000/svg">
															<g><path d="M35.2219 42.9875C34.8938 42.3094 35.1836 41.4891 35.8617 41.1609C37.7484 40.2531 39.3453 38.8422 40.4828 37.0758C41.6477 35.2656 42.2656 33.1656 42.2656 31C42.2656 24.7875 37.2125 19.7344 31 19.7344C24.7875 19.7344 19.7344 24.7875 19.7344 31C19.7344 33.1656 20.3523 35.2656 21.5117 37.0813C22.6437 38.8477 24.2461 40.2586 26.1328 41.1664C26.8109 41.4945 27.1008 42.3094 26.7727 42.993C26.4445 43.6711 25.6297 43.9609 24.9461 43.6328C22.6 42.5063 20.6148 40.7563 19.2094 38.5578C17.7656 36.3047 17 33.6906 17 31C17 27.2594 18.4547 23.743 21.1016 21.1016C23.743 18.4547 27.2594 17 31 17C34.7406 17 38.257 18.4547 40.8984 21.1016C43.5453 23.7484 45 27.2594 45 31C45 33.6906 44.2344 36.3047 42.7852 38.5578C41.3742 40.7508 39.3891 42.5063 37.0484 43.6328C36.3648 43.9555 35.55 43.6711 35.2219 42.9875Z" fill="#2BC155"></path><path d="M36.3211 31.7274C36.5891 31.9953 36.7203 32.3453 36.7203 32.6953C36.7203 33.0453 36.5891 33.3953 36.3211 33.6633L32.8812 37.1031C32.3781 37.6063 31.7109 37.8797 31.0055 37.8797C30.3 37.8797 29.6273 37.6008 29.1297 37.1031L25.6898 33.6633C25.1539 33.1274 25.1539 32.2633 25.6898 31.7274C26.2258 31.1914 27.0898 31.1914 27.6258 31.7274L29.6437 33.7453L29.6437 25.9742C29.6437 25.2196 30.2562 24.6071 31.0109 24.6071C31.7656 24.6071 32.3781 25.2196 32.3781 25.9742L32.3781 33.7508L34.3961 31.7328C34.9211 31.1969 35.7852 31.1969 36.3211 31.7274Z" fill="#2BC155"></path>
															</g>
														</svg>
													</td>
													<td>
														<h6 class="fs-16 font-w600 mb-0"><a href="javascript:void(0);" class="text-black">Cindy Alexandro</a></h6>
														<span class="fs-14">Transfer</span>
													</td>
													<td>
														<h6 class="fs-16 text-black font-w600 mb-0">June 5, 2020</h6>
														<span class="fs-14">05:34:45 AM</span>
													</td>
													<td><span class="fs-16 text-black font-w600">+$5,553</span></td>
													<td><span class="text-danger fs-16 font-w500 text-end d-block">Canceled</span></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="tab-pane" id="Today" role="tabpanel">
									<div class="table-responsive">
										<table class="table table-responsive-md card-table transactions-table">
											<tbody>
												<tr>
													<td>
														<svg class="bgl-danger tr-icon" width="63" height="63" viewBox="0 0 63 63" fill="none" xmlns="http://www.w3.org/2000/svg">
															<g><path d="M35.2219 19.0125C34.8937 19.6906 35.1836 20.5109 35.8617 20.8391C37.7484 21.7469 39.3453 23.1578 40.4828 24.9242C41.6476 26.7344 42.2656 28.8344 42.2656 31C42.2656 37.2125 37.2125 42.2656 31 42.2656C24.7875 42.2656 19.7344 37.2125 19.7344 31C19.7344 28.8344 20.3523 26.7344 21.5117 24.9187C22.6437 23.1523 24.2461 21.7414 26.1328 20.8336C26.8109 20.5055 27.1008 19.6906 26.7726 19.007C26.4445 18.3289 25.6297 18.0391 24.9461 18.3672C22.6 19.4937 20.6148 21.2437 19.2094 23.4422C17.7656 25.6953 17 28.3094 17 31C17 34.7406 18.4547 38.257 21.1015 40.8984C23.743 43.5453 27.2594 45 31 45C34.7406 45 38.257 43.5453 40.8984 40.8984C43.5453 38.2516 45 34.7406 45 31C45 28.3094 44.2344 25.6953 42.7851 23.4422C41.3742 21.2492 39.389 19.4937 37.0484 18.3672C36.3648 18.0445 35.55 18.3289 35.2219 19.0125Z" fill="#FF2E2E"></path><path d="M36.3211 30.2726C36.589 30.0047 36.7203 29.6547 36.7203 29.3047C36.7203 28.9547 36.589 28.6047 36.3211 28.3367L32.8812 24.8969C32.3781 24.3937 31.7109 24.1203 31.0055 24.1203C30.3 24.1203 29.6273 24.3992 29.1297 24.8969L25.6898 28.3367C25.1539 28.8726 25.1539 29.7367 25.6898 30.2726C26.2258 30.8086 27.0898 30.8086 27.6258 30.2726L29.6437 28.2547L29.6437 36.0258C29.6437 36.7804 30.2562 37.3929 31.0109 37.3929C31.7656 37.3929 32.3781 36.7804 32.3781 36.0258L32.3781 28.2492L34.3961 30.2672C34.9211 30.8031 35.7851 30.8031 36.3211 30.2726Z" fill="#FF2E2E"></path></g>
														</svg>
													</td>
													<td>
														<h6 class="fs-16 font-w600 mb-0"><a href="javascript:void(0);" class="text-black">Chef Renata</a></h6>
														<span class="fs-14">Transfer</span>
													</td>
													<td>
														<h6 class="fs-16 text-black font-w600 mb-0">June 5, 2020</h6>
														<span class="fs-14">05:34:45 AM</span>
													</td>
													<td><span class="fs-16 text-black font-w600">-$167</span></td>
													<td><span class="text-light fs-16 font-w500 text-end d-block">Pending</span></td>
												</tr>
												<tr>
													<td>
														<svg class="bgl-success tr-icon" width="63" height="63" viewBox="0 0 63 63" fill="none" xmlns="http://www.w3.org/2000/svg">
															<g><path d="M35.2219 42.9875C34.8938 42.3094 35.1836 41.4891 35.8617 41.1609C37.7484 40.2531 39.3453 38.8422 40.4828 37.0758C41.6477 35.2656 42.2656 33.1656 42.2656 31C42.2656 24.7875 37.2125 19.7344 31 19.7344C24.7875 19.7344 19.7344 24.7875 19.7344 31C19.7344 33.1656 20.3523 35.2656 21.5117 37.0813C22.6437 38.8477 24.2461 40.2586 26.1328 41.1664C26.8109 41.4945 27.1008 42.3094 26.7727 42.993C26.4445 43.6711 25.6297 43.9609 24.9461 43.6328C22.6 42.5063 20.6148 40.7563 19.2094 38.5578C17.7656 36.3047 17 33.6906 17 31C17 27.2594 18.4547 23.743 21.1016 21.1016C23.743 18.4547 27.2594 17 31 17C34.7406 17 38.257 18.4547 40.8984 21.1016C43.5453 23.7484 45 27.2594 45 31C45 33.6906 44.2344 36.3047 42.7852 38.5578C41.3742 40.7508 39.3891 42.5063 37.0484 43.6328C36.3648 43.9555 35.55 43.6711 35.2219 42.9875Z" fill="#2BC155"></path><path d="M36.3211 31.7274C36.5891 31.9953 36.7203 32.3453 36.7203 32.6953C36.7203 33.0453 36.5891 33.3953 36.3211 33.6633L32.8812 37.1031C32.3781 37.6063 31.7109 37.8797 31.0055 37.8797C30.3 37.8797 29.6273 37.6008 29.1297 37.1031L25.6898 33.6633C25.1539 33.1274 25.1539 32.2633 25.6898 31.7274C26.2258 31.1914 27.0898 31.1914 27.6258 31.7274L29.6437 33.7453L29.6437 25.9742C29.6437 25.2196 30.2562 24.6071 31.0109 24.6071C31.7656 24.6071 32.3781 25.2196 32.3781 25.9742L32.3781 33.7508L34.3961 31.7328C34.9211 31.1969 35.7852 31.1969 36.3211 31.7274Z" fill="#2BC155"></path>
															</g>
														</svg>
													</td>
													<td>
														<h6 class="fs-16 font-w600 mb-0"><a href="javascript:void(0);" class="text-black">Cindy Alexandro</a></h6>
														<span class="fs-14">Transfer</span>
													</td>
													<td>
														<h6 class="fs-16 text-black font-w600 mb-0">June 5, 2020</h6>
														<span class="fs-14">05:34:45 AM</span>
													</td>
													<td><span class="fs-16 text-black font-w600">+$5,553</span></td>
													<td><span class="text-danger fs-16 font-w500 text-end d-block">Canceled</span></td>
												</tr>
												<tr>
													<td>
														<svg class="bgl-success tr-icon" width="63" height="63" viewBox="0 0 63 63" fill="none" xmlns="http://www.w3.org/2000/svg">
															<g><path d="M35.2219 42.9875C34.8938 42.3094 35.1836 41.4891 35.8617 41.1609C37.7484 40.2531 39.3453 38.8422 40.4828 37.0758C41.6477 35.2656 42.2656 33.1656 42.2656 31C42.2656 24.7875 37.2125 19.7344 31 19.7344C24.7875 19.7344 19.7344 24.7875 19.7344 31C19.7344 33.1656 20.3523 35.2656 21.5117 37.0813C22.6437 38.8477 24.2461 40.2586 26.1328 41.1664C26.8109 41.4945 27.1008 42.3094 26.7727 42.993C26.4445 43.6711 25.6297 43.9609 24.9461 43.6328C22.6 42.5063 20.6148 40.7563 19.2094 38.5578C17.7656 36.3047 17 33.6906 17 31C17 27.2594 18.4547 23.743 21.1016 21.1016C23.743 18.4547 27.2594 17 31 17C34.7406 17 38.257 18.4547 40.8984 21.1016C43.5453 23.7484 45 27.2594 45 31C45 33.6906 44.2344 36.3047 42.7852 38.5578C41.3742 40.7508 39.3891 42.5063 37.0484 43.6328C36.3648 43.9555 35.55 43.6711 35.2219 42.9875Z" fill="#2BC155"></path><path d="M36.3211 31.7274C36.5891 31.9953 36.7203 32.3453 36.7203 32.6953C36.7203 33.0453 36.5891 33.3953 36.3211 33.6633L32.8812 37.1031C32.3781 37.6063 31.7109 37.8797 31.0055 37.8797C30.3 37.8797 29.6273 37.6008 29.1297 37.1031L25.6898 33.6633C25.1539 33.1274 25.1539 32.2633 25.6898 31.7274C26.2258 31.1914 27.0898 31.1914 27.6258 31.7274L29.6437 33.7453L29.6437 25.9742C29.6437 25.2196 30.2562 24.6071 31.0109 24.6071C31.7656 24.6071 32.3781 25.2196 32.3781 25.9742L32.3781 33.7508L34.3961 31.7328C34.9211 31.1969 35.7852 31.1969 36.3211 31.7274Z" fill="#2BC155"></path>
															</g>
														</svg>
													</td>
													<td>
														<h6 class="fs-16 font-w600 mb-0"><a href="javascript:void(0);" class="text-black">Paipal</a></h6>
														<span class="fs-14">Transfer</span>
													</td>
													<td>
														<h6 class="fs-16 text-black font-w600 mb-0">June 4, 2020</h6>
														<span class="fs-14">05:34:45 AM</span>
													</td>
													<td><span class="fs-16 text-black font-w600">+$5,553</span></td>
													<td><span class="text-success fs-16 font-w500 text-end d-block">Completed</span></td>
												</tr>
												<tr>
													<td>
														<svg class="bgl-danger tr-icon" width="63" height="63" viewBox="0 0 63 63" fill="none" xmlns="http://www.w3.org/2000/svg">
															<g><path d="M35.2219 19.0125C34.8937 19.6906 35.1836 20.5109 35.8617 20.8391C37.7484 21.7469 39.3453 23.1578 40.4828 24.9242C41.6476 26.7344 42.2656 28.8344 42.2656 31C42.2656 37.2125 37.2125 42.2656 31 42.2656C24.7875 42.2656 19.7344 37.2125 19.7344 31C19.7344 28.8344 20.3523 26.7344 21.5117 24.9187C22.6437 23.1523 24.2461 21.7414 26.1328 20.8336C26.8109 20.5055 27.1008 19.6906 26.7726 19.007C26.4445 18.3289 25.6297 18.0391 24.9461 18.3672C22.6 19.4937 20.6148 21.2437 19.2094 23.4422C17.7656 25.6953 17 28.3094 17 31C17 34.7406 18.4547 38.257 21.1015 40.8984C23.743 43.5453 27.2594 45 31 45C34.7406 45 38.257 43.5453 40.8984 40.8984C43.5453 38.2516 45 34.7406 45 31C45 28.3094 44.2344 25.6953 42.7851 23.4422C41.3742 21.2492 39.389 19.4937 37.0484 18.3672C36.3648 18.0445 35.55 18.3289 35.2219 19.0125Z" fill="#FF2E2E"></path><path d="M36.3211 30.2726C36.589 30.0047 36.7203 29.6547 36.7203 29.3047C36.7203 28.9547 36.589 28.6047 36.3211 28.3367L32.8812 24.8969C32.3781 24.3937 31.7109 24.1203 31.0055 24.1203C30.3 24.1203 29.6273 24.3992 29.1297 24.8969L25.6898 28.3367C25.1539 28.8726 25.1539 29.7367 25.6898 30.2726C26.2258 30.8086 27.0898 30.8086 27.6258 30.2726L29.6437 28.2547L29.6437 36.0258C29.6437 36.7804 30.2562 37.3929 31.0109 37.3929C31.7656 37.3929 32.3781 36.7804 32.3781 36.0258L32.3781 28.2492L34.3961 30.2672C34.9211 30.8031 35.7851 30.8031 36.3211 30.2726Z" fill="#FF2E2E"></path></g>
														</svg>
													</td>
													<td>
														<h6 class="fs-16 font-w600 mb-0"><a href="javascript:void(0);" class="text-black">Hawkins Jr.</a></h6>
														<span class="fs-14">Cashback</span>
													</td>
													<td>
														<h6 class="fs-16 text-black font-w600 mb-0">June 4, 2020</h6>
														<span class="fs-14">05:34:45 AM</span>
													</td>
													<td><span class="fs-16 text-black font-w600">+$5,553</span></td>
													<td><span class="text-danger fs-16 font-w500 text-end d-block">Canceled</span></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-6 col-xxl-12">
						<div class="row">
							<div class="col-xl-12">
								<div class="card coin-card">
									<div class="card-body d-sm-flex d-block align-items-center">
										<span class="coin-icon">
											<svg width="38" height="41" viewBox="0 0 38 41" fill="none" xmlns="http://www.w3.org/2000/svg">
												<g><path d="M14.0413 32.5832C15.7416 32.5934 17.4269 32.2659 18.9997 31.6199C20.5708 32.2714 22.2572 32.5991 23.958 32.5832C29.1218 32.5832 33.1663 29.8278 33.1663 26.3088V20.441C33.1663 16.922 29.1218 14.1666 23.958 14.1666C23.7186 14.1666 23.4834 14.1779 23.2497 14.1906V7.55498C23.2497 4.10823 19.2051 1.41656 14.0413 1.41656C8.87759 1.41656 4.83301 4.10823 4.83301 7.55498V26.4448C4.83301 29.8916 8.87759 32.5832 14.0413 32.5832ZM30.333 26.3088C30.333 27.9366 27.715 29.7499 23.958 29.7499C20.201 29.7499 17.583 27.9366 17.583 26.3088V24.9984C19.5015 26.1652 21.7131 26.7604 23.958 26.714C26.203 26.7604 28.4145 26.1652 30.333 24.9984V26.3088ZM23.958 16.9999C27.715 16.9999 30.333 18.8132 30.333 20.441C30.333 22.0687 27.715 23.8807 23.958 23.8807C20.201 23.8807 17.583 22.0673 17.583 20.441C17.583 18.8147 20.201 16.9999 23.958 16.9999ZM14.0413 4.2499C17.7983 4.2499 20.4163 5.9924 20.4163 7.55498C20.4163 9.11757 17.7983 10.8615 14.0413 10.8615C10.2843 10.8615 7.66634 9.11898 7.66634 7.55498C7.66634 5.99098 10.2843 4.2499 14.0413 4.2499ZM7.66634 12.0161C9.59282 13.1601 11.8012 13.7417 14.0413 13.6948C16.2814 13.7417 18.4899 13.1601 20.4163 12.0161V14.6341C18.8724 15.0232 17.4565 15.8078 16.308 16.9107C15.5631 17.0718 14.8034 17.1545 14.0413 17.1572C10.2843 17.1572 7.66634 15.4146 7.66634 13.8521V12.0161ZM7.66634 18.3132C9.59323 19.4561 11.8015 20.0371 14.0413 19.9905C14.2935 19.9905 14.5372 19.9593 14.7851 19.9466C14.764 20.1106 14.7522 20.2756 14.7497 20.441V23.3947C14.5117 23.4089 14.2822 23.4542 14.0413 23.4542C10.2843 23.4542 7.66634 21.7117 7.66634 20.1477V18.3132ZM7.66634 24.6088C9.59282 25.7529 11.8012 26.3344 14.0413 26.2876C14.2793 26.2876 14.5131 26.2692 14.7497 26.2578V26.3088C14.7699 27.5148 15.2334 28.6711 16.0516 29.5572C15.3887 29.6824 14.7159 29.7469 14.0413 29.7499C10.2843 29.7499 7.66634 28.0074 7.66634 26.4448V24.6088Z" fill="#fff"></path></g>
											</svg>
										</span>
										<div>
											<h3 class="text-white">Get managed by Dompet’s Virtual Assistant</h3>
											<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim </p>
											<a class="text-white" href="javascript:void(0);">Learn more >></a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card progress-card">
									<div class="card-body d-flex">
										<div class="me-auto">
											<h4 class="card-title">Total Transactions</h4>
											<div class="d-flex align-items-center">
												<h2 class="fs-38 mb-0">98k</h2>
												<div class="text-success transaction-caret">
													<i class="fas fa-sort-up"></i>
													<p class="mb-0">+0.5%</p>
												</div>
											</div>
										</div>		
										<div class="progress progress-vertical-bottom" style="min-height:110px;min-width:10px;">
											<div class="progress-bar bg-primary" style="width:10px; height:40%;" role="progressbar">
												<span class="sr-only">40% Complete</span>
											</div>
										</div>
										<div class="progress progress-vertical-bottom" style="min-height:110px;min-width:10px;">
											<div class="progress-bar bg-primary" style="width:10px; height:55%;" role="progressbar">
												<span class="sr-only">55% Complete</span>
											</div>
										</div>
										<div class="progress progress-vertical-bottom" style="min-height:110px;min-width:10px;">
											<div class="progress-bar bg-primary" style="width:10px; height:80%;" role="progressbar">
												<span class="sr-only">80% Complete</span>
											</div>
										</div>
										<div class="progress progress-vertical-bottom" style="min-height:110px;min-width:10px;">
											<div class="progress-bar bg-primary" style="width:10px; height:50%;" role="progressbar">
												<span class="sr-only">50% Complete</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title">Invoice Remaining</h4>
										<div class="d-flex align-items-center">
											<div class="me-auto">
												<div class="progress mt-4" style="height:10px;">
													<div class="progress-bar bg-primary progress-animated" style="width: 45%; height:10px;" role="progressbar">
														<span class="sr-only">60% Complete</span>
													</div>
												</div>
												<p class="fs-16 mb-0 mt-2"><span class="text-danger">-0,8% </span>from last month</p>
											</div>
											<h2 class="fs-38">854</h2>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title mt-2">Invoice Sent</h4>
										<div class="d-flex align-items-center mt-3 mb-2">
											<h2 class="fs-38 mb-0 me-3">456</h2>
											<span class="badge badge-success badge-xl">+0.5%</span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title mt-2">Invoice Compeleted</h4>
										<div class="d-flex align-items-center mt-3 mb-2">
											<h2 class="fs-38 mb-0 me-3">1467</h2>
											<span class="badge badge-danger badge-xl">-6.4%</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div> -->
				</div>
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->
		
		
		
        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
		
            <div class="copyright">
                <p>© Designed &amp; by <a href="#" target="_blank">Re - Code</a> 2024</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->

		


	</div>

	<div class="modal fade" id="lateStudentsModal" tabindex="-1" aria-labelledby="lateStudentsModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="lateStudentsModalLabel">Siswa Terlambat</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="table-responsive">
						<table class="table table-bordered">
							<thead class="table-primary">
								<tr>
									<th>No</th>
									<th>Nama</th>
									<th>Kelas</th>
									<th>Jurusan</th>
									<th>Waktu Terlambat</th>
								</tr>
							</thead>
							<tbody id="lateStudentsTable">
								<!-- Data siswa akan dimasukkan di sini oleh JavaScript -->
							</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="prevPage">Previous</button>
					<button type="button" class="btn btn-primary" id="nextPage">Next</button>
				</div>
			</div>
		</div>
	</div>



        
        <!-- Modal for Bad Mood Students -->
		<div class="modal fade" id="badMoodStudentsModal" tabindex="-1" aria-labelledby="badMoodStudentsModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="badMoodStudentsModalLabel">Students with Bad Mood</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<ul id="badMoodStudentsList">
							<!-- List of students with bad mood will be populated here -->
						</ul>
					</div>
					<div class="modal-footer">
						<!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
						<button type="button" class="btn btn-primary" id="prevPage">Previous</button>
						<button type="button" class="btn btn-primary" id="nextPage">Next</button>
					</div>
				</div>
			</div>
		</div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="vendor/global/global.min.js"></script>
	<script src="vendor/chart.js/Chart.bundle.min.js"></script>
	<script src="vendor/jquery-nice-select/js/jquery.nice-select.min.js"></script>
	
	<!-- Apex Chart -->
	<script src="vendor/apexchart/apexchart.js"></script>
	<script src="vendor/nouislider/nouislider.min.js"></script>
	<script src="vendor/wnumb/wNumb.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

	
	<!-- Dashboard 1 -->
	<script src="js/dashboard/dashboard-1.js"></script>

    <script src="js/custom.min.js"></script>
	<script src="js/dlabnav-init.js"></script>
	<script src="js/demo.js"></script>
    <script src="js/styleSwitcher.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
		$(document).ready(function () {
			var currentPage = 1;
			var itemsPerPage = 10;

			function loadLateStudents(page) {
				$.ajax({
					url: "get_late_students.php",
					type: "GET",
					data: { page: page, itemsPerPage: itemsPerPage },
					success: function (response) {
						$("#lateStudentsTable").html(response);
					}
				});
			}

			// Load data pertama kali saat modal dibuka
			$('#lateStudentsModal').on('show.bs.modal', function () {
				loadLateStudents(currentPage);
			});

			// Tombol Next
			$("#nextPage").click(function () {
				currentPage++;
				loadLateStudents(currentPage);
			});

			// Tombol Previous
			$("#prevPage").click(function () {
				if (currentPage > 1) {
					currentPage--;
					loadLateStudents(currentPage);
				}
			});
		});
	</script>


        <script>
            $(document).ready(function () {
                // When the modal is shown, populate the list of students with bad mood
                $('#badMoodStudentsModal').on('show.bs.modal', function () {
                    // Get the list of students with bad mood from PHP
                    var badMoodStudents = <?php echo json_encode($badMoodStudents); ?>;
                    var listHtml = '';
        
                    if (badMoodStudents.length > 0) {
                        badMoodStudents.forEach(function(student, index) {
                            listHtml += '<li>' + (index + 1) + '. ' + student.Nama + ' (' + student.Kelas + ' - ' + student.Jurusan + ') - ' + student.Catatan + '</li>';
                        });
                    } else {
                        listHtml = '<li>No students with bad mood today.</li>';
                    }
        
                    // Update the modal content
                    $('#badMoodStudentsList').html(listHtml);
                });
            });
        </script>
        <script>
            $(document).ready(function () {
                function paginateList(listId, data) {
                    let currentPage = 0;
                    const pageSize = 10;
            
                    function renderPage() {
                        let start = currentPage * pageSize;
                        let end = start + pageSize;
                        let listHtml = '';
            
                        let pageData = data.slice(start, end);
                        pageData.forEach((student, index) => {
                            listHtml += `<li>${start + index + 1}. ${student.Nama} (${student.Kelas} - ${student.Jurusan}) - ${new Date(student.Waktu).toLocaleString()}</li>`;
                        });
            
                        if (pageData.length === 0) {
                            listHtml = '<li>No data available.</li>';
                        }
            
                        $(listId).html(listHtml);
                        updateButtons();
                    }
            
                    function updateButtons() {
                        $("#prevPage").prop("disabled", currentPage === 0);
                        $("#nextPage").prop("disabled", (currentPage + 1) * pageSize >= data.length);
                    }
            
                    $("#prevPage").click(function () {
                        if (currentPage > 0) {
                            currentPage--;
                            renderPage();
                        }
                    });
            
                    $("#nextPage").click(function () {
                        if ((currentPage + 1) * pageSize < data.length) {
                            currentPage++;
                            renderPage();
                        }
                    });
            
                    renderPage();
                }
            
                // Modal untuk siswa terlambat
                $('#lateStudentsModal').on('show.bs.modal', function () {
                    var lateStudents = <?php echo json_encode($lateStudents); ?>;
                    paginateList('#lateStudentsList', lateStudents);
                });
            
                // Modal untuk bad mood students
                $('#badMoodStudentsModal').on('show.bs.modal', function () {
                    var badMoodStudents = <?php echo json_encode($badMoodStudents); ?>;
                    paginateList('#badMoodStudentsList', badMoodStudents);
                });
            });
        </script>
	
</body>

</html>