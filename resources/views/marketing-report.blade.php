<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Marketing - CatatanKerja</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ url('/public/favicons/briefcase.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                    
                    <!-- Marketing Report Menu -->
                    <a href="{{ route('marketing-report') }}" class="py-4 px-1 border-b-2 border-purple-500 text-purple-600 font-medium flex items-center">
                        <i class="fas fa-bullhorn mr-2"></i>Marketing Report
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
                <a href="{{ route('absensi') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-clock mr-2"></i>Absensi
                </a>
                
                <!-- Daily Report Mobile Menu -->
                <a href="{{ route('report') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-file-alt mr-2"></i>Daily Report
                </a>
                
                <!-- Marketing Report Mobile Menu -->
                <a href="{{ route('marketing-report') }}" class="block py-2 px-3 text-purple-600 bg-purple-50 rounded-lg transition-colors">
                    <i class="fas fa-bullhorn mr-2"></i>Marketing Report
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
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Laporan Marketing</h2>
                <p class="text-gray-600 mt-1">Catat aktivitas marketing dan kunjungan ke berbagai lokasi</p>
            </div>

            <!-- Action Buttons -->
            <div class="mb-6 flex flex-wrap gap-3">
                <a href="{{ route('marketing-reports.history') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                    <i class="fas fa-history mr-2"></i>Riwayat Laporan
                </a>
                <a href="{{ route('marketing-reports.drafts') }}" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors flex items-center">
                    <i class="fas fa-file-alt mr-2"></i>Draft Laporan
                </a>
            </div>

            <!-- Form -->
            <form id="marketingReportForm" class="space-y-6">
                @csrf
                <!-- Date and Location -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Kunjungan</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>

                <!-- Dynamic Location Sections -->
                <div id="locationsContainer" class="space-y-4">
                    <!-- Location 1 (Default) -->
                    <div class="location-section bg-white shadow rounded-lg p-6" data-location-id="1">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-lg font-medium text-gray-900">Lokasi 1</h4>
                            <button type="button" class="remove-location-btn hidden text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                            <input type="text" name="locations[1][lokasi]" required
                                   placeholder="Masukkan nama lokasi"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kontak</label>
                            <input type="text" name="locations[1][nama_kontak]" required
                                   placeholder="Nama orang yang ditemui"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Kontak</label>
                            <input type="tel" name="locations[1][nomor_kontak]" required
                                   placeholder="Nomor telepon/WhatsApp"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                        
                        <div>
                            <!-- Empty div for grid layout -->
                        </div>
                    </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Laporan Kunjungan</label>
                            <textarea name="locations[1][laporan]" rows="4" required
                                      placeholder="Deskripsi hasil kunjungan, diskusi, atau aktivitas marketing..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                        </div>
                        
                        <!-- Photo Section -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Foto Bukti</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                                <input type="file" name="location_photos_1[]" accept="image/*" multiple class="hidden" id="photoInput_1">
                                <label for="photoInput_1" class="cursor-pointer">
                                    <i class="fas fa-camera text-gray-400 text-3xl mb-2"></i>
                                    <p class="text-sm text-gray-600">Klik untuk upload foto bukti</p>
                                    <p class="text-xs text-gray-500">Bisa multiple foto</p>
                                </label>
                            </div>
                            <div id="photoPreview_1" class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-2"></div>
                        </div>
                    </div>
                </div>

                <!-- Add Location Button -->
                <div class="text-center">
                    <button type="button" id="addLocationBtn" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Tambah Lokasi
                    </button>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="saveDraft()" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan ke Draft
                    </button>
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Notification -->
    <div id="notification" class="fixed bottom-4 right-4 hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-4 flex items-center space-x-3">
            <div id="notificationIcon"></div>
            <div>
                <p id="notificationText" class="text-sm font-medium text-gray-900"></p>
            </div>
        </div>
    </div>

    <script>
        let locationCount = 1;
        const maxLocations = 10;

        // Mobile menu toggle
        document.querySelector('.mobile-menu-button').addEventListener('click', function() {
            document.querySelector('.mobile-menu').classList.toggle('hidden');
        });

        // Add location
        document.getElementById('addLocationBtn').addEventListener('click', function() {
            if (locationCount >= maxLocations) {
                showNotification('Maksimal ' + maxLocations + ' lokasi', 'error');
                return;
            }

            locationCount++;
            const locationId = locationCount;
            
            const locationHtml = `
                <div class="location-section bg-white shadow rounded-lg p-6" data-location-id="${locationId}">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-medium text-gray-900">Lokasi ${locationId}</h4>
                        <button type="button" class="remove-location-btn text-red-600 hover:text-red-800" onclick="removeLocation(${locationId})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                            <input type="text" name="locations[${locationId}][lokasi]" required
                                   placeholder="Masukkan nama lokasi"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kontak</label>
                            <input type="text" name="locations[${locationId}][nama_kontak]" required
                                   placeholder="Nama orang yang ditemui"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Kontak</label>
                            <input type="tel" name="locations[${locationId}][nomor_kontak]" required
                                   placeholder="Nomor telepon/WhatsApp"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                        
                        <div>
                            <!-- Empty div for grid layout -->
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Laporan Kunjungan</label>
                        <textarea name="locations[${locationId}][laporan]" rows="4" required
                                  placeholder="Deskripsi hasil kunjungan, diskusi, atau aktivitas marketing..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                    </div>
                    
                    <!-- Photo Section -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Bukti</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                            <input type="file" name="location_photos_${locationId}[]" accept="image/*" multiple class="hidden" id="photoInput_${locationId}">
                            <label for="photoInput_${locationId}" class="cursor-pointer">
                                <i class="fas fa-camera text-gray-400 text-3xl mb-2"></i>
                                <p class="text-sm text-gray-600">Klik untuk upload foto bukti</p>
                                <p class="text-xs text-gray-500">Bisa multiple foto</p>
                            </label>
                        </div>
                        <div id="photoPreview_${locationId}" class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-2"></div>
                    </div>
                </div>
            `;
            
            document.getElementById('locationsContainer').insertAdjacentHTML('beforeend', locationHtml);
            
            // Add photo preview functionality
            setupPhotoPreview(locationId);
            
            // Show remove button for first location if we have more than 1
            if (locationCount > 1) {
                document.querySelector('.location-section[data-location-id="1"] .remove-location-btn').classList.remove('hidden');
            }
        });

        // Remove location
        function removeLocation(locationId) {
            if (locationCount <= 1) {
                showNotification('Minimal harus ada 1 lokasi', 'error');
                return;
            }

            const element = document.querySelector(`.location-section[data-location-id="${locationId}"]`);
            element.remove();
            locationCount--;

            // Hide remove button for first location if we only have 1
            if (locationCount === 1) {
                document.querySelector('.location-section[data-location-id="1"] .remove-location-btn').classList.add('hidden');
            }

            // Renumber locations
            const sections = document.querySelectorAll('.location-section');
            sections.forEach((section, index) => {
                const newId = index + 1;
                section.setAttribute('data-location-id', newId);
                section.querySelector('h4').textContent = 'Lokasi ' + newId;
                section.querySelector('.remove-location-btn').setAttribute('onclick', `removeLocation(${newId})`);
            });
        }

        // Photo preview setup
        function setupPhotoPreview(locationId) {
            const input = document.getElementById(`photoInput_${locationId}`);
            const preview = document.getElementById(`photoPreview_${locationId}`);
            
            input.addEventListener('change', function(e) {
                preview.innerHTML = '';
                const files = Array.from(e.target.files);
                
                files.forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'relative';
                            div.innerHTML = `
                                <img src="${e.target.result}" class="w-full h-24 object-cover rounded">
                                <button type="button" onclick="this.parentElement.remove()" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                    <i class="fas fa-times"></i>
                                </button>
                            `;
                            preview.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });
        }

        // Setup initial photo preview
        setupPhotoPreview(1);

        // Form submission
        document.getElementById('marketingReportForm').addEventListener('submit', function(e) {
            e.preventDefault();
            submitForm();
        });

        function submitForm() {
            const formData = new FormData(document.getElementById('marketingReportForm'));
            
            // Show loading
            const submitBtn = document.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
            submitBtn.disabled = true;

            fetch('/marketing-report/submit', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Laporan marketing berhasil dikirim!', 'success');
                    setTimeout(() => {
                        window.location.href = '/report/history';
                    }, 1000);
                } else {
                    showNotification(data.error || 'Gagal mengirim laporan', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat mengirim laporan', 'error');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }

        function saveDraft() {
            const formData = new FormData(document.getElementById('marketingReportForm'));
            
            // Show loading
            const draftBtn = document.querySelector('button[onclick="saveDraft()"]');
            const originalText = draftBtn.innerHTML;
            draftBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            draftBtn.disabled = true;

            fetch('/marketing-report/draft', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Draft berhasil disimpan!', 'success');
                    setTimeout(() => {
                        window.location.href = '/report/drafts';
                    }, 1000);
                } else {
                    showNotification(data.error || 'Gagal menyimpan draft', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat menyimpan draft', 'error');
            })
            .finally(() => {
                draftBtn.innerHTML = originalText;
                draftBtn.disabled = false;
            });
        }

        function showNotification(message, type = 'info') {
            const notification = document.getElementById('notification');
            const icon = document.getElementById('notificationIcon');
            const text = document.getElementById('notificationText');
            
            text.textContent = message;
            
            // Set icon and color based on type
            if (type === 'success') {
                icon.innerHTML = '<i class="fas fa-check-circle text-green-500 text-xl"></i>';
            } else if (type === 'error') {
                icon.innerHTML = '<i class="fas fa-exclamation-circle text-red-500 text-xl"></i>';
            } else {
                icon.innerHTML = '<i class="fas fa-info-circle text-blue-500 text-xl"></i>';
            }
            
            notification.classList.remove('hidden');
            
            setTimeout(() => {
                notification.classList.add('hidden');
            }, 3000);
        }

        // Modal functions
        function openHistoryModal() {
            document.getElementById('historyModal').classList.remove('hidden');
            loadHistoryData();
        }

        function closeHistoryModal() {
            document.getElementById('historyModal').classList.add('hidden');
        }

        function openDraftModal() {
            document.getElementById('draftModal').classList.remove('hidden');
            loadDraftData();
        }

        function closeDraftModal() {
            document.getElementById('draftModal').classList.add('hidden');
        }

        function loadHistoryData() {
            const historyContent = document.getElementById('historyContent');
            historyContent.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-blue-500"></i><p class="mt-2">Memuat data...</p></div>';
            
            fetch('/api/marketing-reports/history')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.length > 0) {
                        let html = '<div class="space-y-3">';
                        data.data.forEach(report => {
                            html += `
                                <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer" onclick="viewReport(${report.id})">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900">${report.lokasi}</h4>
                                            <p class="text-sm text-gray-600">${report.tanggal} • ${report.total_locations} lokasi</p>
                                            <p class="text-sm text-gray-500 mt-1">${report.user_name}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full ${report.status === 'submitted' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                                            ${report.status === 'submitted' ? 'Terkirim' : 'Draft'}
                                        </span>
                                    </div>
                                </div>
                            `;
                        });
                        html += '</div>';
                        historyContent.innerHTML = html;
                    } else {
                        historyContent.innerHTML = '<div class="text-center py-8 text-gray-500"><i class="fas fa-inbox text-4xl mb-3"></i><p>Belum ada riwayat laporan</p></div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading history:', error);
                    historyContent.innerHTML = '<div class="text-center py-8 text-red-500"><i class="fas fa-exclamation-triangle text-4xl mb-3"></i><p>Gagal memuat data</p></div>';
                });
        }

        function loadDraftData() {
            const draftContent = document.getElementById('draftContent');
            draftContent.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-yellow-500"></i><p class="mt-2">Memuat data...</p></div>';
            
            fetch('/api/marketing-reports/drafts')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.length > 0) {
                        let html = '<div class="space-y-3">';
                        data.data.forEach(report => {
                            html += `
                                <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer" onclick="editDraft(${report.id})">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900">${report.lokasi}</h4>
                                            <p class="text-sm text-gray-600">${report.tanggal} • ${report.total_locations} lokasi</p>
                                            <p class="text-xs text-gray-500 mt-1">Diperbarui: ${report.updated_at}</p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button onclick="event.stopPropagation(); editDraft(${report.id})" class="px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button onclick="event.stopPropagation(); deleteDraft(${report.id})" class="px-2 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        html += '</div>';
                        draftContent.innerHTML = html;
                    } else {
                        draftContent.innerHTML = '<div class="text-center py-8 text-gray-500"><i class="fas fa-file-alt text-4xl mb-3"></i><p>Belum ada draft laporan</p></div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading drafts:', error);
                    draftContent.innerHTML = '<div class="text-center py-8 text-red-500"><i class="fas fa-exclamation-triangle text-4xl mb-3"></i><p>Gagal memuat data</p></div>';
                });
        }

        function viewReport(reportId) {
            window.location.href = `/marketing-report/view/${reportId}`;
        }

        function editDraft(reportId) {
            window.location.href = `/marketing-report/edit/${reportId}`;
        }

        function deleteDraft(reportId) {
            if (confirm('Apakah Anda yakin ingin menghapus draft ini?')) {
                fetch(`/api/marketing-reports/draft/${reportId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Draft berhasil dihapus', 'success');
                        loadDraftData(); // Reload draft data
                    } else {
                        showNotification(data.error || 'Gagal menghapus draft', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error deleting draft:', error);
                    showNotification('Gagal menghapus draft', 'error');
                });
            }
        }

        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
        }
    </script>

    <!-- History Modal -->
    <div id="historyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Riwayat Laporan Marketing</h3>
                    <button type="button" onclick="closeHistoryModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div id="historyContent" class="max-h-96 overflow-y-auto">
                    <!-- Content will be loaded via JavaScript -->
                </div>
                
                <div class="mt-4 flex justify-end">
                    <button type="button" onclick="closeHistoryModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Draft Modal -->
    <div id="draftModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Draft Laporan Marketing</h3>
                    <button type="button" onclick="closeDraftModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div id="draftContent" class="max-h-96 overflow-y-auto">
                    <!-- Content will be loaded via JavaScript -->
                </div>
                
                <div class="mt-4 flex justify-end">
                    <button type="button" onclick="closeDraftModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
