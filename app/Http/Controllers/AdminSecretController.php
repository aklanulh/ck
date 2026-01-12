<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminSecretController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Dashboard admin rahasia
     */
    public function dashboard()
    {
        $totalUsers = User::count();
        $hiddenUsers = User::where('is_hidden', true)->count();
        $visibleUsers = User::where('is_hidden', false)->count();

        return view('admin.secret.dashboard', compact('totalUsers', 'hiddenUsers', 'visibleUsers'));
    }

    /**
     * Halaman kelola pengguna (hide/unhide)
     */
    public function kelolaPengguna()
    {
        $users = User::orderBy('name')->get();

        return view('admin.secret.kelola-pengguna', compact('users'));
    }

    /**
     * Toggle hide/unhide user
     */
    public function toggleUserVisibility(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        // Prevent hiding super admin
        if ($user->email === 'admin@admin.com') {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menyembunyikan super admin!'
            ], 403);
        }

        $user->is_hidden = !$user->is_hidden;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => $user->is_hidden ? 'Pengguna berhasil disembunyikan' : 'Pengguna berhasil ditampilkan',
            'is_hidden' => $user->is_hidden
        ]);
    }

    /**
     * Halaman custom riwayat absensi cepat
     */
    public function customAbsensi()
    {
        $users = User::where('is_hidden', false)->orderBy('name')->get();
        $absensi = Absensi::with('user')->latest()->take(50)->get();

        return view('admin.secret.custom-absensi', compact('users', 'absensi'));
    }

    /**
     * Create custom absensi record
     */
    public function createCustomAbsensi(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i',
            'keterangan' => 'nullable|string|max:255',
            'status' => 'required|in:hadir,izin,sakit,cuti'
        ]);

        // Check if absensi already exists for this user and date
        $existing = Absensi::where('user_id', $request->user_id)
            ->whereDate('tanggal', $request->tanggal)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Data absensi untuk tanggal ini sudah ada!'
            ], 422);
        }

        $absensi = Absensi::create([
            'user_id' => $request->user_id,
            'tanggal' => $request->tanggal,
            'check_in' => $request->jam_masuk,
            'check_out' => $request->jam_keluar,
            'check_in_location' => 'Kota Wisata, Limusnunggal, Cileungsi, Bogor, Jawa Barat, Jawa, 16829, Indonesia',
            'check_out_location' => 'Kota Wisata, Limusnunggal, Cileungsi, Bogor, Jawa Barat, Jawa, 16829, Indonesia',
            'keterangan' => $request->keterangan,
            'status' => $request->status,
            'created_by_admin' => true,
            'admin_id' => Auth::id(),
            'created_at' => $request->tanggal . ' ' . ($request->jam_masuk ?? '08:00:00'),
            'updated_at' => $request->tanggal . ' ' . ($request->jam_keluar ?? '17:00:00')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data absensi berhasil ditambahkan',
            'data' => $absensi->load('user')
        ]);
    }

    /**
     * Update absensi record
     */
    public function updateAbsensi(Request $request, $id)
    {
        $absensi = Absensi::findOrFail($id);

        $request->validate([
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i',
            'keterangan' => 'nullable|string|max:255',
            'status' => 'required|in:hadir,izin,sakit,cuti'
        ]);

        $absensi->update([
            'check_in' => $request->jam_masuk,
            'check_out' => $request->jam_keluar,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
            'updated_by_admin' => true,
            'admin_id' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data absensi berhasil diperbarui',
            'data' => $absensi->load('user')
        ]);
    }

    /**
     * Delete absensi record
     */
    public function deleteAbsensi($id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data absensi berhasil dihapus'
        ]);
    }

    /**
     * Get absensi data for editing
     */
    public function getAbsensiData($id)
    {
        $absensi = Absensi::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $absensi
        ]);
    }

    /**
     * Bulk create absensi for multiple users
     */
    public function bulkCreateAbsensi(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i',
            'keterangan' => 'nullable|string|max:255',
            'status' => 'required|in:hadir,izin,sakit,cuti'
        ]);

        $created = [];
        $skipped = [];

        foreach ($request->user_ids as $userId) {
            // Check if absensi already exists
            $existing = Absensi::where('user_id', $userId)
                ->whereDate('tanggal', $request->tanggal)
                ->first();

            if ($existing) {
                $skipped[] = User::find($userId)->name;
                continue;
            }

            $absensi = Absensi::create([
                'user_id' => $userId,
                'tanggal' => $request->tanggal,
                'check_in' => $request->jam_masuk,
                'check_out' => $request->jam_keluar,
                'check_in_location' => 'Kota Wisata, Limusnunggal, Cileungsi, Bogor, Jawa Barat, Jawa, 16829, Indonesia',
                'check_out_location' => 'Kota Wisata, Limusnunggal, Cileungsi, Bogor, Jawa Barat, Jawa, 16829, Indonesia',
                'keterangan' => $request->keterangan,
                'status' => $request->status,
                'created_by_admin' => true,
                'admin_id' => Auth::id(),
                'created_at' => $request->tanggal . ' ' . ($request->jam_masuk ?? '08:00:00'),
                'updated_at' => $request->tanggal . ' ' . ($request->jam_keluar ?? '17:00:00')
            ]);

            $created[] = $absensi->load('user');
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil membuat " . count($created) . " data absensi. " .
                (count($skipped) > 0 ? count($skipped) . " data dilewati karena sudah ada." : ""),
            'created' => $created,
            'skipped' => $skipped
        ]);
    }

    /**
     * Generate 1 month attendance with one click
     */
    public function generateMonthlyAttendance(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'year_month' => 'required_without:use_custom_date_range|date_format:Y-m', // Format: 2024-01
            'start_date' => 'required_if:use_custom_date_range,1|date|before_or_equal:end_date',
            'end_date' => 'required_if:use_custom_date_range,1|date|after_or_equal:start_date',
            'exclude_weekends' => 'boolean',
            'force_override' => 'boolean',
            'use_custom_date_range' => 'boolean'
        ]);

        $userId = $request->user_id;
        $excludeWeekends = $request->exclude_weekends ?? true;
        $forceOverride = $request->force_override ?? false;
        $useCustomDateRange = $request->use_custom_date_range ?? false;

        // Get the user
        $user = User::findOrFail($userId);

        // Determine date range
        if ($useCustomDateRange) {
            $startDate = new \DateTime($request->start_date);
            $endDate = new \DateTime($request->end_date);
        } else {
            $yearMonth = $request->year_month;
            // Parse year and month
            [$year, $month] = explode('-', $yearMonth);

            // Get all days in the month
            $startDate = new \DateTime("$year-$month-01");
            $endDate = new \DateTime("$year-$month-" . cal_days_in_month(CAL_GREGORIAN, $month, $year));
        }

        $created = [];
        $updated = [];
        $skipped = [];
        $totalDays = 0;

        foreach (new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate->modify('+1 day')) as $date) {
            $currentDate = $date->format('Y-m-d');

            // Skip weekends if enabled
            if ($excludeWeekends && in_array($date->format('N'), [6, 7])) { // 6=Saturday, 7=Sunday
                continue;
            }

            $totalDays++;

            // Check if absensi already exists
            $existing = Absensi::where('user_id', $userId)
                ->whereDate('tanggal', $currentDate)
                ->first();

            if ($existing) {
                if ($forceOverride) {
                    // Update existing record
                    // Random check-in time between 07:30:00-07:55:59
                    $checkInHour = 7;
                    $checkInMinute = rand(30, 55);
                    $checkInSecond = rand(0, 59);
                    $jamMasuk = sprintf("%02d:%02d:%02d", $checkInHour, $checkInMinute, $checkInSecond);

                    // Random check-out time between 17:00:00-18:00:59
                    $checkOutHour = rand(17, 18);
                    if ($checkOutHour == 17) {
                        $checkOutMinute = rand(0, 59);
                        $checkOutSecond = rand(0, 59);
                    } else {
                        $checkOutMinute = 0;
                        $checkOutSecond = 0;
                    }
                    $jamKeluar = sprintf("%02d:%02d:%02d", $checkOutHour, $checkOutMinute, $checkOutSecond);

                    // Random status (90% hadir, 10% izin)
                    $status = (rand(1, 100) <= 90) ? 'hadir' : 'izin';
                    $keterangan = ''; // Kosongkan keterangan

                    // Disable timestamps temporarily
                    $existing->timestamps = false;

                    $existing->update([
                        'check_in' => $jamMasuk,
                        'check_out' => $jamKeluar,
                        'check_in_location' => 'Kota Wisata, Limusnunggal, Cileungsi, Bogor, Jawa Barat, Jawa, 16829, Indonesia',
                        'check_out_location' => 'Kota Wisata, Limusnunggal, Cileungsi, Bogor, Jawa Barat, Jawa, 16829, Indonesia',
                        'keterangan' => $keterangan,
                        'status' => $status,
                        'updated_by_admin' => true,
                        'admin_id' => Auth::id(),
                        'created_at' => $currentDate . ' ' . $jamKeluar,
                        'updated_at' => $currentDate . ' ' . $jamKeluar
                    ]);

                    // Re-enable timestamps
                    $existing->timestamps = true;

                    $updated[] = $existing;
                } else {
                    $skipped[] = $currentDate;
                    continue;
                }
            } else {
                // Create new record
                // Random check-in time between 07:30:00-07:55:59
                $checkInHour = 7;
                $checkInMinute = rand(30, 55);
                $checkInSecond = rand(0, 59);
                $jamMasuk = sprintf("%02d:%02d:%02d", $checkInHour, $checkInMinute, $checkInSecond);

                // Random check-out time between 17:00:00-18:00:59
                $checkOutHour = rand(17, 18);
                if ($checkOutHour == 17) {
                    $checkOutMinute = rand(0, 59);
                    $checkOutSecond = rand(0, 59);
                } else {
                    $checkOutMinute = 0;
                    $checkOutSecond = 0;
                }
                $jamKeluar = sprintf("%02d:%02d:%02d", $checkOutHour, $checkOutMinute, $checkOutSecond);

                // Random status (90% hadir, 10% izin)
                $status = (rand(1, 100) <= 90) ? 'hadir' : 'izin';
                $keterangan = ''; // Kosongkan keterangan

                $absensiData = [
                    'user_id' => $userId,
                    'tanggal' => $currentDate,
                    'check_in' => $jamMasuk,
                    'check_out' => $jamKeluar,
                    'check_in_location' => 'Kota Wisata, Limusnunggal, Cileungsi, Bogor, Jawa Barat, Jawa, 16829, Indonesia',
                    'check_out_location' => 'Kota Wisata, Limusnunggal, Cileungsi, Bogor, Jawa Barat, Jawa, 16829, Indonesia',
                    'keterangan' => $keterangan,
                    'status' => $status,
                    'created_by_admin' => true,
                    'admin_id' => Auth::id(),
                    'created_at' => $currentDate . ' ' . $jamKeluar,
                    'updated_at' => $currentDate . ' ' . $jamKeluar
                ];

                $absensi = Absensi::insert($absensiData);

                // Get the inserted record for response
                $absensi = Absensi::where('user_id', $userId)
                    ->where('tanggal', $currentDate)
                    ->first();

                $created[] = $absensi;
            }
        }

        $action = $forceOverride ? 'update/generate' : 'generate';

        // Format date range for message
        if ($useCustomDateRange) {
            $dateRange = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
            $periodText = "periode $dateRange";
        } else {
            $periodText = "bulan $yearMonth";
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil $action " . (count($created) + count($updated)) . " data absensi untuk " .
                $user->name . " $periodText. " .
                (count($skipped) > 0 ? count($skipped) . " data dilewati karena sudah ada." : ""),
            'summary' => [
                'user' => $user->name,
                'period' => $useCustomDateRange ? $dateRange : $yearMonth,
                'use_custom_date_range' => $useCustomDateRange,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'total_working_days' => $totalDays,
                'created' => count($created),
                'updated' => count($updated),
                'skipped' => count($skipped),
                'force_override' => $forceOverride,
                'exclude_weekends' => $excludeWeekends
            ],
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped
        ]);
    }
}
