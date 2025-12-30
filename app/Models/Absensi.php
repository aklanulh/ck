<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'user_id',
        'tanggal',
        'check_in',
        'check_in_location',
        'check_out',
        'check_out_location',
        'location',
        'keterangan',
        'status',
        'total_jam',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'check_in' => 'string',
        'check_out' => 'string',
        'total_jam' => 'decimal:2',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope for today's attendance
    public function scopeToday($query)
    {
        return $query->whereDate('tanggal', today());
    }

    // Scope for specific user
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Calculate total hours
    public function calculateTotalHours()
    {
        if ($this->check_in && $this->check_out) {
            $checkIn = Carbon::createFromFormat('H:i:s', $this->check_in);
            $checkOut = Carbon::createFromFormat('H:i:s', $this->check_out);

            // Convert to minutes for easier calculation
            $checkInMinutes = $checkIn->hour * 60 + $checkIn->minute + ($checkIn->second / 60);
            $checkOutMinutes = $checkOut->hour * 60 + $checkOut->minute + ($checkOut->second / 60);

            // If check out is earlier than check in, assume it's next day
            if ($checkOutMinutes < $checkInMinutes) {
                $checkOutMinutes += 24 * 60; // Add 24 hours (1440 minutes)
            }

            $totalMinutes = $checkOutMinutes - $checkInMinutes;
            $totalHours = $totalMinutes / 60;

            return round($totalHours, 2);
        }

        return null;
    }

    // Update total hours
    public function updateTotalHours()
    {
        $this->total_jam = $this->calculateTotalHours();
        $this->save();
    }
}
