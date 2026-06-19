<form action="/log-aktivitas/{{ $log->id }}" method="POST">
    @csrf
    @method('PUT')

    <label>Nama Pengguna</label>
    <input type="text"
           name="nama_pengguna"
           value="{{ $log->nama_pengguna }}">

    <br><br>

    <label>Aktivitas</label>
    <input type="text"
           name="aktivitas"
           value="{{ $log->aktivitas }}">

    <br><br>

    <label>Waktu</label>
    <input type="datetime-local"
           name="waktu"
           value="{{ date('Y-m-d\TH:i', strtotime($log->waktu)) }}">

    <br><br>

    <button type="submit">
        Update
    </button>
</form>