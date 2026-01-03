<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - CatatanKerja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .input-focus:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        .floating-icon {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <!-- Logo dan Brand -->
        <div class="text-center mb-6 sm:mb-8">
            <div class="floating-icon inline-block mb-3 sm:mb-4">
                <div class="bg-white rounded-full p-3 sm:p-4 shadow-lg">
                    <i class="fas fa-briefcase text-3xl sm:text-4xl text-purple-600"></i>
                </div>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">CatatanKerja</h1>
            <p class="text-purple-100">Sistem Absensi & Daily Report Karyawan</p>
        </div>

        <!-- Login Card -->
        <div class="glass-effect rounded-2xl shadow-2xl p-6 sm:p-8">
            <!-- Header -->
            <div class="text-center mb-4 sm:mb-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">Selamat Datang</h2>
                <p class="text-gray-600 text-sm sm:text-base">Silakan login untuk melanjutkan</p>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login.submit') }}" class="space-y-6">
                @csrf

                <!-- Email Field -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-purple-600"></i>Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="input-focus w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:outline-none transition text-sm sm:text-base"
                        placeholder="nama@perusahaan.com"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-purple-600"></i>Password
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="input-focus w-full px-3 sm:px-4 py-2 sm:py-3 pr-10 sm:pr-12 border border-gray-300 rounded-lg focus:outline-none transition text-sm sm:text-base"
                            placeholder="Masukkan password"
                        >
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute right-2 sm:right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-purple-600 focus:outline-none p-1"
                        >
                            <i id="password-icon" class="fas fa-eye text-sm sm:text-base"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0 mb-6">
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                        >
                        <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                    </label>
                    <a href="/forgot-password" class="text-sm text-purple-600 hover:text-purple-700 font-medium text-center">
                        Lupa password?
                    </a>
                </div>

                <!-- Login Button -->
                <button
                    type="submit"
                    class="btn-primary w-full py-2 sm:py-3 px-4 text-white font-semibold rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 text-sm sm:text-base"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Masuk
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-6 sm:mt-8 text-center">
                <p class="text-xs sm:text-sm text-gray-500">
                    &copy; 2024 CatatanKerja. Sistem manajemen karyawan terintegrasi.
                </p>
            </div>
        </div>

        <!-- Background Decorations -->
        <div class="fixed top-10 left-10 text-white opacity-20">
            <i class="fas fa-clock text-6xl"></i>
        </div>
        <div class="fixed bottom-10 right-10 text-white opacity-20">
            <i class="fas fa-chart-line text-6xl"></i>
        </div>
        <div class="fixed top-1/2 left-10 text-white opacity-20">
            <i class="fas fa-users text-5xl"></i>
        </div>
        <div class="fixed top-1/3 right-10 text-white opacity-20">
            <i class="fas fa-calendar-check text-5xl"></i>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
