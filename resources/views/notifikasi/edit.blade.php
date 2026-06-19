<h1>Edit Notifikasi</h1>

<form action="/notifikasi/{{ $notifikasi->id }}" method="POST">
    @csrf
    @method('PUT')

    <label>Pelanggan</label>

    <select name="pelanggan_id">
        @foreach($pelanggan as $p)
            <option value="{{ $p->id }}"
            {{ $notifikasi->pelanggan_id == $p->id ? 'selected' : '' }}>
                {{ $p->nama }}
            </option>
        @endforeach
    </select>

    <br><br>

    <label>Pesan</label>

    <textarea name="pesan">{{ $notifikasi->pesan }}</textarea>

    <br><br>

    <label>Status</label>

    <select name="status_baca">
        <option {{ $notifikasi->status_baca == 'Belum Dibaca' ? 'selected' : '' }}>
            Belum Dibaca
        </option>

        <option {{ $notifikasi->status_baca == 'Sudah Dibaca' ? 'selected' : '' }}>
            Sudah Dibaca
        </option>
    </select>

    <br><br>

    <button type="submit">
        Update
    </button>
</form>