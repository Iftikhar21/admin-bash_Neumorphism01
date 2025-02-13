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
  $dateToday = date('Y-m-d');
  // Mendapatkan data dari database
  $sql = "SELECT * FROM Absensi WHERE DATE(Waktu) = '$dateToday'";
  $result = $conn->query($sql);

  // Menyimpan data dalam array untuk digunakan di JavaScript
  $dataAbsensi = [];
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $dataAbsensi[] = $row;
      }
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
	<title>Absensi Re-Code</title>
	
	<link href="vendor/jquery-nice-select/css/nice-select.css" rel="stylesheet">
	<link rel="stylesheet" href="vendor/nouislider/nouislider.min.css">
	<link rel="stylesheet" type="text/css" href="your_website_domain/css_root/flaticon.css">
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

	<!-- Style css -->
    <link href="css/style.css" rel="stylesheet">

    <style>
        .status-badge {
            padding-top: 2px;
            padding-bottom: 2px;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            width: 80px;
            text-align: center;
        }

        /* Warna berbeda untuk tiap status */
        .status-badge.hadir {
            background-color: #28a745; /* Hijau */
            color: white;
        }

        .status-badge.sakit {
            background-color: #ffc107; /* Kuning */
            color: black;
        }

        .status-badge.izin {
            background-color: #17a2b8; /* Biru */
            color: white;
        }

        .status-badge.terlambat {
            background-color: #dc3545; /* Merah */
            color: white;
        }
    </style>
	
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
                                Absensi 
                            </div>
                        </div>
						<div class="real-time-clock">
							<h3 id="clock" class="m-0"></h3>
						</div>
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
                    <li><a href="index.php" aria-expanded="false">
							<i class="flaticon-025-dashboard"></i>
							<span class="nav-text">Dashboard</span>
						</a>
                    </li>
                    <li><a href="javascript:void()" aria-expanded="false">
						<i class='bx bxs-bar-chart-alt-2'></i>
							<span class="nav-text">Absensi</span>
						</a>
                    </li>
                    <li><a href="javascript:void()" aria-expanded="false">
							<i class="flaticon-051-info"></i>
							<span class="nav-text">About</span>
						</a>
                    </li>
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
                <div class="row">
                    <div class="col-xl-9 col-xxl-12">
						<div class="card">
							<div class="card-body">
								<div class="row align-items-center">
                                    <div class="col-xl-12">
										<div class="row  mt-xl-0 mt-4">
											<div class="col-md-12">
                                                <div class="container mt-3">
                                                    <!-- Filter Section -->
                                                    <div class="card p-3 mb-3">
                                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                                            <div>
                                                                <label for="rowsPerPage">Tampilkan</label>
                                                                <select id="rowsPerPage" class="form-select" style="width: auto; display: inline-block;">
                                                                    <option value="25">25</option>
                                                                    <option value="40">40</option>
                                                                    <option value="75">75</option>
                                                                    <option value="100">100</option>
                                                                </select>
                                                                data per halaman
                                                            </div>
                                                            <div>
                                                                <button id="prevPage" class="btn btn-secondary btn-sm">Sebelumnya</button>
                                                                <span id="pageInfo"></span>
                                                                <button id="nextPage" class="btn btn-secondary btn-sm">Berikutnya</button>
                                                            </div>
                                                        </div>
                                                        <div class="row g-2">
                                                            <div class="col-md-3 col-6">
                                                                <input type="text" class="form-control" placeholder="Cari Nama" id="filterNama">
                                                            </div>
                                                            <div class="col-md-3 col-6">
                                                                <input type="text" class="form-control" placeholder="Cari NISN" id="filterNISN">
                                                            </div>
                                                            <div class="col-md-3 col-6">
                                                                <input type="text" class="form-control" placeholder="Cari Android ID" id="filterAndroidID">
                                                            </div>
                                                            <div class="col-md-3 col-6">
                                                                <select id="filterKelas" class="form-select">
                                                                    <option value="">Semua Kelas</option>
                                                                    <option value="X">X</option>
                                                                    <option value="XI">XI</option>
                                                                    <option value="XII">XII</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3 col-6">
                                                                <select id="filterJurusan" class="form-select">
                                                                    <option value="">Semua Jurusan</option>
                                                                    <option value="RPL 1">RPL 1</option>
                                                                    <option value="RPL 2">RPL 2</option>
                                                                    <option value="TBG 2">TBG 2</option>
                                                                    <option value="^TBG 3$">TBG 3</option>
                                                                    <option value="^PH 1$">PH 1</option>
                                                                    <option value="^PH 2$">PH 2</option>
                                                                    <option value="^PH 3$">PH 3</option>
                                                                    <option value="^TBS 1$">TBS 1</option>
                                                                    <option value="^TBS 2$">TBS 2</option>
                                                                    <option value="^TBS 3$">TBS 3</option>
                                                                    <option value="^ULW$">ULW</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3 col-6">
                                                                <input id="filterTanggal" type="date" class="form-control">
                                                            </div>
                                                            <div class="col-md-3 col-6">
                                                                <select id="filterKehadiran" class="form-select">
                                                                    <option value="">Semua Status</option>
                                                                    <option value="Hadir">Hadir</option>
                                                                    <option value="Sakit">Sakit</option>
                                                                    <option value="Izin">Izin</option>
                                                                    <option value="Terlambat">Terlambat</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3 col-6">
                                                                <input id="filterCatatan" type="text" class="form-control" placeholder="Cari Catatan">
                                                            </div>
                                                            <div class="col-md-3 col-6">
                                                                <select id="filterMood" class="form-select">
                                                                    <option value="">Semua Mood</option>
                                                                    <option value="Baik">Baik</option>
                                                                    <option value="Biasa Aja">Biasa Aja</option>
                                                                    <option value="Buruk">Buruk</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Table Section -->
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered text-center" id="absensiTable">
                                                            <thead class="table-primary">
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Nama</th>
                                                                    <th>NISN</th>
                                                                    <th class="d-none d-md-table-cell">Android ID</th>
                                                                    <th>Kelas</th>
                                                                    <th>Jurusan</th>
                                                                    <th>Tanggal</th>
                                                                    <th>Status</th>
                                                                    <th>Catatan</th>
                                                                    <th>Mood</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php $i = 1; ?>
                                                            <?php if (!empty($dataAbsensi)): ?>
                                                                <?php foreach ($dataAbsensi as $row): ?>
                                                                    <tr>
                                                                        <td><?= $i++ ?></td>
                                                                        <td><?= htmlspecialchars($row['Nama']); ?></td>
                                                                        <td><?= htmlspecialchars($row['NISN']); ?></td>
                                                                        <td class="d-none d-md-table-cell"><?= htmlspecialchars($row['AndroidID']); ?></td>
                                                                        <td><?= htmlspecialchars($row['Kelas']); ?></td>
                                                                        <td><?= htmlspecialchars($row['Jurusan']); ?></td>
                                                                        <td><?= htmlspecialchars($row['Waktu']); ?></td>
                                                                        <td>
                                                                            <span class="status-badge <?= strtolower(htmlspecialchars($row['Kehadiran'])); ?>">
                                                                                <?= htmlspecialchars($row['Kehadiran']); ?>
                                                                            </span>
                                                                        </td>
                                                                        <td><?= htmlspecialchars($row['Catatan']); ?></td>
                                                                        <td><?= htmlspecialchars($row['Mood']); ?></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            <?php else: ?>
                                                                <tr>
                                                                    <td colspan="10">Tidak ada data absensi.</td>
                                                                </tr>
                                                            <?php endif; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
											</div>
										</div>
									</div>
                                </div>
                            </div>
                        </div>
                    </div>
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
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
	
	<!-- Dashboard 1 -->
	<script src="js/dashboard/dashboard-1.js"></script>

    <script src="js/custom.min.js"></script>
	<script src="js/dlabnav-init.js"></script>
	<script src="js/demo.js"></script>
    <script src="js/styleSwitcher.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const table = document.getElementById("absensiTable");
            const tbody = table.querySelector("tbody");
            const filters = {
                nama: document.getElementById("filterNama"),
                nisn: document.getElementById("filterNISN"),
                androidID: document.getElementById("filterAndroidID"),
                kelas: document.getElementById("filterKelas"),
                jurusan: document.getElementById("filterJurusan"),
                tanggal: document.getElementById("filterTanggal"),
                status: document.getElementById("filterKehadiran"),
                catatan: document.getElementById("filterCatatan"),
                mood: document.getElementById("filterMood")
            };

            function filterTable() {
                const rows = tbody.querySelectorAll("tr");
                rows.forEach(row => {
                    const cells = row.getElementsByTagName("td");
                    let show = true;

                    if (filters.nama.value && !cells[1].textContent.toLowerCase().includes(filters.nama.value.toLowerCase())) {
                        show = false;
                    }
                    if (filters.nisn.value && !cells[2].textContent.includes(filters.nisn.value)) {
                        show = false;
                    }
                    if (filters.androidID.value && !cells[3].textContent.includes(filters.androidID.value)) {
                        show = false;
                    }
                    if (filters.kelas.value && filters.kelas.value !== "" && cells[4].textContent !== filters.kelas.value) {
                        show = false;
                    }
                    if (filters.jurusan.value && filters.jurusan.value !== "" && cells[5].textContent !== filters.jurusan.value) {
                        show = false;
                    }
                    if (filters.tanggal.value && cells[6].textContent !== filters.tanggal.value) {
                        show = false;
                    }
                    if (filters.status.value && filters.status.value !== "" && cells[7].textContent !== filters.status.value) {
                        show = false;
                    }
                    if (filters.catatan.value && !cells[8].textContent.toLowerCase().includes(filters.catatan.value.toLowerCase())) {
                        show = false;
                    }
                    if (filters.mood.value && filters.mood.value !== "" && cells[9].textContent !== filters.mood.value) {
                        show = false;
                    }

                    row.style.display = show ? "" : "none";
                });
            }

            Object.values(filters).forEach(filter => {
                filter.addEventListener("input", filterTable);
            });

            // Responsive adjustments for table
            window.addEventListener("resize", function () {
                if (window.innerWidth < 768) {
                    document.querySelectorAll(".d-none.d-md-table-cell").forEach(el => el.classList.add("d-block"));
                } else {
                    document.querySelectorAll(".d-none.d-md-table-cell").forEach(el => el.classList.remove("d-block"));
                }
            });
        });

    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const table = document.getElementById("absensiTable").getElementsByTagName("tbody")[0];
            const rows = Array.from(table.getElementsByTagName("tr"));
            const rowsPerPageSelect = document.getElementById("rowsPerPage");
            const prevButton = document.getElementById("prevPage");
            const nextButton = document.getElementById("nextPage");
            const pageInfo = document.getElementById("pageInfo");

            let currentPage = 1;
            let rowsPerPage = parseInt(rowsPerPageSelect.value);

            function showPage(page) {
                const start = (page - 1) * rowsPerPage;
                const end = start + rowsPerPage;

                rows.forEach((row, index) => {
                    row.style.display = (index >= start && index < end) ? "" : "none";
                });

                pageInfo.innerText = `Halaman ${page} dari ${Math.ceil(rows.length / rowsPerPage)}`;
                prevButton.disabled = page === 1;
                nextButton.disabled = page === Math.ceil(rows.length / rowsPerPage);
            }

            rowsPerPageSelect.addEventListener("change", function () {
                rowsPerPage = parseInt(this.value);
                currentPage = 1;
                showPage(currentPage);
            });

            prevButton.addEventListener("click", function () {
                if (currentPage > 1) {
                    currentPage--;
                    showPage(currentPage);
                }
            });

            nextButton.addEventListener("click", function () {
                if (currentPage < Math.ceil(rows.length / rowsPerPage)) {
                    currentPage++;
                    showPage(currentPage);
                }
            });

            showPage(currentPage);
        });
        function showRealTimeClock() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', timeZone: 'Asia/Jakarta' };
            
            const formattedDate = now.toLocaleDateString('id-ID', options);
            const formattedTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

            document.getElementById("clock").innerHTML = `<strong>${formattedTime}</strong> | ${formattedDate}`;
        }

        setInterval(showRealTimeClock, 1000);
        showRealTimeClock();
</script>

	
</body>

</html>