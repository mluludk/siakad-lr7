<div class="f-box">
	<div class="f-box-body">
		<h4><i class="fa fa-comments"></i> Komentar</h4>			
		<div class="box-body chat" id="chat-box" style="overflow-y: auto; width: auto; max-height: 550px;">
			
		</div>
		
		<form action="{{ route('komentar.post', ['Kegiatan', $kegiatan -> id]) }}" method="post" id="frm-komentar" data-time="0">
			{!! csrf_field() !!}
			<div class="input-group">
				<input type="text" name="komentar" placeholder="Tambahkan Komentar" class="form-control">
				<input type="hidden" name="last_id" value="{{ $last_id ?? 0}}">
				<span class="input-group-btn">
					<button class="btn btn-default modal-link" type="button" data-toggle="modal" href="#myModal" data-href="{{ route('file.manager', 'gambar') }}"><i class="fa fa-image"></i>&nbsp;</button>
					<button class="btn btn-default modal-link" type="button" data-toggle="modal" href="#myModal" data-href="{{ route('file.manager', 'video') }}"><i class="fa fa-video-camera"></i>&nbsp;</button>
					<button class="btn btn-default modal-link" type="button" data-toggle="modal" href="#myModal" data-href="{{ route('file.manager', 'dokumen') }}"><i class="fa fa-file-text-o"></i>&nbsp;</button>
					<button type="button" class="btn btn-success" id="btn-komentar" data-toggle="tooltip" data-placement="top" title="Kirim"><i class="fa fa-send"></i>&nbsp;</button>
				</span>
			</div>
		</form>
		
	</div>
</div>


<div class="modal-container"></div>
@push('scripts')
<script src="{{ url('/js/jquery.form.min.js') }}"></script>
<script>
	$(document).on('click', '#btn-komentar', function(){
	if($('input[name=komentar]').val() != ''){
	$('form#frm-komentar').submit();
	}
	});
	
	$('form#frm-komentar').ajaxForm({
	beforeSend: function() {
	if($('input[name=komentar]').val() == '') return false;
	if(Date.now() - $('#frm-komentar').attr('data-time') < 5000){alert('Spam detected');return false;}
	},
	success: function(data) {
	if(!data.success)
	{
	alert('Terjadi kesalahan: ' + data.error);
	}
	else
	{
	var item = '';
	var last_id = 0;
	let i;
	for(i of Object.values(data.items))
	{					
	item += formatKomentar(i);
	last_id = i.id;
	}
	$('input[name=last_id]').val(last_id);
	$('#frm-komentar').attr('data-time', Date.now());
	$('.chat').append(item).scrollTop($("#chat-box")[0].scrollHeight);
	$("time.timeago").timeago();
	}
	},
	complete: function(xhr) {
	$('input[name=komentar]').val('');
	},
	error: function(XMLHttpRequest, textStatus, errorThrown){
	alert('Terjadi kesalahan: ' + errorThrown);
	}
	});  
	
	function getKomentar(model, id, last_id=0)
	{
	$.ajax({
	url: "{{ url('komentar') }}/" + model + "/" + id,
	type: "get",
	success: function(data){
	var item = '';
	var last_id = 0;
	let i;
	for(i of Object.values(data))
	{					
	item += formatKomentar(i);
	last_id = i.id;
	}
	$('input[name=last_id]').val(last_id);
	$('#frm-komentar').attr('data-time', Date.now());
	$('.chat').append(item).scrollTop($("#chat-box")[0].scrollHeight);
	$("time.timeago").timeago();			
	},
	error: function(XMLHttpRequest, textStatus, errorThrown){
	alert('Terjadi kesalahan: ' + errorThrown);
	}
	});  
	}
	function formatKomentar(i)
	{
	return '<div class="item">'+
	'<img src="'+ i.image +'" alt="'+ i.user +'" class="'+ i.status +'">'+			
	'<p class="message">'+
	'<a href="#" class="name">'+
	'<time class="text-muted pull-right timeago" datetime="'+ i.waktu +'">Baru saja</time>'+
	i.user +
	'</a>'+
	i.komentar +
	'</p>'+
	'</div>';
}

$(function () {
	getKomentar('Kegiatan', {{ $kegiatan -> id }});
	
	$('[data-toggle="tooltip"]').tooltip();
	
	$('.modal-link').click(function(e) {
		var url = $(this).attr('data-href');
		$('.modal-container').load(url, function(result){
			$('#myModal').modal({show:true});
			$('#myModal').attr('data-type', 'attachment');
			$('#myModal').attr('data-target', 'frm-komentar');
		});
	});
	
	$(document).on('hidden.bs.modal', '#myModal',function (e) {
		$('#myModal').remove();
	});
});
</script>
@endpush
