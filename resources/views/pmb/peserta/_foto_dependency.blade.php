@push('styles')
<style>
	/* input file - http://tympanus.net/codrops/2015/09/15/styling-customizing-file-inputs-smart-way/ */
	.upload {
	width: 0.1px;
	height: 0.1px;
	opacity: 0;
	overflow: hidden;
	position: absolute;
	z-index: -1;
	}
	.upload + label {
    display: inline-block;
	cursor: pointer;
	}
	.upload:focus + label {
	outline: 1px dotted #000;
	outline: -webkit-focus-ring-color auto 5px;
	}
	.upload + label * {
	pointer-events: none;
	}
	
	.preview{
	display:block;
	width: 100%;
	padding: 5px;
	margin-bottom: 3px;
	border: 1px solid #999;
	}
</style>
@endpush

@push('scripts')
<script src="{{ url('/js/jquery.form.min.js') }}"></script>
@endpush