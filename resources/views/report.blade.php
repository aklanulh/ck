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
                        <div class="bg-purple-600 rounded-lg p-2">
                            <i class="fas fa-briefcase text-white text-xl"></i>
                        </div>
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
                            type="date" 
                            id="tanggal" 
                            name="tanggal"
                            value="{{ $todayAttendance?->tanggal ?? now()->format('Y-m-d') }}"
                            max="{{ now()->format('Y-m-d') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            required>
                        <p class="text-xs text-gray-500 mt-1">Pilih tanggal untuk laporan yang akan dibuat</p>
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
        // Load draft data if available
        function loadDraftData() {
            const draftData = sessionStorage.getItem('editDraft');
            if (draftData) {
                const draft = JSON.parse(draftData);
                
                // Convert date format from "d F Y" to "Y-m-d" for input field
                let tanggalInput = draft.tanggal;
                if (tanggalInput && tanggalInput.includes(' ')) {
                    // Parse Indonesian date format "d F Y" and convert to "Y-m-d"
                    const months = {
                        'Januari': '01', 'Februari': '02', 'Maret': '03', 'April': '04',
                        'Mei': '05', 'Juni': '06', 'Juli': '07', 'Agustus': '08',
                        'September': '09', 'Oktober': '10', 'November': '11', 'Desember': '12'
                    };
                    
                    const parts = tanggalInput.split(' ');
                    if (parts.length === 3) {
                        const day = parts[0].padStart(2, '0');
                        const month = months[parts[1]] || '01';
                        const year = parts[2];
                        tanggalInput = `${year}-${month}-${day}`;
                    }
                }
                
                // Populate form fields with draft data
                document.getElementById('tanggal').value = tanggalInput;
                document.getElementById('lokasi').value = draft.lokasi;
                document.getElementById('laporan').value = draft.laporan;
                document.getElementById('masalah').value = draft.masalah || '';
                document.getElementById('solusi').value = draft.solusi || '';
                
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

        // Load draft data on page load
        document.addEventListener('DOMContentLoaded', loadDraftData);

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
            draftData.append('tanggal', document.getElementById('tanggal').value);
            draftData.append('lokasi', document.getElementById('lokasi').value);
            draftData.append('laporan', document.getElementById('laporan').value);
            draftData.append('masalah', document.getElementById('masalah').value);
            draftData.append('solusi', document.getElementById('solusi').value);
            
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
            const tanggal = document.getElementById('tanggal').value;
            const confirmMessage = `Apakah Anda yakin ingin mengirim laporan untuk tanggal ${tanggal}?\n\nLaporan yang sudah dikirim tidak dapat diedit kembali.`;
            
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
        });

        // Toggle mobile menu
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
    </script>
</body>
</html>
