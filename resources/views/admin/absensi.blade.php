@extends('admin.app')

@section('title', 'Kelola Absensi')

@section('content')
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Kelola Absensi</h2>
                <p class="text-gray-600 mt-1">Riwayat absensi semua pengguna</p>
            </div>
            <button onclick="refreshAbsensi()" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-sync-alt mr-2"></i>Refresh
            </button>
        </div>

        <!-- Legend -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h4 class="text-sm font-medium text-blue-900 mb-2">Keterangan Waktu:</h4>
            <div class="flex flex-wrap gap-4 text-xs">
                <div class="flex items-center">
                    <span class="text-green-600 font-medium">- 15m</span>
                    <span class="text-gray-600 ml-1">= 15 menit lebih awal</span>
                </div>
                <div class="flex items-center">
                    <span class="text-gray-500 font-medium">Tepat waktu</span>
                    <span class="text-gray-600 ml-1">= Sesuai jadwal</span>
                </div>
                <div class="flex items-center">
                    <span class="text-red-600 font-medium">+ 30m</span>
                    <span class="text-gray-600 ml-1">= 30 menit terlambat</span>
                </div>
                <div class="flex items-center">
                    <span class="text-orange-600 font-medium">- 1j 30m</span>
                    <span class="text-gray-600 ml-1">= Pulang 1j 30m lebih awal</span>
                </div>
                <div class="flex items-center">
                    <span class="text-blue-600 font-medium">+ 45m</span>
                    <span class="text-gray-600 ml-1">= Pulang 45 menit lembur</span>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-lg p-2 sm:p-3">
                        <i class="fas fa-check-circle text-green-600 text-lg sm:text-xl"></i>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Hadir</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ \App\Models\Absensi::whereDate('created_at', today())->where('status', 'hadir')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-2 sm:p-3">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-lg sm:text-xl"></i>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Terlambat</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ \App\Models\Absensi::whereDate('created_at', today())->where('status', 'terlambat')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-100 rounded-lg p-2 sm:p-3">
                        <i class="fas fa-times-circle text-red-600 text-lg sm:text-xl"></i>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Izin/Sakit</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ \App\Models\Absensi::whereDate('created_at', today())->where('status', 'izin')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-lg p-2 sm:p-3">
                        <i class="fas fa-calendar text-blue-600 text-lg sm:text-xl"></i>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Total Bulan Ini</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ \App\Models\Absensi::whereMonth('created_at', now()->month)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Absensi Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Riwayat Absensi</h3>
                <p class="text-sm text-gray-500 mt-1">Tabel menunjukkan lokasi check in/check out dan selisih waktu dari jam standar (Masuk 08:00, Pulang 17:00)</p>
            </div>
            <div class="overflow-x-auto">
                <div class="min-w-full">
                    <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengguna</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi Check In</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Out</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi Check Out</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Jam Kerja</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($absensi as $absen)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <i class="fas fa-user text-gray-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $absen->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $absen->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $absen->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($absen->check_in)
                                        <div class="text-sm text-gray-900">{{ $absen->check_in }}</div>
                                        @if($absen->check_in_diff)
                                            <div class="text-xs @if(str_starts_with($absen->check_in_diff, '+')) text-red-600 @elseif(str_starts_with($absen->check_in_diff, '-')) text-green-600 @else text-gray-500 @endif">
                                                ({{ $absen->check_in_diff }})
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($absen->check_in_location)
                                        <div class="flex items-center">
                                            <i class="fas fa-map-marker-alt mr-1 text-blue-500"></i>
                                            <span class="truncate max-w-xs">{{ $absen->check_in_location }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($absen->check_out)
                                        <div class="text-sm text-gray-900">{{ $absen->check_out }}</div>
                                        @if($absen->check_out_diff)
                                            <div class="text-xs @if(str_starts_with($absen->check_out_diff, '-')) text-orange-600 @elseif(str_starts_with($absen->check_out_diff, '+')) text-blue-600 @else text-gray-500 @endif">
                                                ({{ $absen->check_out_diff }})
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($absen->check_out_location)
                                        <div class="flex items-center">
                                            <i class="fas fa-map-marker-alt mr-1 text-blue-500"></i>
                                            <span class="truncate max-w-xs">{{ $absen->check_out_location }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($absen->total_jam)
                                        <div class="text-sm text-gray-900 font-medium">{{ number_format($absen->total_jam, 2) }} jam</div>
                                        <div class="text-xs text-gray-500">{{ floor($absen->total_jam) }}j {{ floor(($absen->total_jam - floor($absen->total_jam)) * 60) }}m</div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($absen->status == 'hadir') bg-green-100 text-green-800
                                        @elseif($absen->status == 'terlambat') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($absen->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($absen->keterangan)
                                        <div class="text-sm text-gray-900 max-w-xs">
                                            <p class="truncate" title="{{ $absen->keterangan }}">
                                                {{ $absen->keterangan }}
                                            </p>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="testDeleteClick({{ $absen->id }}, '{{ $absen->user->name }}', '{{ $absen->created_at->format('d M Y') }}')" class="text-red-600 hover:text-red-900 transition-colors p-2 rounded hover:bg-red-50">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-clock text-4xl mb-4"></i>
                                        <p class="text-lg font-medium">Belum ada data absensi</p>
                                        <p class="text-sm mt-1">Data absensi akan muncul di sini setelah pengguna melakukan check-in/check-out</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
            
            <!-- Pagination -->
            @if($absensi->hasPages())
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if($absensi->currentPage() > 1)
                            <a href="{{ $absensi->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Previous
                            </a>
                        @endif
                        
                        @if($absensi->hasMorePages())
                            <a href="{{ $absensi->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Next
                            </a>
                        @endif
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing
                                <span class="font-medium">{{ $absensi->firstItem() }}</span>
                                to
                                <span class="font-medium">{{ $absensi->lastItem() }}</span>
                                of
                                <span class="font-medium">{{ $absensi->total() }}</span>
                                results
                            </p>
                        </div>
                        <div>
                            {{ $absensi->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Hapus Data Absensi</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            Apakah Anda yakin ingin menghapus data absensi <span id="deleteUserName" class="font-medium"></span> pada tanggal <span id="deleteDate" class="font-medium"></span>?
                        </p>
                        <p class="text-xs text-red-600 mt-2">⚠️ Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                    <div class="mt-4 px-7 py-3">
                        <input type="password" id="adminPassword" placeholder="Masukkan password admin" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                    <div class="flex items-center px-4 py-3">
                        <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            Hapus
                        </button>
                        <button onclick="closeDeleteModal()" class="ml-3 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md w-24 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
@endsection

<script>
    // Global function - ensure this loads immediately
    function testDeleteClick(id, userName, date) {
        console.log('Delete button clicked!', { id, userName, date });
        
        // Open modal directly
        try {
            currentDeleteId = id;
            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('deleteDate').textContent = date;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('adminPassword').value = '';
            
            // Focus on password field
            setTimeout(() => {
                document.getElementById('adminPassword').focus();
            }, 100);
        } catch (error) {
            console.error('Error opening modal:', error);
            alert('Error: ' + error.message);
        }
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        currentDeleteId = null;
    }
    
    // Global variable
    let currentDeleteId = null;
    
    // Test if script is loaded
    console.log('Admin absensi script loaded successfully!');
    
    // Setup event listeners when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Confirm delete button
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            const password = document.getElementById('adminPassword').value;
            
            if (!password) {
                alert('Password admin harus diisi!');
                return;
            }

            const deleteButton = this;
            const originalText = deleteButton.innerHTML;
            
            // Disable button and show loading
            deleteButton.disabled = true;
            deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghapus...';

            console.log('Deleting absensi ID:', currentDeleteId);
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found');
                alert('CSRF token tidak ditemukan!');
                deleteButton.disabled = false;
                deleteButton.innerHTML = originalText;
                return;
            }

            // Send delete request
            const formData = new FormData();
            formData.append('password', password);
            formData.append('_method', 'DELETE');
            formData.append('_token', csrfToken.getAttribute('content'));

            fetch(`/admin/absensi/${currentDeleteId}`, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    showNotification('Data absensi berhasil dihapus', 'success');
                    closeDeleteModal();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert(data.error || 'Gagal menghapus data absensi');
                    deleteButton.disabled = false;
                    deleteButton.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                alert('Terjadi kesalahan saat menghapus data: ' + error.message);
                deleteButton.disabled = false;
                deleteButton.innerHTML = originalText;
            });
        });

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Handle Enter key in password field
        document.getElementById('adminPassword').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('confirmDeleteBtn').click();
            }
        });
        
        console.log('Event listeners setup completed!');
    });
    
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg text-white z-50 ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
</script>

<script>
    // Refresh absensi data
    function refreshAbsensi() {
        window.location.reload();
    }
</script>
