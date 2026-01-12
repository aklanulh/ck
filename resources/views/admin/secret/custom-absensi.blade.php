@extends('admin.secret-layout')

@section('title', 'Custom Absensi - Admin Secret')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-gray-900">
                                Admin
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('admin.secret.dashboard') }}" class="ml-1 text-gray-700 hover:text-gray-900 md:ml-2">
                                    Secret Panel
                                </a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-gray-500 md:ml-2 font-medium">Custom Absensi</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold text-gray-900">⚡ Custom Riwayat Absensi</h1>
                <p class="text-gray-600">Atur riwayat absensi dengan cepat dan efisien</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Form Section -->
                <div class="lg:col-span-1">
                    <!-- Single Absensi Form -->
                    <div class="bg-white shadow rounded-lg mb-6">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Absensi Single</h3>
                            <form id="singleAbsensiForm">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Pengguna</label>
                                        <select name="user_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                            <option value="">Pilih Pengguna</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                                        <input type="date" name="tanggal" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Jam Masuk</label>
                                            <input type="time" name="jam_masuk" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Jam Keluar</label>
                                            <input type="time" name="jam_keluar" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Status</label>
                                        <select name="status" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                            <option value="hadir">🟢 Hadir</option>
                                            <option value="izin">🟡 Izin</option>
                                            <option value="sakit">🔴 Sakit</option>
                                            <option value="cuti">🔵 Cuti</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                        <textarea name="keterangan" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Opsional"></textarea>
                                    </div>
                                    
                                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        ➕ Tambah Absensi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Bulk Absensi Form -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Bulk Absensi</h3>
                            <form id="bulkAbsensiForm">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Pilih Pengguna (Multiple)</label>
                                        <div class="mt-1 max-h-32 overflow-y-auto border border-gray-300 rounded-md">
                                            @foreach($users as $user)
                                                <label class="flex items-center p-2 hover:bg-gray-50 cursor-pointer">
                                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    <span class="ml-2 text-sm text-gray-700">{{ $user->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                                        <input type="date" name="tanggal" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Jam Masuk</label>
                                            <input type="time" name="jam_masuk" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Jam Keluar</label>
                                            <input type="time" name="jam_keluar" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Status</label>
                                        <select name="status" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                            <option value="hadir">🟢 Hadir</option>
                                            <option value="izin">🟡 Izin</option>
                                            <option value="sakit">🔴 Sakit</option>
                                            <option value="cuti">🔵 Cuti</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                        <textarea name="keterangan" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Opsional"></textarea>
                                    </div>
                                    
                                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        🚀 Bulk Tambah
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Monthly Attendance Generator -->
                    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 shadow rounded-lg text-white">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium mb-4">🎯 Generator Absensi 1 Bulan (1 Klik)</h3>
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 mb-4">
                                <p class="text-sm text-white/90 mb-2">
                                    <strong>Default Settings:</strong>
                                </p>
                                <ul class="text-xs text-white/80 space-y-1">
                                    <li>📍 Lokasi: Kota Wisata, Limusnunggal, Cileungsi, Bogor, Jawa Barat</li>
                                    <li>🕐 Check-in: 07:30 - 07:55 (random)</li>
                                    <li>🕕 Check-out: 17:00 - 18:00 (random)</li>
                                    <li>📊 Status: 90% Hadir, 10% Izin (random)</li>
                                    <li>🗓️ Skip weekend (Sabtu & Minggu)</li>
                                </ul>
                            </div>
                            <form id="monthlyGeneratorForm">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-white">Pilih Pengguna</label>
                                        <select name="user_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md text-gray-900">
                                            <option value="">Pilih Pengguna</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-white">Bulan & Tahun</label>
                                        <input type="month" name="year_month" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900">
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="checkbox" id="excludeWeekends" name="exclude_weekends" value="1" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <label for="excludeWeekends" class="ml-2 text-sm text-white">
                                            Skip weekend (Sabtu & Minggu)
                                        </label>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="checkbox" id="forceOverride" name="force_override" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <label for="forceOverride" class="ml-2 text-sm text-white">
                                            🔥 Force Override (update data yang sudah ada)
                                        </label>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="checkbox" id="includeIzin" name="include_izin" value="1" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <label for="includeIzin" class="ml-2 text-sm text-white">
                                            📄 Tambahkan Izin (random)
                                        </label>
                                    </div>
                                    
                                    <div id="izinSettings" class="space-y-2 pl-6">
                                        <div>
                                            <label class="block text-sm font-medium text-white/90">Persentase Izin (%)</label>
                                            <select name="izin_percentage" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900">
                                                <option value="5">5% (Sangat Jarang)</option>
                                                <option value="10" selected>10% (Jarang)</option>
                                                <option value="15">15% (Kadang)</option>
                                                <option value="20">20% (Sering)</option>
                                                <option value="25">25% (Sering Sekali)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-white/90">Alasan Izin</label>
                                            <select name="izin_reason" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900">
                                                <option value="random">🎲 Random Acak</option>
                                                <option value="sakit">😷 Sakit</option>
                                                <option value="izin">📝 Izin Pribadi</option>
                                                <option value="cuti">🏖️ Cuti</option>
                                                <option value="dinas_luar">🚗 Dinas Luar</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transform transition hover:scale-105">
                                        ⚡ Generate 1 Bulan (1 Klik)
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Data Section -->
                <div class="lg:col-span-2">
                    <!-- Recent Absensi -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Riwayat Absensi Terbaru</h3>
                                <button onclick="refreshData()" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    🔄 Refresh
                                </button>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tanggal
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Pengguna
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Jam
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="absensiTableBody" class="bg-white divide-y divide-gray-200">
                                        @foreach($absensi as $absen)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ \Carbon\Carbon::parse($absen->tanggal)->format('d/m/Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $absen->user->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $absen->user->email }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @if($absen->check_in && $absen->check_out)
                                                        {{ $absen->check_in }} - {{ $absen->check_out }}
                                                    @elseif($absen->check_in)
                                                        {{ $absen->check_in }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @if($absen->status == 'hadir') bg-green-100 text-green-800
                                                        @elseif($absen->status == 'izin') bg-yellow-100 text-yellow-800
                                                        @elseif($absen->status == 'sakit') bg-red-100 text-red-800
                                                        @else bg-blue-100 text-blue-800 @endif">
                                                        {{ ucfirst($absen->status) }}
                                                    </span>
                                                    @if($absen->created_by_admin)
                                                        <span class="ml-1 text-xs text-purple-600">👨‍💻</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <button onclick="editAbsensi({{ $absen->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                        ✏️ Edit
                                                    </button>
                                                    <button onclick="deleteAbsensi({{ $absen->id }})" class="text-red-600 hover:text-red-900">
                                                        🗑️ Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-6">
                <a href="{{ route('admin.secret.dashboard') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Dashboard Secret
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Absensi</h3>
            <form id="editAbsensiForm">
                @csrf
                <input type="hidden" id="editAbsensiId" name="id">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jam Masuk</label>
                            <input type="time" id="editJamMasuk" name="jam_masuk" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jam Keluar</label>
                            <input type="time" id="editJamKeluar" name="jam_keluar" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="editStatus" name="status" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="hadir">🟢 Hadir</option>
                            <option value="izin">🟡 Izin</option>
                            <option value="sakit">🔴 Sakit</option>
                            <option value="cuti">🔵 Cuti</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                        <textarea id="editKeterangan" name="keterangan" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    </div>
                    
                    <div class="flex space-x-3">
                        <button type="submit" class="flex-1 flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            💾 Simpan
                        </button>
                        <button type="button" onclick="closeEditModal()" class="flex-1 flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Batal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Single Absensi Form
document.getElementById('singleAbsensiForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('/admin/secret/custom-absensi', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Absensi berhasil ditambahkan!');
            this.reset();
            refreshData();
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambah absensi');
    });
});

// Bulk Absensi Form
document.getElementById('bulkAbsensiForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Check if at least one user is selected
    const checkboxes = this.querySelectorAll('input[name="user_ids[]"]:checked');
    if (checkboxes.length === 0) {
        alert('Pilih minimal satu pengguna!');
        return;
    }
    
    const formData = new FormData(this);
    
    fetch('/admin/secret/bulk-absensi', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            this.reset();
            refreshData();
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambah bulk absensi');
    });
});

function refreshData() {
    location.reload();
}

// Monthly Generator Form
document.getElementById('monthlyGeneratorForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Show loading
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '⏳ Generating...';
    submitBtn.disabled = true;
    
    fetch('/admin/secret/generate-monthly', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show detailed summary
            let message = data.message + '\n\n';
            message += '📊 Summary:\n';
            message += `👤 User: ${data.summary.user}\n`;
            message += `📅 Month: ${data.summary.month}\n`;
            message += `📆 Working Days: ${data.summary.total_working_days}\n`;
            message += `✅ Created: ${data.summary.created}\n`;
            message += `🔄 Updated: ${data.summary.updated || 0}\n`;
            message += `⏭️ Skipped: ${data.summary.skipped}\n`;
            message += `🗓️ Weekends Excluded: ${data.summary.exclude_weekends ? 'Yes' : 'No'}\n`;
            message += `🔥 Force Override: ${data.summary.force_override ? 'Yes' : 'No'}`;
            
            alert(message);
            this.reset();
            refreshData();
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat generate absensi bulanan');
    })
    .finally(() => {
        // Restore button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

function editAbsensi(id) {
    // Fetch absensi data
    fetch(`/admin/secret/custom-absensi/${id}/edit-data`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('editAbsensiId').value = data.data.id;
                document.getElementById('editJamMasuk').value = data.data.check_in || '';
                document.getElementById('editJamKeluar').value = data.data.check_out || '';
                document.getElementById('editStatus').value = data.data.status;
                document.getElementById('editKeterangan').value = data.data.keterangan || '';
                
                document.getElementById('editModal').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengambil data absensi');
        });
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

// Edit Absensi Form
document.getElementById('editAbsensiForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('editAbsensiId').value;
    const formData = new FormData(this);
    
    fetch(`/admin/secret/custom-absensi/${id}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Absensi berhasil diperbarui!');
            closeEditModal();
            refreshData();
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memperbarui absensi');
    });
});

function deleteAbsensi(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus data absensi ini?')) {
        return;
    }
    
    fetch(`/admin/secret/custom-absensi/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Absensi berhasil dihapus!');
            refreshData();
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus absensi');
    });
}

// Toggle Izin Settings visibility
document.getElementById('includeIzin').addEventListener('change', function() {
    const izinSettings = document.getElementById('izinSettings');
    if (this.checked) {
        izinSettings.style.display = 'block';
    } else {
        izinSettings.style.display = 'none';
    }
});

// Initialize izin settings visibility
document.addEventListener('DOMContentLoaded', function() {
    const includeIzinCheckbox = document.getElementById('includeIzin');
    const izinSettings = document.getElementById('izinSettings');
    
    if (includeIzinCheckbox.checked) {
        izinSettings.style.display = 'block';
    } else {
        izinSettings.style.display = 'none';
    }
});
</script>
@endsection
