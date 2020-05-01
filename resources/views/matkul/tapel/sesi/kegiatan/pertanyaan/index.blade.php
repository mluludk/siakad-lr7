<div class="box box-default">
	<div class="box-header with-border">
		<h3 class="box-title"> Pertanyaan Quiz</h3>
	</div>
	<div class="box-body">
		<button class="btn btn-info modal-link btn-flat" type="button" id="btn-tambah-pertanyaan" data-toggle="modal" href="#modal">Tambah Pertanyaan</button>
		<hr/>
		<table width="100%" id="tbl-soal">
			<tbody>
				@if(isset($kegiatan -> isi))
				<?php $c = 1; ?>
				@foreach($kegiatan -> isi as $isi)
				<tr class="tr-soal" id="soal-{{ $c }}">
					<td width="30px">{{ $c }}.</td>
					<td width="550px">{!! $isi['soal'] !!}</td>
					<td width="30px" class="text-info">{{ $isi['bobot'] }}</td>
					<td width="100px">
						<button class="btn btn-warning btn-sm btn-flat btn-edit-soal" type="button" id="edt-{{ $c }}"><i class="fa fa-edit"></i></button>
						<button class="btn btn-danger btn-sm btn-flat btn-del-soal" type="button" id="del-{{ $c }}"><i class="fa fa-trash"></i></button>
						<input type="hidden" name="soal[]" value="{{ $isi['soal'] }}" id="soa-{{ $c }}"/>
						<input type="hidden" name="bobot[]" value="{{ $isi['bobot'] }}" id="bob-{{ $c }}"/>
						<input type="hidden" name="pilihan[]" value="{{ implode(';', $isi['pilihan']) }};" id="pil-{{ $c }}"/>
						<input type="hidden" name="benar[]" value="{{ $isi['benar'] }}" id="ben-{{ $c }}"/>
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
				@endif
			</tbody>
		</table>
	</div>
</div>

<div id="modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="file-title"><strong>Tambah Pertanyaan</strong></h4>
			</div>
			
			<div class="modal-body">
				<div class="form-horizontal">
					<div class="form-group">
						{!! Form::label('pertanyaan', 'Pertanyaan:', array('class' => 'col-md-2 control-label')) !!}
						<div class="col-md-10">
							<div id="pertanyaan">{!! $pertanyaan ?? '' !!}</div>
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('bobot', 'Bobot:', array('class' => 'col-md-2 control-label')) !!}
						<div class="col-md-2">
							<input class="form-control" placeholder="Bobot" type="text" id="bobot">
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('jawaban', 'Jawaban:', array('class' => 'col-md-2 control-label')) !!}
						<div class="col-md-9" id="j-wrapper">
							<div class="col-md-12 j-div">
								<button class="btn btn-info btn-flat" id="btn-tambah-j" type="button">Tambah Jawaban</button>
							</div>
							
						</div>
					</div>
				</div>
				
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary btn-flat" id="btn-simpan" type="button" data-target="">Simpan</button>
			</div>
			
		</div>
	</div>
</div>	

<script type="text/template" id="tpl-row">
	<tr class="tr-soal" id="soal-[no]">
<td width="30px">[no].</td>
<td width="550px">[soal]</td>
<td width="30px" class="text-info">[bobot]</td>
<td width="100px">
	<button class="btn btn-warning btn-sm btn-flat btn-edit-soal" type="button" id="edt-[no]"><i class="fa fa-edit"></i></button>
	<button class="btn btn-danger btn-sm btn-flat btn-del-soal" type="button" id="del-[no]"><i class="fa fa-trash"></i></button>
	<input type="hidden" name="soal[]" value="[soal]" id="soa-[no]"/>
	<input type="hidden" name="bobot[]" value="[bobot]" id="bob-[no]"/>
	<input type="hidden" name="pilihan[]" value="[pilihan]" id="pil-[no]"/>
	<input type="hidden" name="benar[]" value="[benar]" id="ben-[no]"/>
</td>
</tr>
</script>

<script type="text/template" id="tpl-input">
	<div class="col-md-12 jaw j-div" id="div-[l]">
	<div class="input-group">
<span class="input-group-addon">[u]</span>
<input class="form-control txt-jaw" id="[l]" placeholder="Deskripsi Jawaban" type="text">
<span class="input-group-addon" data-toggle="tooltip" data-placement="left" title="Pilih [u] sebagai jawaban benar">
	<input class="chk-jaw" type="radio" name="benar" value="[j]" [c]>
</span>
<span class="input-group-btn">
	<button class="btn btn-danger btn-delete" type="button" id="del-[l]"><i class="fa fa-times"></i></button>
</span>
</div>
</div>
</script>

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/toastr.min.css') }}">
<style>
	.j-div{
	padding-left: 0px;
	margin-bottom: 5px;
	}
	#tbl-soal tr{
	border-bottom: 1px solid #eee;
	}
	#tbl-soal td{
	padding: 5px;
	}
	td > p{
	margin: 0;
	}
</style>
@endpush

@push('scripts')
<script src="{{ asset('/js/toastr.min.js') }}"></script>
<script>
	$(document).on('click', '.btn-del-soal', function(){
	var id = $(this).attr('id').split('-')[1];
	var last = $('tbody .tr-soal:last-child').attr('id');
	
	if(confirm('Apakah Anda yakin akan menghapus Soal ini?'))
	{
	$('#soal-' + id).remove();
	if(last != 'soal-' + id)
	{
	refreshSoal();
	}
	}		
	});
	
	$(document).on('click', '.btn-edit-soal', function(){
	var pil = ['a', 'b', 'c', 'd', 'e'];
	var id = $(this).attr('id').split('-')[1];
	var tpl = $('#tpl-input').html();
	
	//flag
	$('#btn-simpan').addClass('flag-edit');
	$('#btn-simpan').attr('data-target', 'soal-' + id);
	
	$('#pertanyaan').summernote('code', $('#soa-' + id).val());
	$('#bobot').val($('#bob-' + id).val());
	
	var pilihan = $('#pil-' + id).val().split(';');
	for(i = 0; i < pilihan.length-1; i++)
	{
	createJawaban($('#ben-' + id).val());
	$('input#' + pil[i]).val(pilihan[i]);
	}
	
	$('#modal').modal('show');
	});
	
	$(document).on('click', '.btn-delete', function(){
	var id = $(this).attr('id').split('-')[1];
	var last_id = $('.j-div').last().attr('id').split('-')[1];
	if(last_id != id) 
	{
	toastr.warning('Jawaban tidak bisa dihapus. Hanya jawaban terakhir yang bisa dihapus', 'Informasi'); 
	return false;
	}
	
	if(confirm('Apakah Anda yakin akan menghapus Jawaban ini?'))
	{
	$('#div-' + id).remove();
	}
	});
	
	$('#btn-simpan').on('click', function(){
	if($('#pertanyaan').summernote('isEmpty')) {toastr.warning('Pertanyaan belum diisi', 'Informasi'); return false;}
	
	var pilihan = '';
	var ok = true;
	var target = null;
	
	if($('.jaw').length < 1) {toastr.warning('Jawaban belum diisi', 'Informasi'); return false;}
	$('.txt-jaw').each(function(i){
	if($(this).val() == '')
	ok = false;
	else 
	pilihan += $(this).val() + ';';
	});
	
	if(!ok)	{toastr.warning('Jawaban '+ $(this).attr('id').toUpperCase() +' belum diisi', 'Informasi'); return false;}
	
	target = $(this).hasClass('flag-edit') ? $(this).attr('data-target') : null;
	
	addSoal({
	'soal': $('#pertanyaan').summernote('code'),
	'bobot': isNaN(parseInt($('#bobot').val())) ? 0 : parseInt($('#bobot').val()),
	'pilihan': pilihan,
	'benar': $('.chk-jaw:checked').val()
	}, target);
	
	});
	
	$('#btn-tambah-j').on('click', function(){
	createJawaban();
	});
	
	$(function () {
	toastr.options = {
	"newestOnTop": true,
	"positionClass": "toast-top-center"
	}
	$('[data-toggle="tooltip"]').tooltip();
	$('#pertanyaan').summernote({
	minHeight: 50, 
	maxHeight: null, 
	toolbar: [
	['style', ['bold', 'italic', 'underline', 'clear']],
	['font', ['strikethrough', 'superscript', 'subscript']],
	['fontsize', ['fontname', 'fontsize']],
	['color', ['color']],
	['para', ['ul', 'ol']]
	]
	});
	
	$(document).on('hidden.bs.modal', '#modal',function (e) {
	cleaning();
	});
	});
	
	function refreshSoal()
	{
		var data = [];
		$('.tr-soal').each(function(e){
			var me = $(this);
			data[e] ={
				'soal': me.find('input[name="soal[]"]').val(),
				'bobot': me.find('input[name="bobot[]"]').val(),
				'pilihan': me.find('input[name="pilihan[]"]').val(),
				'benar': me.find('input[name="benar[]"]').val()
			};
		});
		$('.tr-soal').remove();
		
		for(var k in data)
		{
			addSoal(data[k]);
		}
	}
	function addSoal(data, target)
	{
		var next = 1;
		if(target == null )
		{
			next = $('.tr-soal').length + 1;
		}
		else
		{
		next = target.split('-')[1];
	$('tr#' + target).remove();
	
	//remove flag
	$('#btn-simpan').removeClass('flag-edit');
	$('#btn-simpan').attr('data-target', '');
}

var tpl = $('#tpl-row').html();
var tr = '';

tr = tpl.replace(/\[no\]/g, next)
.replace(/\[soal\]/g, data.soal)
.replace(/\[pilihan\]/g, data.pilihan)
.replace(/\[benar\]/g, data.benar)
.replace(/\[bobot\]/g, data.bobot);

$('#tbl-soal tbody').append(tr);

$('#modal').modal('hide');
}

function createJawaban(ck=0)
{
	var pil = ['a', 'b', 'c', 'd', 'e'];
	var tpl = $('#tpl-input').html();
	
	var cj = $('.jaw').length;
	
	if(cj == 4) $(this).addClass('hidden');	

if(cj == ck) tpl = tpl.replace(/\[c\]/g, 'checked');
else  tpl = tpl.replace(/\[c\]/g, '');

tpl = tpl.replace(/\[l\]/g, pil[cj])
.replace(/\[j\]/g, cj)
.replace(/\[u\]/g, pil[cj].toUpperCase());

$('#j-wrapper').append(tpl);	

return cj;
}

function cleaning()
{
	$('#bobot').val('');
	$('#pertanyaan').summernote('code', '');
	$('.jaw').remove();	
}
</script>	
@endpush	
