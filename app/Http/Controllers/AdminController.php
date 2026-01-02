<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Report;
use App\Models\VisitSchedule;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Show admin dashboard.
     */
    public function dashboard()
    {
        // Check if user is admin
        if (session('user')['role'] !== 'admin') {
            return redirect('/absensi')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        // Get statistics
        $totalUsers = User::count();
        $totalAbsensi = Absensi::count();
        $totalReports = Report::count();
        $totalVisitSchedules = VisitSchedule::count();

        // Get recent activities
        $recentAbsensi = Absensi::with('user')->latest()->take(5)->get();
        $recentReports = Report::with('user')->latest()->take(5)->get();

        // Get attendance statistics for today
        $todayAbsensiQuery = Absensi::query();
        $hadirToday = $todayAbsensiQuery->whereDate('created_at', today())->where('status', 'hadir')->count();
        $terlambatToday = $todayAbsensiQuery->whereDate('created_at', today())->where('status', 'terlambat')->count();
        $izinToday = $todayAbsensiQuery->whereDate('created_at', today())->where('status', 'izin')->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAbsensi',
            'totalReports',
            'totalVisitSchedules',
            'recentAbsensi',
            'recentReports',
            'hadirToday',
            'terlambatToday',
            'izinToday'
        ));
    }

    /**
     * Show all users.
     */
    public function users()
    {
        if (session('user')['role'] !== 'admin') {
            return redirect('/absensi')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        $users = User::latest()->paginate(10);
        return view('admin.users', compact('users'));
    }

    /**
     * Show user creation form.
     */
    public function createUser()
    {
        if (session('user')['role'] !== 'admin') {
            return redirect('/absensi')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        return view('admin.create-user');
    }

    /**
     * Store new user.
     */
    public function storeUser(Request $request)
    {
        if (session('user')['role'] !== 'admin') {
            return redirect('/absensi')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,user'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role
        ]);

        return redirect('/admin/users')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Show user edit form.
     */
    public function editUser($id)
    {
        if (session('user')['role'] !== 'admin') {
            return redirect('/absensi')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    /**
     * Update user.
     */
    public function updateUser(Request $request, $id)
    {
        if (session('user')['role'] !== 'admin') {
            return redirect('/absensi')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,user'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:6|confirmed'
            ]);
            $user->update(['password' => bcrypt($request->password)]);
        }

        return redirect('/admin/users')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Delete user.
     */
    public function deleteUser($id)
    {
        if (session('user')['role'] !== 'admin') {
            return redirect('/absensi')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        $user = User::findOrFail($id);

        // Prevent deletion of current admin
        if ($user->id === session('user')['id']) {
            return redirect('/admin/users')->with('error', 'Tidak dapat menghapus akun yang sedang digunakan.');
        }

        $user->delete();
        return redirect('/admin/users')->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Show all attendance records.
     */
    public function absensi()
    {
        if (session('user')['role'] !== 'admin') {
            return redirect('/absensi')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        $absensi = Absensi::with('user')->latest()->paginate(20);

        // Add time calculations for each attendance record
        foreach ($absensi as $absen) {
            $absen->check_in_diff = $this->calculateTimeDifference($absen->check_in, '08:00:00');
            $absen->check_out_diff = $this->calculateTimeDifference($absen->check_out, '17:00:00');

            // Calculate total working hours
            if ($absen->check_in && $absen->check_out) {
                $absen->total_jam = $absen->calculateTotalHours();
            }
        }

        return view('admin.absensi', compact('absensi'));
    }

    /**
     * Delete attendance record with admin password verification
     */
    public function deleteAbsensi(Request $request, $id)
    {
        if (session('user')['role'] !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'password' => 'required|string'
        ]);

        try {
            // Verify admin password
            $admin = User::find(session('user')['id']);
            if (!Hash::check($request->password, $admin->password)) {
                return response()->json(['error' => 'Password admin salah!'], 401);
            }

            // Find and delete the attendance record
            $absensi = Absensi::findOrFail($id);
            $userId = $absensi->user_id;
            $userName = $absensi->user->name;
            $tanggal = $absensi->created_at->format('d M Y');

            $absensi->delete();

            // Clear all related cache for this user using AuthController method
            AuthController::clearAttendanceCache($userId);

            return response()->json([
                'success' => true,
                'message' => "Data absensi {$userName} tanggal {$tanggal} berhasil dihapus!"
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Calculate time difference from standard time
     */
    private function calculateTimeDifference($actualTime, $standardTime)
    {
        if (!$actualTime) return null;

        try {
            $actual = \Carbon\Carbon::createFromFormat('H:i:s', $actualTime);
            $standard = \Carbon\Carbon::createFromFormat('H:i:s', $standardTime);

            // Calculate difference in minutes
            $actualMinutes = $actual->hour * 60 + $actual->minute;
            $standardMinutes = $standard->hour * 60 + $standard->minute;

            $diffMinutes = $actualMinutes - $standardMinutes;

            if ($diffMinutes > 0) {
                // Late (plus)
                $hours = floor($diffMinutes / 60);
                $minutes = $diffMinutes % 60;
                return '+ ' . ($hours > 0 ? $hours . 'j ' : '') . $minutes . 'm';
            } elseif ($diffMinutes < 0) {
                // Early (minus)
                $diffMinutes = abs($diffMinutes);
                $hours = floor($diffMinutes / 60);
                $minutes = $diffMinutes % 60;
                return '- ' . ($hours > 0 ? $hours . 'j ' : '') . $minutes . 'm';
            } else {
                // On time
                return 'Tepat waktu';
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Show all reports.
     */
    public function reports()
    {
        if (session('user')['role'] !== 'admin') {
            return redirect('/absensi')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        $reports = Report::with('user')->where('status', '!=', 'draft')->latest()->paginate(20);
        return view('admin.reports', compact('reports'));
    }

    /**
     * Show report detail.
     */
    public function showReport($id)
    {
        if (session('user')['role'] !== 'admin') {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }

        $report = Report::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'report' => [
                'id' => $report->id,
                'title' => $report->title,
                'tanggal' => $report->tanggal ? $report->tanggal->format('d F Y') : 'N/A',
                'lokasi' => $report->lokasi,
                'laporan' => $report->laporan,
                'masalah' => $report->masalah,
                'solusi' => $report->solusi,
                'photo_evidence' => $report->photo_evidence_with_urls, // Use corrected URLs
                'status' => $report->status,
                'created_at' => $report->created_at->format('d F Y H:i'),
                'user' => [
                    'name' => $report->user->name,
                    'email' => $report->user->email
                ]
            ]
        ]);
    }

    /**
     * Delete report.
     */
    public function deleteReport($id)
    {
        if (session('user')['role'] !== 'admin') {
            return redirect('/absensi')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        $report = Report::findOrFail($id);

        // Delete associated photos
        if ($report->photo_evidence) {
            $this->deletePhotos($report->photo_evidence);
        }

        $report->delete();

        return redirect('/admin/reports')->with('success', 'Laporan berhasil dihapus.');
    }

    /**
     * Show visit schedules.
     */
    public function visitSchedules()
    {
        if (session('user')['role'] !== 'admin') {
            return redirect('/absensi')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        $schedules = VisitSchedule::with('user')->latest()->paginate(20);
        return view('admin.visit-schedules', compact('schedules'));
    }

    /**
     * Get visit schedules for calendar.
     */
    public function getVisitSchedulesCalendar()
    {
        if (session('user')['role'] !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $schedules = VisitSchedule::with('user')->get();

        $events = [];
        foreach ($schedules as $schedule) {
            if ($schedule->tanggal_kunjungan) {
                $events[] = [
                    'id' => $schedule->id,
                    'title' => $schedule->judul_kunjungan ?? 'Jadwal Kunjungan',
                    'start' => $schedule->tanggal_kunjungan->format('Y-m-d'),
                    'description' => $schedule->deskripsi_kunjungan ?? '',
                    'location' => $schedule->lokasi_kunjungan ?? '',
                    'status' => $schedule->status ?? 'scheduled',
                    'user' => $schedule->user->name,
                    'user_email' => $schedule->user->email,
                    'backgroundColor' => $schedule->status === 'completed' ? '#10b981' : '#f59e0b',
                    'borderColor' => $schedule->status === 'completed' ? '#059669' : '#d97706',
                ];
            }
        }

        return response()->json($events);
    }

    /**
     * Show system settings.
     */
    public function settings()
    {
        if (session('user')['role'] !== 'admin') {
            return redirect('/absensi')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        return view('admin.settings');
    }

    /**
     * Clear application cache.
     */
    public function clearCache()
    {
        if (session('user')['role'] !== 'admin') {
            return redirect('/absensi')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            return redirect('/admin/settings')->with('success', 'Cache berhasil dibersihkan!');
        } catch (\Exception $e) {
            return redirect('/admin/settings')->with('error', 'Gagal membersihkan cache: ' . $e->getMessage());
        }
    }

    /**
     * Delete photos from storage.
     */
    private function deletePhotos($photos)
    {
        if (is_array($photos)) {
            foreach ($photos as $photo) {
                if (isset($photo['path'])) {
                    Storage::disk('public')->delete($photo['path']);
                }
            }
        }
    }
}
