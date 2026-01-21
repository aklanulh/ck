<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Report;
use App\Models\VisitSchedule;
use App\Models\Izin;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $totalUsers = User::where('is_hidden', false)->count();
        $totalAbsensi = Absensi::count();
        $totalReports = Report::count();
        $totalVisitSchedules = VisitSchedule::count();

        // Get recent activities
        $recentAbsensi = Absensi::with('user')
            ->whereHas('user', function ($query) {
                $query->where('is_hidden', false);
            })
            ->latest()->take(5)->get();
        $recentReports = Report::with('user')
            ->whereHas('user', function ($query) {
                $query->where('is_hidden', false);
            })
            ->latest()->take(5)->get();

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

        $users = User::where('is_hidden', false)->latest()->paginate(10);
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
    public function absensi(Request $request)
    {
        if (session('user')['role'] !== 'admin') {
            return redirect('/absensi')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        // Get the date from request or default to today
        $date = $request->get('date', now()->format('Y-m-d'));
        $dateObj = \Carbon\Carbon::createFromFormat('Y-m-d', $date);

        // Get all users for export filter
        $users = User::where('is_hidden', false)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        // Get attendance for the specific date
        $absensi = Absensi::with('user')
            ->whereHas('user', function ($query) {
                $query->where('is_hidden', false);
            })
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        // Add time calculations for each attendance record
        foreach ($absensi as $absen) {
            $absen->check_in_diff = $this->calculateTimeDifference($absen->check_in, '08:00:00');
            $absen->check_out_diff = $this->calculateTimeDifference($absen->check_out, '17:00:00');

            // Calculate total working hours
            if ($absen->check_in && $absen->check_out) {
                $absen->total_jam = $absen->calculateTotalHours();
            }
        }

        // Get izin data for the page
        $izin = Izin::with('user')->latest()->paginate(10);

        // Calculate previous and next dates
        $previousDate = $dateObj->copy()->subDay()->format('Y-m-d');
        $nextDate = $dateObj->copy()->addDay()->format('Y-m-d');

        return view('admin.absensi', compact('absensi', 'izin', 'date', 'previousDate', 'nextDate', 'dateObj', 'users'));
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
            // Handle both H:i:s and H:i formats
            if (strlen($actualTime) === 5) {
                // Format is H:i, add :00 to make it H:i:s
                $actualTime .= ':00';
            }
            if (strlen($standardTime) === 5) {
                // Format is H:i, add :00 to make it H:i:s
                $standardTime .= ':00';
            }

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
    public function reports(Request $request)
    {
        if (session('user')['role'] !== 'admin') {
            return redirect('/absensi')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        // Get the date from request or default to today
        $date = $request->get('date', now()->format('Y-m-d'));
        $dateObj = \Carbon\Carbon::createFromFormat('Y-m-d', $date);

        // Get all users for export filter
        $users = User::where('is_hidden', false)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        // Get reports for the specific date
        $reports = Report::with('user')
            ->whereHas('user', function ($query) {
                $query->where('is_hidden', false);
            })
            ->where('status', '!=', 'draft')
            ->whereDate('tanggal', $date)
            ->orderBy('tanggal', 'desc')
            ->get();

        // Calculate previous and next dates
        $previousDate = $dateObj->copy()->subDay()->format('Y-m-d');
        $nextDate = $dateObj->copy()->addDay()->format('Y-m-d');

        return view('admin.reports', compact('reports', 'date', 'previousDate', 'nextDate', 'dateObj', 'users'));
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
     * Delete report with admin password verification.
     */
    public function deleteReport(Request $request, $id)
    {
        if (session('user')['role'] !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'delete_password' => 'required|string'
        ]);

        try {
            // Verify admin password
            $admin = User::find(session('user')['id']);
            if (!Hash::check($request->delete_password, $admin->password)) {
                return response()->json(['error' => 'Password admin salah!'], 401);
            }

            // Find and delete report
            $report = Report::findOrFail($id);
            $reportTitle = $report->title;
            $userName = $report->user->name;

            // Delete associated photos
            if ($report->photo_evidence) {
                $this->deletePhotos($report->photo_evidence);
            }

            $report->delete();

            return response()->json([
                'success' => true,
                'message' => "Laporan '{$reportTitle}' oleh {$userName} berhasil dihapus!"
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus laporan: ' . $e->getMessage()], 500);
        }
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
     * Generate one-time reset link for user
     */
    public function generateResetLink(Request $request, $id)
    {
        if (session('user')['role'] !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $user = User::findOrFail($id);

            // Generate unique reset token
            $token = Str::random(60);
            $user->reset_token = $token;
            $user->reset_token_expires = now()->addHours(24); // Valid for 24 hours
            $user->save();

            $resetLink = url("/reset-password/{$token}");

            return response()->json([
                'success' => true,
                'message' => "Link reset password untuk {$user->name} ({$user->email}) telah dibuat",
                'reset_link' => $resetLink,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'expires_at' => $user->reset_token_expires->format('d M Y H:i'),
                'whatsapp_message' => "Halo {$user->name},\n\nLink reset password Anda: {$resetLink}\n\nLink berlaku sampai: " . $user->reset_token_expires->format('d M Y H:i') . "\n\nJangan bagikan link ini ke siapapun."
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal generate link: ' . $e->getMessage()], 500);
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

    /**
     * Get izin data for admin management.
     */
    public function getIzinList(Request $request)
    {
        if (session('user')['role'] !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $izinQuery = Izin::with(['user', 'approvedBy', 'rejectedBy'])->latest();

            // Apply filters
            if ($request->has('status') && $request->status) {
                $izinQuery->where('status', $request->status);
            }

            if ($request->has('jenis') && $request->jenis) {
                $izinQuery->where('jenis_izin', $request->jenis);
            }

            $izinData = $izinQuery->get()->map(function ($izin) {
                return [
                    'id' => $izin->id,
                    'user_id' => $izin->user_id,
                    'user_name' => $izin->user->name,
                    'user_email' => $izin->user->email,
                    'jenis_izin' => $izin->jenis_izin,
                    'tanggal_mulai' => $izin->tanggal_mulai->format('Y-m-d'),
                    'tanggal_selesai' => $izin->tanggal_selesai->format('Y-m-d'),
                    'alasan' => $izin->alasan,
                    'bukti_path' => $izin->bukti_path,
                    'status' => $izin->status,
                    'catatan_admin' => $izin->catatan_admin,
                    'approved_at' => $izin->approved_at?->format('Y-m-d H:i:s'),
                    'rejected_at' => $izin->rejected_at?->format('Y-m-d H:i:s'),
                    'approved_by_name' => $izin->approvedBy?->name,
                    'rejected_by_name' => $izin->rejectedBy?->name,
                    'created_at' => $izin->created_at->format('Y-m-d H:i:s'),
                ];
            });

            // Get statistics
            $stats = [
                'pending' => Izin::where('status', 'pending')->count(),
                'approved' => Izin::where('status', 'approved')->count(),
                'rejected' => Izin::where('status', 'rejected')->count(),
                'total' => Izin::count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $izinData,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load izin data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get izin data for admin management.
     */
    public function getIzinData(Request $request)
    {
        if (session('user')['role'] !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $izinQuery = Izin::with(['user', 'approvedBy', 'rejectedBy'])->latest();

            // Apply filters
            if ($request->has('status') && $request->status) {
                $izinQuery->where('status', $request->status);
            }

            if ($request->has('jenis_izin') && $request->jenis_izin) {
                $izinQuery->where('jenis_izin', $request->jenis_izin);
            }

            $izinData = $izinQuery->get()->map(function ($izin) {
                return [
                    'id' => $izin->id,
                    'user_id' => $izin->user_id,
                    'user_name' => $izin->user->name,
                    'user_email' => $izin->user->email,
                    'jenis_izin' => $izin->jenis_izin,
                    'tanggal_mulai' => $izin->tanggal_mulai->format('Y-m-d'),
                    'tanggal_selesai' => $izin->tanggal_selesai->format('Y-m-d'),
                    'alasan' => $izin->alasan,
                    'bukti_path' => $izin->bukti_path,
                    'status' => $izin->status,
                    'catatan_admin' => $izin->catatan_admin,
                    'approved_at' => $izin->approved_at?->format('Y-m-d H:i:s'),
                    'rejected_at' => $izin->rejected_at?->format('Y-m-d H:i:s'),
                    'approved_by_name' => $izin->approvedBy?->name,
                    'rejected_by_name' => $izin->rejectedBy?->name,
                    'created_at' => $izin->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $izinData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengambil data izin: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve izin request.
     */
    public function approveIzin(Request $request)
    {
        if (session('user')['role'] !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $izinId = $request->input('izin_id');
            $izin = Izin::findOrFail($izinId);
            $adminId = session('user')['id'];

            if ($izin->approve($adminId, $request->catatan_admin)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengajuan izin berhasil disetujui'
                ]);
            }

            return response()->json(['error' => 'Gagal menyetujui izin'], 500);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menyetujui izin: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject izin request.
     */
    public function rejectIzin(Request $request)
    {
        if (session('user')['role'] !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $izinId = $request->input('izin_id');
            $izin = Izin::findOrFail($izinId);
            $adminId = session('user')['id'];

            if ($izin->reject($adminId, $request->catatan_admin)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengajuan izin berhasil ditolak'
                ]);
            }

            return response()->json(['error' => 'Gagal menolak izin'], 500);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menolak izin: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get attendance data for weekly calendar view.
     */
    public function getAttendanceWeekly(Request $request)
    {
        if (session('user')['role'] !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');

            if (!$startDate || !$endDate) {
                return response()->json([
                    'success' => false,
                    'error' => 'Start date and end date parameters are required'
                ], 400);
            }

            // Get all users (including admin)
            $users = \App\Models\User::all();

            // Get attendance data for the week
            $attendances = Absensi::with('user')
                ->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)
                ->get();

            // Group attendance by user and date
            $attendanceData = [];
            foreach ($attendances as $attendance) {
                $date = $attendance->created_at->format('Y-m-d');
                $userId = $attendance->user_id;

                if (!isset($attendanceData[$userId])) {
                    $attendanceData[$userId] = [];
                }

                $attendanceData[$userId][$date] = [
                    'id' => $attendance->id,
                    'user_id' => $attendance->user_id,
                    'status' => $attendance->status,
                    'check_in' => $attendance->check_in,
                    'check_out' => $attendance->check_out,
                    'check_in_location' => $attendance->check_in_location,
                    'check_out_location' => $attendance->check_out_location,
                    'keterangan' => $attendance->keterangan,
                    'created_at' => $attendance->created_at->format('Y-m-d H:i:s'),
                ];
            }

            // Prepare users data
            $usersData = $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'users' => $usersData,
                    'attendance' => $attendanceData
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load weekly data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get attendance details for specific date.
     */
    public function getAttendanceDetail(Request $request)
    {
        if (session('user')['role'] !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $date = $request->get('date');

            if (!$date) {
                return response()->json([
                    'success' => false,
                    'error' => 'Date parameter is required'
                ], 400);
            }

            $attendances = Absensi::with('user')
                ->whereDate('created_at', $date)
                ->get();

            $attendanceData = $attendances->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'user_id' => $attendance->user_id,
                    'user_name' => $attendance->user->name,
                    'user_email' => $attendance->user->email,
                    'status' => $attendance->status,
                    'check_in' => $attendance->check_in,
                    'check_out' => $attendance->check_out,
                    'check_in_location' => $attendance->check_in_location,
                    'check_out_location' => $attendance->check_out_location,
                    'keterangan' => $attendance->keterangan,
                    'created_at' => $attendance->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $attendanceData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load attendance details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get izin detail by ID.
     */
    public function getIzinDetail($id)
    {
        if (session('user')['role'] !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $izin = Izin::with(['user', 'approvedBy', 'rejectedBy'])->findOrFail($id);

            $izinData = [
                'id' => $izin->id,
                'user_id' => $izin->user_id,
                'user_name' => $izin->user->name,
                'user_email' => $izin->user->email,
                'jenis_izin' => $izin->jenis_izin,
                'tanggal_mulai' => $izin->tanggal_mulai->format('Y-m-d'),
                'tanggal_selesai' => $izin->tanggal_selesai->format('Y-m-d'),
                'alasan' => $izin->alasan,
                'bukti_path' => $izin->bukti_path,
                'status' => $izin->status,
                'catatan_admin' => $izin->catatan_admin,
                'approved_at' => $izin->approved_at?->format('Y-m-d H:i:s'),
                'rejected_at' => $izin->rejected_at?->format('Y-m-d H:i:s'),
                'approved_by_name' => $izin->approvedBy?->name,
                'rejected_by_name' => $izin->rejectedBy?->name,
                'created_at' => $izin->created_at->format('Y-m-d H:i:s'),
            ];

            return response()->json([
                'success' => true,
                'izin' => $izinData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load izin detail: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all users for attendance chart navigation.
     */
    public function getAllUsers(Request $request)
    {
        if (session('user')['role'] !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $users = User::where('is_hidden', false)
                ->orderBy('name')
                ->get(['id', 'name', 'email']);

            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get monthly attendance chart data for specific user.
     */
    public function getUserAttendanceChart(Request $request)
    {
        if (session('user')['role'] !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $userId = $request->get('user_id');
            $month = $request->get('month', now()->format('Y-m'));

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'error' => 'User ID is required'
                ], 400);
            }

            // Parse month to get start and end dates
            $startDate = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $endDate = \Carbon\Carbon::createFromFormat('Y-m', $month)->endOfMonth();

            // Get attendance data for the month
            $attendances = Absensi::where('user_id', $userId)
                ->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)
                ->orderBy('created_at')
                ->get();

            // Debug: log the query results
            \Log::info("Attendance query for user {$userId} in {$month}: Found {$attendances->count()} records");

            // Get user info
            $user = User::find($userId);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'User not found'
                ], 404);
            }

            // Prepare chart data
            $chartData = [];
            $checkInTimes = [];
            $checkOutTimes = [];

            foreach ($attendances as $attendance) {
                $date = $attendance->created_at->format('Y-m-d');
                $day = $attendance->created_at->format('d');

                $chartData[] = [
                    'date' => $date,
                    'day' => $day,
                    'check_in' => $attendance->check_in,
                    'check_out' => $attendance->check_out,
                    'status' => $attendance->status,
                    'total_hours' => $attendance->total_jam
                ];

                // Extract times for chart (convert to decimal hours)
                if ($attendance->check_in) {
                    $checkInTimeStr = $attendance->check_in;
                    if (strlen($checkInTimeStr) === 5) {
                        $checkInTimeStr .= ':00';
                    }
                    $checkInTime = \Carbon\Carbon::createFromFormat('H:i:s', $checkInTimeStr);
                    $checkInTimes[] = [
                        'x' => $day,
                        'y' => $checkInTime->hour + ($checkInTime->minute / 60)
                    ];
                }

                if ($attendance->check_out) {
                    $checkOutTimeStr = $attendance->check_out;
                    if (strlen($checkOutTimeStr) === 5) {
                        $checkOutTimeStr .= ':00';
                    }
                    $checkOutTime = \Carbon\Carbon::createFromFormat('H:i:s', $checkOutTimeStr);
                    $checkOutTimes[] = [
                        'x' => $day,
                        'y' => $checkOutTime->hour + ($checkOutTime->minute / 60)
                    ];
                }
            }

            // Calculate statistics
            $totalDays = $attendances->count();
            $hadirCount = $attendances->where('status', 'hadir')->count();
            $terlambatCount = $attendances->where('status', 'terlambat')->count();
            $izinCount = $attendances->where('status', 'izin')->count();
            $alfaCount = $attendances->where('status', 'alfa')->count();

            $stats = [
                'total_days' => $totalDays,
                'hadir' => $hadirCount,
                'terlambat' => $terlambatCount,
                'izin' => $izinCount,
                'alfa' => $alfaCount,
                'attendance_rate' => $totalDays > 0 ? round(($hadirCount + $terlambatCount) / $totalDays * 100, 1) : 0
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email
                    ],
                    'month' => $month,
                    'month_name' => $startDate->format('F Y'),
                    'chart_data' => $chartData,
                    'check_in_times' => $checkInTimes,
                    'check_out_times' => $checkOutTimes,
                    'stats' => $stats
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load attendance chart data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export attendance data to Excel (CSV format)
     */
    public function exportAbsensiExcel(Request $request)
    {
        if (session('user')['role'] !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get delimiter from request (default to semicolon)
        $delimiter = $request->input('delimiter', ';');

        // Start building the query
        $query = Absensi::with('user')
            ->whereHas('user', function ($query) {
                $query->where('is_hidden', false);
            });

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Get the data
        $attendances = $query->orderBy('created_at', 'desc')->get();

        // Generate dynamic filename
        if ($request->filled('user_id')) {
            $user = User::find($request->user_id);
            $userName = $user ? str_replace(' ', '_', $user->name) : 'Unknown_User';
            $filename = 'laporan_absensi_' . $userName . '_' . date('Y-m-d_H-i-s') . '.csv';
        } else {
            $filename = 'laporan_absensi_semua_user_' . date('Y-m-d_H-i-s') . '.csv';
        }

        // Create CSV content with professional report format
        $csvContent = "\xEF\xBB\xBF"; // UTF-8 BOM

        // Add report header
        $reportTitle = "LAPORAN ABSENSI KARYAWAN";
        if ($request->filled('user_id')) {
            $user = User::find($request->user_id);
            if ($user) {
                $reportTitle = "LAPORAN ABSENSI - " . strtoupper($user->name);
            }
        }
        $periodText = "";

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $periodText = "Periode: " . date('d/m/Y', strtotime($request->start_date)) . " - " . date('d/m/Y', strtotime($request->end_date));
        } elseif ($request->filled('start_date')) {
            $periodText = "Tanggal: " . date('d/m/Y', strtotime($request->start_date));
        } else {
            $periodText = "Tanggal: " . date('d/m/Y');
        }

        $generatedAt = "Dicetak pada: " . date('d/m/Y H:i:s');

        // Add title and period information
        $csvContent .= $reportTitle . "\n";
        $csvContent .= $periodText . "\n";
        $csvContent .= $generatedAt . "\n";
        $csvContent .= "\n"; // Empty line before headers

        // Add headers with specified delimiter
        $headers = ['ID', 'Nama User', 'Email User', 'Tanggal', 'Check In', 'Check Out', 'Lokasi Check In', 'Lokasi Check Out', 'Total Jam Kerja', 'Status', 'Catatan', 'Dibuat Pada'];
        $csvContent .= implode($delimiter, $headers) . "\n";

        // Add data rows
        foreach ($attendances as $attendance) {
            $row = [
                $attendance->id,
                $attendance->user->name,
                $attendance->user->email,
                $attendance->created_at->format('d/m/Y'),
                $attendance->check_in ?? '',
                $attendance->check_out ?? '',
                $attendance->check_in_location ?? '',
                $attendance->check_out_location ?? '',
                $attendance->total_jam ?? '',
                ucfirst($attendance->status),
                $attendance->keterangan ?? '',
                $attendance->created_at->format('d/m/Y H:i:s')
            ];

            // Escape delimiter and quotes in fields
            $escapedRow = array_map(function ($field) use ($delimiter) {
                // Only escape quotes, let delimiter remain since field is properly quoted
                return '"' . str_replace('"', '""', $field) . '"';
            }, $row);

            $csvContent .= implode($delimiter, $escapedRow) . "\n";
        }

        // Add summary section
        $csvContent .= "\n"; // Empty line before summary
        $csvContent .= "RINGKASAN LAPORAN\n";
        $csvContent .= "Total Data" . $delimiter . count($attendances) . "\n";

        // Count by status
        $statusCounts = $attendances->groupBy('status')->map->count();
        foreach ($statusCounts as $status => $count) {
            $csvContent .= "Total " . ucfirst($status) . $delimiter . $count . "\n";
        }

        $csvContent .= "\n"; // Empty line
        $csvContent .= "Laporan ini dibuat secara otomatis dari sistem CatatanKerja\n";
        $csvContent .= "Hak Cipta © " . date('Y') . " - PT. Example\n";

        // Return CSV download
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Export reports data to Excel (CSV format)
     */
    public function exportReportsExcel(Request $request)
    {
        if (session('user')['role'] !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get delimiter from request (default to semicolon)
        $delimiter = $request->input('delimiter', ';');

        // Start building the query
        $query = Report::with('user')
            ->whereHas('user', function ($query) {
                $query->where('is_hidden', false);
            })
            ->where('status', '!=', 'draft');

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Get the data
        $reports = $query->orderBy('tanggal', 'desc')->get();

        // Generate dynamic filename
        if ($request->filled('user_id')) {
            $user = User::find($request->user_id);
            $userName = $user ? str_replace(' ', '_', $user->name) : 'Unknown_User';
            $delimiterText = $delimiter === ',' ? 'comma' : 'semicolon';
            $filename = 'laporan_kerja_' . $userName . '_' . $delimiterText . '_' . date('Y-m-d_H-i-s') . '.csv';
        } else {
            $delimiterText = $delimiter === ',' ? 'comma' : 'semicolon';
            $filename = 'laporan_kerja_semua_user_' . $delimiterText . '_' . date('Y-m-d_H-i-s') . '.csv';
        }

        // Create CSV content with professional report format
        $csvContent = "\xEF\xBB\xBF"; // UTF-8 BOM

        // Add report header
        $reportTitle = "LAPORAN KERJA KARYAWAN";
        if ($request->filled('user_id')) {
            $user = User::find($request->user_id);
            if ($user) {
                $reportTitle = "LAPORAN KERJA - " . strtoupper($user->name);
            }
        }
        $periodText = "";

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $periodText = "Periode: " . date('d/m/Y', strtotime($request->start_date)) . " - " . date('d/m/Y', strtotime($request->end_date));
        } elseif ($request->filled('start_date')) {
            $periodText = "Tanggal: " . date('d/m/Y', strtotime($request->start_date));
        } else {
            $periodText = "Tanggal: " . date('d/m/Y');
        }

        $generatedAt = "Dicetak pada: " . date('d/m/Y H:i:s');

        // Add title and period information
        $csvContent .= $reportTitle . "\n";
        $csvContent .= $periodText . "\n";
        $csvContent .= $generatedAt . "\n";
        $csvContent .= "\n"; // Empty line before headers

        // Add headers with specified delimiter
        $headers = ['ID', 'Nama User', 'Email User', 'Tanggal', 'Lokasi', 'Isi Laporan', 'Masalah yang Dihadapi', 'Solusi yang Dilakukan', 'Status', 'Dibuat Pada'];
        $csvContent .= implode($delimiter, $headers) . "\n";

        // Add data rows
        foreach ($reports as $report) {
            $row = [
                $report->id,
                $report->user->name,
                $report->user->email,
                $report->tanggal ? $report->tanggal->format('d/m/Y') : '',
                $report->lokasi ?? '',
                $report->laporan,
                $report->masalah ?? '',
                $report->solusi ?? '',
                ucfirst($report->status),
                $report->created_at->format('d/m/Y H:i:s')
            ];

            // Escape delimiter and quotes in fields
            $escapedRow = array_map(function ($field) use ($delimiter) {
                // Only escape quotes, let delimiter remain since field is properly quoted
                return '"' . str_replace('"', '""', $field) . '"';
            }, $row);

            $csvContent .= implode($delimiter, $escapedRow) . "\n";
        }

        // Add summary section
        $csvContent .= "\n"; // Empty line before summary
        $csvContent .= "RINGKASAN LAPORAN\n";
        $csvContent .= "Total Data" . $delimiter . count($reports) . "\n";

        // Count by status
        $statusCounts = $reports->groupBy('status')->map->count();
        foreach ($statusCounts as $status => $count) {
            $csvContent .= "Total " . ucfirst($status) . $delimiter . $count . "\n";
        }

        $csvContent .= "\n"; // Empty line
        $csvContent .= "Laporan ini dibuat secara otomatis dari sistem CatatanKerja\n";
        $csvContent .= "Hak Cipta © " . date('Y') . " - PT. Example\n";

        // Return CSV download
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
