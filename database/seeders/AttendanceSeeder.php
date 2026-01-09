<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Absensi;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding attendance data...');

        // Get all users
        $users = User::all();

        // Generate attendance data for the last 90 days
        $startDate = Carbon::now()->subDays(90);
        $endDate = Carbon::now();

        foreach ($users as $user) {
            $this->command->info("Generating attendance for user: {$user->name}");

            // Generate attendance for each day
            for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
                // Skip weekends (Saturday, Sunday)
                if ($date->isWeekend()) {
                    continue;
                }

                // Randomly skip some days (izin/sakit)
                if (rand(1, 100) <= 15) { // 15% chance of not attending
                    $this->createIzinAttendance($user, $date);
                    continue;
                }

                // Create regular attendance
                $this->createRegularAttendance($user, $date);
            }
        }

        $this->command->info('Attendance data seeded successfully!');
    }

    private function createRegularAttendance($user, $date)
    {
        // Check if it's a holiday (simplified - just random)
        $isHoliday = rand(1, 100) <= 5; // 5% chance of holiday

        if ($isHoliday) {
            return; // Skip holidays
        }

        // Generate check-in time (7:30 - 9:30)
        $checkInHour = rand(7, 9);
        $checkInMinute = $checkInHour == 7 ? rand(30, 59) : rand(0, 30);
        $checkIn = sprintf('%02d:%02d', $checkInHour, $checkInMinute);

        // Generate check-out time (16:00 - 19:00)
        $checkOutHour = rand(16, 19);
        $checkOutMinute = rand(0, 59);
        $checkOut = sprintf('%02d:%02d', $checkOutHour, $checkOutMinute);

        // Determine status based on check-in time
        $status = $checkInHour <= 8 ? 'hadir' : 'terlambat';

        // Generate locations
        $locations = [
            'Kantor Pusat - Lantai 3',
            'Kantor Cabang - Jakarta',
            'Kantor Cabang - Surabaya',
            'Work From Home',
            'Kantor Pusat - Lantai 2',
            'Kantor Cabang - Bandung'
        ];

        $checkInLocation = $locations[array_rand($locations)];
        $checkOutLocation = $locations[array_rand($locations)];

        // Generate keterangan
        $keterangan = $status === 'terlambat' ? 'Macet di jalan' : '';

        // Create attendance record
        Absensi::create([
            'user_id' => $user->id,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'check_in_location' => $checkInLocation,
            'check_out_location' => $checkOutLocation,
            'status' => $status,
            'keterangan' => $keterangan,
            'created_at' => $date->copy()->setTime($checkInHour, $checkInMinute),
            'updated_at' => $date->copy()->setTime($checkOutHour, $checkOutMinute),
        ]);

        $this->command->line("  - {$date->format('Y-m-d')}: {$status} (In: {$checkIn}, Out: {$checkOut})");
    }

    private function createIzinAttendance($user, $date)
    {
        $izinReasons = [
            'Sakit - Flu demam',
            'Izin pribadi - Urusan keluarga',
            'Cuti tahunan',
            'Sakit - Pusing migraine',
            'Izin - Acara penting',
            'Sakit - Batuk pilek',
            'Cuti - Liburan keluarga',
            'Izin - Kedukaan',
            'Sakit - Masuk angin',
            'Izin - Pengobatan rutin'
        ];

        $keterangan = $izinReasons[array_rand($izinReasons)];

        // Create izin attendance
        Absensi::create([
            'user_id' => $user->id,
            'check_in' => null,
            'check_out' => null,
            'check_in_location' => null,
            'check_out_location' => null,
            'status' => 'izin',
            'keterangan' => $keterangan,
            'created_at' => $date->copy()->setTime(rand(8, 10), rand(0, 59)),
            'updated_at' => $date->copy()->setTime(rand(16, 18), rand(0, 59)),
        ]);

        $this->command->line("  - {$date->format('Y-m-d')}: izin ({$keterangan})");
    }
}
