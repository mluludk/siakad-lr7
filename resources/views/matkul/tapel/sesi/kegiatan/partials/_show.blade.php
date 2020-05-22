@push('styles')
<style>
    #tbl-soal tr {
        border-bottom: 1px solid #eee;
    }

    #tbl-soal td {
        padding: 5px;
    }

    td>p {
        margin: 0;
    }

    #cd {
        font-size: 20px;
    }

    div>label {
        font-weight: normal;
    }
</style>
<link rel="stylesheet" href="{{ asset('/css/toastr.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('/js/toastr.min.js') }}"></script>
<script>
    toastr.options = {
        "newestOnTop": true,
        "positionClass": "toast-top-center",
    }
</script>

@if($kegiatan -> batas_waktu != '')
<script>
    // https://www.w3schools.com/howto/howto_js_countdown.asp
    var dt = new Date("{{ $kegiatan -> batas_waktu }}").getTime();
    var x = setInterval(function() {
        var now = new Date().getTime();
        var dst = dt - now;
        var cd = '<span class="text-info">';

        var hr = Math.floor(dst / (1000 * 60 * 60 * 24));
        var jm = Math.floor((dst % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var mn = Math.floor((dst % (1000 * 60 * 60)) / (1000 * 60));
        var dtk = Math.floor((dst % (1000 * 60)) / 1000);

        if (hr > 0) cd += hr + " Hari ";
        if (jm > 0) cd += jm + " Jam ";
        if (mn > 0) cd += mn + " Menit ";

        if (dtk > 0) cd += dtk + " Detik";

        $("#cd").html(cd);
        cd += '</span>';

        if (dst < 0) {
            clearInterval(x);
            $("#cd").html('<button class="btn btn-danger" disabled><i class="fa fa-times"></i> Waktu habis</button>');
        }

        @if($stop)
            clearInterval(x);
            $("#cd").html('<button class="btn btn-success" disabled><i class="fa fa-check"></i> Selesai</button>');
        @endif
    }, 1000);
</script>
@endif
@endpush
