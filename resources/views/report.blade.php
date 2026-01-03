<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan Harian - CatatanKerja</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicons/brieftcase.svg') }}">
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
                        <input 
                            type="text" 
                            id="lokasi" 
                            name="lokasi"
                            placeholder="Masukkan lokasi kerja Anda"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            required>
                        <p class="text-xs text-gray-500 mt-1">Masukkan lokasi tempat Anda bekerja</p>
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
                                    <div class="absolute top-2 right-2 bg-black bg-opacity-75 text-white px-3 py-2 rounded-lg text-base font-medium" id="cameraTimestamp"></div>
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

    <!-- Photo Preview Modal -->
    <div id="photoModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4" onclick="closePhotoModalOnBackdrop(event)">
        <div class="relative max-w-4xl max-h-full" onclick="event.stopPropagation()">
            <img id="modalImage" src="" alt="Full size photo" class="max-w-full max-h-full rounded-lg">
            <button type="button" onclick="closePhotoModal()" class="absolute top-4 right-4 bg-white bg-opacity-90 text-gray-800 rounded-full p-2 hover:bg-opacity-100 transition-all">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
    </div>

    <script>
        // Camera variables
        let stream = null;
        let capturedPhotos = [];
        let photoIdCounter = 0;
        let currentLocation = null;
        let cameraLocation = 'Mendeteksi lokasi...';

        // Load draft data if available
        function loadDraftData() {
            const draftData = sessionStorage.getItem('editDraft');
            console.log('Draft data from sessionStorage:', draftData);
            
            if (draftData) {
                const draft = JSON.parse(draftData);
                console.log('Parsed draft data:', draft);
                
                // Populate form fields with draft data (except tanggal which is always today)
                document.getElementById('lokasi').value = draft.lokasi;
                document.getElementById('laporan').value = draft.laporan;
                document.getElementById('masalah').value = draft.masalah || '';
                document.getElementById('solusi').value = draft.solusi || '';
                
                // Load photo evidence if exists
                if (draft.photo_evidence) {
                    console.log('Photo evidence found:', draft.photo_evidence);
                    capturedPhotos = draft.photo_evidence;
                    console.log('Captured photos set to:', capturedPhotos);
                    displayCapturedPhotos();
                } else {
                    console.log('No photo evidence in draft');
                }
                
                // Update character counters
                updateLaporanCount();
                updateMasalahCount();
                updateSolusiCount();
                
                // Clear sessionStorage after loading
                sessionStorage.removeItem('editDraft');
                
                // Show notification
                showNotification('Draft berhasil dimuat. Lanjutkan mengedit laporan Anda.', 'info');
            } else {
                console.log('No draft data found in sessionStorage');
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
                
                // Show camera section and hide start button
                document.getElementById('cameraSection').classList.remove('hidden');
                document.getElementById('startCameraSection').classList.add('hidden');
                
                // Get camera location
                getCameraLocation();
                
                // Start video stream
                const video = document.getElementById('cameraVideo');
                video.srcObject = stream;
                
                // Start updating timestamp
                updateCameraTimestamp();
                setInterval(updateCameraTimestamp, 1000);
                
                showNotification('Kamera berhasil dibuka', 'success');
            } catch (error) {
                console.error('Error accessing camera:', error);
                showNotification('Tidak dapat mengakses kamera. Pastikan izin kamera telah diberikan.', 'error');
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
                const dateTimeString = now.toLocaleString('id-ID');
                timestampElement.textContent = `${dateTimeString}\n${cameraLocation}`;
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
            const dateTimeString = timestamp.toLocaleString('id-ID');
            const timestampText = `${dateTimeString}\n${cameraLocation}`;
            
            // Configure timestamp style
            context.font = 'bold 20px Arial';
            context.fillStyle = 'white';
            context.strokeStyle = 'black';
            context.lineWidth = 4;
            context.textAlign = 'right';
            
            // Add timestamp with outline for better visibility
            const x = canvas.width - 15;
            const y = canvas.height - 15;
            context.strokeText(timestampText, x, y);
            context.fillText(timestampText, x, y);
            
            // Convert canvas to blob
            canvas.toBlob(function(blob) {
                // Create form data for upload
                const formData = new FormData();
                formData.append('photo', blob, 'photo.jpg');
                formData.append('timestamp', timestampText);
                formData.append('lokasi', cameraLocation);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                
                // Upload photo to server
                fetch('{{ route("report.uploadPhoto") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Add to captured photos
                        capturedPhotos.push(data.photo);
                        displayCapturedPhotos();
                        updatePhotoEvidenceInput();
                        showNotification('Foto berhasil ditangkap dan diupload', 'success');
                    } else {
                        showNotification(data.error || 'Gagal mengupload foto', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error uploading photo:', error);
                    showNotification('Terjadi kesalahan saat mengupload foto', 'error');
                });
            }, 'image/jpeg', 0.8);
        }

        function displayCapturedPhotos() {
            const container = document.getElementById('capturedPhotos');
            console.log('Displaying captured photos. Container:', container);
            console.log('Captured photos array:', capturedPhotos);
            console.log('Number of photos:', capturedPhotos.length);
            
            container.innerHTML = '';
            
            if (capturedPhotos.length === 0) {
                console.log('No photos to display');
                return;
            }
            
            capturedPhotos.forEach((photo, index) => {
                console.log(`Processing photo ${index}:`, photo);
                const photoDiv = document.createElement('div');
                photoDiv.className = 'flex items-center space-x-3 p-3 bg-gray-50 rounded-lg';
                photoDiv.innerHTML = `
                    <img src="${photo.url}" alt="Bukti foto" class="w-20 h-20 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity" onclick="openPhotoModal('${photo.url}')">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Foto Bukti</p>
                        <p class="text-xs text-gray-500">${photo.timestampText}</p>
                        <p class="text-xs text-gray-400">📍 ${photo.lokasi}</p>
                    </div>
                    <button type="button" onclick="removePhoto('${photo.id}')" class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-colors">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                container.appendChild(photoDiv);
                console.log(`Photo ${index} added to container`);
            });
            
            console.log('Finished displaying photos');
        }

        // Photo modal functions
        function openPhotoModal(imageUrl) {
            const modal = document.getElementById('photoModal');
            const modalImage = document.getElementById('modalImage');
            
            modalImage.src = imageUrl;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Add escape key listener
            document.addEventListener('keydown', handleEscapeKey);
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }

        function closePhotoModal() {
            const modal = document.getElementById('photoModal');
            
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            
            // Remove escape key listener
            document.removeEventListener('keydown', handleEscapeKey);
            
            // Restore body scroll
            document.body.style.overflow = 'auto';
        }

        function handleEscapeKey(event) {
            if (event.key === 'Escape') {
                closePhotoModal();
            }
        }

        function closePhotoModalOnBackdrop(event) {
            // Close modal only if clicked on backdrop (outside the image container)
            if (event.target === event.currentTarget) {
                closePhotoModal();
            }
        }

        function removePhoto(photoId) {
            // Find the photo to remove
            const photoToRemove = capturedPhotos.find(photo => photo.id === photoId);
            
            if (photoToRemove) {
                // Delete photo from server
                fetch('{{ route("report.deletePhoto") }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        photo_path: photoToRemove.path
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove from array and update display
                        capturedPhotos = capturedPhotos.filter(photo => photo.id !== photoId);
                        displayCapturedPhotos();
                        updatePhotoEvidenceInput();
                        showNotification('Foto berhasil dihapus', 'success');
                    } else {
                        showNotification(data.error || 'Gagal menghapus foto dari server', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error deleting photo:', error);
                    showNotification('Terjadi kesalahan saat menghapus foto', 'error');
                });
            } else {
                // Fallback: just remove from array if photo not found
                capturedPhotos = capturedPhotos.filter(photo => photo.id !== photoId);
                displayCapturedPhotos();
                updatePhotoEvidenceInput();
                showNotification('Foto dihapus dari daftar', 'info');
            }
        }

        function updatePhotoEvidenceInput() {
            const input = document.getElementById('photoEvidence');
            input.value = JSON.stringify(capturedPhotos);
        }

        // Function to cleanup unused photos from server
        function cleanupPhotos() {
            if (capturedPhotos.length > 0) {
                // Only cleanup if form is not submitted (no report saved)
                const formSubmitted = sessionStorage.getItem('reportSubmitted') === 'true';
                
                if (!formSubmitted) {
                    // Delete all uploaded photos from server
                    capturedPhotos.forEach(photo => {
                        fetch('{{ route("report.deletePhoto") }}', {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                photo_path: photo.path
                            })
                        })
                        .catch(error => {
                            console.error('Error cleaning up photo:', error);
                        });
                    });
                }
                
                // Clear the flag
                sessionStorage.removeItem('reportSubmitted');
            }
        }

        // Load draft data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadDraftData();
            
            // Clear submission flag on page load
            sessionStorage.removeItem('reportSubmitted');
        });

        // Get camera location (separate from form location)
        function getCameraLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        currentLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                            accuracy: position.coords.accuracy
                        };
                        
                        // Try to get address from coordinates (using reverse geocoding)
                        getCameraAddressFromCoordinates(currentLocation.lat, currentLocation.lng);
                    },
                    function(error) {
                        console.error('Error getting camera location:', error);
                        // If geolocation fails, try IP-based location
                        getCameraLocationFromIP();
                    }
                );
            } else {
                // If geolocation not supported, try IP-based location
                getCameraLocationFromIP();
            }
        }

        // Get camera address from coordinates using reverse geocoding
        function getCameraAddressFromCoordinates(lat, lng) {
            // Using Nominatim (OpenStreetMap) for reverse geocoding
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=id`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        cameraLocation = data.display_name;
                        updateCameraTimestamp();
                        showNotification('Lokasi kamera berhasil dideteksi', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error getting camera address:', error);
                    getCameraLocationFromIP();
                });
        }

        // Get camera location from IP as fallback
        function getCameraLocationFromIP() {
            fetch('https://ipapi.co/json/')
                .then(response => response.json())
                .then(data => {
                    if (data && data.city && data.country_name) {
                        cameraLocation = `${data.city}, ${data.region}, ${data.country_name}`;
                        updateCameraTimestamp();
                        showNotification('Lokasi kamera berhasil dideteksi berdasarkan IP', 'info');
                    } else {
                        cameraLocation = 'Lokasi tidak diketahui';
                        updateCameraTimestamp();
                    }
                })
                .catch(error => {
                    console.error('Error getting camera IP location:', error);
                    cameraLocation = 'Lokasi tidak diketahui';
                    updateCameraTimestamp();
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
            const tanggal = document.getElementById('tanggal').value.trim();
            
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
            
            // Create form data manually to ensure all fields are included
            const formData = new FormData();
            formData.append('tanggal', tanggal);
            formData.append('lokasi', lokasi);
            formData.append('laporan', laporan);
            formData.append('masalah', document.getElementById('masalah').value.trim());
            formData.append('solusi', document.getElementById('solusi').value.trim());
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            // Add photo evidence to form data
            capturedPhotos.forEach((photo, index) => {
                formData.append(`photo_evidence[${index}]`, JSON.stringify(photo));
            });
            
            const saveButton = document.querySelector('button[onclick="saveDraft()"]');
            
            // Disable button and show loading
            saveButton.disabled = true;
            saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            
            fetch('{{ route("report.saveDraft") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mark form as submitted to prevent cleanup
                    sessionStorage.setItem('reportSubmitted', 'true');
                    showNotification('Draft berhasil disimpan!', 'success');
                    // Redirect after a short delay
                    setTimeout(() => {
                        window.location.href = '{{ route("report.drafts") }}';
                    }, 1500);
                } else {
                    showNotification(data.error || 'Gagal menyimpan draft', 'error');
                }
            })
            .catch(error => {
                console.error('Error saving draft:', error);
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
            const tanggal = document.getElementById('tanggal').value.trim();
            
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
            
            // Create form data manually to ensure all fields are included
            const formData = new FormData();
            formData.append('tanggal', tanggal);
            formData.append('lokasi', lokasi);
            formData.append('laporan', laporan);
            formData.append('masalah', document.getElementById('masalah').value.trim());
            formData.append('solusi', document.getElementById('solusi').value.trim());
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            // Add photo evidence as array
            capturedPhotos.forEach((photo, index) => {
                formData.append(`photo_evidence[${index}]`, JSON.stringify(photo));
            });
            
            const submitButton = document.querySelector('button[type="submit"]');
            
            // Disable submit button and show loading
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
            
            fetch('{{ route("report.generate") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mark form as submitted to prevent cleanup
                    sessionStorage.setItem('reportSubmitted', 'true');
                    showNotification(data.message, 'success');
                    // Redirect to report history after successful submit
                    setTimeout(() => {
                        window.location.href = '{{ route("report.history") }}';
                    }, 1500);
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
        });

        // Toggle mobile menu
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Cleanup camera when page unloads
        window.addEventListener('beforeunload', function() {
            stopCamera();
            cleanupPhotos();
        });

        // Cleanup camera when navigating away
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopCamera();
                cleanupPhotos();
            }
        });
    </script>
</body>
</html>
