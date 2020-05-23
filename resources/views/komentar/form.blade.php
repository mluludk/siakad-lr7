<div class="f-box">
    <div class="f-box-body">
        <h4><i class="fa fa-comments"></i> Komentar</h4>
        <div class="box-body chat" id="chat-box" style="overflow-y: auto; width: auto; max-height: 550px;">

        </div>

        <form action="{{ route('komentar.post', ['Kegiatan', $kegiatan -> id]) }}" method="post" id="frm-komentar"
            data-time="0">
            {!! csrf_field() !!}
            <div class="input-group">
                <span class="has-float-label">
                    <input type="text" name="komentar" placeholder="Tambahkan Komentar" class="form-control"
                        id="inp-komentar">
                    <label for="inp-komentar">Tambahkan Komentar</label>
                </span>
                <input type="hidden" name="attachment" value="">
                <input type="hidden" name="reply_id" value="">
                <input type="hidden" name="last_id" value="{{ $last_id ?? 0}}">
                <span class="input-group-btn">
                    <button class="btn btn-default modal-link" type="button" data-toggle="modal" href="#myModal"
                        data-href="{{ route('file.manager', 'gambar') }}"><i class="fa fa-image"></i>&nbsp;</button>
                    <button class="btn btn-default modal-link" type="button" data-toggle="modal" href="#myModal"
                        data-href="{{ route('file.manager', 'video') }}"><i
                            class="fa fa-video-camera"></i>&nbsp;</button>
                    <button class="btn btn-default modal-link" type="button" data-toggle="modal" href="#myModal"
                        data-href="{{ route('file.manager', 'dokumen') }}"><i
                            class="fa fa-file-text-o"></i>&nbsp;</button>
                    <button type="button" class="btn btn-danger" id="btn-reset" data-toggle="tooltip"
                        data-placement="top" title="Bersihkan"><i class="fa fa-times"></i>&nbsp;</button>
                    <button type="button" class="btn btn-success" id="btn-komentar" data-toggle="tooltip"
                        data-placement="top" title="Kirim"><i class="fa fa-send"></i>&nbsp;</button>
                </span>
            </div>
        </form>

    </div>
</div>

<div id="fm-komentar"></div>

@push('styles')
<style>
    /* https://cdn.rawgit.com/tonystar/bootstrap-float-label/v3.0.1/dist/bootstrap-float-label.min.css */
    .has-float-label {
        display: block;
        position: relative
    }

    .has-float-label label,
    .has-float-label>span {
        position: absolute;
        cursor: text;
        font-size: 75%;
        opacity: 1;
        -webkit-transition: all .2s;
        transition: all .2s;
        top: -.5em;
        left: 12px;
        z-index: 3;
        line-height: 1;
        padding: 0 1px
    }

    .has-float-label label::after,
    .has-float-label>span::after {
        content: " ";
        display: block;
        position: absolute;
        background: #fff;
        height: 2px;
        top: 50%;
        left: -.2em;
        right: -.2em;
        z-index: -1
    }

    .has-float-label .form-control::-webkit-input-placeholder {
        opacity: 1;
        -webkit-transition: all .2s;
        transition: all .2s
    }

    .has-float-label .form-control::-moz-placeholder {
        opacity: 1;
        transition: all .2s
    }

    .has-float-label .form-control:-ms-input-placeholder {
        opacity: 1;
        transition: all .2s
    }

    .has-float-label .form-control::placeholder {
        opacity: 1;
        -webkit-transition: all .2s;
        transition: all .2s
    }

    .has-float-label .form-control:placeholder-shown:not(:focus)::-webkit-input-placeholder {
        opacity: 0
    }

    .has-float-label .form-control:placeholder-shown:not(:focus)::-moz-placeholder {
        opacity: 0
    }

    .has-float-label .form-control:placeholder-shown:not(:focus):-ms-input-placeholder {
        opacity: 0
    }

    .has-float-label .form-control:placeholder-shown:not(:focus)::placeholder {
        opacity: 0
    }

    .has-float-label .form-control:placeholder-shown:not(:focus)+* {
        font-size: 150%;
        opacity: .5;
        top: .3em;
        font-weight: 400
    }

    .input-group .has-float-label {
        display: table-cell
    }

    .input-group .has-float-label .form-control {
        border-radius: 4px
    }

    .input-group .has-float-label:not(:last-child) .form-control {
        border-bottom-right-radius: 0;
        border-top-right-radius: 0
    }

    .input-group .has-float-label:not(:first-child) .form-control {
        border-bottom-left-radius: 0;
        border-top-left-radius: 0;
        margin-left: -1px
    }
</style>
<style>
    div.item:not(:last-child) {
        border-bottom: 1px solid grey;
        padding-bottom: 3px;
    }

    p.message img,
    video {
        width: 200px;
    }

    a.att {
        display: block;
    }

    a.reply {
        display: block;
        padding: 10px;
        border-radius: 5px;
        background-color: #f4f4f4;
        text-decoration: none;
        color: #333;
        clear: both;
    }

    .btn-reply {
        font-size: 12px;
    }
</style>
@endpush
@push('scripts')
<script src="{{ url('/js/jquery.form.min.js') }}"></script>
<script src="{{ asset('/js/jquery.timeago.js') }}" type="text/javascript"></script>
<script>
    $(document).on('click', '#btn-reset', function(){
        resetKomentar();
    });
    $(document).on('click', '.btn-delete', function(){
        if(!confirm('Apakah Anda yakin akan menghapus Komentar ini?')) return false;
        var id = $(this).attr('id').split('-')[1];
        $.ajax({
            url: "{{ url('komentar') }}/" + id + '/delete',
            type: "get",
            success: function(data){
                if(data.success)
                {
                    $('#komentar-' + id).remove();
                    alert(data.message);
                }
                else
                alert('Terjadi kesalahan: ' + data.message);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                alert('Terjadi kesalahan: ' + errorThrown);
            }
        });
	});
    $(document).on('click', '.btn-reply', function(){
	$('#frm-komentar input[name=reply_id]').val($(this).attr('id').split('-')[1]);
	$('#frm-komentar input[name=komentar]').attr('placeholder', 'Balas Komentar');
	$('#frm-komentar input[name=komentar]').siblings('label').html('Balas Komentar');
	$('#frm-komentar input[name=komentar]').focus();
	});
    $(document).on('click', '#btn-komentar', function(){
	if($('#frm-komentar input[name=komentar]').val() != ''){
	$('form#frm-komentar').submit();
	}
	});

	$('form#frm-komentar').ajaxForm({
	beforeSend: function() {
	if($('#frm-komentar input[name=komentar]').val() == '') return false;
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
	$('#frm-komentar input[name=last_id]').val(last_id);
	$('#frm-komentar').attr('data-time', Date.now());
	$('#chat-box').append(item).scrollTop($("#chat-box")[0].scrollHeight);
	$("time.timeago").timeago();
        resetKomentar();
	}
	},
	complete: function(xhr) {
	},
	error: function(XMLHttpRequest, textStatus, errorThrown){
	alert('Terjadi kesalahan: ' + errorThrown);
	}
	});

    function resetKomentar()
    {
        $('#frm-komentar input[name=komentar]').val('');
        $('#frm-komentar input[name=attachment]').val('');
        $('#frm-komentar input[name=reply_id]').val('');
        $('#frm-komentar input[name=komentar]').attr('placeholder', 'Tambahkan Komentar');
        $('#frm-komentar input[name=komentar]').siblings('label').html('Tambahkan Komentar');
    }
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
                $('#frm-komentar input[name=last_id]').val(last_id);
                $('#frm-komentar').attr('data-time', Date.now());
                $('#chat-box').append(item).scrollTop($("#chat-box")[0].scrollHeight);
                $("time.timeago").timeago();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                alert('Terjadi kesalahan: ' + errorThrown);
            }
        });
	}
	function formatKomentar(i)
	{
        return '<div class="item" id="komentar-'+i.id+'">'+
        '<img src="'+ i.image +'" alt="'+ i.user +'" class="'+ i.status +'">'+
        '<p class="message">'+
        '<a href="#" class="name">'+
        '<time class="text-muted pull-right timeago" datetime="'+ i.waktu +'">Baru saja</time>'+
        i.user +
        '</a>'+
        i.reply+
        i.komentar +
        '</p>'+
        '<a href="javascript:" class="pull-right btn-delete btn btn-xs btn-danger btn-flat" id="delete-'+ i.id +'" title="Hapus Komentar"><i class="fa fa-trash"></i></a>'+
        '<a href="javascript:" class="pull-right btn-reply btn btn-xs btn-info btn-flat" id="reply-'+ i.id +'" title="Balas Komentar"><i class="fa fa-reply"></i></a>'+
        '</div>';
    }

$(function () {

	getKomentar('Kegiatan', {{ $kegiatan -> id }});
    jQuery.timeago.settings.strings = {
		prefixAgo: null,
		prefixFromNow: null,
		suffixAgo: "yang lalu",
		suffixFromNow: "dari sekarang",
		seconds: "kurang dari semenit",
		minute: "sekitar satu menit",
		minutes: "%d menit",
		hour: "sekitar sejam",
		hours: "sekitar %d jam",
		day: "sehari",
		days: "%d hari",
		month: "sekitar sebulan",
		months: "%d bulan",
		year: "sekitar setahun",
		years: "%d tahun"
	};


	$('[data-toggle="tooltip"]').tooltip();

	$('.modal-link').click(function(e) {
		var url = $(this).attr('data-href');

		$('#fm-komentar').load(url, function(result){
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
