@extends('layout')

@section('title', 'Edit Notifikasi')

@section('content')
    <div class="page-header">
        <div>
            <h1>Edit Notifikasi</h1>
            <p>Perbarui pesan dan status baca notifikasi pelanggan.</p>
        </div>
    </div>

    <form class="form-card" action="/notifikasi/{{ $notifikasi->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="field">
            <label for="pelanggan_id">Pelanggan</label>
            <select id="pelanggan_id" name="pelanggan_id">
                @foreach($pelanggan as $p)
                    <option value="{{ $p->id }}" {{ old('pelanggan_id', $notifikasi->pelanggan_id) == $p->id ? 'selected' : '' }}>
                        {{ $p->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="field">
            <label for="pesan">Pesan</label>
            <textarea id="pesan" name="pesan">{{ old('pesan', $notifikasi->pesan) }}</textarea>
        </div>

        <div class="field">
            <label for="status_baca">Status</label>
            <select id="status_baca" name="status_baca">
                <option {{ old('status_baca', $notifikasi->status_baca) == 'Belum Dibaca' ? 'selected' : '' }}>Belum Dibaca</option>
                <option {{ old('status_baca', $notifikasi->status_baca) == 'Sudah Dibaca' ? 'selected' : '' }}>Sudah Dibaca</option>
            </select>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="/notifikasi" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
@endsection
