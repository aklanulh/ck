<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender Jadwal Kunjungan - CatatanKerja</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
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
        
        /* Custom calendar styles */
        .fc-event {
            border-radius: 6px !important;
            padding: 2px 6px !important;
            font-size: 12px !important;
            border: none !important;
        }
        
        .fc-event-title {
            font-weight: 600 !important;
        }
        
        .fc-daygrid-day-number {
            color: #374151 !important;
            font-weight: 500 !important;
        }
        
        .fc-toolbar-title {
            color: #111827 !important;
            font-weight: 700 !important;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
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
                    <a href="{{ route('absensi') }}" class="py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium flex items-center">
                        <i class="fas fa-clock mr-2"></i>Absensi
                    </a>
                    
                    <!-- Daily Report Menu -->
                    <a href="{{ route('report') }}" class="py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium flex items-center">
                        <i class="fas fa-file-alt mr-2"></i>Daily Report
                    </a>
                    
                    <!-- Calendar Menu -->
                    <a href="{{ route('calendar') }}" class="py-4 px-1 border-b-2 border-purple-500 text-purple-600 font-medium flex items-center">
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
                <a href="{{ route('absensi') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-clock mr-2"></i>Absensi
                </a>
                
                <!-- Daily Report Mobile Menu -->
                <a href="{{ route('report') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-file-alt mr-2"></i>Daily Report
                </a>
                
                <!-- Calendar Mobile Menu -->
                <a href="{{ route('calendar') }}" class="block py-2 px-3 text-purple-600 bg-purple-50 rounded-lg transition-colors">
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

        <div class="px-4 py-6 sm:px-0">
            <!-- Page Title -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Kalender Jadwal Kunjungan</h2>
                <p class="text-gray-600 mt-2">Kelola jadwal kunjungan teknisi dan marketing</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                            <i class="fas fa-calendar text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Jadwal</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_schedules'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                            <i class="fas fa-calendar-day text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Bulan Ini</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['this_month'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-orange-100 rounded-lg p-3">
                            <i class="fas fa-map-marker-alt text-orange-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Lokasi</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $schedules->pluck('lokasi_kunjungan')->unique()->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                            <i class="fas fa-clock text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Jadwal</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $schedules->count() }} jadwal
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar Section -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Kalender Kunjungan</h3>
                            <p class="text-sm text-gray-500 mt-1">Klik pada tanggal untuk membuat jadwal baru</p>
                        </div>
                        <button onclick="openAddModal()" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            <i class="fas fa-plus mr-2"></i>Tambah Jadwal
                        </button>
                    </div>
                </div>
                
                <div class="p-6">
                    <div id='calendar'></div>
                </div>
            </div>

            <!-- Upcoming Schedules -->
            @if($schedules->count() > 0)
            <div class="mt-8 bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Jadwal Mendatang</h3>
                    <p class="text-sm text-gray-500 mt-1">Jadwal kunjungan untuk bulan ini</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($schedules as $schedule)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="bg-orange-100 rounded-lg p-2">
                                        <i class="fas fa-calendar-check text-orange-600"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $schedule->judul_kunjungan }}</p>
                                    <div class="flex items-center space-x-4 mt-1">
                                        <span class="text-xs text-gray-500">
                                            <i class="fas fa-calendar text-blue-500 mr-1"></i>
                                            {{ $schedule->formatted_tanggal }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            <i class="fas fa-map-marker-alt text-red-500 mr-1"></i>
                                            {{ $schedule->lokasi_kunjungan }}
                                        </span>
                                    </div>
                                    @if($schedule->deskripsi_kunjungan)
                                    <p class="text-xs text-gray-600 mt-2">
                                        <i class="fas fa-sticky-note mr-1"></i>
                                        {{ Str::limit($schedule->deskripsi_kunjungan, 100) }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="editSchedule({{ $schedule->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </button>
                                <button onclick="deleteSchedule({{ $schedule->id }})" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    <i class="fas fa-trash mr-1"></i>Hapus
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </main>

    <!-- Add/Edit Schedule Modal -->
    <div id="scheduleModal" class="modal">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Tambah Jadwal Kunjungan</h3>
            </div>
            
            <form id="scheduleForm" class="px-6 py-4">
                @csrf
                <input type="hidden" id="scheduleId" name="schedule_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Judul Kunjungan <span class="text-red-500">*</span>
                            <span class="text-xs text-gray-500 ml-2">(Max 20 karakter)</span>
                        </label>
                        <input type="text" id="judul_kunjungan" name="judul_kunjungan" required maxlength="20"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                               placeholder="Masukkan judul kunjungan">
                        <div class="text-xs text-gray-500 mt-1">
                            <span id="judulCount">0</span>/20 karakter
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Lokasi Kunjungan
                            <span class="text-xs text-gray-500 ml-2">(Max 60 karakter)</span>
                        </label>
                        <input type="text" id="lokasi_kunjungan" name="lokasi_kunjungan" maxlength="60"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                               placeholder="Masukkan lokasi kunjungan (opsional)">
                        <div class="text-xs text-gray-500 mt-1">
                            <span id="lokasiCount">0</span>/60 karakter
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Deskripsi Kunjungan
                            <span class="text-xs text-gray-500 ml-2">(Max 200 karakter)</span>
                        </label>
                        <textarea id="deskripsi_kunjungan" name="deskripsi_kunjungan" rows="3" maxlength="200"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                  placeholder="Deskripsi kunjungan (opsional)"></textarea>
                        <div class="text-xs text-gray-500 mt-1">
                            <span id="deskripsiCount">0</span>/200 karakter
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Kunjungan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="tanggal_kunjungan" name="tanggal_kunjungan" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                               placeholder="Pilih tanggal kunjungan">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-medium transition">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>
    
    <script>
        // Calendar initialization
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                locale: 'id',
                height: 'auto',
                events: '/calendar/events',
                selectable: true,
                selectMirror: true,
                select: function(arg) {
                    // Set selected date in form
                    console.log('Date selected:', arg.startStr);
                    document.getElementById('tanggal_kunjungan').value = arg.startStr.split('T')[0];
                    openAddModal();
                    calendar.unselect();
                },
                eventClick: function(arg) {
                    // Edit event when clicked
                    editSchedule(arg.event.id);
                },
                eventDidMount: function(info) {
                    // Add tooltip with event details
                    var tooltip = `
                        <div class="text-xs">
                            <strong>${info.event.title}</strong><br>
                            Tanggal: ${info.event.extendedProps.formatted_tanggal}<br>
                            Lokasi: ${info.event.extendedProps.location || '-'}<br>
                            ${info.event.description ? 'Deskripsi: ' + info.event.description : ''}
                        </div>
                    `;
                    
                    info.el.setAttribute('title', tooltip.replace(/<[^>]*>/g, '\n'));
                }
            });
            
            calendar.render();
        });

        // Modal functions
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Jadwal Kunjungan';
            document.getElementById('scheduleForm').reset();
            document.getElementById('scheduleId').value = '';
            document.getElementById('scheduleModal').classList.add('show');
        }

        function closeModal() {
            document.getElementById('scheduleModal').classList.remove('show');
        }

        function editSchedule(id) {
            // Fetch schedule data and populate form
            fetch(`/calendar/events`)
                .then(response => response.json())
                .then(events => {
                    const event = events.find(e => e.id == id);
                    if (event) {
                        document.getElementById('modalTitle').textContent = 'Edit Jadwal Kunjungan';
                        document.getElementById('scheduleId').value = event.id;
                        document.getElementById('judul_kunjungan').value = event.title;
                        document.getElementById('lokasi_kunjungan').value = event.location || '';
                        document.getElementById('deskripsi_kunjungan').value = event.description || '';
                        document.getElementById('tanggal_kunjungan').value = event.start.split('T')[0];
                        document.getElementById('scheduleModal').classList.add('show');
                    }
                });
        }

        function deleteSchedule(id) {
            if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
                fetch(`/calendar/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.error || 'Gagal menghapus jadwal');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus jadwal');
                });
            }
        }

        // Form submission
        document.getElementById('scheduleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            console.log('Form submission started');
            
            const formData = new FormData(this);
            const scheduleId = document.getElementById('scheduleId').value;
            const url = scheduleId ? `/calendar/${scheduleId}` : '/calendar';
            const method = scheduleId ? 'PUT' : 'POST';
            
            console.log('URL:', url, 'Method:', method);
            
            // Convert FormData to JSON
            const jsonData = {};
            formData.forEach((value, key) => {
                if (key !== '_token' && key !== 'schedule_id') {
                    jsonData[key] = value;
                }
            });
            
            console.log('Form data:', jsonData);
            
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(jsonData)
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    closeModal();
                    location.reload();
                } else {
                    alert(data.error || 'Gagal menyimpan jadwal');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan jadwal');
            });
        });

        // Character counters
        function updateCharCount(inputId, countId, maxLength) {
            const input = document.getElementById(inputId);
            const count = document.getElementById(countId);
            
            input.addEventListener('input', function() {
                const currentLength = this.value.length;
                count.textContent = currentLength;
                
                if (currentLength >= maxLength * 0.9) {
                    count.classList.add('text-orange-500');
                } else {
                    count.classList.remove('text-orange-500');
                }
                
                if (currentLength >= maxLength) {
                    count.classList.add('text-red-500');
                } else {
                    count.classList.remove('text-red-500');
                }
            });
        }

        // Initialize character counters
        updateCharCount('judul_kunjungan', 'judulCount', 20);
        updateCharCount('lokasi_kunjungan', 'lokasiCount', 60);
        updateCharCount('deskripsi_kunjungan', 'deskripsiCount', 200);

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('scheduleModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        // Toggle mobile menu
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
    </script>
</body>
</html>
