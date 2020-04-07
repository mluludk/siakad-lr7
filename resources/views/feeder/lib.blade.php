@push('styles')
<link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
<style>
	div.dataTables_info, div.dataTables_filter{
	float:left;
	}
	div.dataTables_length{
	text-align:right;
	}
	div.dataTables_filter{
	text-align:left !important;
	}
	div.dataTables_filter input{
	width: 500px !important;
	}
	#div_button{
	display: none;
	}
</style>
<link rel="stylesheet" href="{{ asset('/css/toastr.min.css') }}">
@endpush			

@push('scripts')
<script src="{{ asset('/js/toastr.min.js') }}"></script>
<script>
	$(document).on('click', '.btn-del-sel', function(){
	if(!confirm('Hapus data yang telah dipilih?')) return false;
	var loader = '<i class="fa fa-spinner fa-spin loader"></i>';
	var me = $(this);
	var n = 0;
	var id = {};
	$('input.data_del:checked').each(function(){
	chk = $(this);
	id[n] = chk.val();
	n++;
	});
	
	if(n > 0)
	{
	$.ajax({
	url: me.attr('data-url'),
	type: "post",
	data: {
	'type': 'check',
	'id': id,
	'_token': $('meta[name="csrf-token"]').attr('content')
},
beforeSend: function()
{
	me.before(loader);
	me.hide();
},
success: function(data){
	me.show();
	$('.loader').remove();
	
	if(data.success > 0) toastr.success(data.success + ' Data ' + data.type + ' berhasil dihapus.', 'Sukses');
	if(data.failed > 0) toastr.error(data.failed + ' Data ' + data.type + ' gagal dihapus.', 'Error');				
}
});  
}
else
{
	toastr.warning('Pilih data yang akan dihapus terlebih dahulu.', 'Peringatan');
}
});

function checkAll(me){
	$(".data_" + me.val()).prop('checked', me.prop('checked'));
}

$(document).on('change', '.check-all', function(){
	checkAll($(this));
});

$(document).on('click', '.btn-check', function(){
	var cb = $(this).children('.check-all');
	var ck = cb.prop('checked');
	
	cb.prop('checked', !ck);
	checkAll(cb);
});

$(function(){
	toastr.options.onHidden = function() {window.location.reload(true);}
	
	$('[data-toggle="popover"]').popover({
		html: true,
		placement: 'auto top',
		trigger: 'hover'
	});
});
</script>
<script src="{{ asset('/js/datatables.min.js') }}"></script>
<script>
	$(function () {
	$('#tbl-data').DataTable({
		dom: 'fl<"toolbar">tip',
		pageLength: 25,
		lengthMenu: [25, 50, 75, 100, 200, 300],
		ordering: false,
		language: 	{
			"sEmptyTable":   "Tidak ada data yang tersedia pada tabel ini",
			"sProcessing":   "Sedang memproses...",
			/* "sLengthMenu":   "Tampilkan _MENU_ entri", */
			"sLengthMenu":   "Tampilkan _MENU_",
			"sZeroRecords":  "Tidak ditemukan data yang sesuai",
			"sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
			"sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 entri",
			"sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
			"sInfoPostFix":  "",
			"sSearch":       "Cari:",
			"sUrl":          "",
			"oPaginate": 	{
				"sFirst":    "Pertama",
				"sPrevious": "Sebelumnya",
				"sNext":     "Selanjutnya",
				"sLast":     "Terakhir"
			}
		}
	});
	/* $("div.toolbar").html('<button type="button" class="btn btn-danger btn-flat btn-check btn-xs"><input type="checkbox" class="check-all" value="ttd">Pilih Mahasiswa yang <strong>BELUM</strong> terdaftar di Feeder </button>'); */
	$("div.toolbar").html($('#div_button').html());
});
</script>
@endpush	