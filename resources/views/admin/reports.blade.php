@extends('admin.app')

@section('title', 'Kelola Laporan')

@section('content')
<!-- Header -->
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Kelola Laporan</h2>
    <p class="text-gray-600 mt-1">Manajemen laporan kerja dan draft laporan</p>
</div>

<!-- Action Buttons -->
<div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div class="flex flex-col sm:flex-row gap-3">
        <button onclick="refreshReports()" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-sync-alt mr-2"></i>Refresh
        </button>
    </div>
    
    <!-- Search and Filter -->
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="relative">
            <input type="text" id="searchInput" placeholder="Cari laporan..." 
                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent w-full sm:w-64">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>
        <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
            <option value="">Semua Status</option>
            <option value="draft">Draft</option>
            <option value="published">Published</option>
        </select>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-blue-100 rounded-lg p-2 sm:p-3">
                <i class="fas fa-file-alt text-blue-600 text-lg sm:text-xl"></i>
            </div>
            <div class="ml-3 sm:ml-4">
                <p class="text-xs sm:text-sm font-medium text-gray-500">Total Laporan</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $reports->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-2 sm:p-3">
                <i class="fas fa-edit text-yellow-600 text-lg sm:text-xl"></i>
            </div>
            <div class="ml-3 sm:ml-4">
                <p class="text-xs sm:text-sm font-medium text-gray-500">Draft</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ \App\Models\Report::where('status', 'draft')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-green-100 rounded-lg p-2 sm:p-3">
                <i class="fas fa-check-circle text-green-600 text-lg sm:text-xl"></i>
            </div>
            <div class="ml-3 sm:ml-4">
                <p class="text-xs sm:text-sm font-medium text-gray-500">Published</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ \App\Models\Report::where('status', 'published')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-purple-100 rounded-lg p-2 sm:p-3">
                <i class="fas fa-calendar text-purple-600 text-lg sm:text-xl"></i>
            </div>
            <div class="ml-3 sm:ml-4">
                <p class="text-xs sm:text-sm font-medium text-gray-500">Bulan Ini</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ \App\Models\Report::whereMonth('created_at', now()->month)->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Reports Table -->
<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Daftar Laporan</h3>
        <p class="text-sm text-gray-500 mt-1">{{ $reports->count() }} laporan terdaftar</p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengguna</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="reportsTableBody">
                @forelse($reports as $report)
                    <tr class="report-row" data-title="{{ strtolower($report->title) }}" data-user="{{ strtolower($report->user->name) }}" data-status="{{ $report->status }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $report->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $report->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <a href="/admin/reports/{{ $report->id }}" class="hover:text-blue-600">{{ $report->title }}</a>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">{{ Str::limit($report->content, 100) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $report->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($report->status == 'published') bg-green-100 text-green-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($report->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="showReportDetail({{ $report->id }})" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="deleteReport({{ $report->id }}, '{{ $report->title }}')" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-file-alt text-4xl mb-4"></i>
                            <p class="text-lg font-medium">Belum ada laporan</p>
                            <p class="text-sm mt-1">Tambahkan laporan pertama untuk memulai</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($reports->hasPages())
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                {{ $reports->links() }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Menampilkan <span class="font-medium">{{ $reports->firstItem() }}</span> hingga 
                        <span class="font-medium">{{ $reports->lastItem() }}</span> dari 
                        <span class="font-medium">{{ $reports->total() }}</span> hasil
                    </p>
                </div>
                <div>
                    {{ $reports->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <div class="mt-4 text-center">
                <h3 class="text-lg font-medium text-gray-900">Hapus Laporan</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus laporan <strong id="deleteReportTitle"></strong>?</p>
                    <p class="text-xs text-red-600 mt-2">Tindakan ini tidak dapat dibatalkan!</p>
                </div>
                <div class="flex gap-3 mt-4">
                    <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                        Batal
                    </button>
                    <button id="confirmDeleteBtn" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Detail Modal -->
<div id="reportDetailModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-4/5 xl:w-3/4 shadow-lg rounded-lg bg-white max-h-[80vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Detail Laporan</h3>
            <button onclick="closeReportDetailModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="reportDetailContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<script>
let deleteReportId = null;

function deleteReport(reportId, reportTitle) {
    deleteReportId = reportId;
    document.getElementById('deleteReportTitle').textContent = reportTitle;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteReportId = null;
}

// Show report detail
function showReportDetail(reportId) {
    fetch(`/admin/reports/${reportId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayReportDetail(data.report);
                document.getElementById('reportDetailModal').classList.remove('hidden');
            } else {
                alert('Gagal memuat detail laporan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat detail laporan');
        });
}

function displayReportDetail(report) {
    const content = `
        <div class="space-y-4">
            <!-- Report Header -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-xl font-semibold text-gray-900">${report.title}</h4>
                    <span class="px-3 py-1 text-sm font-medium rounded-full 
                        ${report.status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                        ${report.status === 'published' ? 'Published' : 'Draft'}
                    </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Pengguna:</span>
                        <span class="ml-2 font-medium">${report.user.name}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Email:</span>
                        <span class="ml-2 font-medium">${report.user.email}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Tanggal:</span>
                        <span class="ml-2 font-medium">${report.tanggal}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Lokasi:</span>
                        <span class="ml-2 font-medium">${report.lokasi}</span>
                    </div>
                </div>
            </div>

            <!-- Report Content -->
            <div class="bg-white rounded-lg border p-4">
                <h5 class="text-lg font-medium text-gray-900 mb-3">Laporan Pekerjaan</h5>
                <div class="bg-gray-50 rounded-lg p-4 whitespace-pre-wrap text-gray-700">${report.laporan}</div>
            </div>

            ${report.masalah ? `
            <div class="bg-white rounded-lg border p-4">
                <h5 class="text-lg font-medium text-red-900 mb-3">Masalah yang Dihadapi</h5>
                <div class="bg-red-50 rounded-lg p-4 whitespace-pre-wrap text-red-700">${report.masalah}</div>
            </div>
            ` : ''}

            ${report.solusi ? `
            <div class="bg-white rounded-lg border p-4">
                <h5 class="text-lg font-medium text-green-900 mb-3">Solusi yang Dilakukan</h5>
                <div class="bg-green-50 rounded-lg p-4 whitespace-pre-wrap text-green-700">${report.solusi}</div>
            </div>
            ` : ''}

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button onclick="exportToPDF(${report.id})" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-file-pdf mr-2"></i>Export PDF
                </button>
                <button onclick="closeReportDetailModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-times mr-2"></i>Tutup
                </button>
            </div>
        </div>
    `;
    
    document.getElementById('reportDetailContent').innerHTML = content;
}

function closeReportDetailModal() {
    document.getElementById('reportDetailModal').classList.add('hidden');
}

// Export to PDF function
function exportToPDF(reportId) {
    fetch(`/admin/reports/${reportId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const report = data.report;
                
                // Create print-friendly HTML content
                const printContent = `
                    <html>
                    <head>
                        <title>Laporan - ${report.title}</title>
                        <style>
                            body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
                            .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #DC2626; padding-bottom: 20px; }
                            .header h1 { color: #DC2626; margin: 0; }
                            .header p { color: #6B7280; margin: 5px 0 0 0; }
                            .section { margin-bottom: 25px; }
                            .section-title { font-weight: bold; color: #374151; margin-bottom: 10px; font-size: 14px; }
                            .section-content { background: #F9FAFB; padding: 15px; border-radius: 8px; border-left: 4px solid #DC2626; }
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
                                <div class="info-label">Judul Laporan</div>
                                <div class="info-value">${report.title}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Pengguna</div>
                                <div class="info-value">${report.user.name}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Email</div>
                                <div class="info-value">${report.user.email}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Lokasi Kerja</div>
                                <div class="info-value">${report.lokasi}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Status</div>
                                <div class="info-value">${report.status === 'published' ? 'Published' : 'Draft'}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Tanggal Dibuat</div>
                                <div class="info-value">${report.created_at}</div>
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
                    }, 500);
                };
            } else {
                alert('Gagal memuat data laporan untuk export');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat export PDF');
        });
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (deleteReportId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/reports/' + deleteReportId;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        form.appendChild(csrfInput);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('.report-row');
    
    rows.forEach(row => {
        const title = row.dataset.title;
        const user = row.dataset.user;
        const status = row.dataset.status;
        
        const matchesSearch = title.includes(searchTerm) || user.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;
        
        if (matchesSearch && matchesStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Status filter
document.getElementById('statusFilter').addEventListener('change', function(e) {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = e.target.value;
    const rows = document.querySelectorAll('.report-row');
    
    rows.forEach(row => {
        const title = row.dataset.title;
        const user = row.dataset.user;
        const status = row.dataset.status;
        
        const matchesSearch = title.includes(searchTerm) || user.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;
        
        if (matchesSearch && matchesStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

function refreshReports() {
    window.location.reload();
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection
