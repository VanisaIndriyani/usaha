<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Monitoring</div>
                <div class="text-xl font-bold tracking-tight text-brand-navy dark:text-white">Activity Log</div>
            </div>
            <form method="GET" action="{{ route('activity-logs.index') }}" class="flex items-center gap-2">
                <input name="q" value="{{ $q }}" placeholder="Cari aktivitas..." class="input w-64" />
                <button class="btn-ghost" type="submit">Cari</button>
            </form>
        </div>
    </x-slot>

    <div class="glass-card">
        <div class="card-header">
            <div>
                <div class="text-sm font-semibold text-black/55 dark:text-white/60">Log</div>
                <div class="text-lg font-bold tracking-tight text-brand-navy dark:text-white">Aktivitas Terbaru</div>
            </div>
            <span class="badge-gold">{{ $logs->total() }}</span>
        </div>
        <div class="card-body">
            <div class="table-modern">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Aksi</th>
                            <th>Subjek</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td class="text-black/55 dark:text-white/60">{{ $log->created_at->format('d M Y H:i') }}</td>
                                <td class="font-semibold">{{ $log->user?->name ?? 'System' }}</td>
                                <td class="font-bold">{{ $log->action }}</td>
                                <td class="text-black/55 dark:text-white/60">
                                    {{ $log->subject_type }}
                                    @if ($log->subject_id)
                                        #{{ $log->subject_id }}
                                    @endif
                                </td>
                                <td class="text-black/55 dark:text-white/60">{{ $log->ip_address }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-sm text-black/50 dark:text-white/60">
                                    Belum ada aktivitas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

