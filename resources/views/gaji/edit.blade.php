@extends('app')

@section('title')
Edit Jenis Pembayaran
@endsection

@section('content')
<h2>Edit Jenis Pembayaran</h2>
{!! Form::model($jbiaya, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form', 'route' => ['jenisbiaya.update', $jbiaya->id]]) !!}
@include('jenisbiaya._partials.form')
{!! Form::close() !!}
@endsection	