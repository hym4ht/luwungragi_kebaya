<x-layouts.admin title="Data Pelanggan">
    <x-page-header title="Pelanggan Terdaftar" subtitle="Pantau jumlah transaksi dan pelanggan aktif yang menggunakan layanan sewa Luwungragi.">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark rounded-pill px-4">Dashboard Admin</a>
    </x-page-header>

    <div class="content-panel">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Total Rental</th>
                        <th>Masih Aktif</th>
                        <th>Selesai</th>
                        <th>Total Belanja</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td class="fw-semibold">{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->rentals_count }}</td>
                            <td>{{ $customer->active_rentals_count }}</td>
                            <td>{{ $customer->completed_rentals_count }}</td>
                            <td>Rp{{ number_format((float) ($customer->total_spent ?? 0), 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state text-center">
                                    <h3 class="h4 mb-2">Belum ada pelanggan</h3>
                                    <p class="text-muted mb-0">Akun penyewa yang terdaftar akan muncul di tabel ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
