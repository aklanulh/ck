<!-- User Navigation -->
<nav class="bg-white shadow-sm border-b sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo & Title -->
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-purple-600 rounded-lg p-2">
                        <i class="fas fa-briefcase text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h1 class="text-xl font-bold text-gray-900">CatatanKerja</h1>
                </div>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden md:flex md:items-center md:space-x-1">
                <!-- Daily Report Menu -->
                <a href="{{ route('report') }}" class="py-4 px-3 border-b-2 @if(request()->is('report') || request()->is('report/')) border-purple-500 text-purple-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif font-medium flex items-center text-sm">
                    <i class="fas fa-plus mr-2"></i>Buat Laporan
                </a>
                
                <!-- Calendar Menu -->
                <a href="{{ route('calendar') }}" class="py-4 px-3 border-b-2 @if(request()->is('calendar*')) border-purple-500 text-purple-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif font-medium flex items-center text-sm">
                    <i class="fas fa-calendar mr-2"></i>Kalender
                </a>
                
                <!-- Absensi Menu -->
                <a href="{{ route('absensi') }}" class="py-4 px-3 border-b-2 @if(request()->is('absensi*')) border-purple-500 text-purple-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif font-medium flex items-center text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>Absensi
                </a>
            </div>

            <!-- User Menu -->
            <div class="hidden md:flex md:items-center md:space-x-3">
                <div class="flex items-center space-x-3 border-l border-gray-200 pl-4">
                    <div class="flex items-center space-x-3">
                        <div class="bg-purple-100 rounded-full p-2">
                            <i class="fas fa-user text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ session('user.name') }}</p>
                            <p class="text-xs text-gray-500">{{ session('user.email') }}</p>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button onclick="toggleMobileMenu()" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t">
            <div class="px-4 py-3 space-y-1">
                <!-- Daily Report Menu -->
                <a href="{{ route('report') }}" class="block py-2 px-3 @if(request()->is('report') || request()->is('report/')) text-purple-600 bg-purple-50 @else text-gray-700 hover:bg-gray-100 @endif rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Buat Laporan
                </a>
                
                <!-- Calendar Menu -->
                <a href="{{ route('calendar') }}" class="block py-2 px-3 @if(request()->is('calendar*')) text-purple-600 bg-purple-50 @else text-gray-700 hover:bg-gray-100 @endif rounded-lg transition-colors">
                    <i class="fas fa-calendar mr-2"></i>Kalender
                </a>
                
                <!-- Absensi Menu -->
                <a href="{{ route('absensi') }}" class="block py-2 px-3 @if(request()->is('absensi*')) text-purple-600 bg-purple-50 @else text-gray-700 hover:bg-gray-100 @endif rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Absensi
                </a>
                
                <!-- Mobile User Section -->
                <div class="border-t border-gray-200 pt-3 mt-3">
                    <div class="flex items-center space-x-3 pb-3">
                        <div class="bg-purple-100 rounded-full p-2">
                            <i class="fas fa-user text-purple-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ session('user.name') }}</p>
                            <p class="text-xs text-gray-500">{{ session('user.email') }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full text-left text-red-600 hover:text-red-800 px-3 py-2 rounded-lg hover:bg-red-50 transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
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
