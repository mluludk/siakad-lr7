<?php 
	$pil = ['Isian', 'Pilihan', 'Upload']; 
	$file = ['gbr' => 'Gambar (JPG, JPEG, PNG)', 'dok' => 'Dokumen (DOCX,DOC,PPTX,PPT,XLSX,XLS,PDF)', 'vid' => 'Video (MP4,OGG)']; 
	$sel = 0;
?>

<div class="box box-default">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-paste"></i> Tugas</h3>
	</div>
	<div class="box-body">
		<table width="100%" id="tbl-soal">
			<tbody>
				@if(isset($kegiatan -> isi['tugas']))
				<?php 
					$c = 1; 
				?>
				@foreach($kegiatan -> isi['tugas'] as $isi)
				<?php 
					$rand = str_random(6);
					$sel = $isi['jenis'];
				?>
				
				<tr class="tr-soal" id="soal-{{ $rand }}">
					<td rowspan="3" valign="top">{{ $c }}.</td>
					<td width="500px">
						<input type="text" name="soal[{{ $rand }}]" class="form-control" placeholder="Pertanyaan" value="{{ $isi['soal'] }}"/>
					</td>
					<td>
						<select name="jenis_soal[{{ $rand }}]" class="form-control jenis_soal">
							@foreach($pil as $k => $p)
							<option value="{{ $k }}"  @if($k == $sel) selected @endif>{{ $p }}</option>
							@endforeach
						</select>
					</td>
				</tr>
				<tr class="tr-jawaban" id="jwb-{{ $rand }}">
					<td colspan="2">
						@if($sel == 0)
						<textarea type="text" class="form-control" placeholder="Jawaban" disabled></textarea>
						@elseif($sel == 1)
						<?php 
							$abc = range('A', 'E');
							$i = 0;
						?>
						@foreach($isi['pilihan'] as $p)
						<div class="input-group" style="width:100%;">
							<span class="input-group-addon">{{ $abc[$i] }}</span>
							<input type="text" class="form-control" aria-label="..." placeholder="Pilihan jawaban" name="pilihan[{{ $rand }}][]" value="{{ $p }}"/>
							<span class="input-group-btn">
								<button class="btn btn-default btn-add-pil" type="button"><i class="fa fa-plus"></i></button>
								<button class="btn btn-default btn-del-pil" type="button"><i class="fa fa-times"></i></button>
							</span>
						</div>
						<?php 
							$i++;
						?>
						@endforeach
						
						@elseif($sel == 2)
						<div class="form-group">
							<label for="jenis">Jenis File:</label>
							<div class="checkbox">
								@foreach($file as $k => $v)
								<div class="radio">
									<label>
										<input type="radio" name="file[{{ $rand }}]" value="{{ $k }}" @if($k == $isi['file']) checked @endif> {{ $v }}
									</label>
								</div>
								@endforeach
							</div>
						</div>
						@endif
					</td>
				</tr>	
				<tr class="tr-aksi" id="aksi-{{ $rand }}">
					<td>&nbsp;</td>
					<td align="right">
						<div class="btn-group" role="group" aria-label="Aksi">
							<button type="button" class="btn btn-info btn-flat btn-xs btn-dup-soal" id="dup-{{ $rand }}"><i class="fa fa-clone"></i> Duplikat</button>
							<button type="button" class="btn btn-danger btn-flat btn-xs btn-del-soal" id="del-{{ $rand }}"><i class="fa fa-trash"></i> Hapus</button>
						</div>
					</td>
				</tr>	
				<?php 
					$c++; 
				?>
				@endforeach				
				@else
				
				@php
				$rand = str_random(6) ;
				@endphp
				
				<tr class="tr-soal" id="soal-{{ $rand }}">
					<td rowspan="3" valign="top">1.</td>
					<td width="500px">
						<input type="text" name="soal[{{ $rand }}]" class="form-control" placeholder="Pertanyaan"/>
					</td>
					<td>
						<select name="jenis_soal[{{ $rand }}]" class="form-control jenis_soal">
							@foreach($pil as $k => $p)
							<option value="{{ $k }}"  @if($k == $sel) selected @endif>{{ $p }}</option>
							@endforeach
						</select>
					</td>
				</tr>
				<tr class="tr-jawaban" id="jwb-{{ $rand }}">
					<td colspan="2">
						<textarea type="text" class="form-control" placeholder="Jawaban" disabled></textarea>
					</td>
				</tr>	
				<tr class="tr-aksi" id="aksi-{{ $rand }}">
					<td>&nbsp;</td>
					<td align="right">
						<div class="btn-group" role="group" aria-label="Aksi">
							<button type="button" class="btn btn-info btn-flat btn-xs btn-dup-soal" id="dup-{{ $rand }}"><i class="fa fa-clone"></i> Duplikat</button>
							<button type="button" class="btn btn-danger btn-flat btn-xs btn-del-soal" id="del-{{ $rand }}"><i class="fa fa-trash"></i> Hapus</button>
						</div>
					</td>
				</tr>	
				@endif
				
			</tbody>
		</table>
		<hr/>
		<button class="btn btn-primary btn-flat" type="button" id="btn-tambah-pertanyaan"><i class="fa fa-plus"></i> Tambah Pertanyaan</button>
	</div>
</div>

<script type="text/template" id="tpl-soal">
	<tr class="tr-soal" id="soal-[rnd]">
<td rowspan="3" valign="top">[no]</td>
<td width="500px">
	<input type="text" name="soal[[rnd]]" class="form-control" placeholder="Pertanyaan"/>
</td>
<td>
	<select name="jenis_soal[[rnd]]" class="form-control jenis_soal">
		<option value="0"  selected>Isian</option>
		<option value="1">Multiple Choice</option>
		<option value="2">Upload File</option>
	</select>
</td>
</tr>
</script> 

<script type="text/template" id="tpl-0">
<textarea type="text" class="form-control" placeholder="Jawaban" disabled></textarea>
</script>

<script type="text/template" id="tpl-1">
	<div class="input-group" style="width:100%;" >
<span class="input-group-addon">[abc]</span>
<input type="text" class="form-control" aria-label="..." placeholder="Pilihan jawaban" name="pilihan[[rnd]][]"/>
<span class="input-group-btn">
	<button class="btn btn-default btn-add-pil" type="button"><i class="fa fa-plus"></i></button>
	<button class="btn btn-default btn-del-pil" type="button"><i class="fa fa-times"></i></button>
</span>
</div>
</script>
<script type="text/template" id="tpl-2">
	<div class="form-group">
<label for="jenis">Jenis File:</label>
<div class="checkbox">
	@foreach($file as $k => $v)
	<div class="radio">
		<label>
			<input type="radio" name="file[[rnd]]" value="{{ $k }}" @if($k == 'gbr') checked @endif> {{ $v }}
		</label>
	</div>
	@endforeach
</div>
</div>
</script>

<script type="text/template" id="tpl-aksi">
	<tr class="tr-aksi" id="aksi-[rnd]">
<td>&nbsp;</td>
<td align="right">
	<div class="btn-group" role="group" aria-label="Aksi">
		<button type="button" class="btn btn-info btn-flat btn-xs btn-dup-soal" id="dup-[rnd]"><i class="fa fa-clone"></i> Duplikat</button>
		<button type="button" class="btn btn-danger btn-flat btn-xs btn-del-soal" id="del-[rnd]"><i class="fa fa-trash"></i> Hapus</button>
	</div>
</td>
</tr>
</script>

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/toastr.min.css') }}">
<style>
	.tr-soal > td{
	padding-top: 8px;
	}
</style>
@endpush

@push('scripts')

@if(App::environment('local'))
<script src="{{ asset('/js/random.js') }}"></script>
<script>
	$('#btn-tambah-pertanyaan').after('<button class="btn btn-danger btn-flat" type="button" id="btn-rand">Random</button>');
	$(document).on('click', '#btn-rand', function()
	{
	$('input[type=text]').each(function(){
	if($(this).val() == '') $(this).val(generateWords(Math.floor(Math.random() * (+9 - +15)) + +9));
	});
	});
</script>
@endif

<script src="{{ asset('/js/toastr.min.js') }}"></script>
<script>
	
	$(document).on('click', '.btn-dup-soal', function()
	{
		var id = $(this).attr('id').split('-')[1];
		var rnd = (+new Date).toString(36).slice(-6);
		var dup = '<tr class="tr-soal" id="soal-'+ rnd +'">' + $('#soal-' + id).html() + '</tr>'; 
		dup += '<tr class="tr-jawaban" id="jwb-'+ rnd +'">' + $('#jwb-' + id).html() + '</tr>'; 
		dup += '<tr class="tr-aksi" id="aksi-'+ rnd +'">' + $('#aksi-' + id).html() + '</tr>'; 
		
		var re = new RegExp(id, 'g');
		dup = dup.replace(re, rnd);
		
		$('#aksi-'+ id).after(dup);
		
		refreshNumber();
	});
	
	$(document).on('click', '.btn-del-soal', function()
	{
		if($('.tr-soal').length < 2) 
		{
			toastr.warning('Soal tidak bisa dihapus', 'Informasi'); 
			return false;				
		}
		else
		{
			if(confirm('Apakah Anda yakin akan menghapus Pertanyaan?'))
			{
				var id = $(this).attr('id').split('-')[1];
				$('#soal-' + id).remove();
				$('#jwb-' + id).remove();
				$('#aksi-' + id).remove();
				
				refreshNumber();
			}
		} 
	});
	
	$(document).on('click', '.btn-add-pil', function(){
	var abc = ['A', 'B', 'C', 'D', 'E'];
	var rnd = $(this).closest('tr').attr('id').split('-')[1];
	var ln = $(this).closest('td').children('div.input-group').length;
	if(ln > 4) 
	{
	toastr.warning('Jumlah Pilihan maksimal adalah 5', 'Informasi'); 
	return false;
	}
	var add = $('#tpl-1').html().replace(/\[rnd\]/g, rnd).replace(/\[abc\]/g, abc[ln]);
	$(this).closest('td').append(add);
});

$(document).on('click', '.btn-del-pil', function()
{
	if(confirm('Apakah Anda yakin akan menghapus Jawaban?'))
	{
		var rnd = $(this).closest('tr').attr('id').split('-')[1];
		$(this).closest('div').remove();
		refreshOption(rnd);
	}
});
$('#btn-tambah-pertanyaan').click(function()
{
	var rnd = (+new Date).toString(36).slice(-6);
	var next = $('.tr-soal').length + 1 + '.';
	
	var soal = $('#tpl-soal').html()
	.replace(/\[no\]/g, next)
	.replace(/\[rnd\]/g, rnd);
	
	var aksi = $('#tpl-aksi').html().replace(/\[rnd\]/g, rnd) ;
	var isi = '<tr class="tr-jawaban" id="jwb-[rnd]"><td colspan="2">' + $('#tpl-0').html()+ '</td></tr>';
	isi = isi.replace(/\[rnd\]/g, rnd);
	$('tbody .tr-aksi:last-child').after(soal + isi + aksi);
});

$(document).on('change', 'select.jenis_soal', function(){
	var target = $(this).closest('tr').next('.tr-jawaban').children('td');
	var rnd = $(this).closest('tr').attr('id').split('-')[1];
	var typ = $(this).val();
	target.html($('#tpl-' + typ).html().replace(/\[rnd\]/g, rnd));
})	;

$(function () {
	toastr.options = {
		"newestOnTop": true,
		"positionClass": "toast-top-center"
	}
})

function refreshNumber()
{
	var c=1;
	$('.tr-soal').each(function(){
		$(this).children('td:first-child').text(c + '.');
		c++;
	});
}
function refreshOption(id)
{
	var abc = ['A', 'B', 'C', 'D', 'E'];
	var c=0;
	$('#jwb-' + id + ' .input-group').each(function(){
		$(this).children('span:first-child').text(abc[c]);
		c++;
	});
}
</script>
@endpush
