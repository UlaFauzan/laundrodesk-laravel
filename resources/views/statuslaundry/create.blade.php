@extends('layout')

@section('title', 'Tambah Status Laundry')

@section('content')
    <div class="page-header">
        <div>
            <h1>Tambah Status Laundry</h1>
            <p>Tambahkan status baru untuk proses laundry.</p>
        </div>
    </div>

    <form class="form-card" action="/status-laundry" method="POST">
        @csrf

        <div class="form-grid">
            <div class="field field--full">
                <label for="nama_status">Status</label>
                <input id="nama_status" type="text" name="nama_status" placeholder="Contoh: Selesai" required>
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="/status-laundry" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
@endsection
