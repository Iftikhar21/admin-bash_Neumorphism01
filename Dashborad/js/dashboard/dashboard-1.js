(function($) {
    /* "use strict" */
	
 var dlabChartlist = function(){
	
	var screenWidth = $(window).width();	
	
	var chartBar = function(){
		
		var options = {
			  series: [
				{
					name: 'Income',
					data: [50, 18, 70, 40],
					//radius: 12,	
				}, 
				{
				  name: 'Outcome',
				  data: [80, 40, 55, 20]
				}, 
				
			],
				chart: {
				type: 'bar',
				height: 200,
				
				toolbar: {
					show: false,
				},
				
			},
			plotOptions: {
			  bar: {
				horizontal: false,
				columnWidth: '57%',
				borderRadius:12
			  },
			  
			},
			states: {
			  hover: {
				filter: 'none',
			  }
			},
			colors:['#ffffff', '#fe7d65'],
			dataLabels: {
			  enabled: false,
			},
			markers: {
		shape: "circle",
		},
		
		
			legend: {
				position: 'top',
				horizontalAlign: 'right', 
				show: false,
				fontSize: '12px',
				labels: {
					colors: '#000000',
					
					},
				markers: {
				width: 18,
				height: 18,
				strokeWidth: 0,
				strokeColor: '#fff',
				fillColors: undefined,
				radius: 12,	
				}
			},
			stroke: {
			  show: true,
			  width: 4,
			  colors: ['transparent']
			},
			grid: {
				borderColor: '#eee',
			},
			xaxis: {
				
			  categories: ['Sun', 'Mon', 'Tue', 'Wed'],
			  labels: {
			   style: {
				  colors: '#3e4954',
				  fontSize: '13px',
				  fontFamily: 'poppins',
				  fontWeight: 400,
				  cssClass: 'apexcharts-xaxis-label',
				},
			  },
			  crosshairs: {
			  show: false,
			  }
			},
			yaxis: {
				labels: {
					offsetX:-16,
				   style: {
					  colors: '#3e4954',
					  fontSize: '13px',
					   fontFamily: 'poppins',
					  fontWeight: 400,
					  cssClass: 'apexcharts-xaxis-label',
				  },
			  },
			},
			fill: {
			  opacity: 1,
			  colors:['#68e365', '#ffa755'],
			},
			tooltip: {
			  y: {
				formatter: function (val) {
				  return "$ " + val + " thousands"
				}
			  }
			},
			responsive: [{
				breakpoint: 1600,
				options: {
					chart: {
						height: 400,
					}
				},
			},
			{
				breakpoint: 575,
				options: {
					chart: {
						height: 250,
					}
				},
			}]
			};

			var chartBar1 = new ApexCharts(document.querySelector("#chartBar"), options);
			chartBar1.render();
	}	
	
	var chartBar2 = function(startDate = null, endDate = null) {
		// Jika startDate dan endDate null, gunakan minggu ini (Senin - Jumat)
		var today = new Date();
		var currentDay = today.getDay();
		startDate = startDate || new Date(today.setDate(today.getDate() - (currentDay - 1))).toISOString().split('T')[0]; // Senin minggu ini
		endDate = endDate || new Date(today.setDate(today.getDate() + (5 - currentDay))).toISOString().split('T')[0]; // Jumat minggu ini
	
		fetch(`data.php?start_date=${startDate}&end_date=${endDate}`)
			.then(response => response.json())
			.then(data => {
				console.log("Data dari server:", data); // Debugging
	
				// Ambil data dari field 'barChart'
				var barData = data.barChart;
	
				var days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
				var hadirData = [];
				var terlambatData = [];
	
				// Ambil data sesuai dengan hari
				days.forEach(day => {
					hadirData.push(barData[day]?.Hadir || 0);
					terlambatData.push(barData[day]?.Terlambat || 0);
				});
	
				var options = {
					series: [
						{
							name: 'Hadir',
							data: hadirData,
						}, 
						{
							name: 'Terlambat',
							data: terlambatData
						}, 
					],
					chart: {
						type: 'bar',
						height: 400,
						toolbar: { show: false }
					},
					plotOptions: {
						bar: {
							horizontal: false,
							columnWidth: '70%',
							borderRadius: 10
							// distributed: true
						},
					},
					colors:['#80ec67', '#fe7d65'],
					xaxis: {
						categories: days,  
					},
					yaxis: {
						labels: {
							offsetX: -16,
						},
					},
					legend: {
						show: false // Menyembunyikan legend
					},
					tooltip: {
						y: {
							formatter: function (val) {
								return "$ " + val + " thousands"
							}
						}
					},
					responsive: [{
						breakpoint: 575,
						options: {
							chart: {
								height: 250,
							}
						},
					}]
				};
				
				var chartBar1 = new ApexCharts(document.querySelector("#chartBar2"), options);
				chartBar1.render();
				
	
				document.querySelector("#chartBar2").innerHTML = ""; // Reset sebelum render ulang
				var chartBar1 = new ApexCharts(document.querySelector("#chartBar2"), options);
				chartBar1.render();
			})
			.catch(error => console.error('Error:', error));
	};
	
	// Jalankan chart otomatis saat halaman dibuka
	chartBar2();	
	
	document.addEventListener("DOMContentLoaded", function() {
		// Fungsi untuk update chart ketika tombol diklik
		document.getElementById("updateButton").addEventListener("click", function() {
			var startDate = document.getElementById("startDate").value;
			var endDate = document.getElementById("endDate").value;
	
			if (!startDate || !endDate) {
				alert("Silakan pilih Start Date dan End Date!");
				return;
			}
	
			chartBar2(startDate, endDate); // Perbarui chart berdasarkan tanggal yang dipilih
		});
	
		// Set default startDate dan endDate ke minggu ini (Senin - Jumat)
		var today = new Date();
		var dayOfWeek = today.getDay();
		var monday = new Date(today.setDate(today.getDate() - (dayOfWeek - 1)));
		var friday = new Date(today.setDate(today.getDate() + (5 - dayOfWeek)));
	
		var formatDate = date => date.toISOString().split('T')[0];
	
		document.getElementById("startDate").value = formatDate(monday);
		document.getElementById("endDate").value = formatDate(friday);
	
		// Jalankan chart pertama kali dengan default minggu ini
		chartBar2(formatDate(monday), formatDate(friday));
	});
	

	var ctx = document.getElementById("polarChart").getContext('2d');
	fetch("data.php")
	  .then(response => response.json())
	  .then(data => {
		console.log("Data dari PHP:", data); // Debugging
	
		var myChart = new Chart(ctx, {
		  type: 'pie',
		  data: {
			labels: ["Belum Absen", "Hadir", "Terlambat"],
			datasets: [{
			  backgroundColor: ["#496ecc", "#68e365", "#ffa755"],
			  borderColor: "#fff",
			  borderWidth: 2, 
			  data: [data.belumAbsen, data.hadir, data.terlambat]
			}]
		  },
		  options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
			  legend: {
				display: false // Menyembunyikan legend jika tidak diperlukan
			  },
			  tooltip: {
				enabled: true // Menampilkan tooltip untuk setiap bagian pie
			  }
			},
			animation: {
			  animateRotate: true,  // Animasi rotasi
			  animateScale: true    // Animasi skala
			}
		  }
		});
	  })
	  .catch(error => console.error("Gagal mengambil data:", error));
	

	
	var handleCard = function(){
		
		// Vars
		var reloadButton  = document.querySelector( '.change-btn' );
		var reloadIcon     = document.querySelector( '.reload' );
		var reloadEnabled = true;
		var rotation      = 0;
		// Events
		reloadButton.addEventListener('click', function() { reloadClick() });
		// Functions
		function reloadClick() {
		  reloadEnabled = false;
		  rotation += 360;
		  // Eh, this works.
		  reloadIcon.style.webkitTransform = 'translateZ(0px) rotateZ( ' + rotation + 'deg )';
		  reloadIcon.style.MozTransform  = 'translateZ(0px) rotateZ( ' + rotation + 'deg )';
		  reloadIcon.style.transform  = 'translateZ(0px) rotateZ( ' + rotation + 'deg )';
		}
		// Show button.
		setTimeout(function() {
		  reloadButton.classList.add('active');
		}, 1);
		
		//Number formatting
		var sliderFormat = document.getElementById('slider-format');
		noUiSlider.create(sliderFormat, {
			start: [20000],
			step: 1000,
			connect: [true, false],
			range: {
				'min': [20000],
				'max': [80000]
			},
			ariaFormat: wNumb({
				decimals: 3
			}),
			format: wNumb({
				decimals: 3,
				thousand: '.',
				//suffix: ' (US $)'
			})
		});

		var inputFormat = document.getElementById('input-format');
		sliderFormat.noUiSlider.on('update', function (values, handle) {
			inputFormat.value = values[handle];
		});

		inputFormat.addEventListener('change', function () {
			sliderFormat.noUiSlider.set(this.value);
		});
		//Number formatting ^
	}
 
	/* Function ============ */
		return {
			init:function(){
			},
			
			
			load:function(){
				chartBar();
				chartBar2();
				polarChart();
				handleCard();
			},
			
			resize:function(){
			}
		}
	
	}();

	
		
	jQuery(window).on('load',function(){
		setTimeout(function(){
			dlabChartlist.load();
		}, 1000); 
		
	});
})(jQuery);

function showRealTimeClock() {
	const now = new Date();
	const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', timeZone: 'Asia/Jakarta' };
	
	const formattedDate = now.toLocaleDateString('id-ID', options);
	const formattedTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

	document.getElementById("clock").innerHTML = `<strong>${formattedTime}</strong> | ${formattedDate}`;
}

setInterval(showRealTimeClock, 1000);
showRealTimeClock();