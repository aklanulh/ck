<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Report;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Check user in database
        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Store user data in session
            session(['user' => [
                'id' => $user['id'],
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? 'user'
            ]]);

            return redirect()->intended('/absensi')
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        session()->forget('user');

        return redirect('/login')
            ->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Show the absensi page.
     */
    public function absensi()
    {
        // Get today's attendance for current user
        $todayAttendance = null;
        $attendanceHistory = [];

        if (session('user')) {
            $userData = session('user');
            $userId = $userData['id'];

            // Use cache for today's attendance (cache for 5 minutes)
            $cacheKey = "attendance_today_{$userId}";
            $todayAttendance = cache()->remember($cacheKey, 300, function () use ($userId) {
                return Absensi::select('id', 'user_id', 'tanggal', 'check_in', 'check_in_location', 'check_out', 'check_out_location', 'keterangan', 'status', 'total_jam')
                    ->where('user_id', $userId)
                    ->whereDate('tanggal', today())
                    ->first();
            });

            // Use cache for attendance history (cache for 10 minutes)
            $historyCacheKey = "attendance_history_{$userId}";
            $attendanceHistory = cache()->remember($historyCacheKey, 600, function () use ($userId) {
                return Absensi::select('id', 'user_id', 'tanggal', 'check_in', 'check_in_location', 'check_out', 'check_out_location', 'keterangan', 'status', 'total_jam')
                    ->where('user_id', $userId)
                    ->whereDate('tanggal', '>=', now()->subDays(3))
                    ->orderBy('tanggal', 'desc')
                    ->get();
            });
        }

        return view('absensi', compact('todayAttendance', 'attendanceHistory'));
    }

    /**
     * Handle check in request.
     */
    public function checkIn(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'catatan' => 'nullable|string|max:200',
        ]);

        try {
            // Find or create today's attendance record
            $attendance = Absensi::firstOrCreate(
                [
                    'user_id' => $user['id'],
                    'tanggal' => today()
                ],
                [
                    'status' => 'hadir'
                ]
            );

            // Only update check_in if null (first time or new record)
            if (!$attendance->check_in) {
                $attendance->check_in = now()->format('H:i:s');
                $attendance->check_in_location = $request->location ?? 'Unknown';

                // Add catatan if provided
                if ($request->catatan) {
                    $attendance->keterangan = 'Check-in: ' . $request->catatan;
                }

                $attendance->save();

                // Clear cache after successful check-in
                self::clearAttendanceCache($user['id']);

                return response()->json([
                    'success' => true,
                    'message' => 'Check in berhasil',
                    'check_in_time' => $attendance->check_in,
                    'check_in_location' => $attendance->check_in_location,
                    'catatan' => $attendance->keterangan
                ]);
            } else {
                return response()->json(['error' => 'Sudah check in hari ini'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal check in: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Handle check out request.
     */
    public function checkOut(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'catatan' => 'nullable|string|max:200',
            'location' => 'nullable|string|max:255',
        ]);

        try {
            // Use cache to get today's attendance first
            $cacheKey = "attendance_today_{$user['id']}";
            $attendance = cache()->remember($cacheKey, 300, function () use ($user) {
                return Absensi::select('id', 'check_in', 'check_out', 'keterangan', 'total_jam')
                    ->where('user_id', $user['id'])
                    ->whereDate('tanggal', today())
                    ->first();
            });

            if (!$attendance || !$attendance->check_in) {
                return response()->json(['error' => 'Belum check in hari ini'], 400);
            }

            if ($attendance->check_out) {
                return response()->json(['error' => 'Sudah check out hari ini'], 400);
            }

            // Update check out
            $attendance->check_out = now()->format('H:i:s');
            $attendance->check_out_location = $request->location ?? 'Unknown';

            // Combine catatan from check-in and check-out
            if ($request->catatan) {
                if ($attendance->keterangan) {
                    // Append check-out catatan to existing check-in catatan
                    $attendance->keterangan .= ' | Check-out: ' . $request->catatan;
                } else {
                    $attendance->keterangan = 'Check-out: ' . $request->catatan;
                }
            }
            $attendance->updateTotalHours();
            $attendance->save();

            // Clear cache after successful check-out
            self::clearAttendanceCache($user['id']);

            return response()->json([
                'success' => true,
                'message' => 'Check out berhasil',
                'check_out_time' => $attendance->check_out,
                'check_out_location' => $attendance->check_out_location,
                'total_hours' => $attendance->total_jam,
                'catatan' => $attendance->keterangan
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal check out: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get today's attendance status.
     */
    public function getAttendanceStatus()
    {
        $user = session('user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $userId = $user['id'];

        // Use cache for attendance status (cache for 2 minutes)
        $cacheKey = "attendance_status_{$user['id']}";
        $attendance = cache()->remember($cacheKey, 120, function () use ($userId) {
            return Absensi::select('id', 'check_in', 'check_out', 'keterangan', 'total_jam')
                ->where('user_id', $userId)
                ->whereDate('tanggal', today())
                ->first();
        });

        return response()->json([
            'attendance' => $attendance
        ]);
    }

    /**
     * Clear attendance cache for specific user (called by admin)
     */
    public static function clearAttendanceCache($userId)
    {
        cache()->forget("attendance_today_{$userId}");
        cache()->forget("attendance_history_{$userId}");
        cache()->forget("attendance_status_{$userId}");
        cache()->forget("attendance_full_history_{$userId}");
    }

    /**
     * Clear current user's attendance cache (API endpoint)
     */
    public function clearUserCache()
    {
        $user = session('user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        self::clearAttendanceCache($user['id']);

        return response()->json([
            'success' => true,
            'message' => 'Cache berhasil dibersihkan!'
        ]);
    }

    /**
     * Get all attendance history for current user.
     */
    public function getAttendanceHistory()
    {
        $user = session('user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $userId = $user['id'];

        // Use cache for attendance history (cache for 5 minutes)
        $cacheKey = "attendance_full_history_{$user['id']}";
        $attendanceHistory = cache()->remember($cacheKey, 300, function () use ($userId) {
            return Absensi::select('id', 'tanggal', 'check_in', 'check_in_location', 'check_out', 'check_out_location', 'keterangan', 'total_jam', 'status')
                ->where('user_id', $userId)
                ->orderBy('tanggal', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($attendance) {
                    return [
                        'id' => $attendance->id,
                        'tanggal' => \Carbon\Carbon::parse($attendance->tanggal)->locale('id')->format('l, d F Y'),
                        'check_in' => $attendance->check_in,
                        'check_in_location' => $attendance->check_in_location,
                        'check_out' => $attendance->check_out,
                        'check_out_location' => $attendance->check_out_location,
                        'keterangan' => $attendance->keterangan,
                        'total_jam' => $attendance->total_jam,
                        'status' => $attendance->status
                    ];
                });
        });

        return response()->json([
            'success' => true,
            'attendance' => $attendanceHistory
        ]);
    }

    /**
     * Show the daily report form.
     */
    public function showReportForm()
    {
        $user = session('user');
        if (!$user) {
            return redirect('/login');
        }

        // Get today's attendance for report
        $todayAttendance = Absensi::where('user_id', $user['id'])
            ->whereDate('tanggal', today())
            ->first();

        return view('report', compact('todayAttendance'));
    }

    /**
     * Show report history.
     */
    public function showReportHistory()
    {
        $user = session('user');
        if (!$user) {
            return redirect('/login');
        }

        // Get only submitted reports for the user, ordered by date descending
        $reports = Report::byUser($user['id'])
            ->submitted()
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($report) {
                return [
                    'id' => $report->id,
                    'tanggal' => \Carbon\Carbon::parse($report->tanggal)->locale('id')->format('d F Y'),
                    'lokasi' => $report->lokasi,
                    'laporan' => $report->laporan,
                    'masalah' => $report->masalah,
                    'solusi' => $report->solusi,
                    'photo_evidence' => $report->photo_evidence_with_urls, // Use corrected URLs
                    'status' => $report->status,
                    'submitted_at' => $report->submitted_at ? \Carbon\Carbon::parse($report->submitted_at)->locale('id')->format('d F Y H:i') : null,
                ];
            });

        return view('report-history', compact('reports'));
    }

    /**
     * Show drafts.
     */
    public function showDrafts()
    {
        $user = session('user');
        if (!$user) {
            return redirect('/login');
        }

        // Get only draft reports for the user, ordered by date descending
        $drafts = Report::byUser($user['id'])
            ->draft()
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($report) {
                return [
                    'id' => $report->id,
                    'tanggal' => \Carbon\Carbon::parse($report->tanggal)->locale('id')->format('d F Y'),
                    'lokasi' => $report->lokasi,
                    'laporan' => $report->laporan,
                    'masalah' => $report->masalah,
                    'solusi' => $report->solusi,
                    'photo_evidence' => $report->photo_evidence_with_urls, // Use corrected URLs
                    'status' => $report->status,
                    'submitted_at' => $report->submitted_at ? \Carbon\Carbon::parse($report->submitted_at)->locale('id')->format('d F Y H:i') : null,
                ];
            });

        return view('report-drafts', compact('drafts'));
    }

    /**
     * Generate and save daily report.
     */
    public function generateReport(Request $request)
    {
        \Log::info('generateReport called', ['request' => $request->all()]);

        $user = session('user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:100',
            'laporan' => 'required|string|max:2000',
            'masalah' => 'nullable|string|max:1000',
            'solusi' => 'nullable|string|max:1000',
            'photo_evidence' => 'nullable|array',
        ]);

        // Process photo evidence array
        $photoEvidence = null;
        if ($request->has('photo_evidence')) {
            $photoEvidence = [];
            foreach ($request->photo_evidence as $photo) {
                if (is_string($photo)) {
                    $photoEvidence[] = json_decode($photo, true);
                } else {
                    $photoEvidence[] = $photo;
                }
            }
        }

        try {
            // Convert request tanggal to database format
            $tanggalDb = \Carbon\Carbon::parse($request->tanggal)->format('Y-m-d');

            // Check if user already has 2 submitted reports for this date
            $existingSubmittedReports = Report::byUser($user['id'])->byDate($tanggalDb)->submitted()->get();

            if ($existingSubmittedReports->count() >= 2) {
                return response()->json(['error' => 'Maksimal 2 laporan untuk tanggal ' . $request->tanggal . '.']);
            }

            // Check if draft exists for this date and user
            $existingDraft = Report::byUser($user['id'])->byDate($tanggalDb)->draft()->first();

            \Log::info('Draft check', [
                'user_id' => $user['id'],
                'tanggal_request' => $request->tanggal,
                'tanggal_db' => $tanggalDb,
                'existing_draft_id' => $existingDraft ? $existingDraft->id : null,
                'existing_draft_status' => $existingDraft ? $existingDraft->status : null
            ]);

            if ($existingDraft) {
                // Update draft to submitted status (keep same ID)
                \Log::info('Updating draft to submitted', ['draft_id' => $existingDraft->id]);

                $existingDraft->update([
                    'lokasi' => $request->lokasi,
                    'laporan' => $request->laporan,
                    'masalah' => $request->masalah,
                    'solusi' => $request->solusi,
                    'photo_evidence' => $photoEvidence,
                    'status' => 'submitted',
                    'submitted_at' => now(),
                ]);

                \Log::info('Draft updated successfully', ['draft_id' => $existingDraft->id, 'new_status' => $existingDraft->status]);

                return response()->json([
                    'success' => true,
                    'message' => 'Draft berhasil dikirim sebagai laporan!'
                ]);
            } else {
                \Log::info('No draft found, creating new report');

                // Create new submitted report
                $report = Report::create([
                    'user_id' => $user['id'],
                    'tanggal' => $request->tanggal,
                    'lokasi' => $request->lokasi,
                    'laporan' => $request->laporan,
                    'masalah' => $request->masalah,
                    'solusi' => $request->solusi,
                    'photo_evidence' => $photoEvidence,
                    'status' => 'submitted',
                    'submitted_at' => now(),
                ]);

                \Log::info('New report created', ['report_id' => $report->id]);

                return response()->json([
                    'success' => true,
                    'message' => 'Laporan harian berhasil dikirim dan disimpan!'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menyimpan laporan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Save report as draft.
     */
    public function saveDraft(Request $request)
    {
        // Debug: Log request
        \Log::info('saveDraft called', ['request' => $request->all()]);

        $user = session('user');
        if (!$user) {
            \Log::error('User not authenticated in saveDraft');
            return response()->json(['error' => 'Unauthorized - Please login first'], 401);
        }

        \Log::info('User authenticated', ['user_id' => $user['id']]);

        $request->validate([
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:100',
            'laporan' => 'required|string|max:2000',
            'masalah' => 'nullable|string|max:1000',
            'solusi' => 'nullable|string|max:1000',
            'photo_evidence' => 'nullable|array',
        ]);

        // Process photo evidence array
        $photoEvidence = null;
        if ($request->has('photo_evidence')) {
            $photoEvidence = [];
            foreach ($request->photo_evidence as $photo) {
                if (is_string($photo)) {
                    $photoEvidence[] = json_decode($photo, true);
                } else {
                    $photoEvidence[] = $photo;
                }
            }
        }

        \Log::info('Validation passed');

        try {
            // Check if user already has 2 submitted reports for this date
            $tanggalDb = \Carbon\Carbon::parse($request->tanggal)->format('Y-m-d');
            $existingSubmittedReports = Report::byUser($user['id'])->byDate($tanggalDb)->submitted()->get();

            if ($existingSubmittedReports->count() >= 2) {
                return response()->json(['error' => 'Maksimal 2 laporan untuk tanggal ' . $request->tanggal . '.']);
            }

            // Check if draft already exists for this date and user
            $existingDraft = Report::byUser($user['id'])->byDate($tanggalDb)->draft()->first();

            if ($existingDraft) {
                // Update existing draft
                $existingDraft->update([
                    'lokasi' => $request->lokasi,
                    'laporan' => $request->laporan,
                    'masalah' => $request->masalah,
                    'solusi' => $request->solusi,
                    'photo_evidence' => $photoEvidence,
                ]);

                \Log::info('Draft updated successfully');

                return response()->json([
                    'success' => true,
                    'message' => 'Draft berhasil diperbarui!'
                ]);
            } else {
                // Create new draft
                $report = Report::create([
                    'user_id' => $user['id'],
                    'tanggal' => $request->tanggal,
                    'lokasi' => $request->lokasi,
                    'laporan' => $request->laporan,
                    'masalah' => $request->masalah,
                    'solusi' => $request->solusi,
                    'photo_evidence' => $photoEvidence,
                    'status' => 'draft',
                ]);

                \Log::info('New draft created successfully', ['report_id' => $report->id]);

                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil disimpan sebagai draft!'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error in saveDraft', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Gagal menyimpan draft: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete draft.
     */
    public function deleteDraft($id)
    {
        $user = session('user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Find the draft
            $draft = Report::byUser($user['id'])->draft()->find($id);

            if (!$draft) {
                return response()->json(['error' => 'Draft tidak ditemukan'], 404);
            }

            // Delete associated photos
            if ($draft->photo_evidence) {
                $this->deletePhotos($draft->photo_evidence);
            }

            // Delete the draft
            $draft->delete();

            return response()->json([
                'success' => true,
                'message' => 'Draft berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menghapus draft: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Upload photo evidence.
     */
    public function uploadPhoto(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,jpg,png|max:5120', // Max 5MB
                'timestamp' => 'required|string',
                'lokasi' => 'required|string'
            ]);

            $photo = $request->file('photo');
            $timestamp = $request->input('timestamp');
            $lokasi = $request->input('lokasi');

            // Generate unique filename
            $filename = 'photo_' . $user['id'] . '_' . time() . '_' . uniqid() . '.jpg';

            // Store photo
            $path = $photo->storeAs('photos', $filename, 'public');

            // Create photo data
            $photoData = [
                'id' => uniqid(),
                'path' => $path,
                'url' => config('app.url') . '/ck/public/storage/' . $path,
                'timestamp' => now()->toISOString(),
                'timestampText' => $timestamp,
                'lokasi' => $lokasi
            ];

            return response()->json([
                'success' => true,
                'photo' => $photoData,
                'message' => 'Foto berhasil diupload!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengupload foto: ' . $e->getMessage()
            ]);
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
