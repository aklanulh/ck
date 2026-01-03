@extends('admin.app')

@section('title', 'Kelola Pengguna')

@section('content')
<!-- Header -->
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Kelola Pengguna</h2>
    <p class="text-gray-600 mt-1">Manajemen pengguna sistem CatatanKerja</p>
</div>

<!-- Action Buttons -->
<div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="/admin/users/create" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>Tambah Pengguna
        </a>
        <button onclick="refreshUsers()" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-sync-alt mr-2"></i>Refresh
        </button>
    </div>
    
    <!-- Search -->
    <div class="relative">
        <input type="text" id="searchInput" placeholder="Cari pengguna..." 
               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent w-full sm:w-64">
        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
    </div>
</div>

<!-- Users Table -->
<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Daftar Pengguna</h3>
        <p class="text-sm text-gray-500 mt-1">{{ $users->count() }} pengguna terdaftar</p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengguna</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terdaftar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="usersTableBody">
                @forelse($users as $user)
                    <tr class="user-row" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">ID: {{ $user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($user->role == 'admin') bg-purple-100 text-purple-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="/admin/users/{{ $user->id }}/edit" class="text-blue-600 hover:text-blue-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="generateResetLink({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')" class="text-green-600 hover:text-green-900" title="Generate Reset Link">
                                    <i class="fas fa-key"></i>
                                </button>
                                @if($user->id !== session('user')['id'])
                                    <button onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" class="text-red-600 hover:text-red-900" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-users text-4xl mb-4"></i>
                            <p class="text-lg font-medium">Belum ada pengguna</p>
                            <p class="text-sm mt-1">Tambahkan pengguna pertama untuk memulai</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($users->hasPages())
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                {{ $users->links() }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Menampilkan <span class="font-medium">{{ $users->firstItem() }}</span> hingga 
                        <span class="font-medium">{{ $users->lastItem() }}</span> dari 
                        <span class="font-medium">{{ $users->total() }}</span> hasil
                    </p>
                </div>
                <div>
                    {{ $users->links() }}
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
                <h3 class="text-lg font-medium text-gray-900">Hapus Pengguna</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus pengguna <strong id="deleteUserName"></strong>?</p>
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

<!-- Reset Link Modal -->
<div id="resetLinkModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-[500px] shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full">
                <i class="fas fa-key text-green-600"></i>
            </div>
            <div class="mt-4 text-center">
                <h3 class="text-lg font-medium text-gray-900">Generate Link Reset Password</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Buat link reset password untuk <strong id="resetUserName"></strong>?</p>
                    <p class="text-xs text-gray-600 mt-2">Link akan berlaku selama 24 jam</p>
                </div>
                <div class="flex gap-3 mt-4">
                    <button onclick="closeResetLinkModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                        Batal
                    </button>
                    <button id="generateResetBtn" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-spinner fa-spin mr-2 hidden" id="resetSpinner"></i>
                        Generate Link
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reset Link Result Modal -->
<div id="resetResultModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-[600px] shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full">
                <i class="fas fa-check text-green-600"></i>
            </div>
            <div class="mt-4 text-center">
                <h3 class="text-lg font-medium text-gray-900">Link Reset Password Berhasil Dibuat</h3>
                <div class="mt-4 px-7 py-3 text-left">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Link Reset Password:</label>
                        <div class="flex items-center gap-2">
                            <input type="text" id="resetLink" readonly class="flex-1 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm">
                            <button onclick="copyResetLink()" class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pesan WhatsApp:</label>
                        <textarea id="whatsappMessage" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm h-24"></textarea>
                        <button onclick="copyWhatsAppMessage()" class="mt-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                            <i class="fab fa-whatsapp mr-2"></i>Salin Pesan WhatsApp
                        </button>
                    </div>
                    <div class="text-xs text-gray-500">
                        <p>Berlaku sampai: <span id="expiresAt"></span></p>
                    </div>
                </div>
                <div class="mt-4">
                    <button onclick="closeResetResultModal()" class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let deleteUserId = null;
let resetUserId = null;

function deleteUser(userId, userName) {
    deleteUserId = userId;
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteUserId = null;
}

function generateResetLink(userId, userName, userEmail) {
    resetUserId = userId;
    document.getElementById('resetUserName').textContent = userName + ' (' + userEmail + ')';
    document.getElementById('resetLinkModal').classList.remove('hidden');
}

function closeResetLinkModal() {
    document.getElementById('resetLinkModal').classList.add('hidden');
    resetUserId = null;
}

function closeResetResultModal() {
    document.getElementById('resetResultModal').classList.add('hidden');
}

function copyResetLink() {
    const resetLink = document.getElementById('resetLink');
    resetLink.select();
    document.execCommand('copy');
    
    // Show feedback
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check"></i>';
    setTimeout(() => {
        btn.innerHTML = originalText;
    }, 1000);
}

function copyWhatsAppMessage() {
    const whatsappMessage = document.getElementById('whatsappMessage');
    whatsappMessage.select();
    document.execCommand('copy');
    
    // Show feedback
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check mr-2"></i>Tersalin!';
    setTimeout(() => {
        btn.innerHTML = originalText;
    }, 1000);
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (deleteUserId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/users/' + deleteUserId;
        
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

// Generate Reset Link
document.getElementById('generateResetBtn').addEventListener('click', function() {
    if (resetUserId) {
        const btn = this;
        const spinner = document.getElementById('resetSpinner');
        const originalText = btn.innerHTML;
        
        btn.disabled = true;
        spinner.classList.remove('hidden');
        
        fetch('/admin/users/' + resetUserId + '/reset-link', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Fill result modal
                document.getElementById('resetLink').value = data.reset_link;
                document.getElementById('whatsappMessage').value = data.whatsapp_message;
                document.getElementById('expiresAt').textContent = data.expires_at;
                
                // Close confirmation modal and show result
                closeResetLinkModal();
                document.getElementById('resetResultModal').classList.remove('hidden');
            } else {
                alert('Error: ' + (data.message || 'Gagal generate link'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat generate link reset');
        })
        .finally(() => {
            btn.disabled = false;
            spinner.classList.add('hidden');
            btn.innerHTML = originalText;
        });
    }
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.user-row');
    
    rows.forEach(row => {
        const name = row.dataset.name;
        const email = row.dataset.email;
        
        if (name.includes(searchTerm) || email.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

function refreshUsers() {
    window.location.reload();
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

document.getElementById('resetLinkModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeResetLinkModal();
    }
});

document.getElementById('resetResultModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeResetResultModal();
    }
});
</script>
@endsection
