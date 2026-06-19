<h1>Edit Pelanggan</h1>

<form action="/pelanggan/{{ $pelanggan->id }}" method="POST">
    @csrf
    @method('PUT')

    <label>Nama</label>
    <input type="text" name="nama"
        value="{{ $pelanggan->nama }}">

    <br><br>

    <label>Telepon</label>
    <input type="text" name="telepon"
        value="{{ $pelanggan->telepon }}">

    <br><br>

    <label>Alamat</label>
    <textarea name="alamat">{{ $pelanggan->alamat }}</textarea>

    <br><br>

    <button type="submit">
        Update
    </button>
</form>