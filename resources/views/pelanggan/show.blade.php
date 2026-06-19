@extends('layout')

@section('content')
    <h2>Detail Pelanggan</h2>

    <div class="card" style="padding: 20px; max-width: 600px;">
        <p><strong>ID:</strong> {{ $pelanggan->id }}</p>
        <p><strong>Nama:</strong> {{ $pelanggan->nama }}</p>
        <p><strong>Telepon:</strong> {{ $pelanggan->telepon }}</p>
        <p><strong>Alamat:</strong> {{ $pelanggan->alamat }}</p>
    </div>
@endsection
