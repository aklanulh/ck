<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VisitSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
    /**
     * Display the calendar page.
     */
    public function index()
    {
        $user = session('user');
        if (!$user) {
            return redirect('/login');
        }

        // Get current month schedules
        $schedules = VisitSchedule::where('user_id', $user['id'])
            ->whereMonth('tanggal_kunjungan', now()->month)
            ->whereYear('tanggal_kunjungan', now()->year)
            ->orderBy('tanggal_kunjungan')
            ->orderBy('waktu_mulai')
            ->get();

        // Get statistics
        $stats = [
            'total_schedules' => VisitSchedule::where('user_id', $user['id'])->count(),
            'this_month' => VisitSchedule::where('user_id', $user['id'])
                ->whereMonth('tanggal_kunjungan', now()->month)
                ->whereYear('tanggal_kunjungan', now()->year)
                ->count(),
        ];

        return view('calendar', compact('schedules', 'stats'));
    }

    /**
     * Get calendar events for FullCalendar.
     */
    public function getEvents(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $start = $request->get('start');
        $end = $request->get('end');

        $schedules = VisitSchedule::where('user_id', $user['id'])
            ->whereBetween('tanggal_kunjungan', [$start, $end])
            ->get();

        $events = $schedules->map(function ($schedule) {
            $title = $schedule->judul_kunjungan;
            if ($schedule->lokasi_kunjungan) {
                $title .= ' - ' . $schedule->lokasi_kunjungan;
            }

            return [
                'id' => $schedule->id,
                'title' => $title,
                'start' => $schedule->tanggal_kunjungan->format('Y-m-d'),
                'end' => $schedule->tanggal_kunjungan->format('Y-m-d'),
                'description' => $schedule->deskripsi_kunjungan,
                'location' => $schedule->lokasi_kunjungan,
                'backgroundColor' => '#f97316', // orange-500
                'borderColor' => '#ea580c', // orange-600
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'formatted_tanggal' => $schedule->formatted_tanggal,
                    'lokasi' => $schedule->lokasi_kunjungan,
                ]
            ];
        });

        return response()->json($events);
    }

    /**
     * Store a new visit schedule.
     */
    public function store(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Log incoming data for debugging
        Log::info('Store request data:', $request->all());

        $request->validate([
            'judul_kunjungan' => 'required|string|max:20',
            'lokasi_kunjungan' => 'nullable|string|max:60',
            'deskripsi_kunjungan' => 'nullable|string|max:200',
            'tanggal_kunjungan' => 'required|date',
        ]);

        try {
            $schedule = VisitSchedule::create([
                'user_id' => $user['id'],
                'judul_kunjungan' => $request->judul_kunjungan,
                'lokasi_kunjungan' => $request->lokasi_kunjungan,
                'deskripsi_kunjungan' => $request->deskripsi_kunjungan,
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
            ]);

            Log::info('Schedule created successfully:', ['schedule_id' => $schedule->id]);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal kunjungan berhasil ditambahkan!',
                'schedule' => $schedule
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating schedule:', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Gagal menyimpan jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a visit schedule.
     */
    public function update(Request $request, $id)
    {
        $user = session('user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $schedule = VisitSchedule::where('user_id', $user['id'])->find($id);
        if (!$schedule) {
            return response()->json(['error' => 'Jadwal tidak ditemukan'], 404);
        }

        $request->validate([
            'judul_kunjungan' => 'required|string|max:20',
            'lokasi_kunjungan' => 'nullable|string|max:60',
            'deskripsi_kunjungan' => 'nullable|string|max:200',
            'tanggal_kunjungan' => 'required|date',
        ]);

        try {
            $schedule->update([
                'judul_kunjungan' => $request->judul_kunjungan,
                'lokasi_kunjungan' => $request->lokasi_kunjungan,
                'deskripsi_kunjungan' => $request->deskripsi_kunjungan,
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal kunjungan berhasil diperbarui!',
                'schedule' => $schedule
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal memperbarui jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a visit schedule.
     */
    public function destroy($id)
    {
        $user = session('user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $schedule = VisitSchedule::where('user_id', $user['id'])->find($id);
        if (!$schedule) {
            return response()->json(['error' => 'Jadwal tidak ditemukan'], 404);
        }

        try {
            $schedule->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal kunjungan berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menghapus jadwal: ' . $e->getMessage()
            ], 500);
        }
    }
}
