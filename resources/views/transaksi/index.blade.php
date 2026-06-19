<h1>Data Transaksi</h1>

<a href="/transaksi/create">
    Tambah Transaksi
</a>

<table border="1">
    <tr>
        <th>No</th>
        <th>Pelanggan</th>
        <th>Layanan</th>
        <th>Berat</th>
        <th>Total Harga</th>
        <th>Pembayaran</th>
        <th>Status</th>
        <th>Tanggal Masuk</th>
        <th>Tanggal Selesai</th>
        <th>Aksi</th>
    </tr>

    @foreach($transaksi as $t)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $t->pelanggan->nama }}</td>
        <td>{{ $t->layanan->nama_layanan }}</td>
        <td>{{ $t->berat_kg }}</td>
        <td>{{ number_format($t->total_harga, 0, ',', '.') }}</td>
        <td>
            @if($t->pembayaran)
                @php
                    $sisa = $t->total_harga - $t->pembayaran->jumlah_bayar;
                @endphp
                @if($sisa > 0)
                    <strong style="color: #ea580c;">Kurang Bayar</strong><br>
                    Rp{{ number_format($t->pembayaran->jumlah_bayar, 0, ',', '.') }}<br>
                    <small style="color: #b91c1c;">Sisa: Rp{{ number_format($sisa, 0, ',', '.') }}</small><br>
                    <small>{{ $t->pembayaran->metode_pembayaran }} / hutang</small>
                @else
                    <strong style="color: #059669;">Sudah Bayar</strong><br>
                    Rp{{ number_format($t->pembayaran->jumlah_bayar, 0, ',', '.') }}<br>
                    <small>{{ $t->pembayaran->metode_pembayaran }} / {{ $t->pembayaran->status_pembayaran }}</small>
                @endif
            @else
                <strong style="color: #b91c1c;">Belum Bayar</strong>
            @endif
        </td>
        <td>
            @php
                $statusPembayaran = $t->getStatusPembayaranSebenarnya();
            @endphp
            @if($statusPembayaran === 'hutang')
                <span style="color: #ea580c; font-weight: bold;">{{ $statusPembayaran }}</span>
            @else
                {{ $t->statusLaundry?->nama_status ?? $t->status }}
            @endif
        </td>
        <td>{{ $t->tanggal_masuk }}</td>
        <td>{{ $t->tanggal_selesai }}</td>

        <td>
            <a href="/transaksi/{{ $t->id }}/edit">
                Edit
            </a>

            @if ($t->pembayaran)
                @php
                    $sisa = $t->total_harga - $t->pembayaran->jumlah_bayar;
                @endphp
                @if ($sisa > 0)
                    <a href="/pembayaran/{{ $t->pembayaran->id }}/edit">
                        Bayar Hutang
                    </a>
                @endif
            @else
                <a href="/transaksi/{{ $t->id }}/edit">
                    Bayar
                </a>
            @endif

            <form action="/transaksi/{{ $t->id }}" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit">
                    Hapus
                </button>
            </form>
        </td>
    </tr>
    @endforeach

</table>
