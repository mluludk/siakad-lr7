@extends('app')

@push('styles')
<style>
	body {
	width: 100%;
	height: 100%;
	display: table;
	font-weight: 100;
	}	
	.main_logo {
	display:block;
	margin:10px auto;
	}
	
	/* .intro-header img{
	-webkit-filter: blur(1px);
	filter: blur(1px);
	position: absolute;
	top:0px;
	left: 0px;
	overflow:hidden;
	display:block;
	} */
	
	.intro-header {
	background-color: #808080;
	background: no-repeat center center;
	background-attachment: scroll;
	background-size: cover;
	-o-background-size: cover;
	background-image: url('/img/wallpaper/w2.jpg');
	margin-top: 50px;
	margin-bottom: -30px;
	}
	.intro-header .site-heading,
	.intro-header .post-heading,
	.intro-header .page-heading {
	padding: 100px 0 50px;
	color: white;
	}
	@media only screen and (min-width: 768px) {
	.intro-header .site-heading,
	.intro-header .post-heading,
	.intro-header .page-heading {
    padding: 200px 0px 250px 0px;
    /* padding: 130px 0px 150px 0px; */
	}
	}
	.intro-header .site-heading,
	.intro-header .page-heading {
	text-align: center;
	}
	.intro-header .site-heading h1,
	.intro-header .page-heading h1 {
	margin-top: 0;
	font-size: 50px;
	}
	.intro-header .site-heading .subheading,
	.intro-header .page-heading .subheading {
	font-size: 24px;
	line-height: 1.1;
	display: block;
	font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
	font-weight: 300;
	margin: 10px 0 0;
	}
	@media only screen and (min-width: 768px) {
	.intro-header .site-heading h1,
	.intro-header .page-heading h1 {
    font-size: 80px;
	}
	}
	.intro-header .post-heading h1 {
	font-size: 35px;
	}
	.intro-header .post-heading .subheading,
	.intro-header .post-heading .meta {
	line-height: 1.1;
	display: block;
	}
	.intro-header .post-heading .subheading {
	font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
	font-size: 24px;
	margin: 10px 0 30px;
	font-weight: 600;
	}
	.intro-header .post-heading .meta {
	font-family: 'Lora', 'Times New Roman', serif;
	// font-style: italic;
	font-weight: 300;
	font-size: 20px;
	}
	.intro-header .post-heading .meta a {
	color: white;
	}
	@media only screen and (min-width: 768px) {
	.intro-header .post-heading h1 {
    font-size: 55px;
	}
	.intro-header .post-heading .subheading {
    font-size: 30px;
	}
	}
</style>
@endpush

@section('intro-header')
<header class="intro-header">
	<!--img src="/img/wallpaper/w2.jpg" /-->
	<div class="container">
		<div class="row">
			<div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
				<div class="post-heading">
					<h3 style="margin-bottom:0px;">{{ config('custom.school.type') }}</h3>
					<h1 style="margin:0px;">{{ config('custom.school.name') }}</h1>
					<span class="meta">{{ config('custom.school.address') }}</span>
				</div>
			</div>
		</div>
	</div>	
</header>
@endsection

@section('content')
<div class="row">
	<div class="col-sm-6 col-md-4">
		<div class="thumbnail">
			<img data-src="holder.js/300x200" alt="300x200" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMDAiIGhlaWdodD0iMjAwIj48cmVjdCB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjE1MCIgeT0iMTAwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjE5cHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MzAweDIwMDwvdGV4dD48L3N2Zz4=" style="width: 300px; height: 200px;">
			<div class="caption">
				<h3>Qui qui voluptatibus</h3>
				<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
				<p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-md-4">
		<div class="thumbnail">
			<img data-src="holder.js/300x200" alt="300x200" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMDAiIGhlaWdodD0iMjAwIj48cmVjdCB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjE1MCIgeT0iMTAwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjE5cHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MzAweDIwMDwvdGV4dD48L3N2Zz4=" style="width: 300px; height: 200px;">
			<div class="caption">
				<h3>Non placeat qui aut vitae</h3>
				<p>Deleniti dolores ut illum qui officiis voluptas possimus. Qui qui voluptatibus consectetur voluptatem iste repellendus soluta. Veniam ratione enim provident amet illo sunt qui tempora.</p>
				<p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-md-4">
		<div class="thumbnail">
			<img alt="300x200" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMDAiIGhlaWdodD0iMjAwIj48cmVjdCB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjE1MCIgeT0iMTAwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjE5cHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MzAweDIwMDwvdGV4dD48L3N2Zz4=" style="width: 300px; height: 200px;">
			<div class="caption">
				<h3>Alice had learnt several</h3>
				<p>The Gryphon sat up and leave the room, when her eye fell on a little quicker. 'What a curious plan!' exclaimed Alice. 'That's very curious!' she thought. 'I must go and take it away!' </p>
				<p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
			</div>
		</div>
	</div>
</div>
@endsection	