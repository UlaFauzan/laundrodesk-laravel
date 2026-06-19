@extends('layout')

@section('content')
    <style>
        .log-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        .log-table th,
        .log-table td {
            border: 1px solid #dddddd;
            padding: 10px;
            text-align: left;
        }

        .log-table th {
            background: #444444;
            color: #ffffff;
        }

        .log-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .btn-delete {
            background: #dc3545;
            border: none;
            border-radius: 4px;
            color: #ffffff;
            cursor: pointer;
            padding: 6px 12px;
        }

        .btn-delete:hover {
            background: #bb2d3b;
        }

        .empty-message {
            color: #888888;
            text-align: center;
            padding: 24px;
        }
    </style>

    <h1>Data Log Aktivitas Pelanggan</h1>

    <table class="log-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pelanggan</th>
                <th>Aktivitas</th>
                <th>Waktu</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($log as $l)
            <tr>
                <td>{{ $l->id }}</td>
                <td>{{ $l->nama_pengguna }}</td>
                <td>{{ $l->aktivitas }}</td>
                <td>{{ $l->waktu ? $l->waktu->format('d M Y H:i') : '-' }}</td>
                <td>
                    <form action="{{ route('log-aktivitas.destroy', $l->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus log ini?')" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="empty-message">Tidak ada data log aktivitas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection
