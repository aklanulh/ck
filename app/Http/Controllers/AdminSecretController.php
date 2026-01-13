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
    public function customAbsensi(Request $request)
    {
        $users = User::where('is_hidden', false)->orderBy('name')->get();

        // Build query with filters
        $query = Absensi::with('user');

        // Filter by user
        if ($request->filled('filter_user')) {
            $query->where('user_id', $request->filter_user);
        }

        // Filter by status
        if ($request->filled('filter_status')) {
            $query->where('status', $request->filter_status);
        }

        // Filter by date range
        if ($request->filled('filter_start_date')) {
            $query->whereDate('tanggal', '>=', $request->filter_start_date);
        }

        if ($request->filled('filter_end_date')) {
            $query->whereDate('tanggal', '<=', $request->filter_end_date);
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'tanggal_desc');
        switch ($sortBy) {
            case 'tanggal_asc':
                $query->orderBy('tanggal', 'asc');
                break;
            case 'name_asc':
                $query->join('users', 'absensi.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'asc')
                    ->select('absensi.*');
                break;
            case 'name_desc':
                $query->join('users', 'absensi.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'desc')
                    ->select('absensi.*');
                break;
            case 'status_asc':
                $query->orderBy('status', 'asc');
                break;
            case 'check_in_asc':
                $query->orderBy('check_in', 'asc');
                break;
            case 'check_in_desc':
                $query->orderBy('check_in', 'desc');
                break;
            case 'tanggal_desc':
            default:
                $query->orderBy('tanggal', 'desc');
                break;
        }

        // Apply limit
        $limit = $request->get('limit', '50');
        if ($limit !== 'all') {
            $query->limit((int)$limit);
        }

        $absensi = $query->get();

        return view('admin.secret.custom-absensi', compact('users', 'absensi'));
    }

    /**
     * Filter absensi data for AJAX requests
     */
    public function filterAbsensi(Request $request)
    {
        // Build query with filters
        $query = Absensi::with('user');

        // Filter by user
        if ($request->filled('filter_user')) {
            $query->where('user_id', $request->filter_user);
        }

        // Filter by status
        if ($request->filled('filter_status')) {
            $query->where('status', $request->filter_status);
        }

        // Filter by date range
        if ($request->filled('filter_start_date')) {
            $query->whereDate('tanggal', '>=', $request->filter_start_date);
        }

        if ($request->filled('filter_end_date')) {
            $query->whereDate('tanggal', '<=', $request->filter_end_date);
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'tanggal_desc');
        switch ($sortBy) {
            case 'tanggal_asc':
                $query->orderBy('tanggal', 'asc');
                break;
            case 'name_asc':
                $query->join('users', 'absensi.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'asc')
                    ->select('absensi.*');
                break;
            case 'name_desc':
                $query->join('users', 'absensi.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'desc')
                    ->select('absensi.*');
                break;
            case 'status_asc':
                $query->orderBy('status', 'asc');
                break;
            case 'check_in_asc':
                $query->orderBy('check_in', 'asc');
                break;
            case 'check_in_desc':
                $query->orderBy('check_in', 'desc');
                break;
            case 'tanggal_desc':
            default:
                $query->orderBy('tanggal', 'desc');
                break;
        }

        // Apply limit
        $limit = $request->get('limit', '50');
        if ($limit !== 'all') {
            $query->limit((int)$limit);
        }

        $absensi = $query->get();

        return response()->json([
            'success' => true,
            'data' => $absensi,
            'count' => $absensi->count()
        ]);
    }

    /**
     * Bulk delete absensi records
     */
    public function bulkDeleteAbsensi(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:absensi,id'
        ]);

        $ids = $request->ids;
        $deletedCount = Absensi::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'deleted' => $deletedCount,
            'message' => "{$deletedCount} data absensi berhasil dihapus"
        ]);
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
        $rules = [
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'required|exists:users,id',
            'year_month' => 'required_without:use_custom_date_range|date_format:Y-m', // Format: 2024-01
            'exclude_weekends' => 'boolean',
            'force_override' => 'boolean',
            'use_custom_date_range' => 'boolean',
            'include_izin' => 'boolean',
            'izin_percentage' => 'required_if:include_izin,true|integer|min:1|max:100',
            'izin_reason' => 'required_if:include_izin,true|string|in:random,sakit,izin,cuti'
        ];

        // Add date validation rules only if custom date range is enabled
        if ($request->use_custom_date_range) {
            $rules['start_date'] = 'required|date|before_or_equal:end_date';
            $rules['end_date'] = 'required|date|after_or_equal:start_date';
        }

        $request->validate($rules);

        $userIds = $request->user_ids;
        $excludeWeekends = $request->exclude_weekends ?? true;
        $forceOverride = $request->force_override ?? false;
        $useCustomDateRange = $request->use_custom_date_range ?? false;
        $includeIzin = $request->include_izin ?? false;
        $izinPercentage = $request->izin_percentage ?? 10;
        $izinReason = $request->izin_reason ?? 'random';

        // Get the users
        $users = User::whereIn('id', $userIds)->get();

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
        $totalUsersProcessed = 0;

        // Helper function to generate random status
        $generateRandomStatus = function () use ($includeIzin, $izinPercentage, $izinReason) {
            if (!$includeIzin) {
                return ['status' => 'hadir', 'keterangan' => ''];
            }

            // Calculate if this should be an izin day
            $isIzin = (rand(1, 100) <= $izinPercentage);

            if ($isIzin) {
                // Generate izin status
                $statusOptions = ['izin', 'sakit', 'cuti'];

                if ($izinReason === 'random') {
                    $status = $statusOptions[array_rand($statusOptions)];
                } else {
                    $status = $izinReason;
                }

                // Generate appropriate keterangan
                $keteranganOptions = [
                    'izin' => ['Izin pribadi', 'Ada urusan keluarga', 'Keperluan mendadak'],
                    'sakit' => ['Sakit demam', 'Sakit kepala', 'Sakit perut', 'Check-up dokter'],
                    'cuti' => ['Cuti tahunan', 'Cuti bersama', 'Liburan keluarga']
                ];

                $keterangan = $keteranganOptions[$status][array_rand($keteranganOptions[$status])];

                return ['status' => $status, 'keterangan' => $keterangan];
            } else {
                return ['status' => 'hadir', 'keterangan' => ''];
            }
        };

        // Process each user
        foreach ($users as $user) {
            $totalUsersProcessed++;
            $userCreated = [];
            $userUpdated = [];
            $userSkipped = [];

            foreach (new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate->modify('+1 day')) as $date) {
                $currentDate = $date->format('Y-m-d');

                // Skip weekends if enabled
                if ($excludeWeekends && in_array($date->format('N'), [6, 7])) { // 6=Saturday, 7=Sunday
                    continue;
                }

                $totalDays++;

                // Check if absensi already exists for this user
                $existing = Absensi::where('user_id', $user->id)
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

                        // Generate status using helper function
                        $statusData = $generateRandomStatus();

                        // Disable timestamps temporarily
                        $existing->timestamps = false;

                        $existing->update([
                            'check_in' => $jamMasuk,
                            'check_out' => $jamKeluar,
                            'check_in_location' => 'Kota Wisata, Limusnunggal, Cileungsi, Bogor, Jawa Barat, Jawa, 16829, Indonesia',
                            'check_out_location' => 'Kota Wisata, Limusnunggal, Cileungsi, Bogor, Jawa Barat, Jawa, 16829, Indonesia',
                            'keterangan' => $statusData['keterangan'],
                            'status' => $statusData['status'],
                            'updated_by_admin' => true,
                            'admin_id' => Auth::id(),
                            'created_at' => $currentDate . ' ' . $jamKeluar,
                            'updated_at' => $currentDate . ' ' . $jamKeluar
                        ]);

                        // Re-enable timestamps
                        $existing->timestamps = true;

                        $userUpdated[] = $existing;
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

                    // Generate status using helper function
                    $statusData = $generateRandomStatus();

                    $absensiData = [
                        'user_id' => $user->id,
                        'tanggal' => $currentDate,
                        'check_in' => $jamMasuk,
                        'check_out' => $jamKeluar,
                        'check_in_location' => 'Kota Wisata, Limusnunggal, Cileungsi, Bogor, Jawa Barat, Jawa, 16829, Indonesia',
                        'check_out_location' => 'Kota Wisata, Limusnunggal, Cileungsi, Bogor, Jawa Barat, Jawa, 16829, Indonesia',
                        'keterangan' => $statusData['keterangan'],
                        'status' => $statusData['status'],
                        'created_by_admin' => true,
                        'admin_id' => Auth::id(),
                        'created_at' => $currentDate . ' ' . $jamKeluar,
                        'updated_at' => $currentDate . ' ' . $jamKeluar
                    ];

                    $absensi = Absensi::insert($absensiData);

                    // Get the inserted record for response
                    $absensi = Absensi::where('user_id', $user->id)
                        ->where('tanggal', $currentDate)
                        ->first();

                    $userCreated[] = $absensi;
                }
            }

            // Merge user results into main results
            $created = array_merge($created, $userCreated);
            $updated = array_merge($updated, $userUpdated);
            $skipped = array_merge($skipped, $userSkipped);
        }

        // Format date range for message
        if ($useCustomDateRange) {
            $dateRange = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
            $periodText = "periode $dateRange";
        } else {
            $periodText = "bulan $yearMonth";
        }

        $action = $forceOverride ? 'update/generate' : 'generate';

        return response()->json([
            'success' => true,
            'message' => "Berhasil $action " . (count($created) + count($updated)) . " data absensi untuk " .
                $totalUsersProcessed . " pengguna $periodText. " .
                (count($skipped) > 0 ? count($skipped) . " data dilewati karena sudah ada." : ""),
            'summary' => [
                'total_users_processed' => $totalUsersProcessed,
                'period' => $useCustomDateRange ? $dateRange : $yearMonth,
                'use_custom_date_range' => $useCustomDateRange,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'total_working_days' => $totalDays / $totalUsersProcessed,
                'total_created' => count($created),
                'total_updated' => count($updated),
                'total_skipped' => count($skipped),
                'force_override' => $forceOverride,
                'exclude_weekends' => $excludeWeekends,
                'include_izin' => $includeIzin,
                'izin_percentage' => $izinPercentage,
                'izin_reason' => $izinReason
            ],
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped
        ]);
    }
}
