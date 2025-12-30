<!-- Main Navigation -->
<nav class="bg-white shadow-sm border-b sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo & Title -->
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-red-600 rounded-lg p-2">
                        <i class="fas fa-shield-alt text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-3 sm:ml-4">
                    <h1 class="text-lg sm:text-xl font-bold text-gray-900">Admin Panel</h1>
                    <p class="text-xs text-gray-500">CatatanKerja Management System</p>
                </div>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden lg:flex lg:items-center lg:space-x-8">
                <a href="/admin" class="py-4 px-1 border-b-2 @if(request()->is('admin') || request()->is('admin/*')) border-red-500 text-red-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif font-medium flex items-center">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                
                <!-- Users Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="py-4 px-1 border-b-2 @if(request()->is('admin/users*')) border-red-500 text-red-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif font-medium flex items-center transition-colors">
                        <i class="fas fa-users mr-2"></i>Pengguna
                        <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="absolute top-full left-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                        <div class="py-1">
                            <a href="/admin/users" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-list mr-2"></i>Daftar Pengguna
                            </a>
                            <a href="/admin/users/create" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-plus mr-2"></i>Tambah Pengguna
                            </a>
                        </div>
                    </div>
                </div>

                <a href="/admin/absensi" class="py-4 px-1 border-b-2 @if(request()->is('admin/absensi*')) border-red-500 text-red-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif font-medium flex items-center">
                    <i class="fas fa-clock mr-2"></i>Absensi
                </a>
                
                <!-- Reports Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="py-4 px-1 border-b-2 @if(request()->is('admin/reports*')) border-red-500 text-red-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif font-medium flex items-center transition-colors">
                        <i class="fas fa-file-alt mr-2"></i>Laporan
                        <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="absolute top-full left-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                        <div class="py-1">
                            <a href="/admin/reports" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-list mr-2"></i>Daftar Laporan
                            </a>
                            <a href="/admin/reports/drafts" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-edit mr-2"></i>Draft Laporan
                            </a>
                        </div>
                    </div>
                </div>

                <a href="/admin/visit-schedules" class="py-4 px-1 border-b-2 @if(request()->is('admin/visit-schedules*')) border-red-500 text-red-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif font-medium flex items-center">
                    <i class="fas fa-calendar-alt mr-2"></i>Jadwal Kunjungan
                </a>
                
                <!-- Settings Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="py-4 px-1 border-b-2 @if(request()->is('admin/settings*')) border-red-500 text-red-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif font-medium flex items-center transition-colors">
                        <i class="fas fa-cog mr-2"></i>Pengaturan
                        <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="absolute top-full left-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                        <div class="py-1">
                            <a href="/admin/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-cog mr-2"></i>Umum
                            </a>
                            <a href="/admin/settings/company" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-building mr-2"></i>Perusahaan
                            </a>
                            <a href="/admin/settings/system" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-server mr-2"></i>Sistem
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-3 border-l border-gray-200 pl-4">
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

            <!-- Mobile Menu Button -->
            <div class="lg:hidden flex items-center space-x-2">
                <button onclick="toggleMobileMenu()" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobileMenu" class="hidden lg:hidden bg-white border-t">
            <div class="px-4 py-3 space-y-2">
                <a href="/admin" class="block py-2 px-3 @if(request()->is('admin') || request()->is('admin/')) text-red-600 bg-red-50 @else text-gray-700 hover:bg-gray-100 @endif rounded-lg transition-colors">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                
                <!-- Mobile Users Section -->
                <div class="space-y-1">
                    <div class="py-2 px-3 text-sm font-medium text-gray-500">Pengguna</div>
                    <a href="/admin/users" class="block py-2 px-3 pl-6 @if(request()->is('admin/users') && !request()->is('admin/users/*')) text-red-600 bg-red-50 @else text-gray-700 hover:bg-gray-100 @endif rounded-lg transition-colors">
                        <i class="fas fa-list mr-2"></i>Daftar Pengguna
                    </a>
                    <a href="/admin/users/create" class="block py-2 px-3 pl-6 @if(request()->is('admin/users/create')) text-red-600 bg-red-50 @else text-gray-700 hover:bg-gray-100 @endif rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>Tambah Pengguna
                    </a>
                </div>

                <a href="/admin/absensi" class="block py-2 px-3 @if(request()->is('admin/absensi*')) text-red-600 bg-red-50 @else text-gray-700 hover:bg-gray-100 @endif rounded-lg transition-colors">
                    <i class="fas fa-clock mr-2"></i>Absensi
                </a>
                
                <!-- Mobile Reports Section -->
                <div class="space-y-1">
                    <div class="py-2 px-3 text-sm font-medium text-gray-500">Laporan</div>
                    <a href="/admin/reports" class="block py-2 px-3 pl-6 @if(request()->is('admin/reports') && !request()->is('admin/reports/*')) text-red-600 bg-red-50 @else text-gray-700 hover:bg-gray-100 @endif rounded-lg transition-colors">
                        <i class="fas fa-list mr-2"></i>Daftar Laporan
                    </a>
                    <a href="/admin/reports/drafts" class="block py-2 px-3 pl-6 @if(request()->is('admin/reports/drafts')) text-red-600 bg-red-50 @else text-gray-700 hover:bg-gray-100 @endif rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i>Draft Laporan
                    </a>
                </div>

                <a href="/admin/visit-schedules" class="block py-2 px-3 @if(request()->is('admin/visit-schedules*')) text-red-600 bg-red-50 @else text-gray-700 hover:bg-gray-100 @endif rounded-lg transition-colors">
                    <i class="fas fa-calendar-alt mr-2"></i>Jadwal Kunjungan
                </a>
                
                <!-- Mobile Settings Section -->
                <div class="space-y-1">
                    <div class="py-2 px-3 text-sm font-medium text-gray-500">Pengaturan</div>
                    <a href="/admin/settings" class="block py-2 px-3 pl-6 @if(request()->is('admin/settings') && !request()->is('admin/settings/*')) text-red-600 bg-red-50 @else text-gray-700 hover:bg-gray-100 @endif rounded-lg transition-colors">
                        <i class="fas fa-cog mr-2"></i>Umum
                    </a>
                    <a href="/admin/settings/company" class="block py-2 px-3 pl-6 @if(request()->is('admin/settings/company')) text-red-600 bg-red-50 @else text-gray-700 hover:bg-gray-100 @endif rounded-lg transition-colors">
                        <i class="fas fa-building mr-2"></i>Perusahaan
                    </a>
                    <a href="/admin/settings/system" class="block py-2 px-3 pl-6 @if(request()->is('admin/settings/system')) text-red-600 bg-red-50 @else text-gray-700 hover:bg-gray-100 @endif rounded-lg transition-colors">
                        <i class="fas fa-server mr-2"></i>Sistem
                    </a>
                </div>

                <!-- Mobile User Section -->
                <div class="border-t border-gray-200 pt-3 mt-3">
                    <div class="flex items-center space-x-3 pb-3">
                        <div class="bg-red-100 rounded-full p-2">
                            <i class="fas fa-user-shield text-red-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ session('user')['name'] }}</p>
                            <p class="text-xs text-gray-500">Administrator</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full text-left text-red-600 hover:text-red-800 px-3 py-2 rounded-lg hover:bg-red-50 transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
// Mobile menu toggle
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    menu.classList.toggle('hidden');
}

// Close mobile menu when clicking outside
document.addEventListener('click', function(event) {
    const menu = document.getElementById('mobileMenu');
    const menuButton = event.target.closest('button[onclick="toggleMobileMenu()"]');
    
    if (!menu.contains(event.target) && !menuButton && !menu.classList.contains('hidden')) {
        menu.classList.add('hidden');
    }
});
</script>
