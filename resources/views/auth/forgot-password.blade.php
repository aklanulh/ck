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
            <p class="text-gray-600 mt-2">Masukkan email untuk reset password</p>
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

        <!-- Forgot Password Form -->
        <div class="bg-white shadow-lg rounded-lg p-8">
            <form id="forgotForm" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email Address
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="nama@email.com">
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Kirim Link Reset
                    </button>
                </div>

                <div class="text-center">
                    <a href="/login" class="text-purple-600 hover:text-purple-500 text-sm font-medium">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Kembali ke Login
                    </a>
                </div>
            </form>
        </div>

        <!-- Manual Reset (Development Only) -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-tools text-yellow-400 text-lg"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Reset Manual (Development)</h3>
                    <p class="text-sm text-yellow-700 mt-1">Untuk development, gunakan form manual reset:</p>
                    <form id="manualResetForm" class="mt-3 space-y-3">
                        @csrf
                        <input type="email" id="resetEmail" placeholder="Email user" required
                               class="w-full px-3 py-2 border border-yellow-300 rounded-lg text-sm">
                        <input type="password" id="resetPassword" placeholder="Password baru" required
                               class="w-full px-3 py-2 border border-yellow-300 rounded-lg text-sm">
                        <button type="submit" 
                                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-2 px-4 rounded-lg text-sm font-medium">
                            <i class="fas fa-key mr-2"></i>
                            Reset Password Manual
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Forgot Password Form
        document.getElementById('forgotForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
            
            fetch('/forgot-password', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: formData.get('email')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Link reset password: ' + data.reset_link + '\n\nCatatan: Dalam production link akan dikirim via email');
                } else {
                    alert('Error: ' + (data.message || 'Terjadi kesalahan'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim link reset');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });

        // Manual Reset Form
        document.getElementById('manualResetForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('resetEmail').value;
            const password = document.getElementById('resetPassword').value;
            
            if (!email || !password) {
                alert('Email dan password baru harus diisi');
                return;
            }
            
            if (confirm('Reset password untuk ' + email + ' menjadi: ' + password + '?')) {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mereting...';
                
                fetch('/manual-reset-password', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        email: email,
                        new_password: password
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        document.getElementById('resetEmail').value = '';
                        document.getElementById('resetPassword').value = '';
                    } else {
                        alert('Error: ' + (data.message || 'Terjadi kesalahan'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mereset password');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            }
        });
    </script>
</body>
</html>
