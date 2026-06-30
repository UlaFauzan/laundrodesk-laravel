@extends('layout')

@section('content')
    <h2>Notifikasi Saya</h2>

    @if ($notifications->isEmpty())
        <p>Belum ada notifikasi.</p>
    @else
        <div class="notification-list" style="display:grid; gap:16px; max-width:720px;">
            @foreach($notifications as $notification)
                <div class="card" style="padding:16px; border:1px solid rgba(0,0,0,.08); border-radius:16px; position:relative;">
                    <div style="display:flex; justify-content:space-between; gap:10px; align-items:flex-start;">
                        <div>
                            <p style="margin:0 0 8px; font-weight:700;">{{ $notification->pesan }}</p>
                            @if($notification->errorReport?->admin_note)
                                <p style="margin:0 0 8px; color:#374151;">Catatan admin: {{ $notification->errorReport->admin_note }}</p>
                            @endif
                            <p style="margin:0; color:var(--muted); font-size:.95rem;">{{ $notification->created_at->format('d M Y H:i') }}</p>
                        </div>
                        @if($notification->status_baca !== 'Sudah Dibaca')
                            <span style="background:#fee2e2; color:#b91c1c; padding:4px 10px; border-radius:999px; font-size:.85rem;">Baru</span>
                        @endif
                    </div>

                    @if($notification->status_baca !== 'Sudah Dibaca')
                        <form action="{{ route('pelanggan.notifications.read', $notification) }}" method="POST" style="margin-top:12px;">
                            @csrf
                            <button type="submit" class="btn btn-primary">Tandai Sudah Dibaca</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
@endsection
