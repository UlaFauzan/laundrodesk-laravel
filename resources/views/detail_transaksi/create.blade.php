<h1>Tambah Detail Transaksi</h1>

<form action="/detail-transaksi" method="POST">

    @csrf

    <label>ID Transaksi</label>
    <input type="number" name="transaksi_id">

    <br><br>

    <label>ID Layanan</label>
    <input type="number" name="layanan_id">

    <br><br>

    <label>Berat (Kg)</label>
    <input type="number" step="0.01" name="berat_kg">

    <br><br>

    <label>Subtotal</label>
    <input type="number" name="subtotal">

    <br><br>

    <button type="submit">
        Simpan
    </button>

</form>