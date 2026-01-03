<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - CatatanKerja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8 p-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-purple-600 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-briefcase text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Lupa Password</h2>
            <p class="text-gray-600 mt-2">Hubungi admin untuk reset password</p>
        </div>

        <!-- Error/Success Messages -->
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
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

        <!-- Admin Contact Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-user-shield text-blue-400 text-lg"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Hubungi Admin</h3>
                    <p class="text-sm text-blue-700 mt-1">Untuk reset password, silakan hubungi admin developer sistem ini.</p>
                    <p class="text-xs text-blue-500 mt-2">Admin akan memberikan link reset password khusus untuk Anda</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // No form submission needed - just display contact info
    </script>
</body>
</html>
