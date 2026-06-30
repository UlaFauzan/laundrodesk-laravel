@extends('layout')

@section('title', 'Edit Status Laundry')

@section('content')
    <div class="page-header">
        <div>
            <h1>Edit Status Laundry</h1>
            <p>Perbarui nama status laundry.</p>
        </div>
    </div>

    <form class="form-card" action="/status-laundry/{{ $statusLaundry->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="field field--full">
                <label for="nama_status">Status</label>
                <input id="nama_status" type="text" name="nama_status" value="{{ old('nama_status', $statusLaundry->nama_status) }}" placeholder="Contoh: Selesai" required>
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="/status-laundry" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
@endsection
