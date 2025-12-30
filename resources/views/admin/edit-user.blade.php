<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna - Admin CatatanKerja</title>
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
                        <p class="text-xs text-gray-500">Edit Pengguna</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="/admin/users" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Pengguna
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

        <!-- Form Card -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Edit Pengguna</h3>
                <p class="text-sm text-gray-600 mt-1">Perbarui data pengguna: {{ $user->name }}</p>
            </div>
            
            <form action="/admin/users/{{ $user->id }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <!-- Error Messages -->
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                        <div class="font-medium mb-2">Error:</div>
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}"
                               required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Masukkan nama lengkap">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}"
                               required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="email@example.com">
                    </div>

                    <!-- Password (Optional) -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password <span class="text-gray-500">(Kosongkan jika tidak diubah)</span>
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               minlength="6"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Minimal 6 karakter">
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                            Konfirmasi Password <span class="text-gray-500">(Isi jika mengubah password)</span>
                        </label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               minlength="6"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ulangi password baru">
                    </div>

                    <!-- Role -->
                    <div class="md:col-span-2">
                        <label for="role" class="block text-sm font-medium text-gray-700">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select id="role" 
                                name="role" 
                                required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Role</option>
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                </div>

                <!-- User Information -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Informasi Pengguna:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">ID Pengguna:</span>
                            <span class="ml-2 font-medium">{{ $user->id }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Tanggal Dibuat:</span>
                            <span class="ml-2 font-medium">{{ $user->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Terakhir Diperbarui:</span>
                            <span class="ml-2 font-medium">{{ $user->updated_at->format('d M Y H:i') }}</span>
                        </div>
                        @if($user->email_verified_at)
                        <div>
                            <span class="text-gray-500">Email Terverifikasi:</span>
                            <span class="ml-2 font-medium text-green-600">{{ $user->email_verified_at->format('d M Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Role Information -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h4 class="text-sm font-medium text-blue-900 mb-2">Informasi Role:</h4>
                    <div class="space-y-2 text-sm text-blue-800">
                        <div class="flex items-start">
                            <i class="fas fa-user mt-1 mr-2 text-blue-600"></i>
                            <div>
                                <strong>User:</strong> Dapat mengakses halaman absensi, membuat laporan, dan melihat jadwal kunjungan.
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-user-shield mt-1 mr-2 text-blue-600"></i>
                            <div>
                                <strong>Admin:</strong> Memiliki akses penuh ke semua fitur termasuk manajemen pengguna dan laporan.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Warning for Self-Edit -->
                @if($user->id === session('user')['id'])
                    <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
                        <div class="flex">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-2"></i>
                            <div class="text-sm text-yellow-800">
                                <strong>Perhatian:</strong> Anda sedang mengedit profil Anda sendiri. Perubahan role dapat mempengaruhi akses Anda ke sistem.
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form Actions -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="/admin/users" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Perbarui Pengguna
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
