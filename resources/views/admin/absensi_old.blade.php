<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Absensi - Admin CatatanKerja</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    <!-- Main Navigation -->
    <nav class="bg-white shadow-sm border-b sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo & Title -->
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-red-600 rounded-lg p-2">
                            <i class="fas fa-shield-alt text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <h1 class="text-lg sm:text-xl font-bold text-gray-900">Admin Panel</h1>
                        <p class="text-xs text-gray-500">CatatanKerja Management System</p>
                    </div>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden lg:flex lg:items-center lg:space-x-8">
                    <a href="/admin" class="py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium flex items-center">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                    
                    <!-- Users Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium flex items-center transition-colors">
                            <i class="fas fa-users mr-2"></i>Pengguna
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="absolute top-full left-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                            <div class="py-1">
                                <a href="/admin/users" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-list mr-2"></i>Daftar Pengguna
                                </a>
                                <a href="/admin/users/create" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-plus mr-2"></i>Tambah Pengguna
                                </a>
                            </div>
                        </div>
                    </div>

                    <a href="/admin/absensi" class="py-4 px-1 border-b-2 border-red-500 text-red-600 font-medium flex items-center">
                        <i class="fas fa-clock mr-2"></i>Absensi
                    </a>
                    
                    <!-- Reports Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium flex items-center transition-colors">
                            <i class="fas fa-file-alt mr-2"></i>Laporan
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="absolute top-full left-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                            <div class="py-1">
                                <a href="/admin/reports" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-list mr-2"></i>Daftar Laporan
                                </a>
                                <a href="/admin/reports/drafts" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-edit mr-2"></i>Draft Laporan
                                </a>
                            </div>
                        </div>
                    </div>

                    <a href="/admin/visit-schedules" class="py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i>Jadwal Kunjungan
                    </a>
                    
                    <!-- Settings Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium flex items-center transition-colors">
                            <i class="fas fa-cog mr-2"></i>Pengaturan
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="absolute top-full left-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                            <div class="py-1">
                                <a href="/admin/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-cog mr-2"></i>Umum
                                </a>
                                <a href="/admin/settings/company" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-building mr-2"></i>Perusahaan
                                </a>
                                <a href="/admin/settings/system" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-server mr-2"></i>Sistem
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center space-x-3 border-l border-gray-200 pl-4">
                        <div class="flex items-center space-x-3">
                            <div class="bg-red-100 rounded-full p-2">
                                <i class="fas fa-user-shield text-red-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ session('user')['name'] }}</p>
                                <p class="text-xs text-gray-500">Administrator</p>
                            </div>
                        </div>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <div class="lg:hidden flex items-center space-x-2">
                    <button onclick="toggleMobileMenu()" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div id="mobileMenu" class="hidden lg:hidden bg-white border-t">
                <div class="px-4 py-3 space-y-2">
                    <a href="/admin" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                    
                    <!-- Mobile Users Section -->
                    <div class="space-y-1">
                        <div class="py-2 px-3 text-sm font-medium text-gray-500">Pengguna</div>
                        <a href="/admin/users" class="block py-2 px-3 pl-6 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-list mr-2"></i>Daftar Pengguna
                        </a>
                        <a href="/admin/users/create" class="block py-2 px-3 pl-6 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2"></i>Tambah Pengguna
                        </a>
                    </div>

                    <a href="/admin/absensi" class="block py-2 px-3 text-red-600 bg-red-50 rounded-lg transition-colors">
                        <i class="fas fa-clock mr-2"></i>Absensi
                    </a>
                    
                    <!-- Mobile Reports Section -->
                    <div class="space-y-1">
                        <div class="py-2 px-3 text-sm font-medium text-gray-500">Laporan</div>
                        <a href="/admin/reports" class="block py-2 px-3 pl-6 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-list mr-2"></i>Daftar Laporan
                        </a>
                        <a href="/admin/reports/drafts" class="block py-2 px-3 pl-6 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-edit mr-2"></i>Draft Laporan
                        </a>
                    </div>

                    <a href="/admin/visit-schedules" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-calendar-alt mr-2"></i>Jadwal Kunjungan
                    </a>
                    
                    <!-- Mobile Settings Section -->
                    <div class="space-y-1">
                        <div class="py-2 px-3 text-sm font-medium text-gray-500">Pengaturan</div>
                        <a href="/admin/settings" class="block py-2 px-3 pl-6 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-cog mr-2"></i>Umum
                        </a>
                        <a href="/admin/settings/company" class="block py-2 px-3 pl-6 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-building mr-2"></i>Perusahaan
                        </a>
                        <a href="/admin/settings/system" class="block py-2 px-3 pl-6 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-server mr-2"></i>Sistem
                        </a>
                    </div>

                    <!-- Mobile User Section -->
                    <div class="border-t border-gray-200 pt-3 mt-3">
                        <div class="flex items-center space-x-3 pb-3">
                            <div class="bg-red-100 rounded-full p-2">
                                <i class="fas fa-user-shield text-red-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ session('user')['name'] }}</p>
                                <p class="text-xs text-gray-500">Administrator</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full text-left text-red-600 hover:text-red-800 px-3 py-2 rounded-lg hover:bg-red-50 transition-colors">
                                <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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

        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Kelola Absensi</h2>
            <p class="text-gray-600 mt-1">Riwayat absensi semua pengguna</p>
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
                                        <span class="text-grcheckay-400">-</span>
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
                                                {!! str_replace('|', '<br>', e($absen->keterangan)) !!}
                                            </p>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="confirmDelete({{ $absen->id }}, '{{ $absen->user->name }}', '{{ $absen->created_at->format('d M Y') }}')" class="text-red-600 hover:text-red-900 transition-colors">
                                        <i class="fas fa-trash-alt mr-1"></i>Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-4 text-center text-gray-500">
                                    <i class="fas fa-clock mb-2"></i>
                                    <p>Belum ada data absensi</p>
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
                    {{ $absensi->links() }}
                </div>
            @endif
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 text-center">Konfirmasi Hapus</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 text-center">
                        Apakah Anda yakin ingin menghapus data absensi ini?
                    </p>
                    <div class="mt-3 text-sm text-gray-600 bg-gray-50 p-3 rounded">
                        <p><strong>Pengguna:</strong> <span id="deleteUserName"></span></p>
                        <p><strong>Tanggal:</strong> <span id="deleteDate"></span></p>
                    </div>
                    <div class="mt-4">
                        <label for="adminPassword" class="block text-sm font-medium text-gray-700 mb-1">
                            Password Admin <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="adminPassword" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan password admin">
                        <p class="text-xs text-gray-500 mt-1">Masukkan password admin untuk konfirmasi</p>
                    </div>
                </div>
                <div class="flex justify-center space-x-3 mt-4">
                    <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        Batal
                    </button>
                    <button onclick="deleteAttendance()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-trash-alt mr-2"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let deleteId = null;

        function confirmDelete(id, userName, date) {
            deleteId = id;
            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('deleteDate').textContent = date;
            document.getElementById('adminPassword').value = '';
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            deleteId = null;
            document.getElementById('adminPassword').value = '';
        }

        async function deleteAttendance() {
            const password = document.getElementById('adminPassword').value;
            
            if (!password) {
                alert('Password admin harus diisi!');
                return;
            }

            if (!deleteId) {
                alert('ID tidak valid!');
                return;
            }

            // Show loading state
            const deleteButton = document.querySelector('#deleteModal button[onclick="deleteAttendance()"]');
            const originalText = deleteButton.innerHTML;
            deleteButton.disabled = true;
            deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghapus...';

            try {
                const response = await fetch('/admin/absensi/' + deleteId, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        password: password
                    })
                });

                const data = await response.json();

                if (data.success) {
                    closeDeleteModal();
                    // Show success message
                    showNotification(data.message || 'Data absensi berhasil dihapus!', 'success');
                    // Reload page after 1 second
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert(data.error || 'Gagal menghapus data absensi');
                    // Restore button
                    deleteButton.disabled = false;
                    deleteButton.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Delete error:', error);
                alert('Terjadi kesalahan saat menghapus data');
                // Restore button
                deleteButton.disabled = false;
                deleteButton.innerHTML = originalText;
            }
        }

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

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Handle Enter key in password field
        document.getElementById('adminPassword').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                deleteAttendance();
            }
        });
    </script>
</body>
</html>
