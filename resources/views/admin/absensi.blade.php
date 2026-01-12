@extends('admin.app')

@section('title', 'Kelola Absensi')

@section('content')
        <!-- Header -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Kelola Absensi</h2>
                    <p class="text-gray-600 mt-1">Riwayat absensi dan pengajuan izin semua pengguna</p>
                </div>
                <div class="flex gap-2">
                    <button onclick="refreshAbsensi()" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                </div>
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
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Riwayat Absensi</h3>
                        <p class="text-sm text-gray-500 mt-1">Tabel menunjukkan lokasi check in/check out dan selisih waktu dari jam standar (Masuk 08:00, Pulang 17:00)</p>
                        <p class="text-xs text-purple-600 mt-1"><i class="fas fa-info-circle mr-1"></i>Klik pada baris karyawan untuk melihat grafik absensi bulanan</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.absensi', ['date' => $previousDate]) }}" class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fas fa-chevron-left mr-2"></i>
                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $previousDate)->format('d M') }}
                        </a>
                        <div class="px-4 py-2 bg-purple-600 text-white rounded-lg font-medium">
                            {{ $dateObj->format('d F Y') }}
                            @if($date == now()->format('Y-m-d'))
                                <span class="ml-2 text-xs bg-purple-700 px-2 py-1 rounded">Hari Ini</span>
                            @endif
                        </div>
                        <a href="{{ route('admin.absensi', ['date' => $nextDate]) }}" class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $nextDate)->format('d M') }}
                            <i class="fas fa-chevron-right ml-2"></i>
                        </a>
                        @if($date != now()->format('Y-m-d'))
                            <a href="{{ route('admin.absensi') }}" class="inline-flex items-center px-3 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors">
                                <i class="fas fa-calendar-day mr-2"></i>
                                Hari Ini
                            </a>
                        @endif
                    </div>
                </div>
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
                            <tr class="hover:bg-gray-50 cursor-pointer transition-colors" onclick="showAttendanceChart({{ $absen->user_id }})">
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
                                    <button onclick="event.stopPropagation(); testDeleteClick({{ $absen->id }}, '{{ $absen->user->name }}', '{{ $absen->created_at->format('d M Y') }}')" class="text-red-600 hover:text-red-900 transition-colors p-2 rounded hover:bg-red-50">
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
        </div>
        </div>

        <!-- Izin Section -->
        <div class="mt-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Pengajuan Izin</h3>
                            <p class="text-sm text-gray-500 mt-1">Kelola persetujuan surat izin, cuti, dan sakit</p>
                        </div>
                        <div class="flex space-x-2">
                            <select id="filterStatus" onchange="filterIzin()" class="px-3 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="">Semua Status</option>
                                <option value="pending">Menunggu</option>
                                <option value="approved">Disetujui</option>
                                <option value="rejected">Ditolak</option>
                            </select>
                            <select id="filterJenis" onchange="filterIzin()" class="px-3 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="">Semua Jenis</option>
                                <option value="izin">Izin</option>
                                <option value="cuti">Cuti</option>
                                <option value="sakit">Sakit</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <div class="min-w-full">
                        <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengguna</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Izin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alasan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diajukan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="izinTableBody">
                            @forelse($izin as $item)
                                <tr class="hover:bg-gray-50 cursor-pointer transition-colors" onclick="showIzinDetail({{ $item->id }})">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-600"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $item->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($item->jenis_izin == 'izin') bg-blue-100 text-blue-800
                                            @elseif($item->jenis_izin == 'cuti') bg-green-100 text-green-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($item->jenis_izin) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->tanggal_mulai->format('d M Y') }} - {{ $item->tanggal_selesai->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs">
                                            <p class="truncate" title="{{ $item->alasan }}">
                                                {{ $item->alasan }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($item->bukti_path)
                                            <button onclick="viewBukti('{{ $item->bukti_path }}')" class="text-blue-600 hover:text-blue-900 transition-colors">
                                                <i class="fas fa-file-alt mr-1"></i>Lihat
                                            </button>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($item->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($item->status == 'approved') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($item->status == 'pending')
                                            <button onclick="event.stopPropagation(); approveIzin({{ $item->id }})" class="text-green-600 hover:text-green-900 transition-colors p-2 rounded hover:bg-green-50 mr-2">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button onclick="event.stopPropagation(); rejectIzin({{ $item->id }})" class="text-red-600 hover:text-red-900 transition-colors p-2 rounded hover:bg-red-50">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <i class="fas fa-file-alt text-4xl mb-4"></i>
                                            <p class="text-lg font-medium">Belum ada data izin</p>
                                            <p class="text-sm mt-1">Data pengajuan izin akan muncul di sini setelah pengguna mengajukan izin</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
                
                <!-- Pagination -->
                @if($izin->hasPages())
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex-1 flex justify-between sm:hidden">
                            @if($izin->currentPage() > 1)
                                <a href="{{ $izin->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Previous
                                </a>
                            @endif
                            
                            @if($izin->hasMorePages())
                                <a href="{{ $izin->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Next
                                </a>
                            @endif
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Menampilkan
                                    <span class="font-medium">{{ $izin->firstItem() }}</span>
                                    hingga
                                    <span class="font-medium">{{ $izin->lastItem() }}</span>
                                    dari
                                    <span class="font-medium">{{ $izin->total() }}</span>
                                    data
                                </p>
                            </div>
                            <div class="flex items-center space-x-1">
                                {{-- Previous Button --}}
                                @if($izin->currentPage() > 1)
                                    <a href="{{ $izin->previousPageUrl() }}" 
                                       class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-chevron-left mr-1"></i>
                                        Sebelumnya
                                    </a>
                                @endif
                                
                                {{-- Page Numbers --}}
                                @if($izin->lastPage() > 1)
                                    @for($i = max(1, $izin->currentPage() - 2); $i <= min($izin->lastPage(), $izin->currentPage() + 2); $i++)
                                        @if($i == $izin->currentPage())
                                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-purple-600 border border-purple-600 rounded-md">
                                                {{ $i }}
                                            </span>
                                        @else
                                            <a href="{{ $izin->url($i) }}" 
                                               class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                                {{ $i }}
                                            </a>
                                        @endif
                                    @endfor
                                @endif
                                
                                {{-- Next Button --}}
                                @if($izin->hasMorePages())
                                    <a href="{{ $izin->nextPageUrl() }}" 
                                       class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                        Selanjutnya
                                        <i class="fas fa-chevron-right ml-1"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                        </div>
                    </div>
                @endif
            </div>
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

        <!-- Izin Detail Modal -->
        <div id="izinDetailModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Detail Pengajuan Izin</h3>
                        <button onclick="closeIzinDetailModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <div id="izinDetailContent" class="space-y-4">
                        <!-- Content will be loaded dynamically -->
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button onclick="closeIzinDetailModal()" class="px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Tutup
                        </button>
                        <div id="izinDetailActions">
                            <!-- Action buttons will be loaded dynamically -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Chart Modal -->
        <div id="attendanceChartModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-2 mx-auto p-3 sm:p-5 border w-full max-w-6xl sm:max-w-4xl lg:max-w-6xl shadow-lg rounded-md bg-white m-2 sm:m-4">
                <div class="mt-2 sm:mt-3">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 gap-3">
                        <h3 class="text-base sm:text-lg leading-6 font-medium text-gray-900">
                            <i class="fas fa-chart-line mr-2"></i>Grafik Absensi Bulanan
                        </h3>
                        <div class="flex items-center justify-between sm:justify-end space-x-2 sm:space-x-3">
                            <select id="chartMonthSelect" class="px-2 py-1 sm:px-3 border border-gray-300 rounded-md text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 w-32 sm:w-auto">
                                <!-- Month options will be populated dynamically -->
                            </select>
                            <button onclick="closeAttendanceChartModal()" class="text-gray-400 hover:text-gray-600 p-1 sm:p-0">
                                <i class="fas fa-times text-lg sm:text-xl"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div id="attendanceChartContent">
                        <!-- User info and statistics -->
                        <div id="chartUserInfo" class="mb-4 sm:mb-6">
                            <!-- User info will be loaded dynamically -->
                        </div>
                        
                        <!-- Statistics cards -->
                        <div id="chartStats" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2 sm:gap-4 mb-4 sm:mb-6">
                            <!-- Stats will be loaded dynamically -->
                        </div>
                        
                        <!-- Chart container -->
                        <div class="bg-white rounded-lg shadow p-2 sm:p-4">
                            <div class="relative" style="height: 500px;">
                                <canvas id="attendanceChart"></canvas>
                            </div>
                        </div>
                        
                        <!-- Legend -->
                        <div class="mt-3 sm:mt-4 grid grid-cols-2 sm:flex sm:flex-wrap sm:justify-center gap-2 sm:gap-4 text-xs sm:text-sm">
                            <div class="flex items-center">
                                <div class="w-3 h-3 sm:w-4 sm:h-4 bg-blue-500 rounded mr-1 sm:mr-2"></div>
                                <span class="truncate">Check In</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 sm:w-4 sm:h-4 bg-red-500 rounded mr-1 sm:mr-2"></div>
                                <span class="truncate">Check Out</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-0 h-0 border-l-[6px] sm:border-l-[8px] border-l-gray-400 border-t-[3px] sm:border-t-[4px] border-t-transparent border-b-[3px] sm:border-b-[4px] border-b-transparent mr-1 sm:mr-2"></div>
                                <span class="truncate">Lupa Check Out</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 sm:w-4 sm:h-4 bg-green-500 rounded mr-1 sm:mr-2"></div>
                                <span class="truncate">Standar In</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 sm:w-4 sm:h-4 bg-orange-500 rounded mr-1 sm:mr-2"></div>
                                <span class="truncate">Standar Out</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 sm:mt-6 flex justify-end">
                        <button onclick="closeAttendanceChartModal()" class="w-full sm:w-auto px-3 sm:px-4 py-2 bg-gray-300 text-gray-700 text-sm sm:text-base font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('scripts')
<script>
    // Show notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
        
        const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
        notification.classList.add(bgColor, 'text-white');
        
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    // Refresh absensi data
    function refreshAbsensi() {
        window.location.reload();
    }

    // Izin management functions
    function filterIzin() {
        const status = document.getElementById('filterStatus').value;
        const jenis = document.getElementById('filterJenis').value;
        
        // Load filtered data
        loadIzinFiltered(status, jenis);
    }

    async function loadIzinFiltered(status = '', jenis = '') {
        try {
            const response = await fetch(`/api/admin/izin-list?status=${status}&jenis_izin=${jenis}`);
            const data = await response.json();
            
            if (data.success) {
                renderIzinTable(data.data);
                updateIzinStats(data.stats);
            }
        } catch (error) {
            console.error('Error loading filtered izin:', error);
        }
    }

    function renderIzinTable(izinData) {
        const tbody = document.getElementById('izinTableBody');
        if (!tbody) return;
        
        if (izinData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="text-gray-500">
                            <i class="fas fa-file-alt text-4xl mb-4"></i>
                            <p class="text-lg font-medium">Tidak ada data izin</p>
                            <p class="text-sm mt-1">Tidak ada pengajuan izin dengan filter yang dipilih</p>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }
        
        let html = '';
        izinData.forEach(izin => {
            html += `
                <tr class="hover:bg-gray-50 cursor-pointer transition-colors" onclick="showIzinDetail(${izin.id})">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">${izin.user_name}</div>
                                <div class="text-sm text-gray-500">${izin.user_email}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            ${izin.jenis_izin === 'izin' ? 'bg-blue-100 text-blue-800' : 
                              izin.jenis_izin === 'cuti' ? 'bg-green-100 text-green-800' : 
                              'bg-yellow-100 text-yellow-800'}">
                            ${izin.jenis_izin.charAt(0).toUpperCase() + izin.jenis_izin.slice(1)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${new Date(izin.tanggal_mulai).toLocaleDateString('id-ID')} - ${new Date(izin.tanggal_selesai).toLocaleDateString('id-ID')}
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 max-w-xs">
                            <p class="truncate" title="${izin.alasan}">
                                ${izin.alasan}
                            </p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${izin.bukti_path ? 
                            `<button onclick="viewBukti('${izin.bukti_path}')" class="text-blue-600 hover:text-blue-900 transition-colors">
                                <i class="fas fa-file-alt mr-1"></i>Lihat
                            </button>` : 
                            '<span class="text-gray-400">-</span>'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${new Date(izin.created_at).toLocaleDateString('id-ID')} ${new Date(izin.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            ${izin.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                              izin.status === 'approved' ? 'bg-green-100 text-green-800' : 
                              'bg-red-100 text-red-800'}">
                            ${izin.status.charAt(0).toUpperCase() + izin.status.slice(1)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        ${izin.status === 'pending' ? 
                            `<button onclick="event.stopPropagation(); approveIzin(${izin.id})" class="text-green-600 hover:text-green-900 transition-colors p-2 rounded hover:bg-green-50 mr-2">
                                <i class="fas fa-check"></i>
                            </button>
                            <button onclick="event.stopPropagation(); rejectIzin(${izin.id})" class="text-red-600 hover:text-red-900 transition-colors p-2 rounded hover:bg-red-50">
                                <i class="fas fa-times"></i>
                            </button>` : 
                            '<span class="text-gray-400">-</span>'}
                    </td>
                </tr>
            `;
        });
        
        tbody.innerHTML = html;
    }

    function updateIzinStats(stats) {
        // Update statistics if needed
        console.log('Izin stats:', stats);
    }

    async function approveIzin(izinId) {
        if (!confirm('Apakah Anda yakin ingin menyetujui pengajuan izin ini?')) {
            return;
        }
        
        try {
            const response = await fetch('/api/admin/approve-izin', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    izin_id: izinId
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification('Pengajuan izin berhasil disetujui', 'success');
                filterIzin(); // Reload data
            } else {
                showNotification(data.error || 'Gagal menyetujui izin', 'error');
            }
        } catch (error) {
            console.error('Error approving izin:', error);
            showNotification('Terjadi kesalahan saat menyetujui izin', 'error');
        }
    }

    async function rejectIzin(izinId) {
        const catatan = prompt('Alasan penolakan (opsional):');
        
        try {
            const response = await fetch('/api/admin/reject-izin', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    izin_id: izinId,
                    catatan_admin: catatan
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification('Pengajuan izin berhasil ditolak', 'success');
                filterIzin(); // Reload data
            } else {
                showNotification(data.error || 'Gagal menolak izin', 'error');
            }
        } catch (error) {
            console.error('Error rejecting izin:', error);
            showNotification('Terjadi kesalahan saat menolak izin', 'error');
        }
    }

    function viewBukti(buktiPath) {
        // Open file in new window or show modal
        window.open(`/public/storage/${buktiPath}`, '_blank');
    }

    // Delete absensi functions
    function testDeleteClick(id, userName, date) {
        document.getElementById('deleteUserName').textContent = userName;
        document.getElementById('deleteDate').textContent = date;
        document.getElementById('deleteModal').classList.remove('hidden');
        
        // Set up delete button
        const deleteBtn = document.getElementById('confirmDeleteBtn');
        deleteBtn.onclick = function() {
            confirmDelete(id);
        };
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('adminPassword').value = '';
    }

    async function confirmDelete(absenId) {
        const password = document.getElementById('adminPassword').value;
        
        if (!password) {
            alert('Password admin harus diisi!');
            return;
        }
        
        try {
            const response = await fetch(`/admin/absensi/${absenId}`, {
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
                showNotification('Data absensi berhasil dihapus', 'success');
                closeDeleteModal();
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(data.error || 'Gagal menghapus data absensi', 'error');
            }
        } catch (error) {
            console.error('Error deleting absensi:', error);
            showNotification('Terjadi kesalahan saat menghapus data', 'error');
        }
    }

    // Izin Detail Modal Functions
    async function showIzinDetail(izinId) {
        try {
            const response = await fetch(`/api/admin/izin-detail/${izinId}`);
            const data = await response.json();
            
            if (data.success) {
                renderIzinDetail(data.izin);
                document.getElementById('izinDetailModal').classList.remove('hidden');
            } else {
                showNotification(data.error || 'Gagal memuat detail izin', 'error');
            }
        } catch (error) {
            console.error('Error loading izin detail:', error);
            showNotification('Terjadi kesalahan saat memuat detail izin', 'error');
        }
    }

    function closeIzinDetailModal() {
        document.getElementById('izinDetailModal').classList.add('hidden');
    }

    function renderIzinDetail(izin) {
        const content = document.getElementById('izinDetailContent');
        const actions = document.getElementById('izinDetailActions');
        
        const statusColor = izin.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                           izin.status === 'approved' ? 'bg-green-100 text-green-800' : 
                           'bg-red-100 text-red-800';
        
        const jenisColor = izin.jenis_izin === 'izin' ? 'bg-blue-100 text-blue-800' : 
                          izin.jenis_izin === 'cuti' ? 'bg-green-100 text-green-800' : 
                          'bg-yellow-100 text-yellow-800';
        
        content.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pengguna</label>
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                            <i class="fas fa-user text-gray-600"></i>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">${izin.user_name}</div>
                            <div class="text-sm text-gray-500">${izin.user_email}</div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Izin</label>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${jenisColor}">
                        ${izin.jenis_izin.charAt(0).toUpperCase() + izin.jenis_izin.slice(1)}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                    <div class="text-sm text-gray-900">
                        ${new Date(izin.tanggal_mulai).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })} - 
                        ${new Date(izin.tanggal_selesai).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        ${calculateDuration(izin.tanggal_mulai, izin.tanggal_selesai)} hari
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusColor}">
                        ${izin.status.charAt(0).toUpperCase() + izin.status.slice(1)}
                    </span>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alasan</label>
                    <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded-md">
                        ${izin.alasan}
                    </div>
                </div>
                
                ${izin.bukti_path ? `
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bukti</label>
                    <div class="border rounded-lg overflow-hidden">
                        <img src="/public/storage/${izin.bukti_path}" alt="Bukti Izin" class="w-full h-auto max-h-96 object-contain bg-gray-50" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div class="hidden p-4 text-center text-gray-500 bg-gray-50">
                            <i class="fas fa-file-alt text-2xl mb-2"></i>
                            <p class="text-sm">File bukti tidak dapat ditampilkan</p>
                            <a href="/public/storage/${izin.bukti_path}" target="_blank" class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors mt-2">
                                <i class="fas fa-external-link-alt mr-1"></i>
                                Buka di Tab Baru
                            </a>
                        </div>
                    </div>
                </div>
                ` : ''}
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Diajukan</label>
                    <div class="text-sm text-gray-900">
                        ${new Date(izin.created_at).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })} 
                        ${new Date(izin.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}
                    </div>
                </div>
                
                ${izin.approved_at ? `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Disetujui</label>
                    <div class="text-sm text-gray-900">
                        ${new Date(izin.approved_at).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })} 
                        ${new Date(izin.approved_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}
                    </div>
                    ${izin.approved_by_name ? `<div class="text-xs text-gray-500">oleh ${izin.approved_by_name}</div>` : ''}
                </div>
                ` : ''}
                
                ${izin.rejected_at ? `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ditolak</label>
                    <div class="text-sm text-gray-900">
                        ${new Date(izin.rejected_at).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })} 
                        ${new Date(izin.rejected_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}
                    </div>
                    ${izin.rejected_by_name ? `<div class="text-xs text-gray-500">oleh ${izin.rejected_by_name}</div>` : ''}
                </div>
                ` : ''}
                
                ${izin.catatan_admin ? `
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Admin</label>
                    <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded-md">
                        ${izin.catatan_admin}
                    </div>
                </div>
                ` : ''}
            </div>
        `;
        
        // Set action buttons based on status
        if (izin.status === 'pending') {
            actions.innerHTML = `
                <button onclick="event.stopPropagation(); approveIzin(${izin.id}); closeIzinDetailModal();" class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <i class="fas fa-check mr-2"></i>Setujui
                </button>
                <button onclick="event.stopPropagation(); rejectIzin(${izin.id}); closeIzinDetailModal();" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    <i class="fas fa-times mr-2"></i>Tolak
                </button>
            `;
        } else {
            actions.innerHTML = '';
        }
    }

    function calculateDuration(startDate, endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        const diffTime = Math.abs(end - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        return diffDays;
    }

    // Attendance Chart Modal Functions
    let attendanceChart = null;
    let currentUserId = null;

    async function showAttendanceChart(userId) {
        currentUserId = userId;
        
        try {
            // Populate month selector
            populateMonthSelector();
            
            // Load initial data for current month
            const currentMonth = new Date().toISOString().slice(0, 7);
            await loadAttendanceChart(userId, currentMonth);
            
            // Show modal
            document.getElementById('attendanceChartModal').classList.remove('hidden');
            
            // Add event listener for month change
            document.getElementById('chartMonthSelect').addEventListener('change', function() {
                loadAttendanceChart(userId, this.value);
            });
            
        } catch (error) {
            console.error('Error showing attendance chart:', error);
            showNotification('Gagal memuat grafik absensi', 'error');
        }
    }

    function populateMonthSelector() {
        const select = document.getElementById('chartMonthSelect');
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear();
        const currentMonth = currentDate.getMonth();
        
        let options = '';
        
        // Generate options for last 6 months and next 2 months
        for (let i = -6; i <= 2; i++) {
            const date = new Date(currentYear, currentMonth + i, 1);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const value = `${year}-${month}`;
            const label = date.toLocaleDateString('id-ID', { year: 'numeric', month: 'long' });
            
            options += `<option value="${value}">${label}</option>`;
        }
        
        select.innerHTML = options;
        
        // Set current month as selected
        const currentMonthValue = new Date().toISOString().slice(0, 7);
        select.value = currentMonthValue;
    }

    async function loadAttendanceChart(userId, month) {
        try {
            const response = await fetch(`/api/admin/user-attendance-chart?user_id=${userId}&month=${month}`);
            const data = await response.json();
            
            if (data.success) {
                renderAttendanceChart(data.data);
            } else {
                showNotification(data.error || 'Gagal memuat data grafik', 'error');
            }
        } catch (error) {
            console.error('Error loading attendance chart:', error);
            showNotification('Terjadi kesalahan saat memuat grafik', 'error');
        }
    }

    function renderAttendanceChart(data) {
        // Update user info
        document.getElementById('chartUserInfo').innerHTML = `
            <div class="flex items-center bg-gray-50 p-4 rounded-lg">
                <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center mr-4">
                    <i class="fas fa-user text-gray-600 text-lg"></i>
                </div>
                <div>
                    <h4 class="text-lg font-medium text-gray-900">${data.user.name}</h4>
                    <p class="text-sm text-gray-500">${data.user.email}</p>
                    <p class="text-sm text-purple-600 font-medium">${data.month_name}</p>
                </div>
            </div>
        `;
        
        // Update statistics
        const statsHtml = `
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500">Hadir</p>
                        <p class="text-xl font-bold text-gray-900">${data.stats.hadir}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500">Terlambat</p>
                        <p class="text-xl font-bold text-gray-900">${data.stats.terlambat}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                        <i class="fas fa-calendar-times text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500">Izin/Sakit</p>
                        <p class="text-xl font-bold text-gray-900">${data.stats.izin}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-100 rounded-lg p-3">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500">Alfa</p>
                        <p class="text-xl font-bold text-gray-900">${data.stats.alfa}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                        <i class="fas fa-percentage text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500">Kehadiran</p>
                        <p class="text-xl font-bold text-gray-900">${data.stats.attendance_rate}%</p>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('chartStats').innerHTML = statsHtml;
        
        // Prepare chart data
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        
        // Destroy existing chart if it exists
        if (attendanceChart) {
            attendanceChart.destroy();
        }
        
        // Prepare datasets
        const checkInData = new Array(31).fill(null);
        const checkOutData = new Array(31).fill(null);
        const missingCheckOutData = new Array(31).fill(null);
        
        data.check_in_times.forEach(point => {
            checkInData[parseInt(point.x) - 1] = point.y;
        });
        
        data.check_out_times.forEach(point => {
            checkOutData[parseInt(point.x) - 1] = point.y;
        });
        
        // Identify missing check-outs (check-in exists but no check-out)
        for (let i = 0; i < 31; i++) {
            if (checkInData[i] !== null && checkOutData[i] === null) {
                // Mark as missing check-out with a special value
                missingCheckOutData[i] = 17; // Show at standard check-out time
            }
        }
        
        // Create new chart
        attendanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: Array.from({length: 31}, (_, i) => i + 1),
                datasets: [
                    {
                        label: 'Check In',
                        data: checkInData,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.1,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        spanGaps: true
                    },
                    {
                        label: 'Check Out',
                        data: checkOutData,
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.1,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        spanGaps: true
                    },
                    {
                        label: 'Lupa Check Out',
                        data: missingCheckOutData,
                        borderColor: 'rgb(156, 163, 175)',
                        backgroundColor: 'rgba(156, 163, 175, 0.1)',
                        borderDash: [5, 5],
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointStyle: 'triangle',
                        showLine: false
                    },
                    {
                        label: 'Standar Check In (08:00)',
                        data: new Array(31).fill(8),
                        borderColor: 'rgb(34, 197, 94)',
                        borderDash: [5, 5],
                        pointRadius: 0,
                        pointHoverRadius: 0
                    },
                    {
                        label: 'Standar Check Out (17:00)',
                        data: new Array(31).fill(17),
                        borderColor: 'rgb(249, 115, 22)',
                        borderDash: [5, 5],
                        pointRadius: 0,
                        pointHoverRadius: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: false // Hide default legend, use custom one
                    },
                    title: {
                        display: true,
                        text: `Grafik Waktu Check In/Check Out - ${data.month_name}`,
                        font: {
                            size: window.innerWidth < 640 ? 12 : 14
                        }
                    },
                    tooltip: {
                        titleFont: {
                            size: window.innerWidth < 640 ? 11 : 12
                        },
                        bodyFont: {
                            size: window.innerWidth < 640 ? 10 : 12
                        },
                        callbacks: {
                            label: function(context) {
                                if (context.parsed.y !== null) {
                                    if (context.dataset.label === 'Lupa Check Out') {
                                        return '⚠️ Lupa Check Out (seharusnya 17:00)';
                                    }
                                    const hours = Math.floor(context.parsed.y);
                                    const minutes = Math.round((context.parsed.y - hours) * 60);
                                    const time = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
                                    return `${context.dataset.label}: ${time}`;
                                }
                                return context.dataset.label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal',
                            font: {
                                size: window.innerWidth < 640 ? 10 : 12
                            }
                        },
                        ticks: {
                            font: {
                                size: window.innerWidth < 640 ? 9 : 11
                            },
                            maxRotation: window.innerWidth < 640 ? 45 : 0,
                            minRotation: window.innerWidth < 640 ? 45 : 0
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Waktu (24 jam)',
                            font: {
                                size: window.innerWidth < 640 ? 10 : 12
                            }
                        },
                        min: 6,
                        max: 24,
                        ticks: {
                            autoSkip: false, // Disable auto skipping to show all ticks
                            stepSize: 1,
                            maxTicksLimit: 19, // Force show all ticks from 6 to 24 (19 ticks total)
                            sampleSize: 19, // Force all ticks to be sampled
                            font: {
                                size: window.innerWidth < 640 ? 9 : 11
                            },
                            callback: function(value) {
                                if (value === 24) {
                                    return '00:00';
                                }
                                return value + ':00';
                            }
                        }
                    }
                }
            }
        });
    }

    function closeAttendanceChartModal() {
        document.getElementById('attendanceChartModal').classList.add('hidden');
        
        // Destroy chart to free memory
        if (attendanceChart) {
            attendanceChart.destroy();
            attendanceChart = null;
        }
        
        currentUserId = null;
    }

    // Add event listener to close modal when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('deleteModal');
        const izinDetailModal = document.getElementById('izinDetailModal');
        const attendanceChartModal = document.getElementById('attendanceChartModal');
        
        if (deleteModal) {
            deleteModal.addEventListener('click', function(e) {
                if (e.target === deleteModal) {
                    closeDeleteModal();
                }
            });
        }
        
        if (izinDetailModal) {
            izinDetailModal.addEventListener('click', function(e) {
                if (e.target === izinDetailModal) {
                    closeIzinDetailModal();
                }
            });
        }
        
        if (attendanceChartModal) {
            attendanceChartModal.addEventListener('click', function(e) {
                if (e.target === attendanceChartModal) {
                    closeAttendanceChartModal();
                }
            });
        }
    });

</script>
@endpush
