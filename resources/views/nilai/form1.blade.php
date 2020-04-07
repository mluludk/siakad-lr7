@extends('app')

@section('title')
Form Nilai
@endsection

@section('header')
<section class="content-header">
	<h1>
		Form Nilai
		<small>Cetak</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/matkul/tapel') }}">Kelas Kuliah</a></li>
		<li><a href="{{ route('matkul.tapel.nilai', $matkul_tapel_id) }}">Nilai Perkuliahan</a></li>
		<li class="active">Form Nilai</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Cetak Form Nilai</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-9">
				{!! Form::model(new Siakad\Nilai, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['matkul.tapel.nilai.cetak', $matkul_tapel_id]]) !!}
				<div class="form-group">
					{!! Form::label('komponen', 'Komponen:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-9">
						@foreach($jenis as $k => $v)
						<label class="checkbox-inline"><input type="checkbox" value="{{ $v }}" checked="checked" name="komponen[{{ $k }}]"> {{ $v }}</label>
						@endforeach
						<label class="checkbox-inline"><input type="checkbox" value="Akhir" checked="checked" name="komponen[1]"> Akhir</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-9">
						<button class="btn btn-primary btn-flat btn-success" type="submit" id="post"><i class="fa fa-print"></i> Cetak</button>
					</div>		
				</div>	
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection