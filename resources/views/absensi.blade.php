<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Karyawan - Catatan Kerja MSA</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="/ck/favicons/brieftcase.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .animate-ping {
            animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
        }
        @keyframes ping {
            75%, 100% {
                transform: scale(2);
                opacity: 0;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="{{ route('absensi') }}" class="block bg-purple-600 rounded-lg p-2 hover:bg-purple-700 transition-colors">
                            <i class="fas fa-briefcase text-white text-xl"></i>
                        </a>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <h1 class="text-lg sm:text-xl font-bold text-gray-900">Catatan Kerja MSA</h1>
                    </div>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-4">
                    @if(session('user')['role'] === 'admin')
                        <a href="/admin" class="flex items-center px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <div class="flex-shrink-0 bg-white rounded-lg p-1.5 mr-2">
                                <i class="fas fa-shield-alt text-red-600 text-xs"></i>
                            </div>
                            <span class="text-sm font-medium">Panel CK MSA</span>
                        </a>
                    @endif
                    
                    <!-- Absensi Menu -->
                    <a href="{{ route('absensi') }}" class="py-4 px-1 border-b-2 border-purple-500 text-purple-600 font-medium flex items-center">
                        <i class="fas fa-clock mr-2"></i>Absensi
                    </a>
                    
                    <!-- Daily Report Menu -->
                    <a href="{{ route('report') }}" class="py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium flex items-center">
                        <i class="fas fa-file-alt mr-2"></i>Daily Report
                    </a>
                    
                    <!-- Calendar Menu -->
                    <a href="{{ route('calendar') }}" class="py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium flex items-center">
                        <i class="fas fa-calendar mr-2"></i>Kalender
                    </a>
                    
                    <div class="flex items-center space-x-3">
                        <div class="bg-purple-100 rounded-full p-2">
                            <i class="fas fa-user text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ session('user')['name'] }}</p>
                            <p class="text-xs text-gray-500">{{ session('user')['role'] }}</p>
                        </div>
                        <form action="/logout" method="POST" class="ml-2">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-colors">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center space-x-2">
                    @if(session('user')['role'] === 'admin')
                        <a href="/admin" class="flex items-center px-2 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            <div class="flex-shrink-0 bg-white rounded-lg p-1 mr-1.5">
                                <i class="fas fa-shield-alt text-red-600 text-xs"></i>
                            </div>
                            <span class="text-xs font-medium">Panel CK MSA</span>
                        </a>
                    @endif
                    
                    <button onclick="toggleMobileMenu()" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t">
            <div class="px-4 py-3 space-y-2">
                <!-- Absensi Mobile Menu -->
                <a href="{{ route('absensi') }}" class="block py-2 px-3 text-purple-600 bg-purple-50 rounded-lg transition-colors">
                    <i class="fas fa-clock mr-2"></i>Absensi
                </a>
                
                <!-- Daily Report Mobile Menu -->
                <a href="{{ route('report') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-file-alt mr-2"></i>Daily Report
                </a>
                
                <!-- Calendar Mobile Menu -->
                <a href="{{ route('calendar') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-calendar mr-2"></i>Kalender
                </a>
                
                <div class="flex items-center space-x-3 pb-3 border-b">
                    <div class="bg-purple-100 rounded-full p-2">
                        <i class="fas fa-user text-purple-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ session('user')['name'] }}</p>
                        <p class="text-xs text-gray-500">{{ session('user')['role'] }}</p>
                    </div>
                </div>
                <form action="/logout" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-left text-red-600 hover:text-red-800 px-3 py-2 rounded-lg hover:bg-red-50 transition-colors">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="px-4 py-4 sm:px-0">
            <!-- Page Title -->
            <div class="mb-6">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Absensi Karyawan</h2>
                <p class="text-gray-600 mt-1 text-sm sm:text-base">Kelola kehadiran harian Anda</p>
            </div>

            <!-- Location Info Card -->
            <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6">
                    <div class="mb-3 sm:mb-0">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900">Lokasi Absensi</h3>
                        <p class="text-gray-500 mt-1 text-sm">Lokasi Anda saat ini</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse" id="gpsStatusIndicator"></div>
                        <span class="text-sm text-green-600 font-medium" id="gpsStatusText">GPS Aktif</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Map Container -->
                    <div class="order-2 lg:order-1">
                        <div class="bg-gray-100 rounded-lg p-3 sm:p-4 h-48 sm:h-64 relative overflow-hidden">
                            <div id="map" class="w-full h-full rounded-lg bg-gray-200 flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-map-marked-alt text-3xl sm:text-4xl text-gray-400 mb-2"></i>
                                    <p class="text-gray-500 text-xs sm:text-sm">Mendeteksi lokasi...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location Details -->
                    <div class="order-1 lg:order-2 space-y-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-2">
                                    <i class="fas fa-map-pin text-blue-600"></i>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-blue-900">Lokasi Anda</h4>
                                    <p class="text-sm text-blue-700 mt-1" id="currentLocation">Mendeteksi lokasi...</p>
                                    <p class="text-xs text-blue-600 mt-1" id="coordinates">--</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-green-100 rounded-lg p-2">
                                    <i class="fas fa-map text-green-600"></i>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-green-900">Alamat</h4>
                                    <p class="text-sm text-green-700 mt-1" id="address">Mendapatkan alamat...</p>
                                    <p class="text-xs text-green-600 mt-1" id="city">--</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-purple-100 rounded-lg p-2">
                                    <i class="fas fa-info-circle text-purple-600"></i>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-purple-900">Info GPS</h4>
                                    <p class="text-sm text-purple-700 mt-1" id="accuracy">Akurasi: --</p>
                                    <p class="text-xs text-purple-600 mt-1" id="timestamp">--</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan Section -->
            <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6">
                    <div class="mb-3 sm:mb-0">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900">Catatan Absensi</h3>
                        <p class="text-gray-500 mt-1 text-sm">Tambahkan catatan untuk hari ini (opsional)</p>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-3">
                    <label for="catatan" class="text-sm font-medium text-gray-700">
                        <i class="fas fa-sticky-note mr-2 text-gray-400"></i>Catatan
                    </label>
                    <span class="text-xs text-gray-500" id="catatanCount">0/200</span>
                </div>
                <textarea 
                    id="catatan" 
                    name="catatan"
                    rows="3"
                    maxlength="200"
                    placeholder="Tambahkan catatan untuk absensi hari ini (contoh: meeting dengan klien, deadline project, dll)"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none text-sm"
                    oninput="updateCatatanCount()"></textarea>
                <div class="mt-2 flex items-center justify-between">
                    <p class="text-xs text-gray-500">Catatan akan disimpan bersamaan dengan check in/check out</p>
                    <button onclick="clearCatatan()" class="text-xs text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times mr-1"></i>Hapus
                    </button>
                </div>
            </div>

            <!-- Current Status Card -->
            <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 mb-6">
                <!-- Location Warning -->
                <div id="locationWarning" class="hidden bg-yellow-50 border border-yellow-200 rounded-lg p-3 sm:p-4 mb-4 sm:mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-yellow-900">Lokasi Belum Terdeteksi</h4>
                            <p class="text-sm text-yellow-700 mt-1">Lokasi Anda belum terdeteksi. Check in dan check out tidak dapat dilakukan. Harap refresh halaman atau periksa pengaturan GPS Anda.</p>
                            <div class="mt-2">
                                <button onclick="refreshLocation()" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm font-medium transition">
                                    <i class="fas fa-sync-alt mr-1"></i>Refresh Lokasi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6">
                    <div class="mb-3 sm:mb-0">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900">Status Absensi Hari Ini</h3>
                        <p class="text-gray-500 mt-1 text-sm">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
                    </div>
                    <div class="text-center sm:text-right">
                        <div class="text-2xl sm:text-3xl font-bold text-blue-600" id="currentTime">--:--:--</div>
                        <div class="text-xs sm:text-sm text-gray-500">Waktu Saat Ini</div>
                    </div>
                </div>

                <!-- Check In Status (Show First) -->
                <div id="checkInStatus" class="bg-green-50 border border-green-200 rounded-lg p-3 sm:p-4 mb-4 sm:mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center mb-4 sm:mb-0">
                            <div class="flex-shrink-0 bg-green-100 rounded-lg p-2 sm:p-3">
                                <i class="fas fa-sign-in-alt text-green-600 text-lg sm:text-xl"></i>
                            </div>
                            <div class="ml-3 sm:ml-4">
                                <h4 class="text-base sm:text-lg font-medium text-green-900">Check In</h4>
                                <p class="text-green-700 text-sm sm:text-base" id="checkInTime">
                                    {{ $todayAttendance?->check_in ?? '--:--' }}
                                </p>
                                <p class="text-xs sm:text-sm text-green-600" id="checkInStatusText">
                                    {{ $todayAttendance?->check_in ? 'Sudah check in' : 'Belum check in' }}
                                </p>
                                @if($todayAttendance?->check_in_location)
                                    <div class="mt-2 p-2 bg-green-100 rounded-lg">
                                        <p class="text-xs text-green-700">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            {{ Str::limit($todayAttendance->check_in_location, 50) }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <button onclick="checkIn()" class="w-full sm:w-auto bg-green-500 hover:bg-green-600 text-white py-2 sm:py-3 px-4 sm:px-6 rounded-lg font-semibold transition transform hover:scale-105 text-sm sm:text-base" id="checkInButton">
                            <i class="fas fa-sign-in-alt mr-2"></i>Check In
                        </button>
                    </div>
                </div>

                <!-- Check Out Status (Only show if already checked in) -->
                @if($todayAttendance && $todayAttendance->check_in)
                <div id="checkOutStatus" class="bg-red-50 border border-red-200 rounded-lg p-3 sm:p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center mb-4 sm:mb-0">
                            <div class="flex-shrink-0 bg-red-100 rounded-lg p-2 sm:p-3">
                                <i class="fas fa-sign-out-alt text-red-600 text-lg sm:text-xl"></i>
                            </div>
                            <div class="ml-3 sm:ml-4">
                                <h4 class="text-base sm:text-lg font-medium text-red-900">Check Out</h4>
                                <p class="text-red-700 text-sm sm:text-base" id="checkOutTime">
                                    {{ $todayAttendance?->check_out ?? '--:--' }}
                                </p>
                                <p class="text-xs sm:text-sm text-red-600" id="checkOutStatusText">
                                    {{ $todayAttendance?->check_out ? 'Sudah check out' : 'Belum check out' }}
                                </p>
                                @if($todayAttendance?->check_out_location)
                                    <div class="mt-2 p-2 bg-red-100 rounded-lg">
                                        <p class="text-xs text-red-700">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            {{ Str::limit($todayAttendance->check_out_location, 50) }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <button onclick="checkOut()" class="w-full sm:w-auto bg-red-500 hover:bg-red-600 text-white py-2 sm:py-3 px-4 sm:px-6 rounded-lg font-semibold transition transform hover:scale-105 text-sm sm:text-base" id="checkOutButton">
                            <i class="fas fa-sign-out-alt mr-2"></i>Check Out
                        </button>
                    </div>
                </div>
                @endif
            </div>

            <!-- Attendance History -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                    <div>
                        <h3 class="text-base sm:text-lg font-medium text-gray-900">Riwayat Absensi</h3>
                        <div class="text-xs sm:text-sm text-gray-500 mt-1">3 hari terakhir</div>
                    </div>
                </div>
                <div class="p-3 sm:p-6" id="attendanceHistory">
                    @if($attendanceHistory->count() > 0)
                        <div class="space-y-3 sm:space-y-4">
                            @foreach($attendanceHistory as $attendance)
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 sm:p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex items-start sm:items-center space-x-3 sm:space-x-4 w-full sm:w-auto">
                                        <div class="flex-shrink-0">
                                            @if($attendance->check_in && $attendance->check_out)
                                                <div class="bg-green-100 rounded-full p-2">
                                                    <i class="fas fa-check-circle text-green-600 text-sm"></i>
                                                </div>
                                            @elseif($attendance->check_in)
                                                <div class="bg-yellow-100 rounded-full p-2">
                                                    <i class="fas fa-clock text-yellow-600 text-sm"></i>
                                                </div>
                                            @else
                                                <div class="bg-gray-100 rounded-full p-2">
                                                    <i class="fas fa-minus-circle text-gray-600 text-sm"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ \Carbon\Carbon::parse($attendance->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                            </p>
                                            <div class="flex flex-wrap items-center gap-2 sm:gap-4 mt-1 sm:mt-2">
                                                <span class="text-xs text-gray-500 whitespace-nowrap">
                                                    <i class="fas fa-sign-in-alt text-green-500 mr-1"></i>
                                                    {{ $attendance->check_in ?? '--:--' }}
                                                </span>
                                                @if($attendance->check_in_location)
                                                    <span class="text-xs text-green-600 truncate max-w-[120px] sm:max-w-xs" title="{{ $attendance->check_in_location }}">
                                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                                        {{ Str::limit($attendance->check_in_location, 15) }}
                                                    </span>
                                                @endif
                                                <span class="text-xs text-gray-500 whitespace-nowrap">
                                                    <i class="fas fa-sign-out-alt text-red-500 mr-1"></i>
                                                    {{ $attendance->check_out ?? '--:--' }}
                                                </span>
                                                @if($attendance->check_out_location)
                                                    <span class="text-xs text-red-600 truncate max-w-[120px] sm:max-w-xs" title="{{ $attendance->check_out_location }}">
                                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                                        {{ Str::limit($attendance->check_out_location, 15) }}
                                                    </span>
                                                @endif
                                                @if($attendance->total_jam)
                                                    <span class="text-xs text-blue-600 font-medium whitespace-nowrap">
                                                        <i class="fas fa-hourglass-half mr-1"></i>
                                                        {{ number_format($attendance->total_jam, 2) }} jam
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if($attendance->check_in && $attendance->check_out)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Lengkap
                                            </span>
                                        @elseif($attendance->check_in)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Check In Saja
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Tidak Hadir
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-history text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500">Belum ada riwayat absensi</p>
                            <p class="text-sm text-gray-400 mt-2">Lakukan check in/check out untuk melihat riwayat</p>
                        </div>
                    @endif
                </div>
            </div>
    </main>

    <script>
        // Location data
        const officeLocation = {
            lat: -6.2088,
            lng: 106.8456,
            address: "Jl. Sudirman No. 123, Jakarta Pusat"
        };

        let currentAddress = ''; // Global variable to store current address
        let locationDetected = false; // Global variable to track location status

        // Update current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            });
            document.getElementById('currentTime').textContent = timeString;
        }

        // Get user location with geolocation (optimized)
        function getUserLocation() {
            if (navigator.geolocation) {
                // Request high accuracy location with shorter timeout
                navigator.geolocation.getCurrentPosition(
                    async (position) => {
                        const userLat = position.coords.latitude;
                        const userLng = position.coords.longitude;
                        const accuracy = position.coords.accuracy;
                        const timestamp = new Date(position.timestamp);
                        
                        // Update location display
                        document.getElementById('currentLocation').textContent = 
                            `${userLat.toFixed(6)}, ${userLng.toFixed(6)}`;
                        document.getElementById('coordinates').textContent = 
                            `Lat: ${userLat.toFixed(6)}, Lng: ${userLng.toFixed(6)}`;
                        
                        // Update GPS info
                        document.getElementById('accuracy').textContent = 
                            `Akurasi: ${accuracy.toFixed(0)} meter`;
                        document.getElementById('timestamp').textContent = 
                            `Update: ${timestamp.toLocaleTimeString('id-ID')}`;
                        
                        // Get address using reverse geocoding (with timeout)
                        try {
                            await getAddressFromCoords(userLat, userLng);
                        } catch (error) {
                            console.error('Error getting address:', error);
                        }
                        
                        // Update map
                        updateMap(userLat, userLng);
                        
                        // Show success notification
                        showNotification('Lokasi berhasil dideteksi!', 'success');
                        
                        // Update location status
                        locationDetected = true;
                        updateLocationStatus();
                    },
                    (error) => {
                        console.error('Error getting location:', error);
                        let errorMessage = 'Lokasi tidak dapat diakses';
                        
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = 'Akses lokasi ditolak';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = 'Informasi lokasi tidak tersedia';
                                break;
                            case error.TIMEOUT:
                                errorMessage = 'Timeout mendapatkan lokasi';
                                break;
                        }
                        
                        document.getElementById('currentLocation').textContent = errorMessage;
                        document.getElementById('coordinates').textContent = 'Error: ' + error.message;
                        showNotification(errorMessage, 'error');
                        
                        // Update location status
                        locationDetected = false;
                        updateLocationStatus();
                    },
                    {
                        enableHighAccuracy: false, // Set to false for faster response
                        timeout: 3000, // Reduced timeout for faster response
                        maximumAge: 60000 // Allow cached position up to 1 minute
                    }
                );
                
                // Watch position for real-time updates (with longer intervals)
                navigator.geolocation.watchPosition(
                    (position) => {
                        const userLat = position.coords.latitude;
                        const userLng = position.coords.longitude;
                        
                        // Update location display
                        document.getElementById('currentLocation').textContent = 
                            `${userLat.toFixed(6)}, ${userLng.toFixed(6)}`;
                        document.getElementById('coordinates').textContent = 
                            `Lat: ${userLat.toFixed(6)}, Lng: ${userLng.toFixed(6)}`;
                        
                        // Update map
                        updateMap(userLat, userLng);
                    },
                    null,
                    {
                        enableHighAccuracy: false,
                        timeout: 5000, // Reduced timeout for faster response
                        maximumAge: 120000 // 2 minutes
                    }
                );
            } else {
                document.getElementById('currentLocation').textContent = 'GPS tidak didukung';
                showNotification('Browser tidak mendukung GPS', 'error');
                
                // Update location status
                locationDetected = false;
                updateLocationStatus();
            }
        }

        // Get address from coordinates using reverse geocoding (with timeout)
        function getAddressFromCoords(lat, lng) {
            return new Promise((resolve, reject) => {
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 5000); // 5 second timeout
                
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1&accept-language=id`, {
                    signal: controller.signal
                })
                    .then(response => {
                        clearTimeout(timeoutId);
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.display_name) {
                            const address = data.display_name;
                            const city = data.address?.city || data.address?.town || data.address?.village || 'Unknown';
                            
                            // Store address globally for use in check-in/check-out
                            currentAddress = address;
                            
                            // Update UI elements
                            document.getElementById('address').textContent = address.length > 50 
                                ? address.substring(0, 50) + '...' 
                                : address;
                            document.getElementById('city').textContent = city;
                            
                            resolve(address);
                        } else {
                            currentAddress = 'Alamat tidak ditemukan';
                            document.getElementById('address').textContent = 'Alamat tidak ditemukan';
                            document.getElementById('city').textContent = 'Unknown';
                            resolve(null);
                        }
                    })
                    .catch(error => {
                        clearTimeout(timeoutId);
                        console.error('Error getting address:', error);
                        
                        let errorMessage = 'Gagal mendapatkan alamat';
                        if (error.name === 'AbortError') {
                            errorMessage = 'Timeout - alamat tidak tersedia';
                        }
                        
                        currentAddress = errorMessage;
                        document.getElementById('address').textContent = errorMessage;
                        document.getElementById('city').textContent = 'Unknown';
                        
                        reject(error);
                    });
            });
        }

        // Update map display with user location
        function updateMap(userLat, userLng) {
            const mapContainer = document.getElementById('map');
            
            // Create OpenStreetMap with real map tiles
            mapContainer.innerHTML = `
                <div class="relative w-full h-full rounded-lg overflow-hidden">
                    <!-- OpenStreetMap iframe -->
                    <iframe 
                        width="100%" 
                        height="100%" 
                        frameborder="0" 
                        scrolling="no" 
                        marginheight="0" 
                        marginwidth="0" 
                        src="https://www.openstreetmap.org/export/embed.html?bbox=${userLng-0.002},${userLat-0.002},${userLng+0.002},${userLat+0.002}&layer=mapnik&marker=${userLat},${userLng}"
                        class="rounded-lg">
                    </iframe>
                    
                    <!-- Location info overlay -->
                    <div class="absolute top-2 left-2 bg-white rounded-lg shadow-lg p-2 text-xs max-w-xs">
                        <div class="flex items-center space-x-2 mb-1">
                            <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                            <span class="font-medium text-gray-700">Lokasi Anda</span>
                        </div>
                        <div class="text-gray-600">
                            ${userLat.toFixed(4)}°, ${userLng.toFixed(4)}°
                        </div>
                    </div>
                    
                    <!-- Refresh button -->
                    <div class="absolute bottom-2 right-2">
                        <button onclick="refreshLocation()" class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-lg p-2 text-xs">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
            `;
        }

        // Update location status and UI
        function updateLocationStatus() {
            const warningDiv = document.getElementById('locationWarning');
            const gpsIndicator = document.getElementById('gpsStatusIndicator');
            const gpsText = document.getElementById('gpsStatusText');
            const checkInButton = document.getElementById('checkInButton');
            const checkOutButton = document.getElementById('checkOutButton');
            
            if (locationDetected) {
                // Hide warning, show GPS active
                if (warningDiv) warningDiv.classList.add('hidden');
                if (gpsIndicator) {
                    gpsIndicator.classList.remove('bg-red-500');
                    gpsIndicator.classList.add('bg-green-500');
                }
                if (gpsText) {
                    gpsText.textContent = 'GPS Aktif';
                    gpsText.classList.remove('text-red-600');
                    gpsText.classList.add('text-green-600');
                }
                
                // Only enable buttons if they are not already disabled due to completion
                if (checkInButton) {
                    const isCompleted = checkInButton.innerHTML.includes('Sudah Melakukan');
                    if (!isCompleted) {
                        checkInButton.disabled = false;
                        checkInButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                }
                
                if (checkOutButton) {
                    const isCompleted = checkOutButton.innerHTML.includes('Sudah Check Out');
                    if (!isCompleted) {
                        checkOutButton.disabled = false;
                        checkOutButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                }
            } else {
                // Show warning, show GPS inactive
                if (warningDiv) warningDiv.classList.remove('hidden');
                if (gpsIndicator) {
                    gpsIndicator.classList.remove('bg-green-500');
                    gpsIndicator.classList.add('bg-red-500');
                }
                if (gpsText) {
                    gpsText.textContent = 'GPS Tidak Aktif';
                    gpsText.classList.remove('text-green-600');
                    gpsText.classList.add('text-red-600');
                }
                
                // Disable buttons unless they are already completed
                if (checkInButton && !checkInButton.innerHTML.includes('Sudah Melakukan')) {
                    checkInButton.disabled = true;
                    checkInButton.classList.add('opacity-50', 'cursor-not-allowed');
                }
                
                if (checkOutButton && !checkOutButton.innerHTML.includes('Sudah Check Out')) {
                    checkOutButton.disabled = true;
                    checkOutButton.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }
        }

        // Refresh location
        function refreshLocation() {
            showNotification('Memperbarui lokasi...', 'success');
            getUserLocation();
        }

        // Update catatan character count
        function updateCatatanCount() {
            const catatanElement = document.getElementById('catatan');
            const countElement = document.getElementById('catatanCount');
            
            if (!catatanElement || !countElement) {
                return; // Exit if elements don't exist
            }
            
            const catatan = catatanElement.value;
            const count = catatan.length;
            countElement.textContent = `${count}/200`;
        }

        // Clear catatan
        function clearCatatan() {
            const catatanElement = document.getElementById('catatan');
            if (catatanElement) {
                catatanElement.value = '';
                updateCatatanCount();
                showNotification('Catatan dihapus', 'success');
            }
        }

        // Get catatan value
        function getCatatan() {
            const catatanElement = document.getElementById('catatan');
            return catatanElement ? catatanElement.value : '';
        }

        // Save catatan to localStorage
        function saveCatatan() {
            const catatan = getCatatan();
            if (catatan.trim()) {
                localStorage.setItem('absensiCatatan', catatan);
            }
        }

        // Load catatan from localStorage
        function loadCatatan() {
            const savedCatatan = localStorage.getItem('absensiCatatan');
            if (savedCatatan) {
                // Only load catatan if user hasn't checked in today
                fetch('/api/attendance-status')
                    .then(response => response.json())
                    .then(data => {
                        if (data.attendance && !data.attendance.check_out) {
                            document.getElementById('catatan').value = savedCatatan;
                            updateCatatanCount();
                        } else {
                            // Clear localStorage if attendance is complete
                            localStorage.removeItem('absensiCatatan');
                        }
                    })
                    .catch(error => {
                        console.error('Error checking attendance status:', error);
                        // Load anyway if there's an error
                        document.getElementById('catatan').value = savedCatatan;
                        updateCatatanCount();
                    });
            }
        }

        // Check In function (optimized)
        async function checkIn() {
            // Check if location is detected
            if (!locationDetected) {
                showNotification('Lokasi belum terdeteksi. Harap refresh lokasi terlebih dahulu.', 'warning');
                return;
            }
            
            const catatan = getCatatan();
            
            // Show loading state
            const checkInButton = document.querySelector('#checkInStatus button');
            if (!checkInButton) {
                showNotification('Tombol Check In tidak ditemukan', 'error');
                return;
            }
            
            const originalText = checkInButton.innerHTML;
            checkInButton.disabled = true;
            checkInButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            
            try {
                const location = await getCurrentLocation();
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    throw new Error('CSRF token tidak ditemukan');
                }
                
                const response = await fetch('/api/check-in', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                    },
                    body: JSON.stringify({
                        catatan: catatan,
                        location: location
                    })
                });

                // Check if response is ok before parsing JSON
                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(`HTTP error! status: ${response.status}: ${errorText}`);
                }

                const data = await response.json();

                if (data.success) {
                    // Update UI immediately without reload
                    const checkInTimeElement = document.getElementById('checkInTime');
                    const checkInStatusTextElement = document.getElementById('checkInStatusText');
                    
                    if (checkInTimeElement) checkInTimeElement.textContent = data.check_in_time;
                    if (checkInStatusTextElement) checkInStatusTextElement.textContent = 'Sudah check in';
                    
                    // Add location display if available
                    if (data.check_in_location && data.check_in_location !== 'Unknown') {
                        const checkInDiv = document.getElementById('checkInStatus');
                        if (checkInDiv) {
                            const existingLocation = checkInDiv.querySelector('.bg-green-100');
                            if (existingLocation) {
                                existingLocation.remove();
                            }
                            
                            const locationDiv = document.createElement('div');
                            locationDiv.className = 'mt-2 p-2 bg-green-100 rounded-lg';
                            locationDiv.innerHTML = `
                                <p class="text-xs text-green-700">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    ${data.check_in_location.length > 50 ? data.check_in_location.substring(0, 50) + '...' : data.check_in_location}
                                </p>
                            `;
                            checkInDiv.appendChild(locationDiv);
                        }
                    }
                    
                    // Update button to disabled state with gray color
                    checkInButton.disabled = true;
                    checkInButton.classList.remove('bg-green-500', 'hover:bg-green-600', 'text-white');
                    checkInButton.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed', 'opacity-50');
                    checkInButton.innerHTML = '<i class="fas fa-check mr-2"></i>Sudah Melakukan';
                    
                    // Show check-out section if it was hidden
                    const checkOutSection = document.getElementById('checkOutStatus');
                    if (checkOutSection) {
                        checkOutSection.style.display = 'block';
                    }
                    
                    // Clear catatan from localStorage
                    localStorage.removeItem('absensiCatatan');
                    
                    // Clear catatan field
                    const catatanElement = document.getElementById('catatan');
                    if (catatanElement) {
                        catatanElement.value = '';
                        updateCatatanCount();
                    }
                    
                    // Show success notification
                    if (data.catatan) {
                        showNotification('Check In berhasil dengan catatan!', 'success');
                    } else {
                        showNotification('Check In berhasil!', 'success');
                    }
                    
                    // Refresh halaman setelah check in berhasil
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                    
                } else {
                    // Restore button and show error
                    checkInButton.disabled = false;
                    checkInButton.innerHTML = originalText;
                    showNotification(data.error || 'Gagal check in', 'error');
                }
            } catch (error) {
                console.error('Check in error:', error);
                // Restore button and show error
                checkInButton.disabled = false;
                checkInButton.innerHTML = originalText;
                
                // Provide more specific error message
                let errorMessage = 'Terjadi kesalahan saat check in';
                if (error.name === 'TypeError' && error.message.includes('fetch')) {
                    errorMessage = 'Koneksi gagal. Periksa koneksi internet Anda.';
                } else if (error.message) {
                    errorMessage = `Error: ${error.message}`;
                }
                
                showNotification(errorMessage, 'error');
            }
        }

        // Check Out function (optimized)
        async function checkOut() {
            // Check if location is detected
            if (!locationDetected) {
                showNotification('Lokasi belum terdeteksi. Harap refresh lokasi terlebih dahulu.', 'warning');
                return;
            }
            
            const catatan = getCatatan();
            
            // Show loading state
            const checkOutButton = document.getElementById('checkOutButton');
            if (!checkOutButton) {
                showNotification('Tombol Check Out tidak ditemukan', 'error');
                return;
            }
            
            const originalText = checkOutButton.innerHTML;
            checkOutButton.disabled = true;
            checkOutButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            
            try {
                const location = await getCurrentLocation();
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    throw new Error('CSRF token tidak ditemukan');
                }
                
                const response = await fetch('/api/check-out', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                    },
                    body: JSON.stringify({
                        catatan: catatan,
                        location: location
                    })
                });

                // Check if response is ok before parsing JSON
                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(`HTTP error! status: ${response.status}: ${errorText}`);
                }

                const data = await response.json();

                if (data.success) {
                    // Update UI immediately without reload
                    const checkOutTimeElement = document.getElementById('checkOutTime');
                    const checkOutStatusTextElement = document.getElementById('checkOutStatusText');
                    
                    if (checkOutTimeElement) checkOutTimeElement.textContent = data.check_out_time;
                    if (checkOutStatusTextElement) checkOutStatusTextElement.textContent = 'Sudah check out';
                    
                    // Add location display if available
                    if (data.check_out_location && data.check_out_location !== 'Unknown') {
                        const checkOutDiv = document.getElementById('checkOutStatus');
                        if (checkOutDiv) {
                            const existingLocation = checkOutDiv.querySelector('.bg-red-100');
                            if (existingLocation) {
                                existingLocation.remove();
                            }
                            
                            const locationDiv = document.createElement('div');
                            locationDiv.className = 'mt-2 p-2 bg-red-100 rounded-lg';
                            locationDiv.innerHTML = `
                                <p class="text-xs text-red-700">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    ${data.check_out_location.length > 50 ? data.check_out_location.substring(0, 50) + '...' : data.check_out_location}
                                </p>
                            `;
                            checkOutDiv.appendChild(locationDiv);
                        }
                    }
                    
                    // Update check out button to disabled state with gray color
                    checkOutButton.disabled = true;
                    checkOutButton.classList.add('opacity-50', 'cursor-not-allowed');
                    checkOutButton.classList.remove('bg-red-500', 'hover:bg-red-600', 'text-white');
                    checkOutButton.classList.add('bg-gray-300', 'text-gray-500');
                    checkOutButton.innerHTML = '<i class="fas fa-check mr-2"></i>Sudah Check Out';
                    
                    // Clear catatan from localStorage
                    localStorage.removeItem('absensiCatatan');
                    
                    // Clear catatan field
                    const catatanElement = document.getElementById('catatan');
                    if (catatanElement) {
                        catatanElement.value = '';
                        updateCatatanCount();
                    }
                    
                    // Show success notification
                    if (data.catatan) {
                        showNotification('Check Out berhasil dengan catatan!', 'success');
                    } else {
                        showNotification('Check Out berhasil!', 'success');
                    }
                    
                    // Refresh halaman setelah check out berhasil
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                    
                } else {
                    // Restore button and show error
                    checkOutButton.disabled = false;
                    checkOutButton.innerHTML = originalText;
                    showNotification(data.error || 'Gagal check out', 'error');
                }
            } catch (error) {
                console.error('Check out error:', error);
                // Restore button and show error
                checkOutButton.disabled = false;
                checkOutButton.innerHTML = originalText;
                
                // Provide more specific error message
                let errorMessage = 'Terjadi kesalahan saat check out';
                if (error.name === 'TypeError' && error.message.includes('fetch')) {
                    errorMessage = 'Koneksi gagal. Periksa koneksi internet Anda.';
                } else if (error.message) {
                    errorMessage = `Error: ${error.message}`;
                }
                
                showNotification(errorMessage, 'error');
            }
        }

        // Load attendance status from database
        async function loadAttendanceStatus() {
            try {
                const response = await fetch('/api/attendance-status');
                const data = await response.json();

                if (data.attendance) {
                    const attendance = data.attendance;
                    
                    // Update check in status
                    if (attendance.check_in) {
                        const checkInTimeElement = document.getElementById('checkInTime');
                        const checkInStatusTextElement = document.getElementById('checkInStatusText');
                        
                        if (checkInTimeElement) checkInTimeElement.textContent = attendance.check_in;
                        if (checkInStatusTextElement) checkInStatusTextElement.textContent = 'Sudah check in';
                        
                        // Update check in button to "Sudah Melakukan"
                        const checkInButton = document.getElementById('checkInButton');
                        if (checkInButton) {
                            checkInButton.disabled = true;
                            checkInButton.classList.remove('bg-green-500', 'hover:bg-green-600', 'text-white');
                            checkInButton.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed', 'opacity-50');
                            checkInButton.innerHTML = '<i class="fas fa-check mr-2"></i>Sudah Melakukan';
                        }
                    }
                    
                    // Update check out status
                    if (attendance.check_out) {
                        const checkOutTimeElement = document.getElementById('checkOutTime');
                        const checkOutStatusTextElement = document.getElementById('checkOutStatusText');
                        
                        if (checkOutTimeElement) checkOutTimeElement.textContent = attendance.check_out;
                        if (checkOutStatusTextElement) checkOutStatusTextElement.textContent = 'Sudah check out';
                        
                        // Update check out button to "Sudah Check Out"
                        const checkOutButton = document.getElementById('checkOutButton');
                        if (checkOutButton) {
                            checkOutButton.disabled = true;
                            checkOutButton.classList.add('opacity-50', 'cursor-not-allowed');
                            checkOutButton.classList.remove('bg-red-500', 'hover:bg-red-600', 'text-white');
                            checkOutButton.classList.add('bg-gray-300', 'text-gray-500');
                            checkOutButton.innerHTML = '<i class="fas fa-check mr-2"></i>Sudah Check Out';
                        }
                        
                        const totalHoursElement = document.getElementById('totalHours');
                        if (totalHoursElement && attendance.total_jam) {
                            totalHoursElement.textContent = `${attendance.total_jam} jam`;
                        }
                    }
                    
                    // Load catatan if exists (but don't show for completed attendance)
                    if (attendance.keterangan && !attendance.check_out) {
                        const catatanElement = document.getElementById('catatan');
                        if (catatanElement) {
                            catatanElement.value = attendance.keterangan;
                            updateCatatanCount();
                        }
                    }
                }
                
                // Additional fallback: Check status text and disable buttons accordingly
                const checkOutStatusText = document.getElementById('checkOutStatusText');
                const checkOutButton = document.getElementById('checkOutButton');
                
                if (checkOutStatusText && checkOutButton && checkOutStatusText.textContent.includes('Sudah check out')) {
                    checkOutButton.disabled = true;
                    checkOutButton.classList.add('opacity-50', 'cursor-not-allowed');
                    checkOutButton.classList.remove('bg-red-500', 'hover:bg-red-600', 'text-white');
                    checkOutButton.classList.add('bg-gray-300', 'text-gray-500');
                    checkOutButton.innerHTML = '<i class="fas fa-check mr-2"></i>Sudah Check Out';
                }
                
            } catch (error) {
                console.error('Load attendance status error:', error);
            }
        }

        // Show notification
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg text-white z-50 ${
                type === 'success' ? 'bg-green-500' : 
                type === 'warning' ? 'bg-yellow-500' : 
                'bg-red-500'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${
                        type === 'success' ? 'fa-check-circle' : 
                        type === 'warning' ? 'fa-exclamation-triangle' : 
                        'fa-exclamation-circle'
                    } mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Show all attendance history
        async function showAllHistory() {
            try {
                showNotification('Memuat semua riwayat absensi...', 'success');
                
                // Redirect to full history page or fetch all data
                const response = await fetch('/api/attendance-history');
                const data = await response.json();
                
                if (data.success) {
                    // Update the attendance history section with all data
                    updateHistoryDisplay(data.attendance);
                    showNotification('Semua riwayat absensi dimuat', 'success');
                } else {
                    showNotification('Gagal memuat riwayat lengkap', 'error');
                }
            } catch (error) {
                console.error('Error loading full history:', error);
                showNotification('Terjadi kesalahan saat memuat riwayat', 'error');
            }
        }

        // Update history display
        function updateHistoryDisplay(allAttendance) {
            const historyContainer = document.querySelector('#attendanceHistory');
            if (!historyContainer) return;
            
            let html = '';
            if (allAttendance.length > 0) {
                allAttendance.forEach(attendance => {
                    const statusIcon = attendance.check_in && attendance.check_out 
                        ? '<i class="fas fa-check-circle text-green-600"></i>'
                        : attendance.check_in 
                            ? '<i class="fas fa-clock text-yellow-600"></i>'
                            : '<i class="fas fa-minus-circle text-gray-600"></i>';
                    
                    const statusBadge = attendance.check_in && attendance.check_out
                        ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Lengkap</span>'
                        : attendance.check_in
                            ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Check In Saja</span>'
                            : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Tidak Hadir</span>';
                    
                    const keterangan = attendance.keterangan 
                        ? `<p class="text-xs text-gray-600 mt-1"><i class="fas fa-sticky-note mr-1"></i>${attendance.keterangan.replace('|', '<br>')}</p>`
                        : '';
                    
                    const totalHours = attendance.total_jam 
                        ? `<span class="text-xs text-blue-600 font-medium"><i class="fas fa-hourglass-half mr-1"></i>${attendance.total_jam} jam</span>`
                        : '';
                    
                    html += `
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 sm:p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-start sm:items-center space-x-3 sm:space-x-4 w-full sm:w-auto">
                                <div class="flex-shrink-0">
                                    <div class="bg-green-100 rounded-full p-2">
                                        ${statusIcon}
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">${attendance.tanggal}</p>
                                    <div class="flex flex-wrap items-center gap-2 sm:gap-4 mt-1 sm:mt-2">
                                        <span class="text-xs text-gray-500 whitespace-nowrap">
                                            <i class="fas fa-sign-in-alt text-green-500 mr-1"></i>${attendance.check_in || '--:--'}
                                        </span>
                                        ${attendance.check_in_location ? 
                                            `<span class="text-xs text-green-600 truncate max-w-[120px] sm:max-w-xs" title="${attendance.check_in_location}">
                                                <i class="fas fa-map-marker-alt mr-1"></i>${attendance.check_in_location.length > 15 ? attendance.check_in_location.substring(0, 15) + '...' : attendance.check_in_location}
                                            </span>` : ''
                                        }
                                        <span class="text-xs text-gray-500 whitespace-nowrap">
                                            <i class="fas fa-sign-out-alt text-red-500 mr-1"></i>${attendance.check_out || '--:--'}
                                        </span>
                                        ${attendance.check_out_location ? 
                                            `<span class="text-xs text-red-600 truncate max-w-[120px] sm:max-w-xs" title="${attendance.check_out_location}">
                                                <i class="fas fa-map-marker-alt mr-1"></i>${attendance.check_out_location.length > 15 ? attendance.check_out_location.substring(0, 15) + '...' : attendance.check_out_location}
                                            </span>` : ''
                                        }
                                        ${totalHours}
                                    </div>
                                    ${keterangan}
                                </div>
                            </div>
                            <div class="text-right mt-2 sm:mt-0">
                                ${statusBadge}
                            </div>
                        </div>
                    `;
                });
            } else {
                html = `
                    <div class="text-center py-8">
                        <i class="fas fa-history text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500">Belum ada riwayat absensi</p>
                        <p class="text-sm text-gray-400 mt-2">Lakukan check in/check out untuk melihat riwayat</p>
                    </div>
                `;
            }
            
            historyContainer.innerHTML = html;
        }

        // Get current location (unified function)
        async function getCurrentLocation() {
            return new Promise((resolve) => {
                if (!navigator.geolocation) {
                    resolve('Location not available');
                    return;
                }

                // Use cached position if available and recent
                navigator.geolocation.getCurrentPosition(
                    async (position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        try {
                            // Get address from coordinates (use unified function)
                            const address = await getAddressFromCoords(lat, lng);
                            currentAddress = address || `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                            resolve(currentAddress);
                        } catch (error) {
                            console.error('Error getting address:', error);
                            // Fallback to coordinates if geocoding fails
                            resolve(`${lat.toFixed(6)}, ${lng.toFixed(6)}`);
                        }
                    },
                    (error) => {
                        console.error('Geolocation error:', error);
                        resolve('Location not available');
                    },
                    {
                        enableHighAccuracy: false, // Set to false for faster response
                        timeout: 3000, // Reduced timeout for faster response
                        maximumAge: 60000 // 1 minute cache for faster response
                    }
                );
            });
        }

        // Initialize
        updateTime();
        setInterval(updateTime, 1000);
        
        // Initialize location status as not detected
        locationDetected = false;
        
        // Load attendance status first to set correct button states
        loadAttendanceStatus();
        loadCatatan();
        
        // Then start location detection
        getUserLocation();

        // Periodically check attendance status to handle admin deletions
        setInterval(async () => {
            try {
                const response = await fetch('/api/attendance-status');
                const data = await response.json();
                
                // If attendance is null but UI shows completed, refresh the page
                if (!data.attendance) {
                    const checkInButton = document.getElementById('checkInButton');
                    const checkOutButton = document.getElementById('checkOutButton');
                    
                    // Check if buttons show completed status but no attendance data
                    const checkInCompleted = checkInButton && checkInButton.innerHTML.includes('Sudah Melakukan');
                    const checkOutCompleted = checkOutButton && checkInButton.innerHTML.includes('Sudah Check Out');
                    
                    if (checkInCompleted || checkOutCompleted) {
                        // Clear cache and reload
                        await fetch('/api/clear-cache', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        // Reload page to update UI
                        window.location.reload();
                    }
                }
            } catch (error) {
                console.log('Status check failed:', error);
            }
        }, 10000); // Check every 10 seconds

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
</body>
</html>
