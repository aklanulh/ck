<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Draft Laporan - CatatanKerja</title>
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
        <div class="px-4 py-6 sm:px-0">
            <!-- Page Title -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">Draft Laporan</h2>
                        <p class="text-gray-600 mt-2">Lihat semua laporan yang belum dikirim</p>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('report') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Buat Laporan Baru
                        </a>
                        <a href="{{ route('report.history') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fas fa-history mr-2"></i>Riwayat Laporan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                            <i class="fas fa-edit text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Draft</p>
                            <p class="text-2xl font-bold text-gray-900">{{ count($drafts) }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                            <i class="fas fa-calendar text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Bulan Ini</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $drafts->filter(function($draft) { return \Carbon\Carbon::parse($draft['tanggal'])->isCurrentMonth(); })->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                            <i class="fas fa-clock text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Terlama</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $drafts->first() ? \Carbon\Carbon::parse($drafts->first()['tanggal'])->diffForHumans() : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Drafts List -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Daftar Draft</h3>
                </div>
                
                @if($drafts->isEmpty())
                    <div class="p-8 text-center">
                        <div class="bg-gray-100 rounded-full p-4 w-16 h-16 mx-auto mb-4">
                            <i class="fas fa-edit text-gray-400 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Draft</h4>
                        <p class="text-gray-500 mb-4">Anda tidak memiliki laporan draft.</p>
                        <a href="{{ route('report') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            <i class="fas fa-plus mr-2"></i>Buat Laporan Baru
                        </a>
                    </div>
                @else
                    <div class="divide-y divide-gray-200">
                        @foreach($drafts as $draft)
                            <div class="p-6 hover:bg-gray-50 transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-edit mr-1"></i>Draft
                                            </span>
                                            <span class="ml-2 text-sm text-gray-500">
                                                <i class="fas fa-calendar mr-1"></i>{{ $draft['tanggal'] }}
                                            </span>
                                            <span class="ml-2 text-sm text-gray-500">
                                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $draft['lokasi'] }}
                                            </span>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <h4 class="text-sm font-medium text-gray-900 mb-1">Laporan Pekerjaan</h4>
                                            <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($draft['laporan'], 150) }}</p>
                                        </div>
                                        
                                        @if($draft['masalah'])
                                            <div class="mb-2">
                                                <h4 class="text-sm font-medium text-red-900 mb-1">Masalah yang Dihadapi</h4>
                                                <p class="text-sm text-red-600 line-clamp-2">{{ Str::limit($draft['masalah'], 100) }}</p>
                                            </div>
                                        @endif
                                        
                                        @if($draft['solusi'])
                                            <div class="mb-2">
                                                <h4 class="text-sm font-medium text-green-900 mb-1">Solusi yang Dilakukan</h4>
                                                <p class="text-sm text-green-600 line-clamp-2">{{ Str::limit($draft['solusi'], 100) }}</p>
                                            </div>
                                        @endif
                                        
                                        <div class="flex items-center text-xs text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>
                                            <span>Dibuat: {{ $draft['submitted_at'] ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="ml-4 flex space-x-2">
                                        <button onclick="continueEdit({{ $draft['id'] }})" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                            <i class="fas fa-edit mr-2"></i>Lanjutkan Mengedit
                                        </button>
                                        <button onclick="deleteDraft({{ $draft['id'] }})" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                            <i class="fas fa-trash mr-2"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </main>

    <script>
        // Store drafts data for JavaScript access
        const draftsData = @json($drafts);

        // Continue editing draft
        function continueEdit(draftId) {
            const draft = draftsData.find(d => d.id === draftId);
            if (!draft) return;

            // Store draft data in sessionStorage for the report form
            sessionStorage.setItem('editDraft', JSON.stringify(draft));
            
            // Redirect to report form
            window.location.href = '{{ route("report") }}';
        }

        // Delete draft
        function deleteDraft(draftId) {
            if (!confirm('Apakah Anda yakin ingin menghapus draft ini? Tindakan ini tidak dapat dibatalkan.')) {
                return;
            }

            const deleteButton = document.querySelector(`button[onclick="deleteDraft(${draftId})"]`);
            
            // Disable button and show loading
            deleteButton.disabled = true;
            deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghapus...';
            
            fetch(`{{ route("report.deleteDraft", ":id") }}`.replace(':id', draftId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    // Find and remove the draft card
                    const draftCard = deleteButton.closest('.p-6');
                    if (draftCard) {
                        draftCard.remove();
                    }
                    
                    // Check if no more drafts and reload page
                    setTimeout(() => {
                        const remainingDrafts = document.querySelectorAll('.divide-y > div');
                        if (remainingDrafts.length === 0) {
                            location.reload();
                        }
                    }, 1000);
                } else {
                    showNotification(data.error || 'Terjadi kesalahan', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat menghapus draft', 'error');
            })
            .finally(() => {
                // Re-enable button (if element still exists)
                if (deleteButton) {
                    deleteButton.disabled = false;
                    deleteButton.innerHTML = '<i class="fas fa-trash mr-2"></i>Hapus';
                }
            });
        }

        // Show notification
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg text-white z-50 ${
                type === 'success' ? 'bg-green-500' : 
                type === 'warning' ? 'bg-yellow-500' : 
                type === 'info' ? 'bg-blue-500' :
                'bg-red-500'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${
                        type === 'success' ? 'fa-check-circle' : 
                        type === 'warning' ? 'fa-exclamation-triangle' : 
                        type === 'info' ? 'fa-info-circle' :
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

        // Toggle mobile menu
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
    </script>
</body>
</html>
