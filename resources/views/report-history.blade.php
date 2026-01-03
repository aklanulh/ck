<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Laporan - CatatanKerja</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ url('/favicons/brieftcase.svg') }}">
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
                        <h2 class="text-3xl font-bold text-gray-900">Riwayat Laporan</h2>
                        <p class="text-gray-600 mt-2">Lihat semua laporan harian yang telah Anda buat</p>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('report') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Buat Laporan Baru
                        </a>
                        <a href="{{ route('report.drafts') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Draft Laporan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                            <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Laporan</p>
                            <p class="text-2xl font-bold text-gray-900">{{ count($reports) }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Laporan Terkirim</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $reports->where('status', 'submitted')->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                            <i class="fas fa-calendar text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Bulan Ini</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $reports->filter(function($report) { return \Carbon\Carbon::parse($report['tanggal'])->isCurrentMonth(); })->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Reports List -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Daftar Laporan</h3>
                </div>
                
                @if($reports->isEmpty())
                    <div class="p-8 text-center">
                        <div class="bg-gray-100 rounded-full p-4 w-16 h-16 mx-auto mb-4">
                            <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Laporan</h4>
                        <p class="text-gray-500 mb-4">Anda belum membuat laporan harian apapun.</p>
                        <a href="{{ route('report') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            <i class="fas fa-plus mr-2"></i>Buat Laporan Pertama
                        </a>
                    </div>
                @else
                    <div class="divide-y divide-gray-200">
                        @foreach($reports as $report)
                            <div class="p-6 hover:bg-gray-50 transition cursor-pointer" onclick="showReportDetail({{ $report['id'] }})">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>{{ $report['status'] === 'submitted' ? 'Terkirim' : 'Draft' }}
                                            </span>
                                            <span class="ml-2 text-sm text-gray-500">
                                                <i class="fas fa-calendar mr-1"></i>{{ $report['tanggal'] }}
                                            </span>
                                            <span class="ml-2 text-sm text-gray-500">
                                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $report['lokasi'] }}
                                            </span>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <h4 class="text-sm font-medium text-gray-900 mb-1">Laporan Pekerjaan</h4>
                                            <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($report['laporan'], 150) }}</p>
                                        </div>
                                        
                                        @if($report['masalah'])
                                            <div class="mb-2">
                                                <h4 class="text-sm font-medium text-red-900 mb-1">Masalah yang Dihadapi</h4>
                                                <p class="text-sm text-red-600 line-clamp-2">{{ Str::limit($report['masalah'], 100) }}</p>
                                            </div>
                                        @endif
                                        
                                        @if($report['solusi'])
                                            <div class="mb-2">
                                                <h4 class="text-sm font-medium text-green-900 mb-1">Solusi yang Dilakukan</h4>
                                                <p class="text-sm text-green-600 line-clamp-2">{{ Str::limit($report['solusi'], 100) }}</p>
                                            </div>
                                        @endif
                                        
                                        <div class="flex items-center text-xs text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>
                                            <span>Dikirim: {{ $report['submitted_at'] ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Report Detail Modal -->
    <div id="reportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Detail Laporan</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="modalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

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
        // Store reports data for JavaScript access
        const reportsData = @json($reports);

        // Show report detail popup
        function showReportDetail(reportId) {
            const report = reportsData.find(r => r.id === reportId);
            if (!report) return;

            const modalContent = document.getElementById('modalContent');
            modalContent.innerHTML = `
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-lg font-semibold text-gray-900">Detail Laporan</h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>${report.status === 'submitted' ? 'Terkirim' : 'Draft'}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tanggal</label>
                            <p class="text-gray-900">${report.tanggal}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Lokasi</label>
                            <p class="text-gray-900">${report.lokasi}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Laporan Pekerjaan</label>
                        <div class="mt-1 p-3 bg-gray-50 rounded-lg">
                            <p class="text-gray-900 whitespace-pre-wrap">${report.laporan}</p>
                        </div>
                    </div>
                    
                    ${report.masalah ? `
                    <div>
                        <label class="text-sm font-medium text-gray-500">Masalah yang Dihadapi</label>
                        <div class="mt-1 p-3 bg-red-50 rounded-lg">
                            <p class="text-red-900 whitespace-pre-wrap">${report.masalah}</p>
                        </div>
                    </div>
                    ` : ''}
                    
                    ${report.solusi ? `
                    <div>
                        <label class="text-sm font-medium text-gray-500">Solusi yang Dilakukan</label>
                        <div class="mt-1 p-3 bg-green-50 rounded-lg">
                            <p class="text-green-900 whitespace-pre-wrap">${report.solusi}</p>
                        </div>
                    </div>
                    ` : ''}
                    
                    ${report.photo_evidence && report.photo_evidence.length > 0 ? `
                    <div>
                        <label class="text-sm font-medium text-gray-500">Bukti Foto</label>
                        <div class="mt-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            ${report.photo_evidence.map(photo => `
                                <div class="relative group">
                                    <img src="${photo.url}" alt="Bukti foto" class="w-full h-48 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity" onclick="openPhotoModal('${photo.url}')">
                                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-75 text-white p-2 rounded-b-lg">
                                        <p class="text-xs">${photo.timestampText}</p>
                                        <p class="text-xs opacity-75">📍 ${photo.lokasi}</p>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    ` : ''}
                    
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        ${report.submitted_at ? `Dikirim: ${report.submitted_at}` : `Dibuat: ${report.created_at}`}
                    </div>
                    
                    <div class="flex justify-end">
                        <button onclick="exportToPDF(${report.id})" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            <i class="fas fa-file-pdf mr-2"></i>Export PDF
                        </button>
                    </div>
                </div>
            `;
            
            document.getElementById('reportModal').classList.remove('hidden');
        }

        // Close modal
        function closeModal() {
            document.getElementById('reportModal').classList.add('hidden');
        }

        // Export to PDF function
        function exportToPDF(reportId) {
            const report = reportsData.find(r => r.id === reportId);
            if (!report) return;

            // Create print-friendly HTML content
            const printContent = `
                <html>
                <head>
                    <title>Laporan Harian - ${report.tanggal}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
                        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #6B46C1; padding-bottom: 20px; }
                        .header h1 { color: #6B46C1; margin: 0; }
                        .header p { color: #6B7280; margin: 5px 0 0 0; }
                        .section { margin-bottom: 25px; }
                        .section-title { font-weight: bold; color: #374151; margin-bottom: 10px; font-size: 14px; }
                        .section-content { background: #F9FAFB; padding: 15px; border-radius: 8px; border-left: 4px solid #6B46C1; }
                        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
                        .info-item { }
                        .info-label { font-weight: bold; color: #6B7280; font-size: 12px; margin-bottom: 5px; }
                        .info-value { color: #111827; font-size: 14px; }
                        .footer { text-align: center; margin-top: 40px; color: #6B7280; font-size: 12px; }
                        .problem-section { border-left-color: #EF4444; }
                        .solution-section { border-left-color: #10B981; }
                        @media print { body { margin: 15px; } }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>LAPORAN HARIAN</h1>
                        <p>CatatanKerja - Sistem Laporan Harian</p>
                        <p>${report.tanggal}</p>
                    </div>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Tanggal Laporan</div>
                            <div class="info-value">${report.tanggal}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Lokasi Kerja</div>
                            <div class="info-value">${report.lokasi}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Status</div>
                            <div class="info-value">${report.status === 'submitted' ? 'Terkirim' : 'Draft'}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Waktu Pengiriman</div>
                            <div class="info-value">${report.submitted_at || 'N/A'}</div>
                        </div>
                    </div>
                    
                    <div class="section">
                        <div class="section-title">LAPORAN PEKERJAAN</div>
                        <div class="section-content">${report.laporan.replace(/\n/g, '<br>')}</div>
                    </div>
                    
                    ${report.masalah ? `
                    <div class="section">
                        <div class="section-title">MASALAH YANG DIHADAPI</div>
                        <div class="section-content problem-section">${report.masalah.replace(/\n/g, '<br>')}</div>
                    </div>
                    ` : ''}
                    
                    ${report.solusi ? `
                    <div class="section">
                        <div class="section-title">SOLUSI YANG DILAKUKAN</div>
                        <div class="section-content solution-section">${report.solusi.replace(/\n/g, '<br>')}</div>
                    </div>
                    ` : ''}
                    
                    <div class="footer">
                        <p>Laporan ini dibuat melalui sistem CatatanKerja</p>
                        <p>Generated on ${new Date().toLocaleString('id-ID')}</p>
                    </div>
                </body>
                </html>
            `;

            // Create a new window for printing
            const printWindow = window.open('', '_blank');
            printWindow.document.write(printContent);
            printWindow.document.close();
            
            // Wait for content to load, then trigger print dialog
            printWindow.onload = function() {
                setTimeout(() => {
                    printWindow.print();
                    
                    // Show notification
                    showNotification('PDF berhasil dibuat. Silakan pilih "Save as PDF" di dialog print.', 'info');
                }, 500);
            };
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
    </script>
</body>
</html>
