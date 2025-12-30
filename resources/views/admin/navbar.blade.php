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
                    <h1 class="text-lg sm:text-xl font-bold text-gray-900">PANEL CK MSA</h1>
                </div>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden lg:flex lg:items-center lg:space-x-6">
                <a href="/admin" class="py-4 px-1 border-b-2 @if(request()->is('admin') || request()->is('admin/')) border-red-500 text-red-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif font-medium flex items-center text-sm">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                
                <a href="/admin/users" class="py-4 px-1 border-b-2 @if(request()->is('admin/users*')) border-red-500 text-red-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif font-medium flex items-center text-sm">
                    <i class="fas fa-users mr-2"></i>Pengguna
                </a>

                <a href="/admin/absensi" class="py-4 px-1 border-b-2 @if(request()->is('admin/absensi*')) border-red-500 text-red-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif font-medium flex items-center text-sm">
                    <i class="fas fa-clock mr-2"></i>Absensi
                </a>
                
                <a href="/admin/reports" class="py-4 px-1 border-b-2 @if(request()->is('admin/reports*')) border-red-500 text-red-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif font-medium flex items-center text-sm">
                    <i class="fas fa-file-alt mr-2"></i>Laporan
                </a>

                <a href="/admin/visit-schedules" class="py-4 px-1 border-b-2 @if(request()->is('admin/visit-schedules*')) border-red-500 text-red-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif font-medium flex items-center text-sm">
                    <i class="fas fa-calendar-alt mr-2"></i>Jadwal
                </a>
                
                <!-- Catatan Kerja Button -->
                <a href="/absensi" class="flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                    <i class="fas fa-briefcase mr-2"></i>
                    <span class="font-medium">Catatan Kerja</span>
                </a>
            </div>

            <!-- User Menu -->
            <div class="hidden lg:flex lg:items-center lg:space-x-3">
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
                        <button type="submit" class="bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm">
                            <i class="fas fa-sign-out-alt mr-1"></i>Keluar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <div class="lg:hidden flex items-center space-x-2">
                <!-- Catatan Kerja Button Mobile -->
                <a href="/absensi" class="flex items-center px-2 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-xs">
                    <i class="fas fa-briefcase mr-1"></i>
                    <span class="font-medium">Catatan Kerja</span>
                </a>
                <button onclick="toggleMobileMenu()" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobileMenu" class="hidden lg:hidden bg-white border-t">
            <div class="px-4 py-3 space-y-1">
                <a href="/admin" class="block py-2 px-3 @if(request()->is('admin') || request()->is('admin/')) text-red-600 bg-red-50 @else text-gray-700 hover:bg-gray-100 @endif rounded-lg transition-colors">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                
                <a href="/admin/users" class="block py-2 px-3 @if(request()->is('admin/users*')) text-red-600 bg-red-50 @else text-gray-700 hover:bg-gray-100 @endif rounded-lg transition-colors">
                    <i class="fas fa-users mr-2"></i>Pengguna
                </a>

                <a href="/admin/absensi" class="block py-2 px-3 @if(request()->is('admin/absensi*')) text-red-600 bg-red-50 @else text-gray-700 hover:bg-gray-100 @endif rounded-lg transition-colors">
                    <i class="fas fa-clock mr-2"></i>Absensi
                </a>
                
                <a href="/admin/reports" class="block py-2 px-3 @if(request()->is('admin/reports*')) text-red-600 bg-red-50 @else text-gray-700 hover:bg-gray-100 @endif rounded-lg transition-colors">
                    <i class="fas fa-file-alt mr-2"></i>Laporan
                </a>

                <a href="/admin/visit-schedules" class="block py-2 px-3 @if(request()->is('admin/visit-schedules*')) text-red-600 bg-red-50 @else text-gray-700 hover:bg-gray-100 @endif rounded-lg transition-colors">
                    <i class="fas fa-calendar-alt mr-2"></i>Jadwal Kunjungan
                </a>
                

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
