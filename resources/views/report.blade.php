<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan Harian - CatatanKerja</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <a href="{{ route('report') }}" class="py-4 px-1 border-b-2 border-purple-500 text-purple-600 font-medium flex items-center">
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
                <a href="{{ route('absensi') }}" class="block py-2 px-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-clock mr-2"></i>Absensi
                </a>
                
                <!-- Daily Report Mobile Menu -->
                <a href="{{ route('report') }}" class="block py-2 px-3 text-purple-600 bg-purple-50 rounded-lg transition-colors">
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

        <!-- Error Message -->
        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="px-4 py-6 sm:px-0">
            <!-- Page Title -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">Buat Laporan Harian</h2>
                        <p class="text-gray-600 mt-2">Buat dan download laporan harian Anda</p>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('report.history') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fas fa-history mr-2"></i>Riwayat Laporan
                        </a>
                        <a href="{{ route('report.drafts') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Draft Laporan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Report Form -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <form method="POST" action="{{ route('report.generate') }}" onsubmit="submitForm(event)">
                    @csrf
                    
                    <!-- Date Selection -->
                    <div class="mb-6">
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2 text-gray-400"></i>Tanggal Laporan
                        </label>
                        <input 
                            type="text" 
                            id="tanggal" 
                            name="tanggal"
                            value="{{ now()->format('d F Y') }}"
                            readonly
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-700 cursor-not-allowed"
                            required>
                        <p class="text-xs text-gray-500 mt-1">Laporan dibuat untuk hari ini</p>
                    </div>

                    <!-- Manual Location -->
                    <div class="mb-6">
                        <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>Lokasi Kerja
                        </label>
                        <div class="flex space-x-2">
                            <input 
                                type="text" 
                                id="lokasi" 
                                name="lokasi"
                                placeholder="Masukkan lokasi kerja Anda"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                required>
                            <button type="button" onclick="getCurrentLocation()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                                <i class="fas fa-location-arrow mr-2"></i>Detect Lokasi
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Masukkan lokasi tempat Anda bekerja atau gunakan deteksi otomatis</p>
                    </div>

                    <!-- Report Content -->
                    <div class="mb-6">
                        <label for="laporan" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-file-alt mr-2 text-gray-400"></i>Isi Laporan
                        </label>
                        <textarea 
                            id="laporan" 
                            name="laporan"
                            rows="6"
                            maxlength="2000"
                            placeholder="Tuliskan laporan pekerjaan Anda hari ini..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 resize-none"
                            required></textarea>
                        <div class="mt-2 flex items-center justify-between">
                            <p class="text-xs text-gray-500">Jelaskan pekerjaan yang telah Anda lakukan pada tanggal yang dipilih</p>
                            <span class="text-xs text-gray-500" id="laporanCount">0/2000</span>
                        </div>
                    </div>

                    <!-- Problems Section -->
                    <div class="mb-6">
                        <label for="masalah" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-exclamation-triangle mr-2 text-gray-400"></i>Masalah yang Dihadapi
                        </label>
                        <textarea 
                            id="masalah" 
                            name="masalah"
                            rows="4"
                            maxlength="1000"
                            placeholder="Jelaskan masalah atau kendala yang dihadapi..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 resize-none"></textarea>
                        <div class="mt-2 flex items-center justify-between">
                            <p class="text-xs text-gray-500">Masalah atau kendala selama bekerja (opsional)</p>
                            <span class="text-xs text-gray-500" id="masalahCount">0/1000</span>
                        </div>
                    </div>

                    <!-- Solutions Section -->
                    <div class="mb-6">
                        <label for="solusi" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lightbulb mr-2 text-gray-400"></i>Solusi yang Dilakukan
                        </label>
                        <textarea 
                            id="solusi" 
                            name="solusi"
                            rows="4"
                            maxlength="1000"
                            placeholder="Jelaskan solusi atau tindakan yang diambil..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 resize-none"></textarea>
                        <div class="mt-2 flex items-center justify-between">
                            <p class="text-xs text-gray-500">Solusi atau tindakan yang diambil (opsional)</p>
                            <span class="text-xs text-gray-500" id="solusiCount">0/1000</span>
                        </div>
                    </div>

                    <!-- Camera Evidence Section -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-camera mr-2 text-gray-400"></i>Bukti Foto (Opsional)
                        </label>
                        
                        <!-- Camera Section -->
                        <div class="space-y-4">
                            <!-- Camera View -->
                            <div id="cameraSection" class="hidden">
                                <div class="relative bg-black rounded-lg overflow-hidden" style="max-width: 640px;">
                                    <video id="cameraVideo" class="w-full" autoplay playsinline></video>
                                    <div class="absolute top-2 right-2 bg-black bg-opacity-75 text-white px-3 py-2 rounded-lg text-sm font-medium" id="cameraTimestamp"></div>
                                </div>
                                <div class="mt-3 flex space-x-3">
                                    <button type="button" onclick="capturePhoto()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                                        <i class="fas fa-camera mr-2"></i>Ambil Foto
                                    </button>
                                    <button type="button" onclick="stopCamera()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition">
                                        <i class="fas fa-stop mr-2"></i>Stop Kamera
                                    </button>
                                </div>
                            </div>

                            <!-- Start Camera Button -->
                            <div id="startCameraSection">
                                <button type="button" onclick="startCamera()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition">
                                    <i class="fas fa-video mr-2"></i>Buka Kamera
                                </button>
                            </div>

                            <!-- Captured Photos -->
                            <div id="capturedPhotos" class="space-y-3">
                                <!-- Photos will be added here dynamically -->
                            </div>

                            <!-- Hidden input to store photo data -->
                            <input type="hidden" id="photoEvidence" name="photo_evidence" value="">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Ambil foto sebagai bukti pelaksanaan pekerjaan (opsional)</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-database mr-1"></i>
                            Laporan akan disimpan di database
                        </div>
                        <div class="space-x-3">
                            <button type="button" onclick="saveDraft()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition">
                                <i class="fas fa-save mr-2"></i>Simpan ke Draft
                            </button>
                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-medium transition">
                                <i class="fas fa-paper-plane mr-2"></i>Kirim Laporan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
    </main>

    <script>
        // Camera variables
        let stream = null;
        let capturedPhotos = [];
        let photoIdCounter = 0;
        let currentLocation = null;

        // Load draft data if available
        function loadDraftData() {
            const draftData = sessionStorage.getItem('editDraft');
            if (draftData) {
                const draft = JSON.parse(draftData);
                
                // Populate form fields with draft data (except tanggal which is always today)
                document.getElementById('lokasi').value = draft.lokasi;
                document.getElementById('laporan').value = draft.laporan;
                document.getElementById('masalah').value = draft.masalah || '';
                document.getElementById('solusi').value = draft.solusi || '';
                
                // Load photo evidence if exists
                if (draft.photo_evidence) {
                    capturedPhotos = JSON.parse(draft.photo_evidence);
                    displayCapturedPhotos();
                }
                
                // Update character counters
                updateLaporanCount();
                updateMasalahCount();
                updateSolusiCount();
                
                // Clear sessionStorage after loading
                sessionStorage.removeItem('editDraft');
                
                // Show notification
                showNotification('Draft berhasil dimuat. Lanjutkan mengedit laporan Anda.', 'info');
            }
        }

        // Camera functions
        async function startCamera() {
            try {
                // Request camera access
                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        facingMode: 'environment',
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    } 
                });
                
                // Show camera section
                document.getElementById('cameraSection').classList.remove('hidden');
                document.getElementById('startCameraSection').classList.add('hidden');
                
                // Start video stream
                const video = document.getElementById('cameraVideo');
                video.srcObject = stream;
                
                // Update timestamp
                updateCameraTimestamp();
                setInterval(updateCameraTimestamp, 1000);
                
                showNotification('Kamera berhasil dibuka', 'success');
            } catch (error) {
                console.error('Error accessing camera:', error);
                showNotification('Tidak dapat mengakses kamera. Pastikan izin kamera diaktifkan.', 'error');
            }
        }

        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            
            document.getElementById('cameraSection').classList.add('hidden');
            document.getElementById('startCameraSection').classList.remove('hidden');
            
            showNotification('Kamera ditutup', 'info');
        }

        function updateCameraTimestamp() {
            const timestampElement = document.getElementById('cameraTimestamp');
            if (timestampElement) {
                const now = new Date();
                const lokasi = document.getElementById('lokasi').value || 'Lokasi tidak diketahui';
                const dateTimeString = now.toLocaleString('id-ID');
                timestampElement.textContent = `${dateTimeString} | ${lokasi}`;
            }
        }

        function capturePhoto() {
            const video = document.getElementById('cameraVideo');
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0);
            
            // Add timestamp with location to photo
            const timestamp = new Date();
            const lokasi = document.getElementById('lokasi').value || 'Lokasi tidak diketahui';
            const dateTimeString = timestamp.toLocaleString('id-ID');
            const timestampText = `${dateTimeString} | ${lokasi}`;
            
            // Configure timestamp style
            context.font = 'bold 14px Arial';
            context.fillStyle = 'white';
            context.strokeStyle = 'black';
            context.lineWidth = 3;
            context.textAlign = 'right';
            
            // Add timestamp with outline for better visibility
            const x = canvas.width - 10;
            const y = canvas.height - 10;
            context.strokeText(timestampText, x, y);
            context.fillText(timestampText, x, y);
            
            // Convert to base64
            const photoData = canvas.toDataURL('image/jpeg', 0.8);
            
            // Add to captured photos
            const photoId = ++photoIdCounter;
            const photo = {
                id: photoId,
                data: photoData,
                timestamp: timestamp.toISOString(),
                timestampText: timestampText,
                lokasi: lokasi
            };
            
            capturedPhotos.push(photo);
            displayCapturedPhotos();
            updatePhotoEvidenceInput();
            
            showNotification('Foto berhasil ditangkap', 'success');
        }

        function displayCapturedPhotos() {
            const container = document.getElementById('capturedPhotos');
            container.innerHTML = '';
            
            capturedPhotos.forEach(photo => {
                const photoDiv = document.createElement('div');
                photoDiv.className = 'flex items-center space-x-3 p-3 bg-gray-50 rounded-lg';
                photoDiv.innerHTML = `
                    <img src="${photo.data}" alt="Bukti foto" class="w-20 h-20 object-cover rounded-lg">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Foto Bukti</p>
                        <p class="text-xs text-gray-500">${photo.timestampText}</p>
                        <p class="text-xs text-gray-400">📍 ${photo.lokasi}</p>
                    </div>
                    <button type="button" onclick="removePhoto(${photo.id})" class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-colors">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                container.appendChild(photoDiv);
            });
        }

        function removePhoto(photoId) {
            capturedPhotos = capturedPhotos.filter(photo => photo.id !== photoId);
            displayCapturedPhotos();
            updatePhotoEvidenceInput();
            showNotification('Foto dihapus', 'info');
        }

        function updatePhotoEvidenceInput() {
            const input = document.getElementById('photoEvidence');
            input.value = JSON.stringify(capturedPhotos);
        }

        // Load draft data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadDraftData();
            // Try to get current location automatically
            setTimeout(getCurrentLocation, 1000);
        });

        // Get current location
        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        currentLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                            accuracy: position.coords.accuracy
                        };
                        
                        // Try to get address from coordinates (using reverse geocoding)
                        getAddressFromCoordinates(currentLocation.lat, currentLocation.lng);
                    },
                    function(error) {
                        console.error('Error getting location:', error);
                        // If geolocation fails, try IP-based location
                        getLocationFromIP();
                    }
                );
            } else {
                // If geolocation not supported, try IP-based location
                getLocationFromIP();
            }
        }

        // Get address from coordinates using reverse geocoding
        function getAddressFromCoordinates(lat, lng) {
            // Using Nominatim (OpenStreetMap) for reverse geocoding
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=id`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        const locationInput = document.getElementById('lokasi');
                        if (!locationInput.value) {
                            locationInput.value = data.display_name;
                            updateCameraTimestamp();
                            showNotification('Lokasi berhasil dideteksi secara otomatis', 'success');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error getting address:', error);
                    getLocationFromIP();
                });
        }

        // Get location from IP as fallback
        function getLocationFromIP() {
            fetch('https://ipapi.co/json/')
                .then(response => response.json())
                .then(data => {
                    if (data && data.city && data.country_name) {
                        const locationInput = document.getElementById('lokasi');
                        if (!locationInput.value) {
                            const location = `${data.city}, ${data.region}, ${data.country_name}`;
                            locationInput.value = location;
                            updateCameraTimestamp();
                            showNotification('Lokasi berhasil dideteksi berdasarkan IP', 'info');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error getting IP location:', error);
                    showNotification('Tidak dapat mendeteksi lokasi otomatis. Silakan masukkan lokasi secara manual.', 'warning');
                });
        }

        // Update character counts
        function updateLaporanCount() {
            const laporan = document.getElementById('laporan').value;
            const count = laporan.length;
            document.getElementById('laporanCount').textContent = `${count}/2000`;
        }

        function updateMasalahCount() {
            const masalah = document.getElementById('masalah').value;
            const count = masalah.length;
            document.getElementById('masalahCount').textContent = `${count}/1000`;
        }

        function updateSolusiCount() {
            const solusi = document.getElementById('solusi').value;
            const count = solusi.length;
            document.getElementById('solusiCount').textContent = `${count}/1000`;
        }

        // Save draft
        function saveDraft() {
            const laporan = document.getElementById('laporan').value.trim();
            const lokasi = document.getElementById('lokasi').value.trim();
            
            if (!lokasi) {
                showNotification('Lokasi kerja tidak boleh kosong', 'error');
                return false;
            }
            
            if (!laporan) {
                showNotification('Isi laporan tidak boleh kosong', 'error');
                return false;
            }
            
            if (laporan.length < 10) {
                showNotification('Isi laporan minimal 10 karakter', 'error');
                return false;
            }
            
            // Get form data
            const form = document.querySelector('form');
            const formData = new FormData(form);
            const saveButton = document.querySelector('button[onclick="saveDraft()"]');
            
            // Disable button and show loading
            saveButton.disabled = true;
            saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            
            // Create new form data for draft
            const draftData = new FormData();
            draftData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            draftData.append('tanggal', '{{ now()->format('d F Y') }}');
            draftData.append('lokasi', document.getElementById('lokasi').value);
            draftData.append('laporan', document.getElementById('laporan').value);
            draftData.append('masalah', document.getElementById('masalah').value);
            draftData.append('solusi', document.getElementById('solusi').value);
            draftData.append('photo_evidence', document.getElementById('photoEvidence').value);
            
            fetch('{{ route("report.saveDraft") }}', {
                method: 'POST',
                body: draftData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.error || 'Terjadi kesalahan', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat menyimpan draft', 'error');
            })
            .finally(() => {
                // Re-enable button
                saveButton.disabled = false;
                saveButton.innerHTML = '<i class="fas fa-save mr-2"></i>Simpan ke Draft';
            });
        }

        // Validate and submit form
        function submitForm(event) {
            event.preventDefault();
            
            const laporan = document.getElementById('laporan').value.trim();
            const lokasi = document.getElementById('lokasi').value.trim();
            
            if (!lokasi) {
                showNotification('Lokasi kerja tidak boleh kosong', 'error');
                return false;
            }
            
            if (!laporan) {
                showNotification('Isi laporan tidak boleh kosong', 'error');
                return false;
            }
            
            if (laporan.length < 10) {
                showNotification('Isi laporan minimal 10 karakter', 'error');
                return false;
            }
            
            // Show confirmation dialog
            const confirmMessage = `Apakah Anda yakin ingin mengirim laporan untuk hari ini?\n\nLaporan yang sudah dikirim tidak dapat diedit kembali.`;
            
            if (!confirm(confirmMessage)) {
                return false;
            }
            
            // Submit form via AJAX
            const form = event.target;
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            
            // Disable submit button and show loading
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    // Clear form
                    form.reset();
                    updateLaporanCount();
                    updateMasalahCount();
                    updateSolusiCount();
                } else {
                    showNotification(data.error || 'Terjadi kesalahan', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat mengirim laporan', 'error');
            })
            .finally(() => {
                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Kirim Laporan';
            });
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

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateLaporanCount();
            updateMasalahCount();
            updateSolusiCount();
            
            // Event listeners
            document.getElementById('laporan').addEventListener('input', updateLaporanCount);
            document.getElementById('masalah').addEventListener('input', updateMasalahCount);
            document.getElementById('solusi').addEventListener('input', updateSolusiCount);
            document.getElementById('lokasi').addEventListener('input', updateCameraTimestamp);
        });

        // Toggle mobile menu
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Cleanup camera when page unloads
        window.addEventListener('beforeunload', function() {
            stopCamera();
        });

        // Cleanup camera when navigating away
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopCamera();
            }
        });
    </script>
</body>
</html>
