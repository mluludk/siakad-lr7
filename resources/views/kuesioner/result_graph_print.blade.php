<!DOCTYPE html>
<html>
	<head>
		<title>{{ $title }}</title>
		<style>
			/* 1cm == 37.8px */
			body{
			padding: 10px;
			width: 35.56cm;
			height: 21cm;
			font-family: tahoma;
			}
			hr{
			margin: 1px;
			}
			h1, h2, h3, h4, h5{
			margin: 0px;
			}
			header{
			text-align: center;
			}
			header div{
			font-size: 12px;
			}
			.sub-header{
			margin: 30px;
			text-align:center;
			}
			img{
			max-width: 100%;
			}
			table{
			width: 100%;
			border-collapse: collapse;
			margin: 10px 0;
			}
			td{
			padding: 3px 5px;
			}
		</style>
		<link rel="stylesheet" href="{{ url('css/morris.css') }}">
		<script src="{{ asset('/js/jquery.min.js') }}"></script>
		<script src="{{ asset('/js/ChartJS.bundle.min.js') }}"></script>
		<script>
			$(function () {
				var ctx = $("#myChart");
				var data = {
					labels: [
					@foreach($result as $id => $rs)
					@if($rs['kode'] != '')
					@if($dosen_id != null)
					"{!! $rs['matakuliah'] !!} ({{ $rs['prodi'] . ' ' . $rs['program']}})",
					@else
					"{!! $rs['matakuliah'] !!} ({{ $rs['kode'] }})",
					@endif
					@else
					"{!! $rs['matakuliah'] !!}",
					@endif
					@endforeach
					],
					datasets: [
					{
						label: "Nilai Akhir",
						backgroundColor: '#3c8dbc',
						borderWidth: 1,
						data: [
						@foreach($result as $id => $rs)
						{{ round($rs['NA'], 2) }},
						@endforeach
						],
					}
					]
				};
				var myBarChart = new Chart(ctx, {
					type: 'bar',
					data: data,
					options: {
						tooltips: false,
						hover: {
							animationDuration: 0
						},
						animation: {
							duration: 0,
							onComplete: function () { 
								var ctx = this.chart.ctx;
								ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, 'normal', Chart.defaults.global.defaultFontFamily);
								ctx.fillStyle = this.chart.config.options.defaultFontColor;
								ctx.textAlign = 'center';
								ctx.textBaseline = 'bottom';
								this.data.datasets.forEach(function (dataset) {
									for (var i = 0; i < dataset.data.length; i++) {
										var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model;
										ctx.fillText(dataset.data[i], model.x, model.y - 5);
									}
								});
							}, 
						}, 
						title: {
							display: true,
							text: "{{ $title }}"
						},
						legend: {
							display: false
						},
						scales: {
							xAxes: [{
								ticks: {
									autoSkip: false
								}
							}]
						}
					}
				});
			});
		</script>
	</head>
	<body>
		@if(!count($result))
		<p class="text-muted">Belum ada data</p>
		@else
		<canvas id="myChart" style="height: 200px;/*  display: none; */"></canvas>
		<!--img id="myPng" /-->
		@endif
		<script>
			window.print();
		</script>
	</body>
</html>