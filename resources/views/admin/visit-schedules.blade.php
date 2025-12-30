@extends('admin.app')

@section('title', 'Jadwal Kunjungan')

@section('content')
<!-- Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Jadwal Kunjungan</h2>
        <p class="text-gray-600 mt-1">Kelola jadwal kunjungan pengguna</p>
    </div>
    <button onclick="refreshSchedules()" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
        <i class="fas fa-sync-alt mr-2"></i>Refresh
    </button>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                <i class="fas fa-calendar text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Jadwal</p>
                <p class="text-2xl font-bold text-gray-900">{{ $schedules->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Selesai</p>
                <p class="text-2xl font-bold text-gray-900">{{ $schedules->where('status', 'completed')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Dijadwalkan</p>
                <p class="text-2xl font-bold text-gray-900">{{ $schedules->where('status', 'scheduled')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                <i class="fas fa-calendar-day text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Hari Ini</p>
                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\VisitSchedule::whereDate('tanggal_kunjungan', today())->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Calendar View -->
<div id="calendarView" class="bg-white shadow rounded-lg overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Kalender Jadwal Kunjungan</h3>
        <p class="text-sm text-gray-500 mt-1">Semua jadwal kunjungan dari semua pengguna</p>
    </div>
    <div class="p-6">
        <div id="calendar"></div>
    </div>
</div>

<!-- Schedules Table -->
<div id="tableView" class="bg-white shadow rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Daftar Jadwal Kunjungan</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengguna</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kunjungan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tujuan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($schedules as $schedule)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $schedule->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $schedule->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($schedule->tanggal_kunjungan)
                                <div class="text-sm text-gray-900">{{ $schedule->tanggal_kunjungan->format('d M Y') }}</div>
                            @else
                                <div class="text-sm text-gray-400">-</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $schedule->lokasi_kunjungan ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ Str::limit($schedule->deskripsi_kunjungan ?? '-', 40) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($schedule->status == 'completed') bg-green-100 text-green-800
                                @elseif($schedule->status == 'scheduled') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($schedule->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            <i class="fas fa-calendar-check mb-2"></i>
                            <p>Belum ada data jadwal kunjungan</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($schedules->hasPages())
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            {{ $schedules->links() }}
        </div>
    @endif
</div>

<!-- Event Detail Modal -->
<div id="eventModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" style="display: none;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Detail Jadwal Kunjungan</h3>
                <button onclick="closeEventModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="eventDetails" class="space-y-3">
                <!-- Event details will be inserted here -->
            </div>
        </div>
    </div>
</div>
@endsection

<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>

<script>
    let calendar;

    document.addEventListener('DOMContentLoaded', function() {
        initializeCalendar();
    });

    function initializeCalendar() {
        const calendarEl = document.getElementById('calendar');
        
        calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'id',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek,dayGridDay'
            },
            height: 'auto',
            events: '/admin/visit-schedules/calendar',
            eventClick: function(info) {
                showEventDetails(info.event);
            },
            eventMouseEnter: function(info) {
                const tooltip = document.createElement('div');
                tooltip.className = 'fc-tooltip';
                tooltip.innerHTML = `
                    <div class="bg-gray-800 text-white p-2 rounded shadow-lg text-sm">
                        <div class="font-semibold">${info.event.title}</div>
                        <div>${info.event.extendedProps.user}</div>
                        <div>${info.event.extendedProps.location || 'Lokasi tidak tersedia'}</div>
                    </div>
                `;
                document.body.appendChild(tooltip);
                
                const rect = info.el.getBoundingClientRect();
                tooltip.style.position = 'absolute';
                tooltip.style.top = rect.top - 10 + 'px';
                tooltip.style.left = rect.left + 'px';
                tooltip.style.transform = 'translateY(-100%)';
                tooltip.style.zIndex = '1000';
                
                info.el.addEventListener('mouseleave', function() {
                    tooltip.remove();
                }, { once: true });
            }
        });
        
        calendar.render();
    }

    function showEventDetails(event) {
        const modal = document.getElementById('eventModal');
        const details = document.getElementById('eventDetails');
        
        details.innerHTML = `
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Judul</label>
                    <p class="text-gray-900">${event.title}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Pengguna</label>
                    <p class="text-gray-900">${event.extendedProps.user}</p>
                    <p class="text-sm text-gray-500">${event.extendedProps.user_email}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Tanggal & Waktu</label>
                    <p class="text-gray-900">${event.start.toLocaleString('id-ID')}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Lokasi</label>
                    <p class="text-gray-900">${event.extendedProps.location || 'Lokasi tidak tersedia'}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Deskripsi</label>
                    <p class="text-gray-900">${event.extendedProps.description || 'Tidak ada deskripsi'}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Status</label>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                        event.extendedProps.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                    }">
                        ${event.extendedProps.status === 'completed' ? 'Selesai' : 'Dijadwalkan'}
                    </span>
                </div>
            </div>
        `;
        
        modal.style.display = 'block';
    }

    function closeEventModal() {
        document.getElementById('eventModal').style.display = 'none';
    }

    // Refresh schedules data
    function refreshSchedules() {
        if (calendar) {
            calendar.refetchEvents();
        } else {
            window.location.reload();
        }
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('eventModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }
</script>
