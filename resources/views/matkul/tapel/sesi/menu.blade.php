<div class="f-col-1">
	<h4><i class="fa fa-align-justify"></i> Menu</h4>
	<div class="f-box">
		<div class="f-box-body">
			<?php
				$menus = [
				'akt' => [
				'label' => 'Aktivitas Pembelajaran',
				'url' => route('matkul.tapel.sesi.index', $id),
				'icon' => 'list'
				],
				'dis' => [
				'label' => 'Diskusi',
				'url' => route('matkul.tapel.diskusi.index', $id),
				'icon' => 'comments'
				],
				'ang' => [
				'label' => 'Anggota',
				'url' => route('matkul.tapel.anggota.index', $id),
				'icon' => 'users'
				],
				'lap' => [
				'label' => 'Laporan',
				'url' => route('matkul.tapel.laporan.index', $id),
				'icon' => 'area-chart'
				]
				];
			?>
			@foreach($menus as $k => $v)
			@if($active == $k)
			<a href="#" onclick="return false;" class="btn btn-block btn-social btn-success btn-flat">
			@else
			<a href="{{ $v['url'] }}" class="btn btn-block btn-social-inactive">
			@endif
				<i class="fa fa-{{ $v['icon'] }}"></i> {{ $v['label'] }}
			</a>
			@endforeach
				
			</div>
		</div>
	</div>	