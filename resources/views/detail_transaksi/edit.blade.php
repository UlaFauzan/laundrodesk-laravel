<h1>Edit Detail Transaksi</h1>

<form action="/detail-transaksi/{{ $detail->id }}" method="POST">

    @csrf
    @method('PUT')

    <label>ID Transaksi</label>
    <input type="number"
           name="transaksi_id"
           value="{{ $detail->transaksi_id }}">

    <br><br>

    <label>ID Layanan</label>
    <input type="number"
           name="layanan_id"
           value="{{ $detail->layanan_id }}">

    <br><br>

    <label>Berat (Kg)</label>
    <input type="number"
           step="0.01"
           name="berat_kg"
           value="{{ $detail->berat_kg }}">

    <br><br>

    <label>Subtotal</label>
    <input type="number"
           name="subtotal"
           value="{{ $detail->subtotal }}">

    <br><br>

    <button type="submit">
        Update
    </button>

</form>