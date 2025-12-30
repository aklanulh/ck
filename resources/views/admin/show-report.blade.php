<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan - Admin CatatanKerja</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-red-600 rounded-lg p-2">
                            <i class="fas fa-shield-alt text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-xl font-bold text-gray-900">Admin Panel</h1>
                        <p class="text-xs text-gray-500">Detail Laporan</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="/admin/reports" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Laporan
                    </a>
                    
                    <div class="flex items-center space-x-3">
                        <div class="bg-red-100 rounded-full p-2">
                            <i class="fas fa-user-shield text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ session('user')['name'] }}</p>
                            <p class="text-xs text-gray-500">Administrator</p>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        <!-- Report Header -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $report->title ?? 'Tanpa Judul' }}</h2>
                        <div class="mt-2 flex items-center space-x-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($report->status == 'published') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($report->status) }}
                            </span>
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $report->created_at->format('d M Y H:i') }}
                            </span>
                        </div>
                    </div>
                    <form action="/admin/reports/{{ $report->id }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                            <i class="fas fa-trash mr-2"></i>Hapus Laporan
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="p-6">
                <!-- User Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Informasi Pengguna</h3>
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 h-12 w-12">
                            <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                                <i class="fas fa-user text-gray-600"></i>
                            </div>
                        </div>
                        <div>
                            <div class="text-lg font-medium text-gray-900">{{ $report->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $report->user->email }}</div>
                        </div>
                    </div>
                </div>

                <!-- Report Content -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Isi Laporan</h3>
                    @if($report->description)
                        <div class="prose max-w-none">
                            {!! nl2br(e($report->description)) !!}
                        </div>
                    @else
                        <p class="text-gray-500 italic">Tidak ada deskripsi</p>
                    @endif
                </div>

                <!-- Report Metadata -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Metadata</h3>
                        <dl class="space-y-2">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">ID Laporan:</dt>
                                <dd class="text-sm text-gray-900">#{{ $report->id }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Dibuat:</dt>
                                <dd class="text-sm text-gray-900">{{ $report->created_at->format('d M Y H:i:s') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Diperbarui:</dt>
                                <dd class="text-sm text-gray-900">{{ $report->updated_at->format('d M Y H:i:s') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Status & Aksi</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-700">Status Saat Ini:</span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($report->status == 'published') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </div>
                            
                            @if($report->attachments)
                                <div class="p-3 bg-blue-50 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-paperclip text-blue-600 mr-2"></i>
                                        <span class="text-sm text-blue-800">Lampiran tersedia</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4">
            <a href="/admin/reports" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-download mr-2"></i>Export PDF
            </button>
        </div>
    </main>
</body>
</html>
