<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Draft Laporan Marketing - CatatanKerja</title>
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
                <h2 class="text-2xl font-bold text-gray-900">Draft Laporan Marketing</h2>
                <p class="text-gray-600 mt-1">Laporan marketing yang belum selesai</p>
            </div>

            <!-- Action Buttons -->
            <div class="mb-6 flex flex-wrap gap-3">
                <a href="{{ route('marketing-report') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center">
                    <i class="fas fa-plus mr-2"></i>Buat Laporan Baru
                </a>
                <a href="{{ route('marketing-reports.history') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                    <i class="fas fa-history mr-2"></i>Riwayat Laporan
                </a>
            </div>

            <!-- Loading State -->
            <div id="loadingState" class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-3xl text-yellow-500"></i>
                <p class="mt-3 text-gray-600">Memuat data...</p>
            </div>

            <!-- Drafts List -->
            <div id="draftsList" class="hidden space-y-4">
                <!-- Content will be loaded via JavaScript -->
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="hidden text-center py-12">
                <i class="fas fa-file-alt text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Draft</h3>
                <p class="text-gray-600">Anda tidak memiliki draft laporan marketing</p>
                <a href="{{ route('marketing-report') }}" class="inline-flex items-center px-4 py-2 mt-4 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Buat Laporan Baru
                </a>
            </div>
        </div>
    </main>

    <!-- Notification -->
    <div id="notification" class="fixed top-4 right-4 z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-4 flex items-center space-x-3 min-w-[300px]">
            <div id="notificationIcon"></div>
            <div>
                <p id="notificationText" class="text-sm font-medium text-gray-900"></p>
            </div>
        </div>
    </div>

    <!-- Report Detail Modal -->
    <div id="reportDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Detail Draft Laporan Marketing</h3>
                    <button type="button" onclick="closeReportDetailModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div id="reportDetailContent">
                    <!-- Content will be loaded via JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadDrafts();
        });

        function loadDrafts() {
            const loadingState = document.getElementById('loadingState');
            const draftsList = document.getElementById('draftsList');
            const emptyState = document.getElementById('emptyState');
            
            loadingState.classList.remove('hidden');
            draftsList.classList.add('hidden');
            emptyState.classList.add('hidden');
            
            fetch('/api/marketing-reports/drafts')
                .then(response => response.json())
                .then(data => {
                    loadingState.classList.add('hidden');
                    
                    if (data.success && data.data.length > 0) {
                        let html = '';
                        data.data.forEach(draft => {
                            html += `
                                <div class="bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow cursor-pointer" onclick="viewDraft(${draft.id})">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">${draft.lokasi}</h3>
                                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                <span><i class="fas fa-calendar mr-1"></i>${draft.tanggal}</span>
                                                <span><i class="fas fa-map-marker-alt mr-1"></i>${draft.total_locations} lokasi</span>
                                                <span><i class="fas fa-clock mr-1"></i>Diperbarui: ${draft.updated_at}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex space-x-2" onclick="event.stopPropagation()">
                                            <button onclick="editDraft(${draft.id})" class="px-3 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </button>
                                            <button onclick="deleteDraft(${draft.id})" class="px-3 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        draftsList.innerHTML = html;
                        draftsList.classList.remove('hidden');
                    } else {
                        emptyState.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    loadingState.classList.add('hidden');
                    console.error('Error loading drafts:', error);
                    showNotification('Gagal memuat data draft', 'error');
                });
        }

        function editDraft(draftId) {
            window.location.href = `/marketing-report/edit/${draftId}`;
        }

        function viewDraft(draftId) {
            openReportDetailModal(draftId);
        }

        function openReportDetailModal(draftId) {
            const modal = document.getElementById('reportDetailModal');
            const content = document.getElementById('reportDetailContent');
            
            // Show loading state
            content.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-3xl text-yellow-500"></i><p class="mt-3 text-gray-600">Memuat detail...</p></div>';
            
            modal.classList.remove('hidden');
            
            fetch(`/api/marketing-reports/${draftId}`)
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        const report = data.data;
                        let html = `
                            <!-- Header Info -->
                            <div class="bg-purple-50 rounded-lg p-4 mb-6">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-sm text-purple-700">${report.tanggal} • ${report.user_name}</p>
                                    </div>
                                    <span class="px-3 py-1 text-sm rounded-full ${report.status === 'submitted' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                                        ${report.status === 'submitted' ? 'Terkirim' : 'Draft'}
                                    </span>
                                </div>
                            </div>
                        `;
                        
                        // Add locations
                        if (report.locations && report.locations.length > 0) {
                            report.locations.forEach((location, index) => {
                                html += `
                                    <!-- Location ${index + 1} -->
                                    <div class="bg-white border rounded-lg p-6 mb-4">
                                        <div class="flex justify-between items-center mb-4">
                                            <h5 class="text-md font-medium text-gray-900">Lokasi ${index + 1}</h5>
                                            <span class="px-3 py-1 text-sm bg-purple-100 text-purple-800 rounded-full">
                                                ${location.lokasi}
                                            </span>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                            <div>
                                                <h6 class="text-sm font-medium text-gray-800 mb-2">Informasi Kontak</h6>
                                                <div class="space-y-2">
                                                    <div>
                                                        <span class="text-xs text-gray-600">Nama:</span>
                                                        <p class="text-sm text-gray-900">${location.nama_kontak}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-xs text-gray-600">Nomor:</span>
                                                        <p class="text-sm text-gray-900">${location.nomor_kontak}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="text-sm font-medium text-gray-800 mb-2">Laporan Kunjungan</h6>
                                                <div class="bg-gray-50 rounded p-3">
                                                    <p class="text-sm text-gray-900 whitespace-pre-wrap">${location.laporan}</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Photos -->
                                        ${location.photos && Array.isArray(location.photos) && location.photos.length > 0 ? `
                                            <div class="mt-4">
                                                <h6 class="text-sm font-medium text-gray-800 mb-2">Bukti Foto</h6>
                                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                                    ${location.photos.map(photo => `
                                                        <div class="relative group cursor-pointer" onclick="viewPhotoInModal('${photo.url}')">
                                                            <img src="${photo.url}" alt="Marketing photo" class="w-full h-24 object-cover rounded-lg">
                                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all rounded-lg flex items-center justify-center">
                                                                <i class="fas fa-search-plus text-white text-lg opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                                            </div>
                                                        </div>
                                                    `).join('')}
                                                </div>
                                            </div>
                                        ` : '<p class="text-sm text-gray-500 italic">Tidak ada foto</p>'}
                                    </div>
                                `;
                            });
                        }
                        
                        // Add action buttons for draft
                        html += `
                            <div class="flex justify-end space-x-3 mt-6">
                                <button onclick="editDraft(${report.id})" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-edit mr-2"></i>Edit Draft
                                </button>
                            </div>
                        `;
                        
                        content.innerHTML = html;
                    } else {
                        content.innerHTML = `<div class="text-center py-8 text-red-500"><i class="fas fa-exclamation-triangle text-4xl mb-3"></i><p>Gagal memuat detail draft</p><p class="text-sm mt-2">${data.error || 'Error tidak diketahui'}</p></div>`;
                        showNotification(data.error || 'Gagal memuat detail draft', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error loading draft:', error);
                    content.innerHTML = `<div class="text-center py-8 text-red-500"><i class="fas fa-exclamation-triangle text-4xl mb-3"></i><p>Gagal memuat detail draft</p><p class="text-sm mt-2">${error.message}</p></div>`;
                    showNotification('Gagal memuat detail draft', 'error');
                });
        }

        function closeReportDetailModal() {
            document.getElementById('reportDetailModal').classList.add('hidden');
        }

        function viewPhotoInModal(photoUrl) {
            // Create modal to view photo
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-90 z-[60] flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="relative max-w-4xl max-h-full">
                    <img src="${photoUrl}" alt="Marketing photo" class="max-w-full max-h-full object-contain rounded-lg">
                    <button onclick="this.parentElement.parentElement.remove()" class="absolute top-4 right-4 bg-white rounded-full p-2 hover:bg-gray-100 transition-colors">
                        <i class="fas fa-times text-gray-600"></i>
                    </button>
                </div>
            `;
            modal.onclick = function(e) {
                if (e.target === modal) {
                    modal.remove();
                }
            };
            document.body.appendChild(modal);
        }

        function deleteDraft(draftId) {
            if (confirm('Apakah Anda yakin ingin menghapus draft ini?')) {
                fetch(`/api/marketing-reports/draft/${draftId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Draft berhasil dihapus', 'success');
                        loadDrafts(); // Reload drafts
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

        // Mobile menu toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
        }
    </script>
</body>
</html>
