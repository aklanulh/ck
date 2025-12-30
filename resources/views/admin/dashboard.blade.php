@extends('admin.app')

@section('title', 'Admin Dashboard')

@section('content')
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-lg p-2 sm:p-3">
                        <i class="fas fa-users text-blue-600 text-lg sm:text-xl"></i>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Total Pengguna</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-lg p-2 sm:p-3">
                        <i class="fas fa-clock text-green-600 text-lg sm:text-xl"></i>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Total Absensi</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $totalAbsensi }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 rounded-lg p-2 sm:p-3">
                        <i class="fas fa-file-alt text-purple-600 text-lg sm:text-xl"></i>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Total Laporan</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $totalReports }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-2 sm:p-3">
                        <i class="fas fa-calendar-check text-yellow-600 text-lg sm:text-xl"></i>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Jadwal Kunjungan</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $totalVisitSchedules }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Attendance Summary -->
        <div class="bg-white rounded-lg shadow mb-6 sm:mb-8">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h3 class="text-base sm:text-lg font-medium text-gray-900">Ringkasan Absensi Hari Ini</h3>
            </div>
            <div class="p-4 sm:p-6">
                <div class="grid grid-cols-3 gap-3 sm:gap-6">
                    <div class="text-center">
                        <div class="text-2xl sm:text-3xl font-bold text-green-600">{{ $hadirToday }}</div>
                        <div class="text-xs sm:text-sm text-gray-500 mt-1">Hadir</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl sm:text-3xl font-bold text-yellow-600">{{ $terlambatToday }}</div>
                        <div class="text-xs sm:text-sm text-gray-500 mt-1">Terlambat</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl sm:text-3xl font-bold text-red-600">{{ $izinToday }}</div>
                        <div class="text-xs sm:text-sm text-gray-500 mt-1">Izin/Sakit</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
            <!-- Recent Absensi -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900">Absensi Terbaru</h3>
                    <a href="/admin/absensi" class="text-xs sm:text-sm text-blue-600 hover:text-blue-800 float-right">Lihat semua</a>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($recentAbsensi as $absen)
                        <div class="p-3 sm:p-4">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $absen->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $absen->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    @if($absen->status == 'hadir') bg-green-100 text-green-800
                                    @elseif($absen->status == 'terlambat') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($absen->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-500">
                            <i class="fas fa-clock mb-2"></i>
                            <p>Belum ada data absensi</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Reports -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900">Laporan Terbaru</h3>
                    <a href="/admin/reports" class="text-xs sm:text-sm text-blue-600 hover:text-blue-800 float-right">Lihat semua</a>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($recentReports as $report)
                        <div class="p-3 sm:p-4">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $report->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $report->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    @if($report->status == 'draft') bg-gray-100 text-gray-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-500">
                            <i class="fas fa-file-alt mb-2"></i>
                            <p>Belum ada data laporan</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobileMenu');
            const menuButton = event.target.closest('button[onclick="toggleMobileMenu()"]');
            
            if (!menu.contains(event.target) && !menuButton && !menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
            }
        });
    </script>
@endsection
