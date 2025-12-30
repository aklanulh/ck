<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'judul_kunjungan',
        'lokasi_kunjungan',
        'deskripsi_kunjungan',
        'tanggal_kunjungan',
        'status',
        'visit_date',
        'visit_time',
        'location',
        'purpose',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'visit_date' => 'date',
        'visit_time' => 'datetime',
    ];

    /**
     * Get the user that owns the visit schedule.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted date for display.
     */
    public function getFormattedTanggalAttribute()
    {
        return $this->tanggal_kunjungan->locale('id')->format('d F Y');
    }
}
